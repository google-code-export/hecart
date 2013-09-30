<?php
class ControllerCatalogCategory extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('catalog/category');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/category');

		$this->getList();
	}

	public function insert()
	{
		$this->load->language('catalog/category');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/category');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
		{
			$this->model_catalog_category->addCategory($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('catalog/category'));
		}

		$this->getForm();
	}

	public function update()
	{
		$this->load->language('catalog/category');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/category');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
		{
			$this->model_catalog_category->editCategory($this->request->get['category_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('catalog/category'));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('catalog/category');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/category');

		if (isset($this->request->post['selected']) && $this->validateDelete())
		{
			foreach ($this->request->post['selected'] as $category_id)
			{
				$this->model_catalog_category->deleteCategory($category_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('catalog/category'));
		}

		$this->getList();
	}

	private function getList()
	{
		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/category'),
			'separator' => ' :: '
		);
		$this->data['insert']        = $this->url->link('catalog/category/insert');
		$this->data['delete']        = $this->url->link('catalog/category/delete');
		$this->data['categories']    = array();
		$results                     = $this->model_catalog_category->getCategories(0);

		foreach ($results as $result)
		{
			$action                     = array();
			$action[]                   = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/category/update', '&category_id=' . $result['category_id'], 'SSL')
			);
			$this->data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['category_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		$this->data['heading_title']     = $this->language->get('heading_title');
		$this->data['text_no_results']   = $this->language->get('text_no_results');
		$this->data['column_name']       = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action']     = $this->language->get('column_action');
		$this->data['button_insert']     = $this->language->get('button_insert');
		$this->data['button_delete']     = $this->language->get('button_delete');

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

		$this->template = 'template/catalog/category_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	private function getForm()
	{
		$lang_str = 'heading_title,text_none,text_default,text_image_manager,text_browse,text_clear,text_enabled,text_disabled,' . 'text_percent,text_amount,entry_name,entry_meta_keyword,entry_meta_description,entry_description,entry_store,entry_keyword,' . 'entry_parent,entry_image,entry_link,entry_top,entry_column,entry_home,entry_home_count,entry_sort_order,entry_status,entry_layout,button_save,button_cancel,tab_general,tab_data,tab_design';
		$lang_res = explode(',', $lang_str);
		foreach ($lang_res as $v)
		{
			$this->data[$v] = $this->language->get($v);
		}
		unset($lang_res);
		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
		$this->data['error_name']    = isset($this->error['name']) ? $this->error['name'] : array();
		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/category'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['category_id']))
		{
			$this->data['action'] = $this->url->link('catalog/category/insert');
		}
		else
		{
			$category_info        = $this->model_catalog_category->getCategory($this->request->get['category_id']);
			$this->data['action'] = $this->url->link('catalog/category/update', '&category_id=' . $this->request->get['category_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/category');

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['category_description']))
		{
			$this->data['category_description'] = $this->request->post['category_description'];
		}
		elseif (isset($this->request->get['category_id']))
		{
			$this->data['category_description'] = $this->model_catalog_category->getCategoryDescriptions($this->request->get['category_id']);
		}
		else
		{
			$this->data['category_description'] = array();
		}

		$categories = $this->model_catalog_category->getCategories(0);

		// Remove own id from list
		if (!empty($category_info))
		{
			foreach ($categories as $key => $category)
			{
				if ($category['category_id'] == $category_info['category_id'])
				{
					unset($categories[$key]);
				}
			}
		}

		$this->data['categories'] = $categories;

		if (isset($this->request->post['parent_id']))
		{
			$this->data['parent_id'] = $this->request->post['parent_id'];
		}
		elseif (!empty($category_info))
		{
			$this->data['parent_id'] = $category_info['parent_id'];
		}
		else
		{
			$this->data['parent_id'] = 0;
		}

		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();
		if (isset($this->request->post['category_store']))
		{
			$this->data['category_store'] = $this->request->post['category_store'];
		}
		elseif (isset($this->request->get['category_id']))
		{
			$this->data['category_store'] = $this->model_catalog_category->getCategoryStores($this->request->get['category_id']);
		}
		else
		{
			$this->data['category_store'] = array(0);
		}

		if (isset($this->request->post['keyword']))
		{
			$this->data['keyword'] = $this->request->post['keyword'];
		}
		elseif (!empty($category_info))
		{
			$this->data['keyword'] = $category_info['keyword'];
		}
		else
		{
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['image']))
		{
			$this->data['image'] = $this->request->post['image'];
		}
		elseif (!empty($category_info))
		{
			$this->data['image'] = $category_info['image'];
		}
		else
		{
			$this->data['image'] = '';
		}

		$this->load->model('tool/image');
		if (isset($this->request->post['image']) && $this->request->post['image'])
		{
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		}
		elseif (!empty($category_info) && $category_info['image'])
		{
			$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
		}
		else
		{
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		if (isset($this->request->post['top']))
		{
			$this->data['top'] = $this->request->post['top'];
		}
		elseif (!empty($category_info))
		{
			$this->data['top'] = $category_info['top'];
		}
		else
		{
			$this->data['top'] = 0;
		}

		if (isset($this->request->post['column']))
		{
			$this->data['column'] = $this->request->post['column'];
		}
		elseif (!empty($category_info))
		{
			$this->data['column'] = $category_info['column'];
		}
		else
		{
			$this->data['column'] = 1;
		}

		if (isset($this->request->post['home']))
		{
			$this->data['home'] = $this->request->post['home'];
		}
		elseif (!empty($category_info))
		{
			$this->data['home'] = $category_info['home'];
		}
		else
		{
			$this->data['home'] = 0;
		}

		if (isset($this->request->post['home_count']))
		{
			$this->data['home_count'] = $this->request->post['home_count'];
		}
		elseif (!empty($category_info))
		{
			$this->data['home_count'] = $category_info['home_count'];
		}
		else
		{
			$this->data['home_count'] = 1;
		}

		if (isset($this->request->post['link']))
		{
			$this->data['link'] = $this->request->post['link'];
		}
		elseif (!empty($category_info))
		{
			$this->data['link'] = $category_info['link'];
		}
		else
		{
			$this->data['link'] = '';
		}

		if (isset($this->request->post['sort_order']))
		{
			$this->data['sort_order'] = $this->request->post['sort_order'];
		}
		elseif (!empty($category_info))
		{
			$this->data['sort_order'] = $category_info['sort_order'];
		}
		else
		{
			$this->data['sort_order'] = 0;
		}

		if (isset($this->request->post['status']))
		{
			$this->data['status'] = $this->request->post['status'];
		}
		elseif (!empty($category_info))
		{
			$this->data['status'] = $category_info['status'];
		}
		else
		{
			$this->data['status'] = 1;
		}

		if (isset($this->request->post['category_layout']))
		{
			$this->data['category_layout'] = $this->request->post['category_layout'];
		}
		elseif (isset($this->request->get['category_id']))
		{
			$this->data['category_layout'] = $this->model_catalog_category->getCategoryLayouts($this->request->get['category_id']);
		}
		else
		{
			$this->data['category_layout'] = array();
		}

		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		$this->template        = 'template/catalog/category_form.tpl';
		$this->children        = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	private function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'catalog/category'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['category_description'] as $language_id => $value)
		{
			if ((mb_strlen($value['name']) < 2) || (mb_strlen($value['name']) > 255))
			{
				$this->error['name'][$language_id] = $this->language->get('error_name');
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
		if (!$this->user->hasPermission('modify', 'catalog/category'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return (!$this->error) ? true : false;
	}
}

?>