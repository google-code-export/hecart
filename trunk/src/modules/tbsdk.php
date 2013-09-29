<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 模块: modules/tbsdk.php
 * 简述: 淘宝接口API
 * 作者: woods·zhang  ->  hoojar@163.com
 * 版本: $Id: tbsdk.php 1 2012-11-20 05:55:12Z Administrator $
 * 版权: Copyright 2006-2013 慧佳工作室拥有此系统所有版权等知识产权
 *
 */
class modules_tbsdk
{
	/**
	 * 指定返回响应格式。默认xml,目前支持格式为xml,json
	 *
	 * @var string
	 */
	public $format = 'json';

	/**
	 * TOP分配给应用的AppKey
	 *
	 * @var string
	 */
	public $appkey = '';

	/**
	 * TOP分配给应用的SecretKey
	 *
	 * @var string
	 */
	public $secretkey = '';

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
	 * 淘宝接口路由地址
	 *
	 * @var string
	 */
	public $gateway = "http://gw.api.taobao.com/router/rest";

	/**
	 * 初始化相关参数
	 *
	 * @param string $appkey       TOP分配给应用的AppKey
	 * @param string $secretkey    TOP分配给应用的SecretKey
	 * @param string $method       TOP调用API接口名称
	 */
	public function __construct($appkey, $secretkey, $method)
	{
		$this->appkey    = $appkey;
		$this->secretkey = $secretkey;
		$this->method    = $method;
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
				'sign_method' => 'md5',
				'timestamp'   => date('Y-m-d H:i:s'),
				'v'           => $this->version,
				'app_key'     => $this->appkey,
				'method'      => $this->method,
				'format'      => $this->format,
			) + $this->_params;
		$params['sign'] = $this->sign($params);

		/**
		 * POST数据到淘宝
		 */
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->gateway);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$content = curl_exec($ch);
		if (curl_errno($ch))
		{
			throw new Exception(curl_error($ch), 0);
		}
		else
		{
			$error_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $error_code)
			{
				throw new Exception($content, $error_code);
			}
		}
		curl_close($ch);
		$this->_params = array();

		/**
		 * 解析TOP返回结果
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
		$sign_string = $this->secretkey;
		foreach ($params as $k => $v)
		{
			if ($v !== '')
			{
				$sign_string .= "{$k}{$v}";
			}
		}
		$sign_string .= $this->secretkey;

		return strtoupper(md5($sign_string));
	}
}

?>