<?php
//Version
define('VERSION', '1.0.0');

//Configuration
define('DIR_SITE', empty($_SERVER['DOCUMENT_ROOT']) ? dirname(__FILE__) : $_SERVER['DOCUMENT_ROOT']);
define('DIR_ROOT', empty($_SERVER['DOCUMENT_ROOT']) ? dirname(dirname(__FILE__)) : dirname($_SERVER['DOCUMENT_ROOT']));
require(DIR_ROOT . '/config/start.php'); //loading start for here

//Cache OR static HTML file
if (true)
{
	//此处加速适合于多语言多货币
	$_GET['language'] = wcore_utils::get_var('language', '', 'c');
	$_GET['currency'] = wcore_utils::get_var('currency', '', 'c');
	$speed            = new wcore_speed('mem');
	unset($_GET['language'], $_GET['currency']);
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
require($vqmod->modCheck(DIR_ROOT . '/system/library/customer.php'));
require($vqmod->modCheck(DIR_ROOT . '/system/library/affiliate.php'));
require($vqmod->modCheck(DIR_ROOT . '/system/library/currency.php'));
require($vqmod->modCheck(DIR_ROOT . '/system/library/tax.php'));
require($vqmod->modCheck(DIR_ROOT . '/system/library/weight.php'));
require($vqmod->modCheck(DIR_ROOT . '/system/library/length.php'));
require($vqmod->modCheck(DIR_ROOT . '/system/library/cart.php'));

//Registry
$registry = new Registry();

//Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

//Config
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

//Settings
$res = $mem_cls->mem_sql("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = " . intval($config->get('config_store_id')), DB_GET_ALL);
foreach ($res as $setting)
{
	$config->set($setting['key'], ($setting['serialized']) ? unserialize($setting['value']) : $setting['value']);
}

//Url
$url = new Url($config->get('config_url'), $config->get('config_use_ssl') ? $config->get('config_ssl') : $config->get('config_url'));
$registry->set('url', $url);

//Log
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

//Error Handler
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

//Request
$request = new Request();
$registry->set('request', $request);

//Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));
$registry->set('response', $response);

//Session
$session = new wcore_session(SESSION_SAVE_TYPE);
$registry->set('session', $session);

//Language Detection
$languages = get_languages($mem_cls);
$code      = $config->get('config_language');
if (isset($request->cookie['language']) && isset($languages[$request->cookie['language']]) && $languages[$request->cookie['language']]['status'])
{
	$code = $request->cookie['language'];
}
else //自动检测语言
{
	if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && ($request->server['HTTP_ACCEPT_LANGUAGE']))
	{
		$browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);
		foreach ($browser_languages as $browser_language)
		{
			foreach ($languages as $key => $value)
			{
				if ($value['status'])
				{
					$locale = explode(',', $value['locale']);
					if (in_array($browser_language, $locale))
					{
						$code = $key;
					}
				}
			}
		}
	}
	$request->cookie['language'] = $code;
	wcore_utils::set_cookie('language', $code, 365);
}
$config->set('config_language_id', $languages[$code]['language_id']);
$config->set('config_language', $languages[$code]['code']);

//Language
$language = new Language($languages[$code]['directory']);
$language->load($languages[$code]['filename']);
$registry->set('language', $language);

//Document
$registry->set('document', new Document());

//Customer
$registry->set('customer', new Customer($registry));

//Affiliate
$registry->set('affiliate', new Affiliate($registry));
if (isset($request->get['tracking']) && !isset($request->cookie['tracking']))
{
	wcore_utils::set_cookie('tracking', $request->get['tracking'], 365);
}

//Currency
$registry->set('currency', new Currency($registry));

//Tax
$registry->set('tax', new Tax($registry));

//Weight
$registry->set('weight', new Weight($registry));

//Length
$registry->set('length', new Length($registry));

//Cart
$registry->set('cart', new Cart($registry));

//Encryption
$registry->set('encryption', new Encryption($config->get('config_encryption')));

//Front Controller
$controller = new Front($registry);

//Router
$action = new Action(isset($request->get['route']) ? $request->get['route'] : 'common/home');

//Dispatch
$controller->dispatch($action, new Action('error/not_found'));

//Output
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