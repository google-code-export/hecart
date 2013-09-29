<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 模块: wcore/object.php
 * 简述: 全局对象操作接口
 * 作者: woods·zhang  ->  hoojar@163.com
 * 版本: $Id: object.php 1 2012-11-20 05:55:12Z Administrator $
 * 版权: Copyright 2006-2013 慧佳工作室拥有此系统所有版权等知识产权
 *
 */
class wcore_object
{
	/**
	 * 常用函数接口
	 *
	 * @var wcore_utils
	 */
	private static $_utils = null;

	/**
	 * 提示函数接口
	 *
	 * @var wcore_tip
	 */
	private static $_tip = null;

	/**
	 * 操作数据库接口
	 *
	 * @var wcore_mysql
	 */
	private static $_db = array();

	/**
	 * 操作MEMCACHED库接口
	 *
	 * @var wcore_mem
	 */
	private static $_mem = null;

	/**
	 * 操作MEMCACHED库接口
	 *
	 * @var Smarty
	 */
	private static $_smarty = null;

	/**
	 * 常用函数接口
	 *
	 * @return wcore_utils 返回常用函数对象
	 */
	public static function &utils()
	{
		if (is_object(self::$_utils))
		{
			return self::$_utils;
		}
		self::$_utils = new wcore_utils();

		return self::$_utils;
	}

	/**
	 * 提示函数接口
	 *
	 * @return wcore_tip 返回常用函数对象
	 */
	public static function &tip()
	{
		if (is_object(self::$_tip))
		{
			return self::$_tip;
		}
		self::$_tip = new wcore_tip();

		return self::$_tip;
	}

	/**
	 * 操作数据库接口
	 * @param string $name
	 * @return wcore_mssql|wcore_mysql|wcore_mysqli|wcore_oci 返回操作数据的对象
	 */
	public static function &db($name = '')
	{
		/**
		 * 判断数据库连接是否已生成数组连接池,是则定位到要调用的连接对象
		 */
		if (isset(self::$_db[$name]))
		{
			return self::$_db[$name]; //数据连接从连接池数组当中取
		}

		/**
		 * 生成数据连接数组池
		 */
		$db_servers = json_decode(DB_SERVERS, true);
		foreach ($db_servers as $k => $v)
		{
			if ($k != $name)
			{
				continue; //若$name不为空就只注册连接需要打开的数据库对象
			}

			switch (strtolower($v['dbtype']))
			{
				case 'mysqli':
					$db = new wcore_mysqli($v['host'], $v['user'], $v['pwd'], $v['dbname'], $v['charset'], $v['port'], $v['pconnect']);
					break;
				case 'oci':
					$db = new wcore_oci($v['host'], $v['user'], $v['pwd'], $v['dbname'], $v['charset'], $v['port'], $v['pconnect']);
					break;
				case 'mssql':
					$db = new wcore_mssql($v['host'], $v['user'], $v['pwd'], $v['dbname'], $v['charset'], $v['port'], $v['pconnect']);
					break;
				default:
					$db = new wcore_mysql($v['host'], $v['user'], $v['pwd'], $v['dbname'], $v['charset'], $v['port'], $v['pconnect']);
					break;
			}
			self::$_db[$k] = $db;

			return $db;
		}

		exit("System can't connect '{$name}' connection name objects.");
	}

	/**
	 * 操作MEMCACHED库接口
	 *
	 * @return wcore_mem 返回操作数据的对象
	 */
	public static function &mem()
	{
		if (is_object(self::$_mem))
		{
			return self::$_mem;
		}

		$mem_servers = json_decode(MEM_SERVERS, true);
		self::$_mem  = new wcore_mem($mem_servers, MEM_PORT, MEM_USE, MEM_EXPIRE, MEM_PREFIX);

		return self::$_mem;
	}

	/**
	 * 操作Smarty库接口
	 *
	 * @return Smarty 返回操作Smarty的对象
	 */
	public static function &smarty()
	{
		if (is_object(self::$_smarty))
		{
			return self::$_smarty;
		}

		require(DIR_ROOT . '/smarty/Smarty.class.php');
		$site_theme = get_site_theme(); //获取站点模板主题
		$doc_root   = get_doc_root_name(); //获取站点目录名称

		/**
		 * SMARTY 缓冲目录
		 */
		$smarty_cache_dir = SMARTY_CACHE_DIR . "{$doc_root}/{$site_theme}";
		if (!file_exists($smarty_cache_dir))
		{
			@mkdir($smarty_cache_dir, 0777, true);
		}

		/**
		 * SMARTY 模板目录
		 */
		$smarty_template_dir = DIR_ROOT . "/{$doc_root}/site/{$site_theme}";
		if (!file_exists($smarty_template_dir))
		{
			@mkdir($smarty_template_dir, 0777, true);
		}

		/**
		 * SMARTY 编译目录
		 */
		$smarty_compile_dir = SMARTY_COMPILE_DIR . "{$doc_root}/{$site_theme}";
		if (!file_exists($smarty_compile_dir))
		{
			@mkdir($smarty_compile_dir, 0777, true);
		}

		self::$_smarty                  = new Smarty();
		self::$_smarty->caching         = SMARTY_CACHE;
		self::$_smarty->debugging       = SMARTY_DEBUGGING;
		self::$_smarty->cache_lifetime  = SMARTY_CACHE_LIFETIME;
		self::$_smarty->cache_dir       = $smarty_cache_dir;
		self::$_smarty->template_dir    = $smarty_template_dir;
		self::$_smarty->compile_dir     = $smarty_compile_dir;
		self::$_smarty->left_delimiter  = SMARTY_LEFT_DELIMITER;
		self::$_smarty->right_delimiter = SMARTY_RIGHT_DELIMITER;

		return self::$_smarty;
	}

	/**
	 * 选择或检查数据连接是否已加载，若没有加载则马上加载
	 *
	 * @param string $tname 操作表名
	 * @param string $lname 连接名称(master|slave)
	 * @return modules_dbase
	 */
	public static function &dbase($tname, $lname = '')
	{
		static $dbase_cls = null;
		if (!isset($dbase_cls[$lname]))
		{
			$dbase_cls[$lname] = new modules_dbase();
			$dbase_cls[$lname]->select_db_link($lname);
		}
		$dbase_cls[$lname]->_opt = $tname;

		return $dbase_cls[$lname];
	}

	/**
	 * 主数据库连接操作(可写可读)
	 *
	 * @return wcore_mysql 返回操作数据的对象
	 */
	public static function mdb()
	{
		static $_db = null;
		if (is_null($_db))
		{
			$_db = wcore_object::db('master');
		}

		return $_db;
	}

	/**
	 * 从数据库连接操作(只读)
	 *
	 * @return wcore_mysql 返回操作数据的对象
	 */
	public static function sdb()
	{
		static $_db = null;
		if (is_null($_db))
		{
			$_db = wcore_object::db('slave');
		}

		return $_db;
	}
}
?>