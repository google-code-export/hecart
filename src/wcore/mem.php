<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 模块: wcore/mem.php
 * 简述: 专门用于提供各种memcached操作
 * 作者: woods·zhang  ->  hoojar@163.com
 * 版本: $Id: mem.php 1 2012-11-20 05:55:12Z Administrator $
 * 版权: Copyright 2006-2013 慧佳工作室拥有此系统所有版权等知识产权
 *
 */
class wcore_mem
{
	/**
	 * memcached 的连接对象
	 *
	 * @var object Memcache
	 */
	public $object = null;

	/**
	 * MEM缓冲的有效期,以分钟为单位
	 *
	 * @var int
	 */
	public $expire = 30;

	/**
	 * 存储数据时KEY的前缀
	 *
	 * @var string
	 */
	public $prefix = '';

	/**
	 * 存储数据时的方式 0为一般MEMCACHE_COMPRESSED为压缩存储
	 *
	 * @var int
	 */
	private $_flag = 0;

	/**
	 * 是否开启使用MEM功能
	 *
	 * @var boolean
	 */
	private $_is_use = true;

	/**
	 * 构造函数，初始化memcached
	 *
	 * @param mixed   $servers 服务器主机或服务器数组 $v[0]['host'] $v[0]['port']
	 * @param integer $port    端口号
	 * @param boolean $is_use  是否使用MEM
	 * @param integer $expire  MEM的有效期,以分钟为单位
	 * @param string  $prefix  存储数据时KEY的前缀
	 */
	public function __construct($servers, $port = 11211, $is_use = true, $expire = 30, $prefix = '')
	{
		//是否可以使用MEM功能
		if (!$this->_is_use = $is_use)
		{
			return;
		}

		$this->expire = intval($expire); //有效期时间
		$this->prefix = $prefix; //KEY的前缀
		$this->object = new Memcache;

		//增加MEMCACHE服务器
		if (is_array($servers))
		{
			foreach ($servers as $v)
			{
				$trs = explode(':', $v);
				$this->object->addServer($trs[0], $trs[1]);
			}
		}
		else
		{
			$this->object->addServer($servers, $port);
		}

		//使用压缩存储数据
		if (function_exists('gzcompress'))
		{
			$this->_flag = MEMCACHE_COMPRESSED;
		}
	}

	/**
	 * 析构函数 关闭连接
	 *
	 */
	public function __destruct()
	{
		$this->close();
	}

	/**
	 * 组合Memcache的KEY
	 *
	 * @param string $t 数据类型字
	 * @param string $k 数据名称
	 * @return string
	 */
	private function _get_key($t, $k)
	{
		return "{$this->prefix}{$t}-{$k}";
	}

	/**
	 * 存储数据
	 *
	 * @param string $type   数据类型说明
	 * @param string $key    数据名称
	 * @param mixed  $value  数据
	 * @param int    $expire 有效期以分钟为单位,为0时则永不过期只有当MEM服务器关闭才过期
	 * @return boolean 存储成功为true反知为false
	 */
	public function set($type, $key, &$value, $expire = -1)
	{
		if (!$this->_is_use)
		{
			return false;
		}
		$prefix = $this->_get_key($type, $key);

		//mt_rand(1, 120)为增加一个两分钟内的随机值，以避免对应缓存的同时更新
		if ($expire > 0)
		{
			$expire = $expire * 60 + mt_rand(1, 120);
		}
		elseif ($expire < 0)
		{
			$expire = $this->expire * 60 + mt_rand(1, 120);
		}

		return $this->object->set($prefix, $value, $this->_flag, $expire);
	}

	/**
	 * 获取数据
	 *
	 * @param string $type    数据类型说明
	 * @param string $key     数据名称
	 * @param mixed  $default 默认值
	 * @return mixed
	 */
	public function &get($type, $key, $default = null)
	{
		if (!$this->_is_use || isset($_GET['nocache']))
		{
			return $default;
		}
		$prefix = $this->_get_key($type, $key);
		$res    = $this->object->get($prefix);

		return $res;
	}

	/**
	 * 为某个数字类型的数据名称增值
	 *
	 * @param string $type  数据类型说明
	 * @param string $key   数据名称
	 * @param int    $value 要增加的数值
	 * @return mixed 成功为增加后的值失败则返回false
	 */
	public function increment($type, $key, $value = 1)
	{
		if (!$this->_is_use || !is_numeric($value))
		{
			return false;
		}
		$prefix = $this->_get_key($type, $key);

		return $this->object->increment($prefix, $value);
	}

	/**
	 * 为某个数字类型的数据名称减值
	 *
	 * @param string $type  数据类型说明
	 * @param string $key   数据名称
	 * @param int    $value 要减的数值
	 * @return mixed 成功为减后的值失败则返回false
	 */
	public function decrement($type, $key, $value = 1)
	{
		if (!$this->_is_use || !is_numeric($value))
		{
			return false;
		}
		$prefix = $this->_get_key($type, $key);

		return $this->object->decrement($prefix, $value);
	}

	/**
	 * 删除某个数据名称当中的数据
	 *
	 * @param string $type 数据类型说明
	 * @param string $key  数据名称
	 * @return boolean 删除成功为true反知为false
	 */
	public function del($type, $key)
	{
		if (!$this->_is_use)
		{
			return false;
		}
		$prefix = $this->_get_key($type, $key);

		return $this->object->delete($prefix);
	}

	/**
	 * 删除某个数据名称当中的数据del的别名函数
	 *
	 * @param string $type 数据类型说明
	 * @param string $key  数据名称
	 * @return boolean 删除成功为true反知为false
	 */
	public function delete($type, $key)
	{
		return $this->del($type, $key);
	}

	/**
	 * 清空MEM当中的所有数据
	 *
	 * @return boolean 清空成功为true反知为false
	 */
	public function flush()
	{
		if (!$this->_is_use)
		{
			return false;
		}

		return $this->object->flush();
	}

	/**
	 * 关闭MEM对象
	 *
	 * @return boolean 关闭成功为true反知为false
	 */
	public function close()
	{
		if (is_object($this->object))
		{
			return $this->object->close();
		}

		return true;
	}

	/**
	 * 获取是否开启MEM功能
	 *
	 * @return boolean
	 */
	public function is_use()
	{
		return $this->_is_use;
	}
}
?>