<?php
class ControllerSaleReturn extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('sale/return');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/return');
		$this->getList();
	}

	public function insert()
	{
		$this->load->language('sale/return');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/return');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
		{
			$this->model_sale_return->addReturn($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url                            = '';

			if (isset($this->request->get['filter_return_id']))
			{
				$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
			}

			if (isset($this->request->get['filter_osn']))
			{
				$url .= '&filter_osn=' . $this->request->get['filter_osn'];
			}

			if (isset($this->request->get['filter_customer']))
			{
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_product']))
			{
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_return_type_id']))
			{
				$url .= '&filter_return_type_id=' . urlencode(html_entity_decode($this->request->get['filter_return_type_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_return_status_id']))
			{
				$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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

			$this->redirect($this->url->link('sale/return', $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update()
	{
		$this->load->language('sale/return');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/return');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
		{
			$this->model_sale_return->editReturn($this->request->get['return_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url                            = '';

			if (isset($this->request->get['filter_return_id']))
			{
				$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
			}

			if (isset($this->request->get['filter_osn']))
			{
				$url .= '&filter_osn=' . $this->request->get['filter_osn'];
			}

			if (isset($this->request->get['filter_customer']))
			{
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_product']))
			{
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_return_type_id']))
			{
				$url .= '&filter_return_type_id=' . urlencode(html_entity_decode($this->request->get['filter_return_type_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_return_status_id']))
			{
				$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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

			$this->redirect($this->url->link('sale/return', $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('sale/return');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/return');

		if (isset($this->request->post['selected']) && $this->validateDelete())
		{
			foreach ($this->request->post['selected'] as $return_id)
			{
				$this->model_sale_return->deleteReturn($return_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			$url                            = '';

			if (isset($this->request->get['filter_return_id']))
			{
				$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
			}

			if (isset($this->request->get['filter_osn']))
			{
				$url .= '&filter_osn=' . $this->request->get['filter_osn'];
			}

			if (isset($this->request->get['filter_customer']))
			{
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_product']))
			{
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_return_type_id']))
			{
				$url .= '&filter_return_type_id=' . urlencode(html_entity_decode($this->request->get['filter_return_type_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_return_status_id']))
			{
				$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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

			$this->redirect($this->url->link('sale/return', $url, 'SSL'));
		}

		$this->getList();
	}

	private function getList()
	{
		if (isset($this->request->get['filter_return_id']))
		{
			$filter_return_id = $this->request->get['filter_return_id'];
		}
		else
		{
			$filter_return_id = null;
		}

		if (isset($this->request->get['filter_osn']))
		{
			$filter_osn = $this->request->get['filter_osn'];
		}
		else
		{
			$filter_osn = null;
		}

		if (isset($this->request->get['filter_customer']))
		{
			$filter_customer = $this->request->get['filter_customer'];
		}
		else
		{
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_product']))
		{
			$filter_product = $this->request->get['filter_product'];
		}
		else
		{
			$filter_product = null;
		}

		if (isset($this->request->get['filter_return_type_id']))
		{
			$filter_return_type_id = $this->request->get['filter_return_type_id'];
		}
		else
		{
			$filter_return_type_id = null;
		}

		if (isset($this->request->get['filter_return_status_id']))
		{
			$filter_return_status_id = $this->request->get['filter_return_status_id'];
		}
		else
		{
			$filter_return_status_id = null;
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
			$sort = 'r.return_id';
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

		if (isset($this->request->get['filter_return_id']))
		{
			$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
		}

		if (isset($this->request->get['filter_osn']))
		{
			$url .= '&filter_osn=' . $this->request->get['filter_osn'];
		}

		if (isset($this->request->get['filter_customer']))
		{
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_product']))
		{
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_return_type_id']))
		{
			$url .= '&filter_return_type_id=' . urlencode(html_entity_decode($this->request->get['filter_return_type_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_return_status_id']))
		{
			$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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
			'href'      => $this->url->link('sale/return', $url, 'SSL'),
			'separator' => ' :: '
		);
		$this->data['insert']        = $this->url->link('sale/return/insert', $url, 'SSL');
		$this->data['delete']        = $this->url->link('sale/return/delete', $url, 'SSL');
		$this->data['returns']       = array();
		$data                        = array(
			'filter_return_id'        => $filter_return_id,
			'filter_osn'              => $filter_osn,
			'filter_customer'         => $filter_customer,
			'filter_product'          => $filter_product,
			'filter_return_type_id'   => $filter_return_type_id,
			'filter_return_status_id' => $filter_return_status_id,
			'filter_date_added'       => $filter_date_added,
			'filter_date_modified'    => $filter_date_modified,
			'sort'                    => $sort,
			'order'                   => $order,
			'start'                   => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                   => $this->config->get('config_admin_limit')
		);
		$return_total                = $this->model_sale_return->getTotalReturns($data);
		$results                     = $this->model_sale_return->getReturns($data);

		foreach ($results as $result)
		{
			$action                  = array();
			$action[]                = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('sale/return/info', '&return_id=' . $result['return_id'] . $url, 'SSL')
			);
			$action[]                = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/return/update', '&return_id=' . $result['return_id'] . $url, 'SSL')
			);
			$this->data['returns'][] = array(
				'return_id'     => $result['return_id'],
				'osn'           => $result['osn'],
				'customer'      => $result['customer'],
				'product'       => $result['product'],
				'type'          => $result['type'],
				'status'        => $result['status'],
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['return_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}

		$this->data['heading_title']        = $this->language->get('heading_title');
		$this->data['text_no_results']      = $this->language->get('text_no_results');
		$this->data['column_return_id']     = $this->language->get('column_return_id');
		$this->data['column_osn']           = $this->language->get('column_order_id');
		$this->data['column_customer']      = $this->language->get('column_customer');
		$this->data['column_product']       = $this->language->get('column_product');
		$this->data['column_type']          = $this->language->get('column_type');
		$this->data['column_status']        = $this->language->get('column_status');
		$this->data['column_date_added']    = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action']        = $this->language->get('column_action');
		$this->data['button_insert']        = $this->language->get('button_insert');
		$this->data['button_delete']        = $this->language->get('button_delete');
		$this->data['button_filter']        = $this->language->get('button_filter');

		if (isset($this->session->data['error']))
		{
			$this->data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		}
		elseif (isset($this->error['warning']))
		{
			$this->data['error_warning'] = $this->error['warning'];
		}
		else
		{
			$this->data['error_warning'] = '';
		}

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

		if (isset($this->request->get['filter_return_id']))
		{
			$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
		}

		if (isset($this->request->get['filter_osn']))
		{
			$url .= '&filter_osn=' . $this->request->get['filter_osn'];
		}

		if (isset($this->request->get['filter_customer']))
		{
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_product']))
		{
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_return_type_id']))
		{
			$url .= '&filter_return_type_id=' . urlencode(html_entity_decode($this->request->get['filter_return_type_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_return_status_id']))
		{
			$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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

		$this->data['sort_return_id']     = $this->url->link('sale/return', '&sort=r.return_id' . $url, 'SSL');
		$this->data['sort_osn']           = $this->url->link('sale/return', '&sort=r.osn' . $url, 'SSL');
		$this->data['sort_customer']      = $this->url->link('sale/return', '&sort=customer' . $url, 'SSL');
		$this->data['sort_product']       = $this->url->link('sale/return', '&sort=product' . $url, 'SSL');
		$this->data['sort_type']          = $this->url->link('sale/return', '&sort=type' . $url, 'SSL');
		$this->data['sort_status']        = $this->url->link('sale/return', '&sort=status' . $url, 'SSL');
		$this->data['sort_date_added']    = $this->url->link('sale/return', '&sort=r.date_added' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('sale/return', '&sort=r.date_modified' . $url, 'SSL');
		$url                              = '';

		if (isset($this->request->get['filter_return_id']))
		{
			$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
		}

		if (isset($this->request->get['filter_osn']))
		{
			$url .= '&filter_osn=' . $this->request->get['filter_osn'];
		}

		if (isset($this->request->get['filter_customer']))
		{
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_product']))
		{
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_return_type_id']))
		{
			$url .= '&filter_return_type_id=' . urlencode(html_entity_decode($this->request->get['filter_return_type_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_return_status_id']))
		{
			$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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

		$pagination                            = new Pagination();
		$pagination->total                     = $return_total;
		$pagination->page                      = $page;
		$pagination->limit                     = $this->config->get('config_admin_limit');
		$pagination->text                      = $this->language->get('text_pagination');
		$pagination->url                       = $this->url->link('sale/return', $url . '&page={page}', 'SSL');
		$this->data['pagination']              = $pagination->render();
		$this->data['filter_return_id']        = $filter_return_id;
		$this->data['filter_osn']              = $filter_osn;
		$this->data['filter_customer']         = $filter_customer;
		$this->data['filter_product']          = $filter_product;
		$this->data['filter_return_type_id']   = $filter_return_type_id;
		$this->data['filter_return_status_id'] = $filter_return_status_id;
		$this->data['filter_date_added']       = $filter_date_added;
		$this->data['filter_date_modified']    = $filter_date_modified;

		$this->load->model('localisation/return_reason');
		$this->data['return_types'] = $this->model_localisation_return_reason->getReturnReasons(array(), 1);

		$this->load->model('localisation/return_status');
		$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		$this->data['sort']  = $sort;
		$this->data['order'] = $order;
		$this->template      = 'template/sale/return_list.tpl';
		$this->children      = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	private function getForm()
	{
		$this->data['entry_osn'] = $this->language->get('entry_order_id');
		$lang_arr                = array(
			'heading_title',
			'text_select',
			'text_opened',
			'text_unopened',
			'entry_customer',
			'entry_date_ordered',
			'entry_firstname',
			'entry_lastname',
			'entry_email',
			'entry_telephone',
			'entry_return_status',
			'entry_comment',
			'entry_product',
			'entry_model',
			'entry_quantity',
			'entry_type',
			'entry_reason',
			'entry_opened',
			'entry_action',
			'button_save',
			'button_cancel',
			'tab_return',
			'tab_product'
		);
		foreach ($lang_arr as $v)
		{
			$this->data[$v] = $this->language->get($v);
		}

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		if (isset($this->error['osn']))
		{
			$this->data['error_osn'] = $this->error['osn'];
		}
		else
		{
			$this->data['error_osn'] = '';
		}

		if (isset($this->error['firstname']))
		{
			$this->data['error_firstname'] = $this->error['firstname'];
		}
		else
		{
			$this->data['error_firstname'] = '';
		}

		if (isset($this->error['lastname']))
		{
			$this->data['error_lastname'] = $this->error['lastname'];
		}
		else
		{
			$this->data['error_lastname'] = '';
		}

		if (isset($this->error['email']))
		{
			$this->data['error_email'] = $this->error['email'];
		}
		else
		{
			$this->data['error_email'] = '';
		}

		if (isset($this->error['telephone']))
		{
			$this->data['error_telephone'] = $this->error['telephone'];
		}
		else
		{
			$this->data['error_telephone'] = '';
		}

		if (isset($this->error['product']))
		{
			$this->data['error_product'] = $this->error['product'];
		}
		else
		{
			$this->data['error_product'] = '';
		}

		if (isset($this->error['model']))
		{
			$this->data['error_model'] = $this->error['model'];
		}
		else
		{
			$this->data['error_model'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_return_id']))
		{
			$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
		}

		if (isset($this->request->get['filter_osn']))
		{
			$url .= '&filter_osn=' . $this->request->get['filter_osn'];
		}

		if (isset($this->request->get['filter_customer']))
		{
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_product']))
		{
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_return_type_id']))
		{
			$url .= '&filter_return_type_id=' . urlencode(html_entity_decode($this->request->get['filter_return_type_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_return_status_id']))
		{
			$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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
			'href'      => $this->url->link('sale/return', $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['return_id']))
		{
			$this->data['action'] = $this->url->link('sale/return/insert', $url, 'SSL');
		}
		else
		{
			$this->data['action'] = $this->url->link('sale/return/update', '&return_id=' . $this->request->get['return_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('sale/return', $url, 'SSL');

		if (isset($this->request->get['return_id']))
		{
			$return_info = $this->model_sale_return->getReturn($this->request->get['return_id']);
		}

		if (isset($this->request->post['osn']))
		{
			$this->data['osn'] = $this->request->post['osn'];
		}
		elseif (!empty($return_info))
		{
			$this->data['osn'] = $return_info['osn'];
		}
		else
		{
			$this->data['osn'] = '';
		}

		if (isset($this->request->post['date_ordered']))
		{
			$this->data['date_ordered'] = $this->request->post['date_ordered'];
		}
		elseif (!empty($return_info))
		{
			$this->data['date_ordered'] = $return_info['date_ordered'];
		}
		else
		{
			$this->data['date_ordered'] = '';
		}

		if (isset($this->request->post['customer']))
		{
			$this->data['customer'] = $this->request->post['customer'];
		}
		elseif (!empty($return_info))
		{
			$this->data['customer'] = $return_info['customer'];
		}
		else
		{
			$this->data['customer'] = '';
		}

		if (isset($this->request->post['customer_id']))
		{
			$this->data['customer_id'] = $this->request->post['customer_id'];
		}
		elseif (!empty($return_info))
		{
			$this->data['customer_id'] = $return_info['customer_id'];
		}
		else
		{
			$this->data['customer_id'] = '';
		}

		if (isset($this->request->post['firstname']))
		{
			$this->data['firstname'] = $this->request->post['firstname'];
		}
		elseif (!empty($return_info))
		{
			$this->data['firstname'] = $return_info['firstname'];
		}
		else
		{
			$this->data['firstname'] = '';
		}

		if (isset($this->request->post['lastname']))
		{
			$this->data['lastname'] = $this->request->post['lastname'];
		}
		elseif (!empty($return_info))
		{
			$this->data['lastname'] = $return_info['lastname'];
		}
		else
		{
			$this->data['lastname'] = '';
		}

		if (isset($this->request->post['email']))
		{
			$this->data['email'] = $this->request->post['email'];
		}
		elseif (!empty($return_info))
		{
			$this->data['email'] = $return_info['email'];
		}
		else
		{
			$this->data['email'] = '';
		}

		if (isset($this->request->post['telephone']))
		{
			$this->data['telephone'] = $this->request->post['telephone'];
		}
		elseif (!empty($return_info))
		{
			$this->data['telephone'] = $return_info['telephone'];
		}
		else
		{
			$this->data['telephone'] = '';
		}

		if (isset($this->request->post['product']))
		{
			$this->data['product'] = $this->request->post['product'];
		}
		elseif (!empty($return_info))
		{
			$this->data['product'] = $return_info['product'];
		}
		else
		{
			$this->data['product'] = '';
		}

		if (isset($this->request->post['product_id']))
		{
			$this->data['product_id'] = $this->request->post['product_id'];
		}
		elseif (!empty($return_info))
		{
			$this->data['product_id'] = $return_info['product_id'];
		}
		else
		{
			$this->data['product_id'] = '';
		}

		if (isset($this->request->post['model']))
		{
			$this->data['model'] = $this->request->post['model'];
		}
		elseif (!empty($return_info))
		{
			$this->data['model'] = $return_info['model'];
		}
		else
		{
			$this->data['model'] = '';
		}

		if (isset($this->request->post['quantity']))
		{
			$this->data['quantity'] = $this->request->post['quantity'];
		}
		elseif (!empty($return_info))
		{
			$this->data['quantity'] = $return_info['quantity'];
		}
		else
		{
			$this->data['quantity'] = '';
		}

		if (isset($this->request->post['opened']))
		{
			$this->data['opened'] = $this->request->post['opened'];
		}
		elseif (!empty($return_info))
		{
			$this->data['opened'] = $return_info['opened'];
		}
		else
		{
			$this->data['opened'] = '';
		}

		if (isset($this->request->post['return_reason_id']))
		{
			$this->data['return_reason_id'] = $this->request->post['return_reason_id'];
		}
		elseif (!empty($return_info))
		{
			$this->data['return_reason_id'] = $return_info['return_reason_id'];
		}
		else
		{
			$this->data['return_reason_id'] = '';
		}

		$this->load->model('localisation/return_reason');
		$this->data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();

		if (isset($this->request->post['return_type_id']))
		{
			$this->data['return_type_id'] = $this->request->post['return_type_id'];
		}
		elseif (!empty($return_info))
		{
			$this->data['return_type_id'] = $return_info['return_type_id'];
		}
		else
		{
			$this->data['return_type_id'] = '';
		}
		$this->data['return_types'] = $this->model_localisation_return_reason->getReturnReasons(array(), 1);

		if (isset($this->request->post['return_action_id']))
		{
			$this->data['return_action_id'] = $this->request->post['return_action_id'];
		}
		elseif (!empty($return_info))
		{
			$this->data['return_action_id'] = $return_info['return_action_id'];
		}
		else
		{
			$this->data['return_action_id'] = '';
		}

		$this->load->model('localisation/return_action');
		$this->data['return_actions'] = $this->model_localisation_return_action->getReturnActions();

		if (isset($this->request->post['comment']))
		{
			$this->data['comment'] = $this->request->post['comment'];
		}
		elseif (!empty($return_info))
		{
			$this->data['comment'] = $return_info['comment'];
		}
		else
		{
			$this->data['comment'] = '';
		}

		if (isset($this->request->post['return_status_id']))
		{
			$this->data['return_status_id'] = $this->request->post['return_status_id'];
		}
		elseif (!empty($return_info))
		{
			$this->data['return_status_id'] = $return_info['return_status_id'];
		}
		else
		{
			$this->data['return_status_id'] = '';
		}

		$this->load->model('localisation/return_status');
		$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();
		$this->template                = 'template/sale/return_form.tpl';
		$this->children                = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	public function info()
	{
		$this->load->model('sale/return');

		if (isset($this->request->get['return_id']))
		{
			$return_id = $this->request->get['return_id'];
		}
		else
		{
			$return_id = 0;
		}

		$return_info = $this->model_sale_return->getReturn($return_id);

		if ($return_info)
		{
			$this->load->language('sale/return');
			$this->document->setTitle($this->language->get('heading_title'));
			$this->data['text_order_id'] = $this->language->get('text_order_id');
			$lang_arr                    = array(
				'heading_title',
				'text_wait',
				'text_return_id',
				'text_date_ordered',
				'text_customer',
				'text_email',
				'text_telephone',
				'text_return_status',
				'text_date_added',
				'text_date_modified',
				'text_product',
				'text_model',
				'text_quantity',
				'text_return_type',
				'text_return_reason',
				'text_opened',
				'text_comment',
				'text_return_action',
				'entry_return_status',
				'entry_notify',
				'entry_comment',
				'button_save',
				'button_cancel',
				'button_add_history',
				'tab_return',
				'tab_product',
				'tab_return_history'
			);
			foreach ($lang_arr as $v)
			{
				$this->data[$v] = $this->language->get($v);
			}

			$url = '';
			if (isset($this->request->get['filter_return_id']))
			{
				$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
			}

			if (isset($this->request->get['filter_osn']))
			{
				$url .= '&filter_osn=' . $this->request->get['filter_osn'];
			}

			if (isset($this->request->get['filter_customer']))
			{
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_product']))
			{
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_return_type_id']))
			{
				$url .= '&filter_return_type_id=' . urlencode(html_entity_decode($this->request->get['filter_return_type_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_return_status_id']))
			{
				$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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
				'href'      => $this->url->link('sale/return', $url, 'SSL'),
				'separator' => ' :: '
			);
			$this->data['cancel']        = $this->url->link('sale/return', $url, 'SSL');
			$this->load->model('sale/order');
			$order_info              = $this->model_sale_order->getOrder($return_info['osn']);
			$this->data['return_id'] = $return_info['return_id'];
			$this->data['osn']       = $return_info['osn'];

			if ($return_info['osn'] && $order_info)
			{
				$this->data['order'] = $this->url->link('sale/order/info', '&osn=' . $return_info['osn'], 'SSL');
			}
			else
			{
				$this->data['order'] = '';
			}

			$this->data['date_ordered'] = date($this->language->get('date_format_short'), strtotime($return_info['date_ordered']));
			$this->data['firstname']    = $return_info['firstname'];
			$this->data['lastname']     = $return_info['lastname'];

			if ($return_info['customer_id'])
			{
				$this->data['customer'] = $this->url->link('sale/customer/update', '&customer_id=' . $return_info['customer_id'], 'SSL');
			}
			else
			{
				$this->data['customer'] = '';
			}

			$this->data['email']     = $return_info['email'];
			$this->data['telephone'] = $return_info['telephone'];
			$this->load->model('localisation/return_status');
			$return_status_info = $this->model_localisation_return_status->getReturnStatus($return_info['return_status_id']);

			if ($return_status_info)
			{
				$this->data['return_status'] = $return_status_info['name'];
			}
			else
			{
				$this->data['return_status'] = '';
			}

			$this->data['date_added']    = date($this->language->get('date_format_short'), strtotime($return_info['date_added']));
			$this->data['date_modified'] = date($this->language->get('date_format_short'), strtotime($return_info['date_modified']));
			$this->data['product']       = $return_info['product'];
			$this->data['model']         = $return_info['model'];
			$this->data['quantity']      = $return_info['quantity'];

			$this->load->model('localisation/return_reason');
			$return_reason_info          = $this->model_localisation_return_reason->getReturnReason($return_info['return_reason_id']);
			$this->data['return_reason'] = !empty($return_reason_info) ? $return_reason_info['name'] : '';

			$return_reason_info        = $this->model_localisation_return_reason->getReturnReason($return_info['return_type_id']);
			$this->data['return_type'] = !empty($return_reason_info) ? $return_reason_info['name'] : '';

			$this->data['opened']  = $return_info['opened'] ? $this->language->get('text_yes') : $this->language->get('text_no');
			$this->data['comment'] = nl2br($return_info['comment']);
			$this->load->model('localisation/return_action');
			$this->data['return_actions'] = $this->model_localisation_return_action->getReturnActions();

			$this->data['return_action_id'] = $return_info['return_action_id'];
			$this->data['return_statuses']  = $this->model_localisation_return_status->getReturnStatuses();
			$this->data['return_status_id'] = $return_info['return_status_id'];
			$this->template                 = 'template/sale/return_info.tpl';
			$this->children                 = array(
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

	private function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'sale/return'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((mb_strlen($this->request->post['firstname']) < 1) || (mb_strlen($this->request->post['firstname']) > 32))
		{
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((mb_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email']))
		{
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((mb_strlen($this->request->post['telephone']) < 3) || (mb_strlen($this->request->post['telephone']) > 32))
		{
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if ((mb_strlen($this->request->post['product']) < 1) || (mb_strlen($this->request->post['product']) > 255))
		{
			$this->error['product'] = $this->language->get('error_product');
		}

		if ((mb_strlen($this->request->post['model']) < 1) || (mb_strlen($this->request->post['model']) > 64))
		{
			$this->error['model'] = $this->language->get('error_model');
		}

		if (empty($this->request->post['return_type_id']))
		{
			$this->error['type'] = $this->language->get('error_type');
		}

		if (empty($this->request->post['return_reason_id']))
		{
			$this->error['reason'] = $this->language->get('error_reason');
		}

		if ($this->error && !isset($this->error['warning']))
		{
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return (!$this->error) ? true : false;
	}

	private function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'sale/return'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return (!$this->error) ? true : false;
	}

	public function action()
	{
		$this->language->load('sale/return');
		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST')
		{
			if (!$this->user->hasPermission('modify', 'sale/return'))
			{
				$json['error'] = $this->language->get('error_permission');
			}

			if (!$json)
			{
				$this->load->model('sale/return');

				$json['success'] = $this->language->get('text_success');
				$this->model_sale_return->editReturnAction($this->request->get['return_id'], $this->request->post['return_action_id']);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function history()
	{
		$this->language->load('sale/return');
		$this->data['error']   = '';
		$this->data['success'] = '';
		$this->load->model('sale/return');

		if ($this->request->server['REQUEST_METHOD'] == 'POST')
		{
			if (!$this->user->hasPermission('modify', 'sale/return'))
			{
				$this->data['error'] = $this->language->get('error_permission');
			}

			if (!$this->data['error'])
			{
				$this->model_sale_return->addReturnHistory($this->request->get['return_id'], $this->request->post);

				$this->data['success'] = $this->language->get('text_success');
			}
		}

		$this->data['text_no_results']   = $this->language->get('text_no_results');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_status']     = $this->language->get('column_status');
		$this->data['column_notify']     = $this->language->get('column_notify');
		$this->data['column_comment']    = $this->language->get('column_comment');

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

		$this->data['histories'] = array();
		$results                 = $this->model_sale_return->getReturnHistories($this->request->get['return_id'], ($page - 1) * 10, 10);

		foreach ($results as $result)
		{
			$this->data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$history_total            = $this->model_sale_return->getTotalReturnHistories($this->request->get['return_id']);
		$pagination               = new Pagination();
		$pagination->total        = $history_total;
		$pagination->page         = $page;
		$pagination->limit        = 10;
		$pagination->text         = $this->language->get('text_pagination');
		$pagination->url          = $this->url->link('sale/return/history', '&return_id=' . $this->request->get['return_id'] . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
		$this->template           = 'template/sale/return_history.tpl';
		$this->response->setOutput($this->render());
	}
}

?>