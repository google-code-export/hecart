<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 模块: wcore/utils.php
 * 简述: 专门用于提供各种函数
 * 作者: woods·zhang  ->  hoojar@163.com
 * 版本: $Id: utils.php 1 2012-11-20 05:55:12Z Administrator $
 * 版权: Copyright 2006-2013 慧佳工作室拥有此系统所有版权等知识产权
 *
 */
class wcore_utils
{
	/**
	 * 安全获取变量
	 *
	 * @param string  $ob      为要取的数据名字
	 * @param string  $type    为要取的是什么数据类型 (i=整形, d=整形, f=浮点, s=字符, c=字符, b=布尔, a=数组, o=对象)
	 * @param string  $gpcs    为是取外部变量还是 get post cookie session，或要取session变量则设置成s，取cookie则设置为c
	 * @param mixed   $default 当取不到数据时则将数据设置成默认值
	 * @param integer $length  如果是字符串则截取指定的长度
	 * @return mixed
	 */
	public static function get_var($ob, $type = '', $gpcs = '', $default = null, $length = 0)
	{
		if (empty($ob))
		{
			return $default;
		}

		$type = ($type) ? strtolower($type) : 'string'; //数据类型
		$gpcs = ($gpcs) ? strtolower($gpcs) : ''; //数据来源

		/**
		 * 从GET、POST、COOKIE、SESSION、REQUEST当中获取数据
		 */
		switch ($gpcs)
		{
			case 'get':
			case 'g':
				$value = isset($_GET[$ob]) ? $_GET[$ob] : $default;
				break;
			case 'post':
			case 'p':
				$value = isset($_POST[$ob]) ? $_POST[$ob] : $default;
				break;
			case 'cookie':
			case 'c':
				$value = isset($_COOKIE[$ob]) ? $_COOKIE[$ob] : $default;
				break;
			case 'session':
			case 's':
				$value = isset($_SESSION[$ob]) ? $_SESSION[$ob] : $default;
				break;
			default:
				$value = isset($_REQUEST[$ob]) ? $_REQUEST[$ob] : $default;
				break;
		}

		/**
		 * 返回转换好的数据类型数据
		 */
		if ($value === 0 || $value === '0')
		{
			return $value;
		}

		/**
		 * 0非常的特殊用empty去判断0结果为true
		 */
		if (empty($value))
		{
			$value = $default;
		}

		switch ($type)
		{
			case 'string':
			case 'char':
			case 's':
			case 'c': //字符类型
				return (settype($value, 'string')) ? (($length === 0) ? $value : mb_strcut($value, 0, intval($length))) : '';
			case 'float':
			case 'f':
			case 'double':
			case 'd': //浮点类型
				return (settype($value, 'float')) ? $value : 0.0;
			case 'int':
			case 'integer':
			case 'i': //整数类型
				return (settype($value, 'integer')) ? $value : 0;
			case 'bool':
			case 'boolean':
			case 'b': //布尔类型
				return (settype($value, 'boolean')) ? $value : false;
			case 'array':
			case 'a': //数组类型
				return (settype($value, 'array')) ? $value : array();
			case 'object':
			case 'o': //对象类型
				return (settype($value, 'object')) ? $value : null;
			default:
				return $default;
		}
	}

	/**
	 * 将Rewrite数据中指定的值转换成GET数据
	 *
	 * @param string $k         Rewrite数据中指定的值
	 * @param string $separator Rewrite数据的分隔符
	 * @return boolean 转换成功为true返知false
	 */
	public static function reparm($k = 'reparm', $separator = '/')
	{
		if (!isset($_GET[$k]) || empty($_GET[$k]))
		{
			return false;
		}

		$val = explode($separator, $_GET[$k]);
		unset($_GET[$k]);

		if (!empty($val))
		{
			$len = count($val);
			for ($i = 0; $i < $len; $i++)
			{
				$tmp_val            = isset($val[$i + 1]) ? $val[$i + 1] : '';
				$_REQUEST[$val[$i]] = $_GET[$val[$i]] = $tmp_val;
				$i++;
			}

			return true;
		}

		return false;
	}

