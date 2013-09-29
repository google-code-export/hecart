<?php
// Register Globals
if (ini_get('register_globals'))
{
	$globals = array(
		$_REQUEST,
		$_SESSION,
		$_SERVER,
		$_FILES
	);

	foreach ($globals as $global)
	{
		foreach (array_keys($global) as $key)
		{
			unset(${$key});
		}
	}
}

// Magic Quotes Fix
if (!ini_get('magic_quotes_gpc'))
{
	function clean($data)
	{
		if (is_array($data))
		{
			foreach ($data as $key => $value)
			{
				unset($data[$key]);
				$data[clean($key)] = clean($value);
			}
		}
		else
		{
			$data = htmlspecialchars($data, ENT_COMPAT);
		}

		return $data;
	}

	$_GET     = clean($_GET);
	$_POST    = clean($_POST);
	$_REQUEST = clean($_REQUEST);
	$_COOKIE  = clean($_COOKIE);
	$_FILES   = clean($_FILES);
	$_SERVER  = clean($_SERVER);
}

if (!ini_get('date.timezone'))
{
	date_default_timezone_set('UTC');
}

// Windows IIS Compatibility
if (!isset($_SERVER['DOCUMENT_ROOT']))
{
	if (isset($_SERVER['SCRIPT_FILENAME']))
	{
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['DOCUMENT_ROOT']))
{
	if (isset($_SERVER['PATH_TRANSLATED']))
	{
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['REQUEST_URI']))
{
	$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);
	if (isset($_SERVER['QUERY_STRING']))
	{
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
	}
}

// Engine
require(DIR_ROOT . '/system/engine/action.php');
require(DIR_ROOT . '/system/engine/controller.php');
require(DIR_ROOT . '/system/engine/front.php');
require(DIR_ROOT . '/system/engine/loader.php');
require(DIR_ROOT . '/system/engine/model.php');
require(DIR_ROOT . '/system/engine/registry.php');

// Common
require(DIR_ROOT . '/system/library/url.php');
require(DIR_ROOT . '/system/library/config.php');
require(DIR_ROOT . '/system/library/document.php');
require(DIR_ROOT . '/system/library/encryption.php');
require(DIR_ROOT . '/system/library/image.php');
require(DIR_ROOT . '/system/library/language.php');
require(DIR_ROOT . '/system/library/log.php');
require(DIR_ROOT . '/system/library/mail.php');
require(DIR_ROOT . '/system/library/pagination.php');
require(DIR_ROOT . '/system/library/request.php');
require(DIR_ROOT . '/system/library/response.php');
require(DIR_ROOT . '/system/library/template.php');
?>