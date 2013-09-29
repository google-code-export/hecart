<?php
final class Loader
{
	/**
	 * @var Registry
	 */
	protected $registry;

	public function __construct($registry)
	{
		$this->registry = $registry;
	}

	public function __get($key)
	{
		return $this->registry->get($key);
	}

	public function __set($key, $value)
	{
		$this->registry->set($key, $value);
	}

	public function library($library)
	{
		$file = DIR_ROOT . "/system/library/{$library}.php";
		if (file_exists($file))
		{
			include($file);
		}
		else
		{
			trigger_error('Error: Could not load library ' . $library . '!');
			exit();
		}
	}

	public function helper($helper)
	{
		$file = DIR_ROOT . "/system/helper/{$helper}.php";
		if (file_exists($file))
		{
			include($file);
		}
		else
		{
			trigger_error('Error: Could not load helper ' . $helper . '!');
			exit();
		}
	}

	public function model($model)
	{
		$class     = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
		$model_cls = 'model_' . str_replace('/', '_', $model);
		if (!is_null($this->registry->get($model_cls)))
		{
			return;
		}

		$file = DIR_SITE . '/model/' . $model . '.php';
		if (file_exists($file))
		{
			include($file);
			$this->registry->set($model_cls, new $class($this->registry));
		}
		else
		{
			trigger_error('Error: Could not load model ' . $model . '!');
			exit();
		}
	}

	public function database($driver, $hostname, $username, $password, $database, $prefix = null, $charset = 'UTF8')
	{
		$file  = DIR_ROOT . "'/system/database/{$driver}.php";
		$class = 'Database' . preg_replace('/[^a-zA-Z0-9]/', '', $driver);

		if (file_exists($file))
		{
			include($file);
			$this->registry->set(str_replace('/', '_', $driver), new $class());
		}
		else
		{
			trigger_error('Error: Could not load database ' . $driver . '!');
			exit();
		}
	}

	public function config($config)
	{
		$this->config->load($config);
	}

	public function language($language)
	{
		return $this->language->load($language);
	}
}

?>