	/**
	 * 加密
	 *
	 * @param string $str 要加密的字符串
	 * @return string 加密好的字符串
	 */
	public static function woods_encode($str)
	{
		if (empty($str))
		{
			return '';
		}

		$str = strrev(base64_encode($str));
		for ($i = 0; $i < strlen($str); ++$i)
		{
			$str[$i] = chr(ord($str[$i]) + 1);
		}

		return mb_encode($str);
	}

	/**
	 * 解密
	 *
	 * @param string $str 要解密的字符串
	 * @return string 解密好的明文
	 */
	public static function woods_decode($str)
	{
		if (empty($str))
		{
			return '';
		}

		$str = mb_decode($str);
		for ($i = 0; $i < strlen($str); ++$i)
		{
			$str[$i] = chr(ord($str[$i]) - 1);
		}
		$str = base64_decode(strrev($str));

		return $str;
	}

	/**
	 * 设置COOKIE数据
	 *
	 * @param string $name      COOKIE名称
	 * @param mixed  $value     COOKIE数据
	 * @param int    $expire    COOKIE有效终止期(自定义设置以天为单位0代表将会在会话结束后（一般是浏览器关闭）失效)
	 * @param string $path      COOKIE存储的路径
	 * @return bool    存储成功返回true反知则为false
	 */
	public static function set_cookie($name, $value, $expire = 0, $path = '/')
	{
		if (empty($name))
		{
			return false;
		}

		$expire = intval($expire);
		if (is_null($value) || $expire < 0)
		{
			$expire = -3600; //清除COOKIE数据
		}
		else if ($expire > 0)
		{
			$expire = time() + $expire * 86400; //设置COOKIE有效期
		}

		return setcookie($name, $value, $expire, $path, DOMAIN_NAME); //保存COOKIE
	}

	/**
	 * 获取cookie数据
	 *
	 * @param string $name 要获取的cookie的名称
	 * @param string $type 为要取的是什么数据类型 (i, f, d, s, c, b, a)
	 * @return mixed
	 */
	public static function get_cookie($name, $type)
	{
		return wcore_utils::get_var($name, $type, 'c');
	}

	/**
	 * 我的md5加密
	 *
	 * @param string $str 要加密的内容
	 * @param string $wdk 加密密匙
	 * @return string 加密码后的内容
	 */
	public static function md5crypt($str, $wdk)
	{
		$string = md5("{$str}-{$wdk}");

		return substr($string, -15) . substr($string, 1, 17);
	}

