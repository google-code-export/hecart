<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 模块: $Id: start.php 49 2012-10-09 06:48:54Z Administrator $
 * 简述: 程序开始文件
 * 作者: woods·zhang  ->  hoojar@163.com
 *
 * 版权 2006-2013, 慧佳工作室拥有此系统所有版权等知识产权
 * Copyright 2006-2013, Hoojar Studio All Rights Reserved.
 *
 * 设置输出是否压缩与加载相关库文件
 */
require(DIR_ROOT . '/config/setting.php');
require(DIR_ROOT . '/wcore/mem.php'); //加载设置与MEM库

/**
 * 自动加载类库
 *
 * @param string $class_name 类名
 */
function _autoload($class_name)
{
	if (false !== strpos($class_name, '_'))
	{
		$class_name = str_replace('_', '/', $class_name);
		$class_name = DIR_ROOT . "/{$class_name}";
	}
	require("{$class_name}.php");
}

spl_autoload_register('_autoload');

/**
 * 从数据库中获取网站列表数据并格式化以域名为数组KEY
 *
 * @param $mem_cls modules_mem
 * @return array
 */
function get_store_info(&$mem_cls)
{
	$store_res = $mem_cls->hash_sql("SELECT * FROM " . DB_PREFIX . "store", 'domain');

	/**
	 * 分析当前域名与哪个数据匹配，先快速定位以域名来判断是否在网站列表数组中
	 */
	$store_info = array();
	$domain     = strtolower(DOMAIN_NAME);
	if (isset($store_res[$domain]))
	{
		$store_info = $store_res[$domain];
	}
	else
	{
		foreach ($store_res as $v)
		{
			if (preg_match("/{$v['domain']}/", $domain))
			{
				$store_info = $v;
				break;
			}
		}
	}

	return $store_info;
}

/**
 * 获取系统语言
 *
 * @param modules_mem $mem_cls 缓冲对象
 * @param bool        $all     获取所有系统语言
 * @return mixed
 */
function get_languages($mem_cls, $all = false)
{
	$sql       = "SELECT * FROM " . DB_PREFIX . "language WHERE " . ($all ? '1' : "status = '1'");
	$languages = $mem_cls->hash_sql($sql, 'code');

	return $languages;
}

?>