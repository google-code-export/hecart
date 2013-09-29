<?php
class Request
{
	public $get = array();

	public $post = array();

	public $cookie = array();

	public $files = array();

	public $server = array();

	public function __construct()
	{
		$this->get     = & $_GET;
		$this->post    = & $_POST;
		$this->request = & $_REQUEST;
		$this->cookie  = & $_COOKIE;
		$this->files   = & $_FILES;
		$this->server  = & $_SERVER;
	}

	/**
	 * 安全获取变量
	 *
	 * @param string $ob      为要取的数据名字
	 * @param string $type    为要取的是什么数据类型 (i=整形, d=整形, f=浮点, s=字符, c=字符, b=布尔, a=数组, o=对象)
	 * @param string $gpcs    为是取外部变量还是 get post cookie session，或要取session变量则设置成s，取cookie则设置为c
	 * @param mixed  $default 当取不到数据时则将数据设置成默认值
	 * @return mixed
	 */
	public function get_var($ob, $type = '', $gpcs = '', $default = null)
	{
		if (empty($ob))
		{
			return $default;
		}
		$type = ($type) ? strtolower($type) : ''; //数据类型
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
			return $value; //0非常的特殊用empty去判断0结果为true
		}
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
				return (settype($value, 'string')) ? $value : '';
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
				return $value;
		}
	}
}

?>