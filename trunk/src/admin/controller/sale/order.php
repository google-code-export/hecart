<?php
class ControllerSaleOrder extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('sale/order');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/order');
		$this->getList();
	}

	public function insert()
	{
		$this->load->language('sale/order');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/order');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
		{
			$this->model_sale_order->addOrder($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url                            = '';

			if (isset($this->request->get['filter_order_id']))
			{
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_customer']))
			{
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_order_status_id']))
			{
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}

			if (isset($this->request->get['filter_total']))
			{
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_added']))
			{
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified']))
			{
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}

			if (isset($this->request->get['sort']))
			{
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order']))
			{
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page']))
			{
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/order', $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update()
	{
		$this->load->language('sale/order');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/order');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
		{
			$this->model_sale_order->editOrder($this->request->get['osn'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url                            = '';

			if (isset($this->request->get['filter_order_id']))
			{
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_customer']))
			{
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_order_status_id']))
			{
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}

			if (isset($this->request->get['filter_total']))
			{
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_added']))
			{
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified']))
			{
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}

			if (isset($this->request->get['sort']))
			{
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order']))
			{
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page']))
			{
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/order', $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('sale/order');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/order');

		if (isset($this->request->post['selected']) && ($this->validateDelete()))
		{
			foreach ($this->request->post['selected'] as $osn)
			{
				$this->model_sale_order->deleteOrder($osn);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			$url                            = '';

			if (isset($this->request->get['filter_order_id']))
			{
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_customer']))
			{
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_order_status_id']))
			{
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}

			if (isset($this->request->get['filter_total']))
			{
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_added']))
			{
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified']))
			{
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}

			if (isset($this->request->get['sort']))
			{
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order']))
			{
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page']))
			{
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/order', $url, 'SSL'));
		}

		$this->getList();
	}

	private function getList()
	{
		if (isset($this->request->get['filter_order_id']))
		{
			$filter_order_id = $this->request->get['filter_order_id'];
		}
		else
		{
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer']))
		{
			$filter_customer = $this->request->get['filter_customer'];
		}
		else
		{
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_order_status_id']))
		{
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		}
		else
		{
			$filter_order_status_id = null;
		}

		if (isset($this->request->get['filter_total']))
		{
			$filter_total = $this->request->get['filter_total'];
		}
		else
		{
			$filter_total = null;
		}

		if (isset($this->request->get['filter_date_added']))
		{
			$filter_date_added = $this->request->get['filter_date_added'];
		}
		else
		{
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_date_modified']))
		{
			$filter_date_modified = $this->request->get['filter_date_modified'];
		}
		else
		{
			$filter_date_modified = null;
		}

		if (isset($this->request->get['sort']))
		{
			$sort = $this->request->get['sort'];
		}
		else
		{
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order']))
		{
			$order = $this->request->get['order'];
		}
		else
		{
			$order = 'DESC';
		}

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

		$url = '';

		if (isset($this->request->get['filter_order_id']))
		{
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer']))
		{
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status_id']))
		{
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total']))
		{
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added']))
		{
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified']))
		{
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort']))
		{
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order']))
		{
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page']))
		{
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/order', $url, 'SSL'),
			'separator' => ' :: '
		);
		$this->data['invoice']       = $this->url->link('sale/order/invoice');
		$this->data['insert']        = $this->url->link('sale/order/insert');
		$this->data['delete']        = $this->url->link('sale/order/delete', $url, 'SSL');
		$this->data['orders']        = array();
		$data                        = array(
			'filter_order_id'        => $filter_order_id,
			'filter_customer'        => $filter_customer,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_total'           => $filter_total,
			'filter_date_added'      => $filter_date_added,
			'filter_date_modified'   => $filter_date_modified,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
		$order_total                 = $this->model_sale_order->getTotalOrders($data);
		$results                     = $this->model_sale_order->getOrders($data);
		foreach ($results as $result)
		{
			$action   = array();
			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('sale/order/info', '&osn=' . $result['osn'] . $url, 'SSL')
			);

			if (strtotime($result['date_added']) > strtotime('-' . (int)$this->config->get('config_order_edit') . ' day'))
			{
				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->link('sale/order/update', '&osn=' . $result['osn'] . $url, 'SSL')
				);
			}

			$this->data['orders'][] = array(
				'order_id'      => $result['osn'],
				'customer'      => $result['customer'],
				'status'        => $result['status'],
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_long'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_long'), strtotime($result['date_modified'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}

		$this->data['heading_title']        = $this->language->get('heading_title');
		$this->data['text_no_results']      = $this->language->get('text_no_results');
		$this->data['text_missing']         = $this->language->get('text_missing');
		$this->data['column_order_id']      = $this->language->get('column_order_id');
		$this->data['column_customer']      = $this->language->get('column_customer');
		$this->data['column_status']        = $this->language->get('column_status');
		$this->data['column_total']         = $this->language->get('column_total');
		$this->data['column_date_added']    = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action']        = $this->language->get('column_action');
		$this->data['button_invoice']       = $this->language->get('button_invoice');
		$this->data['button_insert']        = $this->language->get('button_insert');
		$this->data['button_delete']        = $this->language->get('button_delete');
		$this->data['button_filter']        = $this->language->get('button_filter');

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		if (isset($this->session->data['success']))
		{
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		}
		else
		{
			$this->data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_order_id']))
		{
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer']))
		{
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status_id']))
		{
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total']))
		{
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added']))
		{
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified']))
		{
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC')
		{
			$url .= '&order=DESC';
		}
		else
		{
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page']))
		{
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_order']         = $this->url->link('sale/order', '&sort=o.order_id' . $url, 'SSL');
		$this->data['sort_customer']      = $this->url->link('sale/order', '&sort=customer' . $url, 'SSL');
		$this->data['sort_status']        = $this->url->link('sale/order', '&sort=status' . $url, 'SSL');
		$this->data['sort_total']         = $this->url->link('sale/order', '&sort=o.total' . $url, 'SSL');
		$this->data['sort_date_added']    = $this->url->link('sale/order', '&sort=o.date_added' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('sale/order', '&sort=o.date_modified' . $url, 'SSL');
		$url                              = '';

		if (isset($this->request->get['filter_order_id']))
		{
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer']))
		{
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status_id']))
		{
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total']))
		{
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added']))
		{
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified']))
		{
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort']))
		{
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order']))
		{
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination                           = new Pagination();
		$pagination->total                    = $order_total;
		$pagination->page                     = $page;
		$pagination->limit                    = $this->config->get('config_admin_limit');
		$pagination->text                     = $this->language->get('text_pagination');
		$pagination->url                      = $this->url->link('sale/order', $url . '&page={page}', 'SSL');
		$this->data['pagination']             = $pagination->render();
		$this->data['filter_order_id']        = $filter_order_id;
		$this->data['filter_customer']        = $filter_customer;
		$this->data['filter_order_status_id'] = $filter_order_status_id;
		$this->data['filter_total']           = $filter_total;
		$this->data['filter_date_added']      = $filter_date_added;
		$this->data['filter_date_modified']   = $filter_date_modified;
		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$this->data['sort']           = $sort;
		$this->data['order']          = $order;
		$this->template               = 'template/sale/order_list.tpl';
		$this->children               = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	public function getForm()
	{
		$this->load->model('sale/customer');
		$lang_arr = array(
			'heading_title',
			'text_no_results',
			'text_default',
			'text_select',
			'text_none',
			'text_wait',
			'text_product',
			'text_voucher',
			'text_order',
			'entry_store',
			'entry_customer',
			'entry_customer_group',
			'entry_firstname',
			'entry_lastname',
			'entry_email',
			'entry_telephone',
			'entry_fax',
			'entry_order_status',
			'entry_comment',
			'entry_affiliate',
			'entry_address',
			'entry_company',
			'entry_company_id',
			'entry_tax_id',
			'entry_address_1',
			'entry_address_2',
			'entry_city',
			'entry_postcode',
			'entry_zone',
			'entry_zone_code',
			'entry_country',
			'entry_product',
			'entry_option',
			'entry_quantity',
			'entry_to_name',
			'entry_to_email',
			'entry_from_name',
			'entry_from_email',
			'entry_theme',
			'entry_message',
			'entry_amount',
			'entry_shipping',
			'entry_payment',
			'entry_voucher',
			'entry_coupon',
			'entry_reward',
			'column_product',
			'column_image',
			'column_model',
			'column_quantity',
			'column_price',
			'column_total',
			'button_save',
			'button_cancel',
			'button_add_product',
			'button_add_voucher',
			'button_update_total',
			'button_remove',
			'button_upload',
			'tab_order',
			'tab_customer',
			'tab_payment',
			'tab_shipping',
			'tab_product',
			'tab_voucher',
			'tab_total'
		);
		foreach ($lang_arr as $v)
		{
			$this->data[$v] = $this->language->get($v);
		}

		$err_arr = array(
			'warning',
			'firstname',
			'lastname',
			'email',
			'telephone',
			'payment_firstname',
			'payment_lastname',
			'payment_telephone',
			'payment_address_1',
			'payment_city',
			'payment_postcode',
			'payment_tax_id',
			'payment_country',
			'payment_zone',
			'payment_method',
			'shipping_firstname',
			'shipping_lastname',
			'shipping_telephone',
			'shipping_address_1',
			'shipping_city',
			'shipping_postcode',
			'shipping_country',
			'shipping_zone',
			'shipping_method'
		);
		foreach ($err_arr as $e)
		{
			if (isset($this->error[$e]))
			{
				$this->data['error_' . $e] = $this->error[$e];
			}
			else
			{
				$this->data['error_' . $e] = '';
			}
		}

		$url = '';
		if (isset($this->request->get['filter_order_id']))
		{
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer']))
		{
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_status_id']))
		{
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total']))
		{
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added']))
		{
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified']))
		{
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort']))
		{
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order']))
		{
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page']))
		{
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/order', $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['cancel'] = $this->url->link('sale/order', $url, 'SSL');

		$this->data['osn'] = '';
		if (isset($this->request->get['osn']))
		{
			$this->data['osn']    = $this->request->get['osn'];
			$order_info           = $this->model_sale_order->getOrder($this->request->get['osn']);
			$this->data['action'] = $this->url->link('sale/order/update', '&osn=' . $this->request->get['osn'] . $url, 'SSL');
		}
		else
		{
			$this->data['action'] = $this->url->link('sale/order/insert', $url, 'SSL');
		}

		//for order talbe fields
		$fres = array(
			'invoice_no',
			'invoice_prefix',
			'store_id',
			'store_name',
			'store_url',
			'customer',
			'customer_id',
			'customer_group_id',
			'firstname',
			'lastname',
			'email',
			'telephone',
			'fax',
			'payment_firstname',
			'payment_lastname',
			'payment_telephone',
			'payment_company',
			'payment_company_id',
			'payment_tax_id',
			'payment_address_1',
			'payment_address_2',
			'payment_city',
			'payment_postcode',
			'payment_country',
			'payment_country_id',
			'payment_zone',
			'payment_zone_id',
			'payment_address_format',
			'payment_method',
			'payment_code',
			'shipping_firstname',
			'shipping_lastname',
			'shipping_telephone',
			'shipping_company',
			'shipping_address_1',
			'shipping_address_2',
			'shipping_city',
			'shipping_postcode',
			'shipping_country',
			'shipping_country_id',
			'shipping_zone',
			'shipping_zone_id',
			'shipping_address_format',
			'shipping_method',
			'shipping_code',
			'comment',
			'total',
			'order_status_id',
			'affiliate_id',
			'commission',
			'language_id',
			'currency_id',
			'currency_code',
			'currency_value',
			'ip',
			'forwarded_ip',
			'user_agent',
			'accept_language',
			'date_added',
			'date_modified'
		);
		foreach ($fres as $v)
		{
			if (isset($this->request->post[$v]))
			{
				$this->data[$v] = $this->request->post[$v];
			}
			elseif (!empty($order_info))
			{
				$this->data[$v] = $order_info[$v];
			}
			else
			{
				$this->data[$v] = '';
			}
		}

		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();

		$this->load->model('sale/customer_group');
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/country');
		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('sale/customer');
		if (isset($this->request->post['customer_id']))
		{
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($this->request->post['customer_id']);
		}
		elseif (!empty($order_info))
		{
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($order_info['customer_id']);
		}
		else
		{
			$this->data['addresses'] = array();
		}

		if (isset($this->request->post['affiliate']))
		{
			$this->data['affiliate'] = $this->request->post['affiliate'];
		}
		elseif (!empty($order_info))
		{
			$this->data['affiliate'] = ($order_info['affiliate_id'] ? $order_info['affiliate_firstname'] . ' ' . $order_info['affiliate_lastname'] : '');
		}
		else
		{
			$this->data['affiliate'] = '';
		}

		if (isset($this->request->post['order_product']))
		{
			$order_products = $this->request->post['order_product'];
		}
		elseif (isset($this->request->get['osn']))
		{
			$order_products = $this->model_sale_order->getOrderProducts($order_info['order_id']);
		}
		else
		{
			$order_products = array();
		}

		$this->load->model('catalog/product');
		$this->document->addScript('js/ajaxupload.js');
		$this->data['order_products'] = array();

		$this->load->model('tool/image');
		$img_width  = $this->config->get('config_image_thumb_width');
		$img_height = $this->config->get('config_image_thumb_height');

		foreach ($order_products as $order_product)
		{
			if (isset($order_product['order_option']))
			{
				$order_option = $order_product['order_option'];
			}
			elseif (isset($this->request->get['osn']))
			{
				$order_option = $this->model_sale_order->getOrderOptions($order_info['order_id'], $order_product['order_product_id']);
			}
			else
			{
				$order_option = array();
			}

			if (isset($order_product['order_download']))
			{
				$order_download = $order_product['order_download'];
			}
			elseif (isset($this->request->get['osn']))
			{
				$order_download = $this->model_sale_order->getOrderDownloads($order_info['order_id'], $order_product['order_product_id']);
			}
			else
			{
				$order_download = array();
			}

			$this->data['order_products'][] = array(
				'order_product_id' => $order_product['order_product_id'],
				'product_id'       => $order_product['product_id'],
				'name'             => $order_product['name'],
				'model'            => $order_product['model'],
				'thumb'            => ($order_product['image']) ? $this->model_tool_image->resize($order_product['image'], $img_width, $img_height) : false,
				'option'           => $order_option,
				'download'         => $order_download,
				'quantity'         => $order_product['quantity'],
				'price'            => $order_product['price'],
				'total'            => $order_product['total'],
				'tax'              => $order_product['tax'],
				'reward'           => $order_product['reward']
			);
		}

		if (isset($this->request->post['order_voucher']))
		{
			$this->data['order_vouchers'] = $this->request->post['order_voucher'];
		}
		elseif (isset($this->request->get['osn']))
		{
			$this->data['order_vouchers'] = $this->model_sale_order->getOrderVouchers($order_info['order_id']);
		}
		else
		{
			$this->data['order_vouchers'] = array();
		}

		$this->load->model('sale/voucher_theme');
		$this->data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

		if (isset($this->request->post['order_total']))
		{
			$this->data['order_totals'] = $this->request->post['order_total'];
		}
		elseif (isset($this->request->get['osn']))
		{
			$this->data['order_totals'] = $this->model_sale_order->getOrderTotals($order_info['order_id']);
		}
		else
		{
			$this->data['order_totals'] = array();
		}

		$this->template = 'template/sale/order_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'sale/order'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((mb_strlen($this->request->post['firstname']) < 1) || (mb_strlen($this->request->post['firstname']) > 32))
		{
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((mb_strlen($this->request->post['lastname']) < 1) || (mb_strlen($this->request->post['lastname']) > 32))
		{
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((mb_strlen($this->request->post['email']) > 96) || (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])))
		{
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((mb_strlen($this->request->post['telephone']) < 3) || (mb_strlen($this->request->post['telephone']) > 32))
		{
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if ((mb_strlen($this->request->post['payment_firstname']) < 1) || (mb_strlen($this->request->post['payment_firstname']) > 32))
		{
			$this->error['payment_firstname'] = $this->language->get('error_firstname');
		}

		if ((mb_strlen($this->request->post['payment_lastname']) < 1) || (mb_strlen($this->request->post['payment_lastname']) > 32))
		{
			$this->error['payment_lastname'] = $this->language->get('error_lastname');
		}

		if ((mb_strlen($this->request->post['payment_telephone']) < 1) || (mb_strlen($this->request->post['payment_telephone']) > 32))
		{
			$this->error['payment_telephone'] = $this->language->get('error_telephone');
		}

		if ((mb_strlen($this->request->post['payment_address_1']) < 3) || (mb_strlen($this->request->post['payment_address_1']) > 128))
		{
			$this->error['payment_address_1'] = $this->language->get('error_address_1');
		}

		if ((mb_strlen($this->request->post['payment_city']) < 3) || (mb_strlen($this->request->post['payment_city']) > 128))
		{
			$this->error['payment_city'] = $this->language->get('error_city');
		}

		$this->load->model('localisation/country');
		$country_info = $this->model_localisation_country->getCountry($this->request->post['payment_country_id']);

		if ($country_info)
		{
			if ($country_info['postcode_required'] && (mb_strlen($this->request->post['payment_postcode']) < 2) || (mb_strlen($this->request->post['payment_postcode']) > 10))
			{
				$this->error['payment_postcode'] = $this->language->get('error_postcode');
			}

			// VAT Validation
			$this->load->helper('vat');
			if ($this->config->get('config_vat') && $this->request->post['payment_tax_id'] && (vat_validation($country_info['iso_code_2'], $this->request->post['payment_tax_id']) != 'invalid'))
			{
				$this->error['payment_tax_id'] = $this->language->get('error_vat');
			}
		}

		if ($this->request->post['payment_country_id'] == '')
		{
			$this->error['payment_country'] = $this->language->get('error_country');
		}

		if ($this->request->post['payment_zone_id'] == '')
		{
			$this->error['payment_zone'] = $this->language->get('error_zone');
		}

		if ($this->request->post['payment_method'] == '')
		{
			$this->error['payment_zone'] = $this->language->get('error_zone');
		}

		if (!$this->request->post['payment_method'])
		{
			$this->error['payment_method'] = $this->language->get('error_payment');
		}

		// Check if any products require shipping
		$shipping = false;

		if (isset($this->request->post['order_product']))
		{
			$this->load->model('catalog/product');
			foreach ($this->request->post['order_product'] as $order_product)
			{
				$product_info = $this->model_catalog_product->getProduct($order_product['product_id']);
				if ($product_info && $product_info['shipping'])
				{
					$shipping = true;
				}
			}
		}

		if ($shipping)
		{
			if ((mb_strlen($this->request->post['shipping_firstname']) < 1) || (mb_strlen($this->request->post['shipping_firstname']) > 32))
			{
				$this->error['shipping_firstname'] = $this->language->get('error_firstname');
			}
			if ((mb_strlen($this->request->post['shipping_lastname']) < 1) || (mb_strlen($this->request->post['shipping_lastname']) > 32))
			{
				$this->error['shipping_lastname'] = $this->language->get('error_lastname');
			}

			if ((mb_strlen($this->request->post['shipping_telephone']) < 1) || (mb_strlen($this->request->post['shipping_telephone']) > 32))
			{
				$this->error['shipping_telephone'] = $this->language->get('error_telephone');
			}

			if ((mb_strlen($this->request->post['shipping_address_1']) < 3) || (mb_strlen($this->request->post['shipping_address_1']) > 128))
			{
				$this->error['shipping_address_1'] = $this->language->get('error_address_1');
			}

			if ((mb_strlen($this->request->post['shipping_city']) < 3) || (mb_strlen($this->request->post['shipping_city']) > 128))
			{
				$this->error['shipping_city'] = $this->language->get('error_city');
			}

			$this->load->model('localisation/country');
			$country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);
			if ($country_info && $country_info['postcode_required'] && (mb_strlen($this->request->post['shipping_postcode']) < 2) || (mb_strlen($this->request->post['shipping_postcode']) > 10))
			{
				$this->error['shipping_postcode'] = $this->language->get('error_postcode');
			}

			if ($this->request->post['shipping_country_id'] == '')
			{
				$this->error['shipping_country'] = $this->language->get('error_country');
			}

			if ($this->request->post['shipping_zone_id'] == '')
			{
				$this->error['shipping_zone'] = $this->language->get('error_zone');
			}

			if (!$this->request->post['shipping_method'])
			{
				$this->error['shipping_method'] = $this->language->get('error_shipping');
			}
		}

		if ($this->error && !isset($this->error['warning']))
		{
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return (!$this->error) ? true : false;
	}

	private function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'sale/order'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return (!$this->error) ? true : false;
	}

	public function info()
	{
		$this->load->model('sale/order');
		$osn        = (isset($this->request->get['osn'])) ? $this->request->get['osn'] : 0;
		$order_info = $this->model_sale_order->getOrder($osn);

		if ($order_info)
		{
			$this->load->language('sale/order');
			$this->document->setTitle($this->language->get('heading_title'));

			$lang_arr = array(
				'heading_title',
				'text_order_id',
				'text_invoice_no',
				'text_invoice_date',
				'text_store_name',
				'text_store_url',
				'text_customer',
				'text_customer_group',
				'text_email',
				'text_telephone',
				'text_fax',
				'text_total',
				'text_reward',
				'text_order_status',
				'text_comment',
				'text_affiliate',
				'text_commission',
				'text_ip',
				'text_forwarded_ip',
				'text_user_agent',
				'text_accept_language',
				'text_date_added',
				'text_date_modified',
				'text_firstname',
				'text_lastname',
				'text_company',
				'text_company_id',
				'text_tax_id',
				'text_address_1',
				'text_address_2',
				'text_city',
				'text_postcode',
				'text_zone',
				'text_zone_code',
				'text_country',
				'text_shipping_method',
				'text_payment_method',
				'text_download',
				'text_wait',
				'text_generate',
				'text_reward_add',
				'text_reward_remove',
				'text_commission_add',
				'text_commission_remove',
				'text_credit_add',
				'text_credit_remove',
				'text_country_match',
				'text_country_code',
				'text_high_risk_country',
				'text_distance',
				'text_ip_region',
				'text_ip_city',
				'text_ip_latitude',
				'text_ip_longitude',
				'text_ip_isp',
				'text_ip_org',
				'text_ip_asnum',
				'text_ip_user_type',
				'text_ip_country_confidence',
				'text_ip_region_confidence',
				'text_ip_city_confidence',
				'text_ip_postal_confidence',
				'text_ip_postal_code',
				'text_ip_accuracy_radius',
				'text_ip_net_speed_cell',
				'text_ip_metro_code',
				'text_ip_area_code',
				'text_ip_time_zone',
				'text_ip_region_name',
				'text_ip_domain',
				'text_ip_country_name',
				'text_ip_continent_code',
				'text_ip_corporate_proxy',
				'text_anonymous_proxy',
				'text_proxy_score',
				'text_is_trans_proxy',
				'text_free_mail',
				'text_carder_email',
				'text_high_risk_username',
				'text_high_risk_password',
				'text_bin_match',
				'text_bin_country',
				'text_bin_name_match',
				'text_bin_name',
				'text_bin_phone_match',
				'text_bin_phone',
				'text_customer_phone_in_billing_location',
				'text_ship_forward',
				'text_city_postal_match',
				'text_ship_city_postal_match',
				'text_score',
				'text_explanation',
				'text_risk_score',
				'text_queries_remaining',
				'text_maxmind_id',
				'text_error',
				'column_image',
				'column_product',
				'column_model',
				'column_quantity',
				'column_price',
				'column_total',
				'column_download',
				'column_filename',
				'column_remaining',
				'entry_order_status',
				'entry_notify',
				'entry_comment',
				'button_invoice',
				'button_cancel',
				'button_add_history',
				'tab_order',
				'tab_payment',
				'tab_shipping',
				'tab_product',
				'tab_order_history',
				'tab_fraud'
			);
			foreach ($lang_arr as $v)
			{
				$this->data[$v] = $this->language->get($v);
			}

			$url                         = '';
			$this->data                  = array_merge($this->data, $order_info);
			$this->data['customer_name'] = $order_info['customer'];

			if (isset($this->request->get['filter_order_id']))
			{
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_customer']))
			{
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_order_status_id']))
			{
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}

			if (isset($this->request->get['filter_total']))
			{
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_added']))
			{
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified']))
			{
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}

			if (isset($this->request->get['sort']))
			{
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order']))
			{
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page']))
			{
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['breadcrumbs']   = array();
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
			);
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('sale/order', $url, 'SSL'),
				'separator' => ' :: '
			);

			$this->data['invoice'] = $this->url->link('sale/order/invoice', '&osn=' . $this->request->get['osn'], 'SSL');
			$this->data['cancel']  = $this->url->link('sale/order', $url, 'SSL');
			$this->data['osn']     = $this->request->get['osn'];

			if ($order_info['invoice_no'])
			{
				$this->data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			}
			else
			{
				$this->data['invoice_no'] = '';
			}

			if ($order_info['customer_id'])
			{
				$this->data['customer'] = $this->url->link('sale/customer/update', '&customer_id=' . $order_info['customer_id'], 'SSL');
			}
			else
			{
				$this->data['customer'] = '';
			}

			$this->load->model('sale/customer_group');
			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);
			if ($customer_group_info)
			{
				$this->data['customer_group'] = $customer_group_info['name'];
			}
			else
			{
				$this->data['customer_group'] = '';
			}

			$this->data['email']           = $order_info['email'];
			$this->data['telephone']       = $order_info['telephone'];
			$this->data['fax']             = $order_info['fax'];
			$this->data['comment']         = nl2br($order_info['comment']);
			$this->data['shipping_method'] = $order_info['shipping_method'];
			$this->data['payment_method']  = $order_info['payment_method'];
			$this->data['total']           = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);

			$this->data['credit'] = ($order_info['total'] < 0) ? $order_info['total'] : 0;
			$this->load->model('sale/customer');
			$this->data['credit_total'] = $this->model_sale_customer->getTotalTransactionsByOrderId($order_info['osn']);

			$this->data['reward']              = $order_info['reward'];
			$this->data['reward_total']        = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($order_info['osn'], 'order');
			$this->data['affiliate_firstname'] = $order_info['affiliate_firstname'];
			$this->data['affiliate_lastname']  = $order_info['affiliate_lastname'];

			if ($order_info['affiliate_id'])
			{
				$this->data['affiliate'] = $this->url->link('sale/affiliate/update', '&affiliate_id=' . $order_info['affiliate_id'], 'SSL');
			}
			else
			{
				$this->data['affiliate'] = '';
			}

			$this->data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);
			$this->load->model('sale/affiliate');
			$this->data['commission_total'] = $this->model_sale_affiliate->getTotalTransactionsByOrderId($order_info['osn']);

			$this->load->model('localisation/order_status');
			$order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

			if ($order_status_info)
			{
				$this->data['order_status'] = $order_status_info['name'];
			}
			else
			{
				$this->data['order_status'] = '';
			}

			$this->data['products'] = array();
			$this->load->model('tool/image');
			$img_width  = $this->config->get('config_image_thumb_width');
			$img_height = $this->config->get('config_image_thumb_height');

			$products = $this->model_sale_order->getOrderProducts($order_info['order_id']);
			foreach ($products as $product)
			{
				$option_data = array();
				$options     = $this->model_sale_order->getOrderOptions($order_info['order_id'], $product['order_product_id']);
				foreach ($options as $option)
				{
					if ($option['type'] != 'file')
					{
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					}
					else
					{
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => mb_substr($option['value'], 0, mb_strrpos($option['value'], '.')),
							'type'  => $option['type'],
							'href'  => $this->url->link('sale/order/download', '&osn=' . $this->request->get['osn'] . '&order_option_id=' . $option['order_option_id'], 'SSL')
						);
					}
				}

				$this->data['products'][] = array(
					'order_product_id' => $product['order_product_id'],
					'product_id'       => $product['product_id'],
					'thumb'            => ($product['image']) ? $this->model_tool_image->resize($product['image'], $img_width, $img_height) : false,
					'name'             => $product['name'],
					'model'            => $product['model'],
					'option'           => $option_data,
					'quantity'         => $product['quantity'],
					'price'            => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'            => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'href'             => $this->url->link('catalog/product/update', '&product_id=' . $product['product_id'], 'SSL')
				);
			}

			$this->data['vouchers'] = array();
			$vouchers               = $this->model_sale_order->getOrderVouchers($order_info['order_id']);
			foreach ($vouchers as $voucher)
			{
				$this->data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					'href'        => $this->url->link('sale/voucher/update', '&voucher_id=' . $voucher['voucher_id'], 'SSL')
				);
			}

			$this->data['totals']    = $this->model_sale_order->getOrderTotals($order_info['order_id']);
			$this->data['downloads'] = array();

			foreach ($products as $product)
			{
				$results = $this->model_sale_order->getOrderDownloads($order_info['order_id'], $product['order_product_id']);
				foreach ($results as $result)
				{
					$this->data['downloads'][] = array(
						'name'      => $result['name'],
						'filename'  => $result['mask'],
						'remaining' => $result['remaining']
					);
				}
			}

			$this->data['order_statuses']  = $this->model_localisation_order_status->getOrderStatuses();
			$this->data['order_status_id'] = $order_info['order_status_id'];

			// Fraud
			$this->load->model('sale/fraud');
			$fraud_info = $this->model_sale_fraud->getFraud($order_info['order_id']);

			if ($fraud_info)
			{
				$this->data['country_match'] = $fraud_info['country_match'];

				if ($fraud_info['country_code'])
				{
					$this->data['country_code'] = $fraud_info['country_code'];
				}
				else
				{
					$this->data['country_code'] = '';
				}

				$this->data['high_risk_country'] = $fraud_info['high_risk_country'];
				$this->data['distance']          = $fraud_info['distance'];

				if ($fraud_info['ip_region'])
				{
					$this->data['ip_region'] = $fraud_info['ip_region'];
				}
				else
				{
					$this->data['ip_region'] = '';
				}

				if ($fraud_info['ip_city'])
				{
					$this->data['ip_city'] = $fraud_info['ip_city'];
				}
				else
				{
					$this->data['ip_city'] = '';
				}

				$this->data['ip_latitude']  = $fraud_info['ip_latitude'];
				$this->data['ip_longitude'] = $fraud_info['ip_longitude'];

				if ($fraud_info['ip_isp'])
				{
					$this->data['ip_isp'] = $fraud_info['ip_isp'];
				}
				else
				{
					$this->data['ip_isp'] = '';
				}

				if ($fraud_info['ip_org'])
				{
					$this->data['ip_org'] = $fraud_info['ip_org'];
				}
				else
				{
					$this->data['ip_org'] = '';
				}

				$this->data['ip_asnum'] = $fraud_info['ip_asnum'];

				if ($fraud_info['ip_user_type'])
				{
					$this->data['ip_user_type'] = $fraud_info['ip_user_type'];
				}
				else
				{
					$this->data['ip_user_type'] = '';
				}

				if ($fraud_info['ip_country_confidence'])
				{
					$this->data['ip_country_confidence'] = $fraud_info['ip_country_confidence'];
				}
				else
				{
					$this->data['ip_country_confidence'] = '';
				}

				if ($fraud_info['ip_region_confidence'])
				{
					$this->data['ip_region_confidence'] = $fraud_info['ip_region_confidence'];
				}
				else
				{
					$this->data['ip_region_confidence'] = '';
				}

				if ($fraud_info['ip_city_confidence'])
				{
					$this->data['ip_city_confidence'] = $fraud_info['ip_city_confidence'];
				}
				else
				{
					$this->data['ip_city_confidence'] = '';
				}

				if ($fraud_info['ip_postal_confidence'])
				{
					$this->data['ip_postal_confidence'] = $fraud_info['ip_postal_confidence'];
				}
				else
				{
					$this->data['ip_postal_confidence'] = '';
				}

				if ($fraud_info['ip_postal_code'])
				{
					$this->data['ip_postal_code'] = $fraud_info['ip_postal_code'];
				}
				else
				{
					$this->data['ip_postal_code'] = '';
				}

				$this->data['ip_accuracy_radius'] = $fraud_info['ip_accuracy_radius'];

				if ($fraud_info['ip_net_speed_cell'])
				{
					$this->data['ip_net_speed_cell'] = $fraud_info['ip_net_speed_cell'];
				}
				else
				{
					$this->data['ip_net_speed_cell'] = '';
				}

				$this->data['ip_metro_code'] = $fraud_info['ip_metro_code'];
				$this->data['ip_area_code']  = $fraud_info['ip_area_code'];

				if ($fraud_info['ip_time_zone'])
				{
					$this->data['ip_time_zone'] = $fraud_info['ip_time_zone'];
				}
				else
				{
					$this->data['ip_time_zone'] = '';
				}

				if ($fraud_info['ip_region_name'])
				{
					$this->data['ip_region_name'] = $fraud_info['ip_region_name'];
				}
				else
				{
					$this->data['ip_region_name'] = '';
				}

				if ($fraud_info['ip_domain'])
				{
					$this->data['ip_domain'] = $fraud_info['ip_domain'];
				}
				else
				{
					$this->data['ip_domain'] = '';
				}

				if ($fraud_info['ip_country_name'])
				{
					$this->data['ip_country_name'] = $fraud_info['ip_country_name'];
				}
				else
				{
					$this->data['ip_country_name'] = '';
				}

				if ($fraud_info['ip_continent_code'])
				{
					$this->data['ip_continent_code'] = $fraud_info['ip_continent_code'];
				}
				else
				{
					$this->data['ip_continent_code'] = '';
				}

				if ($fraud_info['ip_corporate_proxy'])
				{
					$this->data['ip_corporate_proxy'] = $fraud_info['ip_corporate_proxy'];
				}
				else
				{
					$this->data['ip_corporate_proxy'] = '';
				}

				$this->data['anonymous_proxy'] = $fraud_info['anonymous_proxy'];
				$this->data['proxy_score']     = $fraud_info['proxy_score'];

				if ($fraud_info['is_trans_proxy'])
				{
					$this->data['is_trans_proxy'] = $fraud_info['is_trans_proxy'];
				}
				else
				{
					$this->data['is_trans_proxy'] = '';
				}

				$this->data['free_mail']    = $fraud_info['free_mail'];
				$this->data['carder_email'] = $fraud_info['carder_email'];

				if ($fraud_info['high_risk_username'])
				{
					$this->data['high_risk_username'] = $fraud_info['high_risk_username'];
				}
				else
				{
					$this->data['high_risk_username'] = '';
				}

				if ($fraud_info['high_risk_password'])
				{
					$this->data['high_risk_password'] = $fraud_info['high_risk_password'];
				}
				else
				{
					$this->data['high_risk_password'] = '';
				}

				$this->data['bin_match'] = $fraud_info['bin_match'];

				if ($fraud_info['bin_country'])
				{
					$this->data['bin_country'] = $fraud_info['bin_country'];
				}
				else
				{
					$this->data['bin_country'] = '';
				}

				$this->data['bin_name_match'] = $fraud_info['bin_name_match'];

				if ($fraud_info['bin_name'])
				{
					$this->data['bin_name'] = $fraud_info['bin_name'];
				}
				else
				{
					$this->data['bin_name'] = '';
				}

				$this->data['bin_phone_match'] = $fraud_info['bin_phone_match'];

				if ($fraud_info['bin_phone'])
				{
					$this->data['bin_phone'] = $fraud_info['bin_phone'];
				}
				else
				{
					$this->data['bin_phone'] = '';
				}

				if ($fraud_info['customer_phone_in_billing_location'])
				{
					$this->data['customer_phone_in_billing_location'] = $fraud_info['customer_phone_in_billing_location'];
				}
				else
				{
					$this->data['customer_phone_in_billing_location'] = '';
				}

				$this->data['ship_forward'] = $fraud_info['ship_forward'];

				if ($fraud_info['city_postal_match'])
				{
					$this->data['city_postal_match'] = $fraud_info['city_postal_match'];
				}
				else
				{
					$this->data['city_postal_match'] = '';
				}

				if ($fraud_info['ship_city_postal_match'])
				{
					$this->data['ship_city_postal_match'] = $fraud_info['ship_city_postal_match'];
				}
				else
				{
					$this->data['ship_city_postal_match'] = '';
				}

				$this->data['score']             = $fraud_info['score'];
				$this->data['explanation']       = $fraud_info['explanation'];
				$this->data['risk_score']        = $fraud_info['risk_score'];
				$this->data['queries_remaining'] = $fraud_info['queries_remaining'];
				$this->data['maxmind_id']        = $fraud_info['maxmind_id'];
				$this->data['error']             = $fraud_info['error'];
			}
			else
			{
				$this->data['maxmind_id'] = '';
			}

			$this->template = 'template/sale/order_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
			$this->response->setOutput($this->render());
		}
		else
		{
			$this->load->language('error/not_found');
			$this->document->setTitle($this->language->get('heading_title'));
			$this->data['heading_title']  = $this->language->get('heading_title');
			$this->data['text_not_found'] = $this->language->get('text_not_found');
			$this->data['breadcrumbs']    = array();
			$this->data['breadcrumbs'][]  = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
			);
			$this->data['breadcrumbs'][]  = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('error/not_found'),
				'separator' => ' :: '
			);
			$this->template               = 'template/error/not_found.tpl';
			$this->children               = array(
				'common/header',
				'common/footer'
			);
			$this->response->setOutput($this->render());
		}
	}

	public function createInvoiceNo()
	{
		$this->language->load('sale/order');
		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order'))
		{
			$json['error'] = $this->language->get('error_permission');
		}
		elseif (isset($this->request->get['osn']))
		{
			$this->load->model('sale/order');
			$invoice_no = $this->model_sale_order->createInvoiceNo($this->request->get['osn']);
			if ($invoice_no)
			{
				$json['invoice_no'] = $invoice_no;
			}
			else
			{
				$json['error'] = $this->language->get('error_action');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function addCredit()
	{
		$this->language->load('sale/order');
		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order'))
		{
			$json['error'] = $this->language->get('error_permission');
		}
		elseif (isset($this->request->get['osn']))
		{
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($this->request->get['osn']);
			if ($order_info && $order_info['customer_id'])
			{
				$this->load->model('sale/customer');
				$credit_total = $this->model_sale_customer->getTotalTransactionsByOrderId($this->request->get['osn']);
				if (!$credit_total)
				{
					$this->model_sale_customer->addTransaction($order_info['customer_id'], $this->language->get('text_order_id') . ' #' . $this->request->get['osn'], $order_info['total'], $this->request->get['osn']);
					$json['success'] = $this->language->get('text_credit_added');
				}
				else
				{
					$json['error'] = $this->language->get('error_action');
				}
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function removeCredit()
	{
		$this->language->load('sale/order');
		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order'))
		{
			$json['error'] = $this->language->get('error_permission');
		}
		elseif (isset($this->request->get['osn']))
		{
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($this->request->get['osn']);
			if ($order_info && $order_info['customer_id'])
			{
				$this->load->model('sale/customer');
				$this->model_sale_customer->deleteTransaction($this->request->get['osn']);
				$json['success'] = $this->language->get('text_credit_removed');
			}
			else
			{
				$json['error'] = $this->language->get('error_action');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function addReward()
	{
		$this->language->load('sale/order');
		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order'))
		{
			$json['error'] = $this->language->get('error_permission');
		}
		elseif (isset($this->request->get['osn']))
		{
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($this->request->get['osn']);
			if ($order_info && $order_info['customer_id'])
			{
				$this->load->model('sale/customer');
				$reward_total = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($order_info['osn'], 'order');
				if (!$reward_total)
				{
					$this->model_sale_customer->addReward($order_info['customer_id'], $this->language->get('text_reward_add') . $this->language->get('text_order_id') . $order_info['osn'], $order_info['reward'], $order_info['osn'], 'order');
					$json['success'] = $this->language->get('text_reward_added');
				}
				else
				{
					$json['error'] = $this->language->get('error_action');
				}
			}
			else
			{
				$json['error'] = $this->language->get('error_action');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function removeReward()
	{
		$this->language->load('sale/order');
		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order'))
		{
			$json['error'] = $this->language->get('error_permission');
		}
		elseif (isset($this->request->get['osn']))
		{
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($this->request->get['osn']);
			if ($order_info && $order_info['customer_id'])
			{
				$this->load->model('sale/customer');
				$this->model_sale_customer->deleteReward($order_info['osn'], $this->language->get('text_reward_remove') . $this->language->get('text_order_id') . $order_info['osn'], 'order');
				$json['success'] = $this->language->get('text_reward_removed');
			}
			else
			{
				$json['error'] = $this->language->get('error_action');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function addCommission()
	{
		$this->language->load('sale/order');
		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order'))
		{
			$json['error'] = $this->language->get('error_permission');
		}
		elseif (isset($this->request->get['osn']))
		{
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($this->request->get['osn']);

			if ($order_info && $order_info['affiliate_id'])
			{
				$this->load->model('sale/affiliate');
				$affiliate_total = $this->model_sale_affiliate->getTotalTransactionsByOrderId($this->request->get['osn']);
				if (!$affiliate_total)
				{
					$this->model_sale_affiliate->addTransaction($order_info['affiliate_id'], $this->language->get('text_order_id') . '  ' . $order_info['osn'], $order_info['commission'], $order_info['osn']);

					$json['success'] = $this->language->get('text_commission_added');
				}
				else
				{
					$json['error'] = $this->language->get('error_action');
				}
			}
			else
			{
				$json['error'] = $this->language->get('error_action');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function removeCommission()
	{
		$this->language->load('sale/order');
		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order'))
		{
			$json['error'] = $this->language->get('error_permission');
		}
		elseif (isset($this->request->get['osn']))
		{
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($this->request->get['osn']);
			if ($order_info && $order_info['affiliate_id'])
			{
				$this->load->model('sale/affiliate');
				$this->model_sale_affiliate->deleteTransaction($order_info['osn']);
				$json['success'] = $this->language->get('text_commission_removed');
			}
			else
			{
				$json['error'] = $this->language->get('error_action');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function history()
	{
		$this->language->load('sale/order');
		$this->data['error']   = '';
		$this->data['success'] = '';
		$this->load->model('sale/order');

		$osn        = $this->request->get['osn'];
		$order_info = $this->model_sale_order->getOrderInfo($osn);
		if (empty($order_info))
		{
			$this->template = 'template/error/not_found.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
			$this->response->setOutput($this->render());
			exit();
		}

		$order_id = $order_info->row['order_id'];
		if ($this->request->server['REQUEST_METHOD'] == 'POST')
		{
			if (!$this->user->hasPermission('modify', 'sale/order'))
			{
				$this->data['error'] = $this->language->get('error_permission');
			}

			if (!$this->data['error'])
			{
				$this->model_sale_order->addOrderHistory($order_id, $this->request->post);
				$this->data['success'] = $this->language->get('text_success');
			}
		}

		$this->data['text_no_results']   = $this->language->get('text_no_results');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_status']     = $this->language->get('column_status');
		$this->data['column_notify']     = $this->language->get('column_notify');
		$this->data['column_comment']    = $this->language->get('column_comment');

		$page                    = (isset($this->request->get['page'])) ? $this->request->get['page'] : 1;
		$this->data['histories'] = array();
		$results                 = $this->model_sale_order->getOrderHistories($order_id, ($page - 1) * 10, 10);

		foreach ($results as $result)
		{
			$this->data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_long'), strtotime($result['date_added']))
			);
		}

		$history_total            = $this->model_sale_order->getTotalOrderHistories($order_id);
		$pagination               = new Pagination();
		$pagination->total        = $history_total;
		$pagination->page         = $page;
		$pagination->limit        = 10;
		$pagination->text         = $this->language->get('text_pagination');
		$pagination->url          = $this->url->link('sale/order/history', '&osn=' . $osn . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
		$this->template           = 'template/sale/order_history.tpl';
		$this->response->setOutput($this->render());
	}

	public function download()
	{
		$this->load->model('sale/order');

		if (isset($this->request->get['order_option_id']))
		{
			$order_option_id = $this->request->get['order_option_id'];
		}
		else
		{
			$order_option_id = 0;
		}

		$option_info = $this->model_sale_order->getOrderOption($this->request->get['osn'], $order_option_id);

		if ($option_info && $option_info['type'] == 'file')
		{
			$file = DIR_ROOT . "/www/download/{$option_info['value']}";
			$mask = basename(mb_substr($option_info['value'], 0, mb_strrpos($option_info['value'], '.')));

			if (!headers_sent())
			{
				if (file_exists($file))
				{
					header('Content-Type: application/octet-stream');
					header('Content-Description: File Transfer');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));

					readfile($file, 'rb');
					exit;
				}
				else
				{
					exit('Error: Could not find file ' . $file . '!');
				}
			}
			else
			{
				exit('Error: Headers already sent out!');
			}
		}
		else
		{
			$this->load->language('error/not_found');
			$this->document->setTitle($this->language->get('heading_title'));
			$this->data['heading_title']  = $this->language->get('heading_title');
			$this->data['text_not_found'] = $this->language->get('text_not_found');
			$this->data['breadcrumbs']    = array();
			$this->data['breadcrumbs'][]  = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
			);
			$this->data['breadcrumbs'][]  = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('error/not_found'),
				'separator' => ' :: '
			);
			$this->template               = 'template/error/not_found.tpl';
			$this->children               = array(
				'common/header',
				'common/footer'
			);
			$this->response->setOutput($this->render());
		}
	}

	public function upload()
	{
		$this->language->load('sale/order');
		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST')
		{
			if (!empty($this->request->files['file']['name']))
			{
				$filename = html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8');

				if ((mb_strlen($filename) < 3) || (mb_strlen($filename) > 128))
				{
					$json['error'] = $this->language->get('error_filename');
				}

				$allowed   = array();
				$filetypes = explode(',', $this->config->get('config_upload_allowed'));
				foreach ($filetypes as $filetype)
				{
					$allowed[] = trim($filetype);
				}

				if (!in_array(mb_substr(strrchr($filename, '.'), 1), $allowed))
				{
					$json['error'] = $this->language->get('error_filetype');
				}

				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK)
				{
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			}
			else
			{
				$json['error'] = $this->language->get('error_upload');
			}

			if (!isset($json['error']))
			{
				if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name']))
				{
					$file = basename($filename) . '.' . md5(mt_rand());

					$json['file'] = $file;

					move_uploaded_file($this->request->files['file']['tmp_name'], DIR_ROOT . '/www/download/' . $file);
				}

				$json['success'] = $this->language->get('text_upload');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function invoice()
	{
		$this->load->language('sale/order');
		$this->data['title'] = $this->language->get('heading_title');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
		{
			$this->data['base'] = 'https://' . DOMAIN_NAME . '/';
		}
		else
		{
			$this->data['base'] = 'http://' . DOMAIN_NAME . '/';
		}

		$lang_arr = array(
			'direction',
			'code',
			'text_invoice',
			'text_order_id',
			'text_invoice_no',
			'text_invoice_date',
			'text_date_added',
			'text_telephone',
			'text_fax',
			'text_to',
			'text_company_id',
			'text_tax_id',
			'text_ship_to',
			'text_payment_method',
			'text_shipping_method',
			'column_product',
			'column_image',
			'column_model',
			'column_quantity',
			'column_price',
			'column_total',
			'column_comment',
		);
		foreach ($lang_arr as $v)
		{
			$this->data[$v] = $this->language->get($v);
		}

		$this->load->model('sale/order');
		$this->load->model('setting/setting');
		$this->data['orders'] = array();
		$orders               = array();

		if (isset($this->request->post['selected']))
		{
			$orders = $this->request->post['selected'];
		}
		elseif (isset($this->request->get['osn']))
		{
			$orders[] = $this->request->get['osn'];
		}

		foreach ($orders as $osn)
		{
			$order_info = $this->model_sale_order->getOrder($osn);
			if (empty($order_info))
			{
				continue;
			}

			$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);
			if ($store_info)
			{
				$store_address   = $store_info['config_address'];
				$store_email     = $store_info['config_email'];
				$store_telephone = $store_info['config_telephone'];
				$store_fax       = $store_info['config_fax'];
			}
			else
			{
				$store_address   = $this->config->get('config_address');
				$store_email     = $this->config->get('config_email');
				$store_telephone = $this->config->get('config_telephone');
				$store_fax       = $this->config->get('config_fax');
			}

			if ($order_info['invoice_no'])
			{
				$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			}
			else
			{
				$invoice_no = '';
			}

			if ($order_info['shipping_address_format'])
			{
				$format = $order_info['shipping_address_format'];
			}
			else
			{
				$format = '{firstname} {lastname} {telephone}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{telephone}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'telephone' => $order_info['shipping_telephone'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']
			);

			$shipping_address = str_replace(array(
												 "\r\n",
												 "\r",
												 "\n"
											), '<br />', preg_replace(array(
																		   "/\s\s+/",
																		   "/\r\r+/",
																		   "/\n\n+/"
																	  ), '<br />', trim(str_replace($find, $replace, $format))));

			if ($order_info['payment_address_format'])
			{
				$format = $order_info['payment_address_format'];
			}
			else
			{
				$format = '{firstname} {lastname} {telephone}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{telephone}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'telephone' => $order_info['payment_telephone'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']
			);

			$payment_address = str_replace(array(
												"\r\n",
												"\r",
												"\n"
										   ), '<br />', preg_replace(array(
																		  "/\s\s+/",
																		  "/\r\r+/",
																		  "/\n\n+/"
																	 ), '<br />', trim(str_replace($find, $replace, $format))));
			$this->load->model('tool/image');
			$img_width  = $this->config->get('config_image_thumb_width');
			$img_height = $this->config->get('config_image_thumb_height');

			$product_data = array();
			$products     = $this->model_sale_order->getOrderProducts($order_info['order_id']);
			foreach ($products as $product)
			{
				$option_data = array();
				$options     = $this->model_sale_order->getOrderOptions($order_info['order_id'], $product['order_product_id']);
				foreach ($options as $option)
				{
					if ($option['type'] != 'file')
					{
						$value = $option['value'];
					}
					else
					{
						$value = mb_substr($option['value'], 0, mb_strrpos($option['value'], '.'));
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => $value
					);
				}

				$product_data[] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'thumb'    => ($product['image']) ? $this->model_tool_image->resize($product['image'], $img_width, $img_height) : false,
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			$voucher_data = array();
			$vouchers     = $this->model_sale_order->getOrderVouchers($order_info['order_id']);
			foreach ($vouchers as $voucher)
			{
				$voucher_data[] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			$total_data = $this->model_sale_order->getOrderTotals($order_info['order_id']);

			$this->data['orders'][] = array(
				'order_id'           => $osn,
				'invoice_no'         => $invoice_no,
				'date_added'         => date($this->language->get('date_format_long'), strtotime($order_info['date_added'])),
				'store_name'         => $order_info['store_name'],
				'store_url'          => rtrim($order_info['store_url'], '/'),
				'store_address'      => nl2br($store_address),
				'store_email'        => $store_email,
				'store_telephone'    => $store_telephone,
				'store_fax'          => $store_fax,
				'email'              => $order_info['email'],
				'telephone'          => $order_info['telephone'],
				'shipping_address'   => $shipping_address,
				'shipping_method'    => $order_info['shipping_method'],
				'payment_address'    => $payment_address,
				'payment_company_id' => $order_info['payment_company_id'],
				'payment_tax_id'     => $order_info['payment_tax_id'],
				'payment_method'     => $order_info['payment_method'],
				'product'            => $product_data,
				'voucher'            => $voucher_data,
				'total'              => $total_data,
				'comment'            => nl2br($order_info['comment'])
			);
		}

		$this->template = 'template/sale/order_invoice.tpl';
		$this->response->setOutput($this->render());
	}
}

?>