	/**
	 * 判断数据来源是否来至我们的主机
	 */
	function data_is_from_ours_host()
	{
		$http_ref = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
		if (empty($http_ref))
		{
			return false;
		}

		preg_match("/:\/\/(.+?)\//si", $http_ref, $rhost);
		$http_ref = $rhost[1]; //只取IP或域名
		$long_ip  = ip2long($http_ref);
		if ($long_ip == -1 || false === $long_ip)
		{
			$host      = explode('.', $http_ref);
			$http_ref  = (count($host) > 2) ? "{$host[1]}.{$host[2]}" : $http_ref;
			$host_name = $_SERVER['HTTP_HOST'];
			$host      = explode('.', $host_name);
			$host_name = (count($host) > 2) ? "{$host[1]}.{$host[2]}" : $host_name;
			if ($host_name != $http_ref)
			{
				return false;
			}
		}
		else
		{
			if ($_SERVER['SERVER_ADDR'] != $http_ref)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * 产生唯一代号
	 * @param string $str 要生成多少位的字符串
	 * @return string 返回生成的字符串
	 */
	public static function uuid($str = '')
	{
		return $str . date('Ymd-siH-') . strtoupper(sprintf('%04x-%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff)));
	}

	/**
	 * 生成随机字符串
	 *
	 * @param int $length 生成几位数
	 * @return string 随机字符串
	 */
	public static function rand_string($length = 4)
	{
		$words    = '';
		$length   = intval($length);
		$charsets = 'ABCDEFGHKLMNPRSTUVWYZabcdefghklmnprstuvwyz23456789';
		$cslen    = strlen($charsets) - 1;
		for ($i = 1; $i <= $length; ++$i)
		{
			$words .= $charsets[rand(0, $cslen)];
		}

		return $words;
	}

	/**
	 *    产生豪秒数据
	 * @return float 当前浮点豪秒数
	 */
	public static function microtime_float()
	{
		list($usec, $sec) = explode(' ', microtime());

		return ((float)$usec + (float)$sec);
	}

	/**
	 * 将字符串统一编码转换成UTF-8
	 *
	 * @param string $str 字符串
	 * @return string 转好的字符串
	 */
	public static function utf8($str)
	{
		//此处为什么返回$str而非空是为了当$str为0时返回0
		if (empty($str) || !trim($str))
		{
			return $str;
		}

		if (mb_detect_encoding($str) == "UTF-8" && mb_check_encoding($str, 'UTF-8'))
		{
			return $str;
		}
		if ($str === mb_convert_encoding(mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
		{
			return $str;
		}

		return iconv('GB2312', 'UTF-8//IGNORE', $str);
	}

	/**
	 * 获取真实的IP地址
	 *
	 * @return string IP地址
	 */
	public static function get_ip()
	{
		if (getenv('HTTP_CLIENT_IP'))
		{
			$ip = getenv('HTTP_CLIENT_IP');
		}
		else if (getenv('HTTP_X_FORWARDED_FOR'))
		{
			list($ip) = explode(',', getenv('HTTP_X_FORWARDED_FOR'));
		}
		else if (getenv('REMOTE_ADDR'))
		{
			$ip = getenv('REMOTE_ADDR');
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}

	/**
	 * 根据IP获取对应的数据(高效)
	 *
	 * @param string $ip       IP地址
	 * @param string $filename IP库路径
	 * @return array 未找到返回false，找到返回array(sIp=>开始IP数字 eIp=>结束IP数字 pId=>省份编号 cId=>城市编号)
	 */
	public static function get_ip_info($ip, $filename = '')
	{
		/**
		 * IP转数字并判断是否为合法IP地址
		 */
		$ip = ip2long($ip); //将IP转换成数字

		//ip转数字无效返回false
		if ($ip === false)
		{
			return false;
		}
		$ip = sprintf('%u', $ip); //将转换成数字的IP再次转换成无符号的长整形

		/**
		 * 打开IP数据库文件并静态文件指针与下标数
		 */
		static $fp = null, $count = null;
		if ($fp === null)
		{
			if (empty($filename))
			{
				$filename = dirname(__FILE__) . '/ip-db.dat';
			}
			$fp = @fopen($filename, 'rb');
			if ($fp === false)
			{
				exit("{$filename} file not exists .");
			}
			$res   = unpack('Nhig', fread($fp, 4)); //高位
			$count = $res['hig'];
			unset($res);
		}

		/**
		 * 初始化二分查找参照数据
		 */
		$seek   = 12; //一条记录占几个字节
		$top    = $middle = 0; //低位//下标
		$bottom = $count; //底（记录总数）

		/**
		 * 二分查找对应的数据
		 */
		while ($top <= $bottom)
		{
			$middle = floor(($top + $bottom) / 2);
			fseek($fp, 4 + $middle * $seek);
			$data        = unpack('NsIp/NeIp/SpId/ScId', fread($fp, $seek));
			$data['sIp'] = sprintf('%u', $data['sIp']);
			$data['eIp'] = sprintf('%u', $data['eIp']);
			if ($ip < $data['sIp'])
			{
				$bottom = $middle - 1;
			}
			elseif ($ip > $data['eIp'])
			{
				$top = $middle + 1;
			}
			else
			{
				$rdata = $data; //找到了
				$top   = $middle + 1; //当有多条记录符号时则尽量取最后一个值
			}
		}

		return isset($rdata) ? $rdata : false; //未找到
	}

	/**
	 * 将IP地址转换成长整形数据
	 *
	 * @param string $ip ip地址
	 * @return long ip长整形
	 */
	public static function get_ip_long($ip = '')
	{
		$ip = $ip ? $ip : wcore_utils::get_ip();
		$ip = ip2long($ip); //将IP转换成数字

		//ip转数字无效返回false
		if ($ip === false)
		{
			return false;
		}

		return sprintf('%u', $ip); //将转换成数字的IP再次转换成无符号的长整形
	}

	/**
	 * 获取访问来源域名
	 *
	 * @return string
	 */
	public static function get_http_referer()
	{
		return (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
	}

	/**
	 * 提升性能的Etag
	 *
	 * @param string  $etag      eTag标签
	 * @param integer $timestamp 时间差
	 * @return bool
	 */
	public static function etag($etag, $timestamp = 0)
	{
		if (empty($etag))
		{
			$etag = date('Ym-dHi');
		}

		header("ETag: {$etag}");
		$modified_since = true; //校验最后修改时间是否有变动的,默认为没有变动

		/**
		 * 判断是否设置了有效期,若有效期不为0或空则设置Etag有效期
		 */
		if ($timestamp)
		{
			if (!is_numeric($timestamp))
			{
				return false;
			}
			$last_modified = substr(date('r', $timestamp), 0, -5) . 'GMT';
			header("Last-Modified: {$last_modified}");
			$if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false;
			$modified_since    = ($if_modified_since && $if_modified_since == $last_modified) ? true : false;
		}

		/**
		 * 判断Etag有效期,若设置了GET[nocache]则无效
		 */
		$if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : false;
		if ($modified_since && $if_none_match == $etag && !isset($_GET['nocache']))
		{
			header('HTTP/1.1 304 Not Modified');
			exit();
		}

		return true;
	}

	/**
	 * 通过socket传递数据到另一个WEB服务器
	 * $data = array('s' => 'php', 't' => 2);//要POST的数据
	 * $get = 'yes';//是否获取返回数据，是则不为空
	 * send_data('http://www.hoojar.com/search.php', $data, $get);
	 * echo($get);//输入返回的数据
	 *
	 * @param string $url     网址
	 * @param array  $params  要发送的数据
	 * @param string $get     获取返回数据，若不想获取则为空,想获取就则填写内容
	 * @param string $method  传递方式 GET POST
	 * @param int    $port    目标端口
	 * @param int    $timeout 超时多少秒
	 * @return boolean true成功false失败
	 */
	public static function send_data($url, &$params, &$get, $method = 'POST', $port = 80, $timeout = 30)
	{
		if (empty($url) || empty($params))
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

		/* 用socket链接主机 */
		$err_no = $str_err = '';
		$fp     = fsockopen($host, $port, $err_no, $str_err, $timeout);
		if (!$fp)
		{
			return ($get) ? $str_err : false;
		}

		/* 组合HTTP头与数据 */
		$str = '';
		if (is_array($params))
		{
			foreach ($params as $key => $val)
			{
				$str .= "{$key}={$val}&";
			}
		}

		$str    = substr($str, 0, -1);
		$method = strtoupper($method);
		$out    = "{$method} {$path} HTTP/1.1\r\nHost: {$host}\r\n";
		$out .= "Pragma: no-cache\r\nContent-Range: bytes\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: " . strlen($str) . "\r\n";
		$out .= "Connection: Close\r\n\r\n{$str}";
		fputs($fp, $out);

		if ($get) //获取返回数据
		{
			$get = '';
			while (!feof($fp))
			{
				$get .= fgets($fp, 1024);
			}
		}
		fclose($fp);

		return true;
	}

	/**
	 * 获取域名若未指定URL地址则获取HTTP REFERER的域名
	 *
	 * @param string $url 域名或URL地址
	 * @return string 返回域名
	 */
	public static function get_http_domain($url = '')
	{
		$url = $url ? $url : wcore_utils::get_http_referer();
		if (empty($url))
		{
			return '';
		}

		$res = wcore_utils::parse_href($url);

		return $res['host'];
	}

	/**
	 * 分析url地址
	 *
	 * @param string $url  域名或URL地址
	 * @return array protocol:协议 host:主机 port:端口 path:路径
	 */
	public static function parse_href($url)
	{
		$data = array(
			'protocol' => '',
			'host'     => '',
			'port'     => '',
			'path'     => ''
		);
		if (empty($url))
		{
			return $data;
		}

		if (strpos($url, '://') !== false)
		{
			list($protocol, $url) = explode('://', $url); //get protocol
			$data['protocol'] = $protocol;
		}

		@list($host, $tmp) = explode('/', $url); //get o host
		list($tmp, $path) = explode($host, $url); //get execute filescript
		@list($host, $port) = explode(':', $host); //get host and port

		$data['host'] = $host;
		$data['port'] = $port ? $port : 80;
		$data['path'] = $path;

		return $data;
	}

	/**
	 * 分析URL地址
	 *
	 * @param string $url 域名或URL地址
	 * @return array 返回域名分解数组
	 */
	public static function parse_url2($url)
	{
		$r = "^(?:(?P<scheme>\w+)://)?";
		$r .= "(?:(?P<login>\w+):(?P<pass>\w+)@)?";
		$r .= "(?P<host>(?:(?P<subdomain>[\w\.]+)\.)?(?P<domain>\w+\.(?P<extension>\w+)))";
		$r .= "(?::(?P<port>\d+))?";
		$r .= "(?P<path>[\w/]*/(?P<file>\w+(?:\.\w+)?)?)?";
		$r .= "(?:\?(?P<arg>[\w=&]+))?";
		$r .= "(?:#(?P<anchor>\w+))?";
		$r = "!$r!";
		preg_match($r, $url, $out);

		return $out;
	}

	/**
	 * 专用于调试，输出所有数据
	 */
	public static function debug()
	{
		$args = func_get_args();
		header('Content-type: text/html; charset=utf-8');
		echo("<html><head><title>debug infomation</title></head><body><pre>\n");
		foreach ($args as $v)
		{
			var_dump($v);
			echo("\n");
		}
		exit('</pre></body><html>');
	}

	/**
	 * 格式化显示价格
	 *
	 * @param int $price     价格
	 * @param int $precision 保留几位小数
	 * @return int 格式化好的价格
	 */
	public static function format_price($price, $precision = 2)
	{
		if ($precision == 0 || $price == round($price))
		{
			return round($price);
		}

		return round($price, $precision);
	}

	/**
	 * 价格4舍5入处理
	 *
	 * @param float $price     价格
	 * @param int   $precision 保留几位小数
	 * @return float 处理后的价格
	 */
	public static function price_round($price, $precision = 0)
	{
		$price = floatval($price);

		//不进行4舍5入处理
		if (PRICE_ROUND <= -1)
		{
			return $price;
		}

		//根据调用函数处自定义的来取小数位
		if ($precision > 0)
		{
			return round($price, $precision);
		}

		return round($price, PRICE_ROUND); //根据系统定义的来取小数位,进行4舍5入处理
	}

	/**
	 * 获取倒计时
	 *
	 * @param int  $endtime 结束时间秒
	 * @param bool $rehtml  是否返回HTML值
	 * @return array 失败返回空,倒计时成功,返回数组(day hour minute second)或组合好的HTML
	 */
	public static function countdown($endtime, $rehtml = true)
	{
		$sec = $endtime - time();
		if ($sec <= 0)
		{
			return '';
		}

		$rdata = array(
			'day'    => floor($sec / 86400),
			'hour'   => floor(($sec / 3600) % 24),
			'minute' => floor(($sec / 60) % 60),
			'second' => floor($sec % 60)
		);

		if (!$rehtml)
		{
			return $rdata;
		}

		$day    = ($rdata['day'] > 0) ? "<em>{$rdata['day']}</em>天" : '';
		$hour   = ($rdata['hour'] > 0) ? "<em>{$rdata['hour']}</em>小时" : '';
		$minute = ($rdata['minute'] > 0) ? "<em>{$rdata['minute']}</em>分" : '';
		$second = ($rdata['second'] > 0) ? "<em>{$rdata['second']}</em>秒" : '';

		return $day . $hour . $minute . $second;
	}

	/**
	 * 将XML数据转换成数组数据
	 * $array =  xml2array(file_get_contents('feed.xml'));
	 * $array =  xml2array(file_get_contents('feed.xml', true, 'attribute'));
	 *
	 * @param string  $contents       XML数据内容
	 * @param boolean $get_attributes 是否获取XML属性值
	 * @param string  $priority       优先顺序标签
	 * @return array 数组数据
	 */
	public static function xml2array($contents, $get_attributes = true, $priority = 'tag')
	{
		if (empty($contents))
		{
			return array();
		}

		if (!function_exists('xml_parser_create'))
		{
			return array();
		}

		//Get the XML parser of PHP - PHP must have this module for the parser to work
		$parser = xml_parser_create('');
		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($contents), $xml_values);
		xml_parser_free($parser);
		if (!$xml_values)
		{
			return;
		}

		//init data
		$xml_array   = array();
		$parents     = array();
		$opened_tags = array();
		$arr         = array();
		$tag         = '';
		$type        = '';
		$level       = 0;
		$current     = & $xml_array;

		//Go through the tags.
		$repeated_tag_index = array(); //Multiple tags with same name will be turned into an array
		foreach ($xml_values as $data)
		{
			unset($attributes, $value); //Remove existing values, or there will be trouble
			extract($data); //We could use the array by itself, but this cooler.
			$result          = array();
			$attributes_data = array();
			if (isset($value))
			{
				if ($priority == 'tag')
				{
					$result = $value;
				}
				else
				{
					$result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
				}
			}

			//Set the attributes too.
			if (isset($attributes) && $get_attributes)
			{
				foreach ($attributes as $attr => $val)
				{
					if ($priority == 'tag')
					{
						$attributes_data[$attr] = $val;
					}
					else
					{
						$result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
					}
				}
			}

			//See tag status and do the needed.
			if ($type == 'open') //The starting of the tag '<tag>'
			{
				$parent[$level - 1] = & $current;
				if (!is_array($current) || (!in_array($tag, array_keys($current)))) //Insert New tag
				{
					$current[$tag] = $result;
					if ($attributes_data)
					{
						$current[$tag . '_attr'] = $attributes_data;
					}
					$repeated_tag_index[$tag . '_' . $level] = 1;
					$current                                 = & $current[$tag];
				}
				else //There was another element with the same tag name
				{
					if (isset($current[$tag][0])) //If there is a 0th element it is already an array
					{
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
						$repeated_tag_index[$tag . '_' . $level]++;
					}
					else //This section will make the value an array if multiple tags with the same name appear together
					{
						//This will combine the existing item and the new item together to make an array
						$current[$tag]                           = array(
							$current[$tag],
							$result
						);
						$repeated_tag_index[$tag . '_' . $level] = 2;
						if (isset($current[$tag . '_attr'])) //The attribute of the last(0th) tag must be moved as well
						{
							$current[$tag]['0_attr'] = $current[$tag . '_attr'];
							unset($current[$tag . '_attr']);
						}
					}
					$last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
					$current         = & $current[$tag][$last_item_index];
				}
			}
			elseif ($type == 'complete') //Tags that ends in 1 line '<tag />'
			{
				//See if the key is already taken.
				if (!isset($current[$tag])) //New Key
				{
					$current[$tag]                           = $result;
					$repeated_tag_index[$tag . '_' . $level] = 1;
					if ($priority == 'tag' && $attributes_data)
					{
						$current[$tag . '_attr'] = $attributes_data;
					}
				}
				else //If taken, put all things inside a list(array)
				{
					if (isset($current[$tag][0]) && is_array($current[$tag])) //If it is already an array...
					{
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result; // ...push the new element into that array.
						if ($priority == 'tag' && $get_attributes && $attributes_data)
						{
							$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
						}
						$repeated_tag_index[$tag . '_' . $level]++;
					}
					else //If it is not an array...
					{
						//Make it an array using using the existing value and the new value
						$current[$tag]                           = array(
							$current[$tag],
							$result
						);
						$repeated_tag_index[$tag . '_' . $level] = 1;
						if ($priority == 'tag' && $get_attributes)
						{
							if (isset($current[$tag . '_attr'])) //The attribute of the last(0th) tag must be moved as well
							{
								$current[$tag]['0_attr'] = $current[$tag . '_attr'];
								unset($current[$tag . '_attr']);
							}

							if ($attributes_data)
							{
								$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
							}
						}
						$repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
					}
				}
			}
			elseif ($type == 'close') //End of </tag>
			{
				$current = & $parent[$level - 1];
			}
		}

		return ($xml_array);
	}

	/**
	 * 获取来源页面中的搜索引擎的搜索的关键字
	 * @param string $ref_url 搜索的URL地址
	 * @return string 返回用户搜索的关键字
	 */
	public static function get_search_keyword($ref_url)
	{
		if (empty($ref_url))
		{
			return '';
		}

		$tmp_array = parse_url(urldecode($ref_url));
		if (!isset($tmp_array['query']) || !$tmp_array['query'])
		{
			return '';
		}

		$host_array  = explode('.', $tmp_array['host']);
		$host        = (count($host_array) >= 3) ? $host_array[1] : $host_array[0];
		$query_array = array();
		parse_str($tmp_array['query'], $query_array);

		switch ($host)
		{
			case 'baidu' :
				$key_str = isset($query_array['wd']) ? $query_array['wd'] : $query_array['word'];
				$is_utf8 = false;
				break;
			case 'google':
				$key_str = $query_array['q'];
				$is_utf8 = ($query_array['ie'] == 'GB') ? false : true;
				break;
			case 'yahoo':
				$is_utf8 = (strtolower($query_array['ei']) == 'utf-8') ? true : false;
				$key_str = ($query_array['p']) ? $query_array['p'] : $query_array['keyword'];
				break;
			case 'zhongsou':
				$key_str = isset($query_array['word']) ? $query_array['word'] : $query_array['w'];
				$is_utf8 = false;
				break;
			case 'sogou':
				$key_str = $query_array['query'];
				$is_utf8 = false;
				break;
			case 'iask':
				$key_str = $query_array['k'];
				$is_utf8 = false;
				break;
			case '163' :
				$key_str = $query_array['q'];
				$is_utf8 = false;
				break;
			case '114' :
				$key_str = isset($query_array['keyword']) ? $query_array['keyword'] : $query_array['kw'];
				$is_utf8 = false;
				break;
			case 'live' :
				$key_str = $query_array['q'];
				$is_utf8 = true;
				break;
			case 'soso' :
				$key_str = $query_array['w'];
				$is_utf8 = false;
				break;
			case 'youdao':
				$key_str = $query_array['q'];
				$is_utf8 = (strtolower($query_array['ue']) == 'utf8') ? true : false;
				break;
			default:
				return '';
		}

		if (!$is_utf8)
		{
			$key_str = iconv('GBK', 'UTF-8', $key_str);
		}

		return $key_str;
	}

	/**
	 * 将获取的PHPINFO信息生成一维数组
	 *
	 * @return array
	 */
	public static function get_php_info()
	{
		ob_start();
		phpinfo();
		$s = ob_get_contents();
		ob_end_clean();

		$a = $mtc = array();
		if (preg_match_all('/<tr><td class="e">(.*?)<\/td><td class="v">(.*?)<\/td>(:?<td class="v">(.*?)<\/td>)?<\/tr>/', $s, $mtc, PREG_SET_ORDER))
		{
			foreach ($mtc as $v)
			{
				if ($v[2] == '<i>no value</i>')
				{
					continue;
				}
				$a[$v[1]] = $v[2];
			}
		}

		return $a;
	}

	/**
	 * hash数组，将数组中的某个字段值索引成数组的KEY
	 *
	 * @param array  $arr 需要HASH的数组
	 * @param string $key 要定的数组字段名
	 * @param string $pre 数组下标前缀
	 * @return array
	 */
	public static function &hash_array($arr, $key, $pre = '')
	{
		if (empty($arr) || !is_array($arr))
		{
			return $arr;
		}

		$rarr = array();
		foreach ($arr as $v)
		{
			if (isset($v[$key]))
			{
				$rarr["{$pre}{$v[$key]}"] = $v;
			}
		}

		return $rarr;
	}

	/**
	 * 将2维数组中某个字段值按分割符组合起来
	 *
	 * @param    array  $arr       要处理的数组
	 * @param    string $fkey      要选择的字段名
	 * @param    string $glue      分割符
	 * @param    string $prefix    组合前缀
	 * @param    string $suffix    组合后缀
	 * @return    string    组合好的字符串
	 */
	public static function array_implode($arr, $fkey, $glue, $prefix = '', $suffix = '')
	{
		if (!is_array($arr) || empty($arr))
		{
			return "{$prefix}{$suffix}";
		}

		$tmp_arr = array();
		foreach ($arr as $v)
		{
			if (isset($v[$fkey]))
			{
				$tmp_arr[] = $v[$fkey];
			}
		}
		$str = !empty($tmp_arr) ? implode($glue, $tmp_arr) : '';

		return "{$prefix}{$str}{$suffix}";
	}
}
?>