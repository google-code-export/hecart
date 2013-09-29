<?php
/**
 * 慧佳工作室 -> hoojar studio
 *
 * 模块: $Id: config.php 1 2012-11-20 05:55:12Z Administrator $
 * 简述: 网站各大参数设置 （注此文件需COPY一份到此目录下并改名为setting.php）
 * 作者: woods·zhang  ->  hoojar@163.com
 *
 * 版权 2006-2013, 慧佳工作室拥有此系统所有版权等知识产权
 * Copyright 2006-2013, Hoojar Studio All Rights Reserved.
 *
 * 设置出错等级
 */
mb_internal_encoding('UTF-8');								//系统使用默认字符集为UTF-8
ini_set('error_reporting',	E_ALL | E_STRICT);				//出错等级
ini_set('display_errors',	isset($_GET['error']) ? 1 : 1);	//是否显示出错信息0关1开
ini_set('date.timezone',	'Asia/Shanghai');				//设置时区

/**
 * 执行文件的文件名与文件路径
 */
$pinfo = pathinfo($_SERVER['SCRIPT_FILENAME']);
define('EXEC_PATH',	$pinfo['dirname']);						//执行文件所在的全路径
define('EXEC_FILE',	$pinfo['basename']);					//执行文件的名称含扩展名
define('EXEC_EXT',	$pinfo['extension']);					//执行文件的扩展名
define('EXEC_NAME',	strtok(EXEC_FILE, '.'));				//执行文件名不含扩展名
unset($pinfo);

/**
 * 图片与JS本要加载或CDN加载开关
 */
define('USE_ISLOCAL_IMG',		0);		//是否使用本机静态文件（图片）
define('USE_ISLOCAL_JS2CSS',	1);		//是否使用本机静态文件（JS,CSS）

/**
 * 提升浏览者速度
 */
define('COMPRESS_HTML',		true);		//是否压缩HTML,为真且不出错展示关闭则对HTML进行压缩（注：如果代码写得较乱者，压缩可能会无法展示）
define('COMPRESS_JS2CSS',	true);		//是否压缩JS与CSS代码,为真且不出错展示关闭则对HTML进行压缩（注：如果代码写得较乱者，压缩可能会导致无法正常执行）

/**
 * 系统常规设置
 */
define('PRICE_ROUND',		-1);		//价格四舍五入，小于0则不4舍5入，大于等于0则4舍5入到几位小数
define('SESSION_SAVE_TYPE',	'db');		//SESSION采取哪种类型与存储长度：db(max:65535) mdb(max:255) mem(max:unlimit) file(max:unlimit) dir(max:unlimit)

define('SPEED_DATA',		true);		//是否启用加速数据服务将HTML缓冲起来或生成静态文件
define('SPEED_DATA_EXPIRE',	30);		//加速数据有效期(单位分钟)

define('MEM_CACHED',		true);		//定义MEMCACHED是否有效
define('MEM_ASYN_SQL_NUM',	100);		//当异步存储了SQL条数达到此数就执行SQL

define('SQL_DEBUG',			false);		//测试SQL调试开关(true or false)
define('SQL_ERROR_FOR_TIP',	false);		//SQL语句执行出错用wcore_tip来展示

define('LOGIN_ERR_NUM',		3);			//登录出错最多可多少次后就锁帐号
define('LOGIN_PASS_HOUR',	6);			//登录出错间隔多少小时内不能登录

define('SITE_MD5_KEY',		'*#6@9');	//网站MD5密匙
define('IMAGES_PATH',		'/img/');	//产品图片相对于www目录而言的相对文件路径
define('DOMAIN_NAME',		isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'hecart.com');//当前域名

/**
 * HTTP URL
 */
define('HTTP_STORE',	'http://w.hecart.com/');		//商城HTTP主页地址
define('HTTPS_STORE',	'https://w.hecart.com/');		//商城HTTPS主页地址
define('DIR_IMAGE',		DIR_ROOT . '/www' . IMAGES_PATH);	//商品图片存储路径

/**
 * 数据库读取类型
 */
define('DB_PREFIX',		'he_');					//数据库表前缀
define('DB_GET_ONE',	'fetch_one');			//获取一个数据
define('DB_GET_ROW',	'fetch_row');			//获取一条数据
define('DB_GET_ALL',	'fetch_all');			//获取多条数据
define('DB_GET_PAIRS',	'fetch_pairs');			//获取一对数据

/**
 * 数据库主库,一般用于写入数据
 */
$db_server						= array();		//数据库服务器连接名称
$db_server['master']['dbtype']	= 'mysqli';		//数据库连接类型
$db_server['master']['host']	= 'localhost';	//数据库服务器主机
$db_server['master']['port']	= 3306;			//数据库服务器主机端口
$db_server['master']['user']	= 'root';		//数据库用户名
$db_server['master']['pwd']		= '123456';		//数据库密码
$db_server['master']['dbname']	= 'hecart';		//数据库名
$db_server['master']['charset']	= 'utf8';		//数据库字符集
$db_server['master']['pconnect']= false;		//是否持续链接数据库

/**
 * 数据库从库,一般用于只读取数据
 */
$db_server['slave']['dbtype']	= 'mysqli';		//数据库连接类型
$db_server['slave']['host']		= 'localhost';	//数据库服务器主机
$db_server['slave']['port']		= 3306;			//数据库服务器主机端口
$db_server['slave']['user']		= 'root';		//数据库用户名
$db_server['slave']['pwd']		= '123456';		//数据库密码
$db_server['slave']['dbname']	= 'hecart';		//数据库名
$db_server['slave']['charset']	= 'utf8';		//数据库字符集
$db_server['slave']['pconnect']	= false;		//是否持续链接数据库
define('DB_SERVERS', json_encode($db_server));
unset($db_server);

/**
 * 缓冲MEMCACHED服务器
 */
define('MEM_USE',		false);			//是否开启使用MEMCACHED服务器
define('MEM_PORT',		11211);			//MEMCACHED单机服务器端口号
define('MEM_EXPIRE',	30);			//MEMCACHED服务器存储数据的有效期，以分钟为单位
define('MEM_PREFIX',	DOMAIN_NAME);	//存储MEMCACHED数据时KEY的前缀
$mem_servers	= array();
$mem_servers[]	= '127.0.0.1:11211';	//MEMCACHED服务器主机1:端口
$mem_servers[]	= '127.0.0.1:11211';	//MEMCACHED服务器主机2:端口
$mem_servers[]	= '127.0.0.1:11211';	//MEMCACHED服务器主机3:端口
define('MEM_SERVERS', json_encode($mem_servers));
unset($mem_servers);

/**
 * 网站图片主机域名（转换图片地址为网站实际对应的地址或CDN地址）
 */
$img_urls	= array();
$img_urls[]	= 'http://img1.hecart.com';	//图片主机域名1
$img_urls[]	= 'http://img2.hecart.com';	//图片主机域名2
$img_urls[]	= 'http://img3.hecart.com';	//图片主机域名3
define('IMG_URLS', json_encode($img_urls));
unset($img_urls);

/**
 * SMTP服务器
 */
$smtp_server		= array();
$smtp_server['host']= 'mail.hecart.com';	//SMTP服务器主机
$smtp_server['port']= 25;					//SMTP服务器端口
$smtp_server['user']= 'getpwd@hecart.com';	//SMTP登录用户账号
$smtp_server['upwd']= 'pwd!(*#489';			//SMTP登录用户密码
$smtp_server['cset']= 'utf-8';				//SMTP邮件内容编码
define('SMTP_SERVER', json_encode($smtp_server));
unset($smtp_server);
?>