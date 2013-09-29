<?php
class Controller extends modules_mem
{
	/**
	 * @var Url
	 */
	protected $url;

	/**
	 * @var Tax
	 */
	protected $tax;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var Loader
	 */
	protected $load;

	/**
	 * @var Registry
	 */
	protected $registry;

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var Response
	 */
	protected $response;

	/**
	 * @var Language
	 */
	protected $language;

	/**
	 * @var Currency
	 */
	protected $currency;

	/**
	 * @var wcore_session
	 */
	protected $session;

	/**
	 * @var Document
	 */
	protected $document;

	/**
	 * @var Customer
	 */
	protected $customer;

	/**
	 * @var Log
	 */
	protected $log;

	protected $id;

	protected $layout;

	/**
	 * 模板路径文件名
	 * @var string
	 */
	protected $template = '';

	protected $children = array();

	/**
	 * 模板数据组
	 * @var array
	 */
	protected $data = array();

	/**
	 * 生成好的HTML内容
	 *
	 * @var string 要输出的内容
	 */
	protected $output = '';

	/**
	 * 模板名称
	 * @var string
	 */
	protected $tplname = 'default';

	/**
	 * @param $registry Registry
	 */
	public function __construct($registry)
	{
		parent::__construct();

		$this->registry = $registry;
		$this->tax      = $registry->get('tax');
		$this->url      = $registry->get('url');
		$this->log      = $registry->get('log');
		$this->load     = $registry->get('load');
		$this->config   = $registry->get('config');
		$this->request  = $registry->get('request');
		$this->session  = $registry->get('session');
		$this->response = $registry->get('response');
		$this->language = $registry->get('language');
		$this->currency = $registry->get('currency');
		$this->document = $registry->get('document');
		$this->customer = $registry->get('customer');
		$this->tplname  = $this->config->get('config_template');
	}

	public function __get($key)
	{
		return $this->registry->get($key);
	}

	public function __set($key, $value)
	{
		$this->registry->set($key, $value);
	}

	protected function forward($route, $args = array())
	{
		return new Action($route, $args);
	}

	protected function redirect($url, $status = 302)
	{
		header('Status: ' . $status);
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
		exit();
	}

	protected function getChild($child, $args = array())
	{
		$action = new Action($child, $args);
		if (file_exists($action->getFile()))
		{
			$class = $action->getClass();
			require_once($action->getFile());
			$controller = new $class($this->registry);
			$controller->{$action->getMethod()}($action->getArgs());

			return $controller->output;
		}
		else
		{
			trigger_error('Error: Could not load controller ' . $child);
			exit();
		}
	}

	/**
	 * 根据模板提取生成后的内容
	 * @return string HTML
	 */
	protected function &render()
	{
		foreach ($this->children as $child)
		{
			$this->data[basename($child)] = $this->getChild($child);
		}

		$tpl_file = DIR_SITE . "/view/{$this->template}";
		if (file_exists($tpl_file))
		{
			extract($this->data);
			ob_start();
			require($tpl_file);
			$this->output = ob_get_contents();
			ob_end_clean();

			return $this->output;
		}
		else
		{
			trigger_error('Error: Could not load template ' . $tpl_file . '!');
			exit();
		}
	}
}

?>