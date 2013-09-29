<?php
class Model extends modules_mem
{
	public $store_id = 0;

	public $language_id = 1;

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

		$this->store_id    = intval($this->config->get('config_store_id'));
		$this->language_id = intval($this->config->get('config_language_id'));
	}

	public function __get($key)
	{
		return $this->registry->get($key);
	}

	public function __set($key, $value)
	{
		$this->registry->set($key, $value);
	}

	public function fullname($as = '')
	{
		/**
		 * 判断姓是否为汉字,中国人的姓是在前面,其他国家的是姓在后
		 */
		return "IF(LENGTH({$as}lastname)=CHAR_LENGTH({$as}lastname),
				CONCAT({$as}firstname, '·', {$as}lastname),
				CONCAT({$as}lastname,{$as}firstname)) ";
	}
}

?>