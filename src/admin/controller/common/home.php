<?php
class ControllerCommonHome extends Controller
{
	public function index()
	{
		define('WCORE_SPEED', true); //允许缓冲页面
		$this->load->language('common/home');
		$this->document->setTitle($this->language->get('heading_title'));

		$lang_arr = array(
			'heading_title',
			'text_overview',
			'text_statistics',
			'text_latest_10_orders',
			'text_total_sale',
			'text_total_sale_year',
			'text_total_order',
			'text_total_customer',
			'text_total_customer_approval',
			'text_total_review_approval',
			'text_total_affiliate',
			'text_total_affiliate_approval',
			'text_day',
			'text_week',
			'text_month',
			'text_year',
			'text_no_results',
			'column_order',
			'column_customer',
			'column_status',
			'column_date_added',
			'column_total',
			'column_firstname',
			'column_lastname',
			'column_action',
			'entry_range'
		);
		foreach ($lang_arr as $v)
		{
			$this->data[$v] = $this->language->get($v);
		}

		// Check install directory exists
		if (is_dir(dirname(DIR_SITE) . '/install'))
		{
			$this->data['error_install'] = $this->language->get('error_install');
		}
		else
		{
			$this->data['error_install'] = '';
		}

		// Check image directory is writable
		if (!is_writable(DIR_IMAGE))
		{
			$this->data['error_image'] = sprintf($this->language->get('error_image'), DIR_IMAGE);
		}
		else
		{
			$this->data['error_image'] = '';
		}

		// Check image cache directory is writable
		if (!is_writable(DIR_IMAGE . 'cache'))
		{
			$this->data['error_image_cache'] = sprintf($this->language->get('error_image_cache'), DIR_IMAGE . 'cache/');
			$this->data['error_cache']       = sprintf($this->language->get('error_image_cache'), DIR_ROOT . '/system/cache/');
		}
		else
		{
			$this->data['error_image_cache'] = '';
			$this->data['error_cache']       = '';
		}

		// Check download directory is writable
		if (!is_writable(DIR_ROOT . '/www/download'))
		{
			$this->data['error_download'] = sprintf($this->language->get('error_download'), DIR_ROOT . '/www/download/');
		}
		else
		{
			$this->data['error_download'] = '';
		}

		// Check logs directory is writable
		if (!is_writable(DIR_ROOT . '/system/logs'))
		{
			$this->data['error_logs'] = sprintf($this->language->get('error_logs'), DIR_ROOT . '/system/logs/');
		}
		else
		{
			$this->data['error_logs'] = '';
		}

		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		$this->load->model('sale/order');
		$this->data['total_sale']      = $this->currency->format($this->model_sale_order->getTotalSales(), $this->config->get('config_currency'));
		$this->data['total_sale_year'] = $this->currency->format($this->model_sale_order->getTotalSalesByYear(date('Y')), $this->config->get('config_currency'));
		$this->data['total_order']     = $this->model_sale_order->getTotalOrders();

		$this->load->model('sale/customer');
		$this->data['total_customer']          = $this->model_sale_customer->getTotalCustomers();
		$this->data['total_customer_approval'] = $this->model_sale_customer->getTotalCustomersAwaitingApproval();

		$this->load->model('catalog/review');
		$this->data['total_review']          = $this->model_catalog_review->getTotalReviews();
		$this->data['total_review_approval'] = $this->model_catalog_review->getTotalReviewsAwaitingApproval();

		$this->load->model('sale/affiliate');
		$this->data['total_affiliate']          = $this->model_sale_affiliate->getTotalAffiliates();
		$this->data['total_affiliate_approval'] = $this->model_sale_affiliate->getTotalAffiliatesAwaitingApproval();
		$this->data['orders']                   = array();

		$data    = array(
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);
		$results = $this->model_sale_order->getOrders($data);
		foreach ($results as $result)
		{
			$action                 = array();
			$action[]               = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('sale/order/info', '&osn=' . $result['osn'], 'SSL')
			);
			$this->data['orders'][] = array(
				'order_id'   => $result['osn'],
				'customer'   => $result['customer'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'action'     => $action
			);
		}

		if ($this->config->get('config_currency_auto'))
		{
			$this->load->model('localisation/currency');
			$this->model_localisation_currency->updateCurrencies();
		}

		$this->template = 'template/home.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	public function chart()
	{
		define('WCORE_SPEED', true); //允许缓冲页面
		$this->load->language('common/home');
		$data                      = array();
		$data['order']             = array();
		$data['customer']          = array();
		$data['xaxis']             = array();
		$data['order']['label']    = $this->language->get('text_order');
		$data['customer']['label'] = $this->language->get('text_customer');

		$range = (isset($this->request->get['range'])) ? $this->request->get['range'] : 'month';
		switch ($range)
		{
			case 'day':
				for ($i = 0; $i < 24; $i++)
				{
					$sql                     = ("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND (DATE(date_added) = DATE(NOW()) AND HOUR(date_added) = '{$i}') GROUP BY HOUR(date_added) ORDER BY date_added ASC");
					$num_rows                = $this->mem_sql($sql, DB_GET_ONE);
					$data['order']['data'][] = array(
						$i,
						$num_rows
					);

					$sql                        = ("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE DATE(date_added) = DATE(NOW()) AND HOUR(date_added) = '{$i}' GROUP BY HOUR(date_added) ORDER BY date_added ASC");
					$num_rows                   = $this->mem_sql($sql, DB_GET_ONE);
					$data['customer']['data'][] = array(
						$i,
						$num_rows
					);

					$data['xaxis'][] = array(
						$i,
						date('H', mktime($i, 0, 0, date('n'), date('j'), date('Y')))
					);
				}
				break;
			case 'week':
				$date_start = strtotime('-' . date('w') . ' days');
				for ($i = 0; $i < 7; $i++)
				{
					$date                    = date('Y-m-d', $date_start + ($i * 86400));
					$sql                     = ("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND DATE(date_added) = '" . $this->sdb()->escape($date) . "' GROUP BY DATE(date_added)");
					$num_rows                = $this->mem_sql($sql, DB_GET_ONE);
					$data['order']['data'][] = array(
						$i,
						$num_rows
					);

					$sql                        = ("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer` WHERE DATE(date_added) = '" . $this->sdb()->escape($date) . "' GROUP BY DATE(date_added)");
					$num_rows                   = $this->mem_sql($sql, DB_GET_ONE);
					$data['customer']['data'][] = array(
						$i,
						(int)$num_rows
					);

					$data['xaxis'][] = array(
						$i,
						date('D', strtotime($date))
					);
				}

				break;
			default:
			case 'month':
				for ($i = 1; $i <= date('t'); $i++)
				{
					$date                    = date('Y') . '-' . date('m') . '-' . $i;
					$sql                     = ("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND (DATE(date_added) = '" . $this->sdb()->escape($date) . "') GROUP BY DAY(date_added)");
					$num_rows                = $this->mem_sql($sql, DB_GET_ONE);
					$data['order']['data'][] = array(
						$i,
						(int)$num_rows
					);

					$sql                        = ("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE DATE(date_added) = '" . $this->sdb()->escape($date) . "' GROUP BY DAY(date_added)");
					$num_rows                   = $this->mem_sql($sql, DB_GET_ONE);
					$data['customer']['data'][] = array(
						$i,
						(int)$num_rows
					);

					$data['xaxis'][] = array(
						$i,
						date('j', strtotime($date))
					);
				}
				break;
			case 'year':
				for ($i = 1; $i <= 12; $i++)
				{
					$sql                     = ("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND YEAR(date_added) = '" . date('Y') . "' AND MONTH(date_added) = '" . $i . "' GROUP BY MONTH(date_added)");
					$num_rows                = $this->mem_sql($sql, DB_GET_ONE);
					$data['order']['data'][] = array(
						$i,
						(int)$num_rows
					);

					$sql                        = ("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE YEAR(date_added) = '" . date('Y') . "' AND MONTH(date_added) = '" . $i . "' GROUP BY MONTH(date_added)");
					$num_rows                   = $this->mem_sql($sql, DB_GET_ONE);
					$data['customer']['data'][] = array(
						$i,
						(int)$num_rows
					);

					$data['xaxis'][] = array(
						$i,
						date('M', mktime(0, 0, 0, $i, 1, date('Y')))
					);
				}
				break;
		}

		$this->response->setOutput(json_encode($data));
	}

	public function login()
	{
		$route = '';
		if (isset($this->request->get['route']))
		{
			$part = explode('/', $this->request->get['route']);

			if (isset($part[0]))
			{
				$route .= $part[0];
			}

			if (isset($part[1]))
			{
				$route .= '/' . $part[1];
			}
		}

		$ignore = array(
			'common/login',
			'common/forgotten',
			'common/reset'
		);

		if (!$this->user->isLogged() && !in_array($route, $ignore))
		{
			return $this->forward('common/login');
		}

		if (isset($this->request->get['route']))
		{
			$ignore = array(
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'
			);

			$config_ignore = array();
			if ($this->config->get('config_token_ignore'))
			{
				$config_ignore = unserialize($this->config->get('config_token_ignore'));
			}

			$ignore = array_merge($ignore, $config_ignore);
			if (!in_array($route, $ignore) && (!isset($this->request->cookie['token']) || !isset($this->session->data['token']) || ($this->request->cookie['token'] != $this->session->data['token'])))
			{
				return $this->forward('common/login');
			}
		}
		else
		{
			if (!isset($this->request->cookie['token']) || !isset($this->session->data['token']) || ($this->request->cookie['token'] != $this->session->data['token']))
			{
				return $this->forward('common/login');
			}
		}
	}

	/**
	 * 判断用户是否有查看权限
	 */
	public function permission()
	{
		if (isset($this->request->get['route']))
		{
			$route = '';
			$part  = explode('/', $this->request->get['route']);

			if (isset($part[0]))
			{
				$route .= $part[0];
			}

			if (isset($part[1]))
			{
				$route .= '/' . $part[1];
			}

			/**
			 * 排除以下操作路由不判断权限
			 */
			$ignore = array(
				'common/home',
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'
			);

			if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route))
			{
				return $this->forward('error/permission');
			}
		}
	}
}

?>