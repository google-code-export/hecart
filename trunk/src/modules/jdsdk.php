<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 模块: $Id: jdsdk.php 1 2012-11-20 05:55:12Z Administrator $
 * 简述: 京东SDK
 * 作者: woods·zhang  ->  hoojar@163.com
 *
 * 版权 2006-2013, 慧佳工作室拥有此系统所有版权等知识产权
 * Copyright 2006-2013, Hoojar Studio All Rights Reserved.
 *
 */
class modules_jdsdk
{
	/**
	 * 指定返回响应格式。默认xml,目前支持格式为xml,json
	 *
	 * @var string
	 */
	public $format = 'json';

	/**
	 * JDSDK分配给应用的appkey
	 *
	 * @var string
	 */
	public $appkey = '';

	/**
	 * JDSDK分配给应用的appsec
	 *
	 * @var string
	 */
	public $appsec = '';

	/**
	 * JDSDK分配给应用的token
	 *
	 * @var string
	 */
	public $token = '';

	/**
	 * API接口名称
	 *
	 * @var string
	 */
	public $method = '';

	/**
	 * API接口版本号
	 *
	 * @var string
	 */
	public $version = '2.0';

	/**
	 * 存储需要发送的参数
	 *
	 * @var array
	 */
	private $_params = array();

	/**
	 * 接口路由地址
	 *
	 * @var string
	 */
	public $gateway = 'http://gw.api.360buy.com/routerjson';

	/**
	 * 初始化相关参数
	 *
	 * @param string $token     JDSDK分配给应用的token
	 * @param string $appkey    JDSDK分配给应用的appkey
	 * @param string $method    JDSDK调用API接口名称
	 */
	public function __construct($token, $appkey, $appsec, $method)
	{
		$this->token  = $token;
		$this->appkey = $appkey;
		$this->appsec = $appsec;
		$this->method = $method;
	}

	/**
	 * 设置需要发送的参数
	 *
	 * @param string $k 名称
	 * @param string $v 数值
	 */
	public function param($k, $v) { $this->_params[$k] = $v; }

	/**
	 * 执行调用接口处理
	 *
	 * @return array 返回所获取的数据内容
	 */
	public function exec()
	{
		$params         = array(
			'v'                 => $this->version,
			'app_key'           => $this->appkey,
			'method'            => $this->method,
			'timestamp'         => date('Y-m-d H:i:s'),
			'access_token'      => $this->token,
			'360buy_param_json' => json_encode($this->_params)
		);
		$sign           = $this->sign($params);
		$params['sign'] = $sign;
		$content        = $this->post($this->gateway, http_build_query($params));

		/**
		 * 解析JDSDK返回结果
		 */
		if ('json' == $this->format)
		{
			$res = json_decode($content, true);
			if (null !== $res)
			{
				foreach ($res as $k => $v)
				{
					$res = $v;
				}
			}
		}
		else if ('xml' == $this->format)
		{
			$res = @simplexml_load_string($content);
		}

		return $res;
	}

	/**
	 * MD5加密签名
	 *
	 * @param    array $params        需要组合加密码的数组
	 * @return    string    MD5加密值
	 */
	private function sign($params)
	{
		ksort($params);
		$sign_string = $this->appsec;
		foreach ($params as $k => $v)
		{
			if ($v !== '')
			{
				$sign_string .= "{$k}{$v}";
			}
		}
		$sign_string .= $this->appsec;

		return strtoupper(md5($sign_string));
	}

	/**
	 * HTTP采用POST方式发送数据
	 *
	 * @param string $url     地址
	 * @param string $data    数据
	 * @return string 返回的数据
	 */
	public function post($url, $data)
	{
		if (empty($url) || empty($data))
		{
			return false;
		}
		if (strpos($url, '://') !== false)
		{
			list($protocol, $url) = explode('://', $url); //get protocol
		}
		@list($host, $tmp) = explode('/', $url); //get o host
		list($tmp, $path) = explode($host, $url); //get execute filescript
		@list($host, $port) = explode(':', $host); //get host and port
		if (empty($path))
		{
			$path = '/';
		}
		if (!$port)
		{
			$port = 80;
		}

		$data         = $this->utfcode($data);
		$http_request = "POST {$path} HTTP/1.0\r\n";
		$http_request .= "Host: {$host}\r\n";
		$http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$http_request .= "Content-Length: " . strlen($data) . "\r\n";
		$http_request .= "\r\n";
		$http_request .= $data;
		$response = '';
		if (($fs = @fsockopen($host, $port, $errno, $errstr, 10)) == false)
		{
			die ('Could not open socket! ' . $errstr);
		}

		fwrite($fs, $http_request);
		while (!feof($fs))
		{
			$response .= fgets($fs, 1024);
		}
		fclose($fs);
		$response = explode("\r\n\r\n", $response, 2);

		return $response[1];
	}

	/**
	 * 将所有编码转换成UTF-8
	 *
	 * @param string $str 字符串
	 * @return string 转好的字符串
	 */
	public function utfcode($str)
	{
		$curr_encoding = mb_detect_encoding($str);
		if ($curr_encoding == "UTF-8" && mb_check_encoding($str, "UTF-8"))
		{
			return $str;
		}

		return mb_encode($str);
	}
}

?>