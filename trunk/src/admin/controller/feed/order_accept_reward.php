<?php
class ControllerFeedOrderAcceptReward extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('feed/order_accept_reward');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
		{
			$this->model_setting_setting->editSetting('order_accept_reward', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('extension/feed'));
		}

		$this->data['heading_title']  = $this->language->get('heading_title');
		$this->data['text_enabled']   = $this->language->get('text_enabled');
		$this->data['text_disabled']  = $this->language->get('text_disabled');
		$this->data['entry_status']   = $this->language->get('entry_status');
		$this->data['entry_shipped']  = $this->language->get('entry_shipped');
		$this->data['entry_value']    = $this->language->get('entry_value');
		$this->data['entry_return']   = $this->language->get('entry_return');
		$this->data['entry_complete'] = $this->language->get('entry_complete');
		$this->data['button_save']    = $this->language->get('button_save');
		$this->data['button_cancel']  = $this->language->get('button_cancel');
		$this->data['tab_general']    = $this->language->get('tab_general');

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		if (isset($this->error['reward_value']))
		{
			$this->data['error_reward_value'] = $this->error['reward_value'];
		}
		else
		{
			$this->data['error_reward_value'] = '';
		}

		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_feed'),
			'href'      => $this->url->link('extension/feed'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('feed/order_accept_reward'),
			'separator' => ' :: '
		);
		$this->data['action']        = $this->url->link('feed/order_accept_reward');
		$this->data['cancel']        = $this->url->link('extension/feed');

		if (isset($this->request->post['order_accept_reward_status']))
		{
			$this->data['order_accept_reward_status'] = $this->request->post['order_accept_reward_status'];
		}
		else
		{
			$this->data['order_accept_reward_status'] = $this->config->get('order_accept_reward_status');
		}

		$this->data['order_accept_reward_shipped']  = $this->config->get('order_accept_reward_shipped');
		$this->data['order_accept_reward_complete'] = $this->config->get('order_accept_reward_complete');
		$this->data['order_accept_reward_return']   = $this->config->get('order_accept_reward_return');
		$this->data['order_accept_reward_value']    = $this->config->get('order_accept_reward_value');

		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/return_reason');
		$this->data['return_types'] = $this->model_localisation_return_reason->getReturnReasons(array(), 1);

		$this->template = 'template/feed/order_accept_reward.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'feed/order_accept_reward'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$reward_value = $this->request->post['order_accept_reward_value'];
		if (!$reward_value || !is_numeric($reward_value) || $reward_value <= 0)
		{
			$this->error['reward_value'] = $this->language->get('error_reward_value');
		}

		return (!$this->error) ? true : false;
	}
}

?>