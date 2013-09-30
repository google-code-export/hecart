<?php
class ControllerModuleMap extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('module/map');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate()))
		{
			$this->model_setting_setting->editSetting('map', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect('/extension/module');
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled']  = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left']     = $this->language->get('text_left');
		$this->data['text_right']    = $this->language->get('text_right');

		$this->data['entry_position']      = $this->language->get('entry_position');
		$this->data['entry_status']        = $this->language->get('entry_status');
		$this->data['entry_layout']        = $this->language->get('entry_layout');
		$this->data['entry_title']         = $this->language->get('entry_title');
		$this->data['entry_width']         = $this->language->get('entry_width');
		$this->data['entry_height']        = $this->language->get('entry_height');
		$this->data['entry_zoom']          = $this->language->get('entry_zoom');
		$this->data['entry_address']       = $this->language->get('entry_address');
		$this->data['entry_sort_order']    = $this->language->get('entry_sort_order');
		$this->data['button_save']         = $this->language->get('button_save');
		$this->data['button_cancel']       = $this->language->get('button_cancel');
		$this->data['button_add_module']   = $this->language->get('button_add_module');
		$this->data['button_remove']       = $this->language->get('button_remove');
		$this->data['text_content_top']    = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$this->data['text_column_left']    = $this->language->get('text_column_left');
		$this->data['text_column_right']   = $this->language->get('text_column_right');

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		$this->data['modules'] = array();
		if (isset($this->request->post['map_module']))
		{
			$this->data['modules'] = $this->request->post['map_module'];
		}
		elseif ($this->config->get('map_module'))
		{
			$this->data['modules'] = $this->config->get('map_module');
		}

		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/map'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('module/map');
		$this->data['cancel'] = $this->url->link('extension/module');

		if (isset($this->request->post['map_title']))
		{
			$this->data['map_title'] = $this->request->post['map_title'];
		}
		else
		{
			$this->data['map_title'] = $this->config->get('map_title');
		}
		if (isset($this->request->post['map_height']))
		{
			$this->data['map_height'] = $this->request->post['map_height'];
		}
		else
		{
			$this->data['map_height'] = $this->config->get('map_height');
		}
		if (isset($this->request->post['map_position']))
		{
			$this->data['map_position'] = $this->request->post['map_position'];
		}
		else
		{
			$this->data['map_position'] = $this->config->get('map_position');
		}

		if (isset($this->request->post['map_status']))
		{
			$this->data['map_status'] = $this->request->post['map_status'];
		}
		else
		{
			$this->data['map_status'] = $this->config->get('map_status');
		}

		if (isset($this->request->post['map_sort_order']))
		{
			$this->data['map_sort_order'] = $this->request->post['map_sort_order'];
		}
		else
		{
			$this->data['map_sort_order'] = $this->config->get('map_sort_order');
		}

		$this->template = 'template/module/map.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'module/map'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return (!$this->error) ? true : false;
	}
}

?>