<?php
// Version
define('VERSION', '1.0.0');

// Configuration
define('DIR_SITE', empty($_SERVER['DOCUMENT_ROOT']) ? dirname(__FILE__) : $_SERVER['DOCUMENT_ROOT']);
define('DIR_ROOT', empty($_SERVER['DOCUMENT_ROOT']) ? dirname(dirname(__FILE__)) : dirname($_SERVER['DOCUMENT_ROOT']));
require(DIR_ROOT . '/config/start.php'); //loading start for here

// Cache OR static HTML file
if (true)
{
	//此处加速适合于多语言多货币
	$_GET['islogged'] = 0;
	$_GET['language'] = wcore_utils::get_var('language', 's', 'c');
	$_GET['currency'] = wcore_utils::get_var('currency', 's', 'c');
	$token            = wcore_utils::get_var('token', 's', 'c');
	$vtoken           = wcore_utils::get_var('vtoken', 's', 'c');
	if (!empty($token) && !empty($vtoken))
	{
		$_GET['islogged'] = ($vtoken == md5(substr($token, 3, 16) . SITE_MD5_KEY)) ? 1 : 0;
	}
	$speed = new wcore_speed('mem');
	unset($_GET['islogged'], $_GET['language'], $_GET['currency'], $token, $vtoken);
}
else
{
	//此处加速仅适应于单语言单货币
	$puid  = ($_SERVER["REQUEST_URI"] == '/' || $_SERVER["REQUEST_URI"] == $_SERVER["SCRIPT_NAME"]) ? 'index.html' : $_SERVER["REQUEST_URI"];
	$speed = new wcore_speed(((strpos($puid, '?') === false) ? 'file' : 'mem'), 0, $puid);
}

$html = $speed->get_data();
if (!empty($html))
{
	exit($html);
}

// VirtualQMOD
require(DIR_ROOT . '/vqmod/vqmod.php');
$vqmod = new VQMod();

// VQMODDED Startup
require($vqmod->modCheck(DIR_ROOT . '/system/startup.php'));

// Application Classes
require($vqmod->modCheck(DIR_ROOT . '/system/library/currency.php'));
require($vqmod->modCheck(DIR_ROOT . '/system/library/user.php'));
require($vqmod->modCheck(DIR_ROOT . '/system/library/weight.php'));
require($vqmod->modCheck(DIR_ROOT . '/system/library/length.php'));

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config  = new Config();
$mem_cls = new modules_mem();
$registry->set('config', $config);
$store_info = get_store_info($mem_cls);
if (empty($store_info))
{
	$config->set('config_store_id', 0);
	$config->set('config_url', 'http://' . DOMAIN_NAME . '/');
	$config->set('config_ssl', 'https://' . DOMAIN_NAME . '/');
}
else
{
	$config->set('config_store_id', $store_info['store_id']);
}

// Settings
$res = $mem_cls->mem_sql("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = " . intval($config->get('config_store_id')), DB_GET_ALL);
foreach ($res as $setting)
{
	$config->set($setting['key'], ($setting['serialized']) ? unserialize($setting['value']) : $setting['value']);
}

// Url
$url = new Url('http://' . DOMAIN_NAME . '/', $config->get('config_use_ssl') ? 'https://' . DOMAIN_NAME . '/' : 'http://' . DOMAIN_NAME . '/');
$registry->set('url', $url);

// Log
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

// Error Handler
function error_handler($errno, $errstr, $errfile, $errline)
{
	global $log, $config;
	switch ($errno)
	{
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}

	if ($config->get('config_error_display'))
	{
		echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}

	if ($config->get('config_error_log'))
	{
		$log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return true;
}

set_error_handler('error_handler');

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Session
$session = new wcore_session(SESSION_SAVE_TYPE);
$registry->set('session', $session);

// Language
$languages = get_languages($mem_cls, true);
$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);

// Language
$language = new Language($languages[$config->get('config_admin_language')]['directory']);
$language->load($languages[$config->get('config_admin_language')]['filename']);
$registry->set('language', $language);

// Document
$registry->set('document', new Document());

// Currency
$registry->set('currency', new Currency($registry));

// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// User
$registry->set('user', new User($registry));

// Front Controller
$controller = new Front($registry);

// Login
$controller->addPreAction(new Action('common/home/login'));

// Permission
$controller->addPreAction(new Action('common/home/permission'));

// Router
$action = new Action(isset($request->get['route']) ? $request->get['route'] : 'common/home');

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
if (defined('WCORE_SPEED'))
{
	$html = $response->render();
	$speed->set_data($html);
	unset($speed);
	echo($html);
}
else
{
	$response->output();
}
?>