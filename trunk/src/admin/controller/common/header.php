<?php
class ControllerCommonHeader extends Controller
{
	protected function index()
	{
		$this->data['title'] = $this->document->getTitle();

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
		{
			$this->data['base'] = 'https://' . DOMAIN_NAME . '/';
		}
		else
		{
			$this->data['base'] = 'http://' . DOMAIN_NAME . '/';
		}

		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords']    = $this->document->getKeywords();
		$this->data['links']       = $this->document->getLinks();
		$this->data['styles']      = $this->document->getStyles();
		$this->data['scripts']     = $this->document->getScripts();
		$this->data['lang']        = $this->language->get('code');

		$this->load->language('common/header');
		$lang_arr = array(
			'direction',
			'heading_title',
			'text_affiliate',
			'text_attribute',
			'text_attribute_group',
			'text_backup',
			'text_flush_mem',
			'text_oper_mem',
			'text_banner',
			'text_catalog',
			'text_category',
			'text_confirm',
			'text_country',
			'text_coupon',
			'text_currency',
			'text_customer',
			'text_customer_group',
			'text_customer_blacklist',
			'text_sale',
			'text_design',
			'text_documentation',
			'text_download',
			'text_error_log',
			'text_extension',
			'text_feed',
			'text_front',
			'text_geo_zone',
			'text_dashboard',
			'text_help',
			'text_information_group',
			'text_information',
			'text_language',
			'text_layout',
			'text_localisation',
			'text_logout',
			'text_contact',
			'text_manufacturer',
			'text_module',
			'text_option',
			'text_order',
			'text_order_status',
			'text_hecart',
			'text_payment',
			'text_product',
			'text_reports',
			'text_report_sale_order',
			'text_report_sale_tax',
			'text_report_sale_shipping',
			'text_report_sale_return',
			'text_report_sale_coupon',
			'text_report_product_viewed',
			'text_report_product_purchased',
			'text_report_customer_online',
			'text_report_customer_order',
			'text_report_customer_reward',
			'text_report_customer_credit',
			'text_report_affiliate_commission',
			'text_report_sale_return',
			'text_report_product_purchased',
			'text_report_product_viewed',
			'text_report_customer_order',
			'text_review',
			'text_return',
			'text_return_action',
			'text_return_reason',
			'text_return_status',
			'text_support',
			'text_shipping',
			'text_setting',
			'text_stock_status',
			'text_system',
			'text_tax',
			'text_tax_class',
			'text_tax_rate',
			'text_total',
			'text_user',
			'text_user_group',
			'text_users',
			'text_voucher',
			'text_voucher_theme',
			'text_weight_class',
			'text_length_class',
			'text_zone',
			'text_livechat'
		);
		foreach ($lang_arr as $v)
		{
			$this->data[$v] = $this->language->get($v);
		}

		if (!$this->user->isLogged() || !isset($this->request->cookie['token']) || !isset($this->session->data['token']) || ($this->request->cookie['token'] != $this->session->data['token']))
		{
			$this->data['logged'] = '';
			$this->data['home']   = $this->url->link('common/login');
		}
		else
		{
			$this->data['logged']                      = sprintf($this->language->get('text_logged'), $this->user->getUserName());
			$this->data['home']                        = $this->url->link('common/home');
			$this->data['affiliate']                   = $this->url->link('sale/affiliate');
			$this->data['attribute']                   = $this->url->link('catalog/attribute');
			$this->data['attribute_group']             = $this->url->link('catalog/attribute_group');
			$this->data['backup']                      = $this->url->link('tool/backup');
			$this->data['flush_mem']                   = $this->url->link('setting/setting/flush');
			$this->data['oper_mem']                    = $this->url->link('tool/memcache');
			$this->data['banner']                      = $this->url->link('design/banner');
			$this->data['category']                    = $this->url->link('catalog/category');
			$this->data['country']                     = $this->url->link('localisation/country');
			$this->data['coupon']                      = $this->url->link('sale/coupon');
			$this->data['currency']                    = $this->url->link('localisation/currency');
			$this->data['customer']                    = $this->url->link('sale/customer');
			$this->data['customer_group']              = $this->url->link('sale/customer_group');
			$this->data['customer_blacklist']          = $this->url->link('sale/customer_blacklist');
			$this->data['download']                    = $this->url->link('catalog/download');
			$this->data['error_log']                   = $this->url->link('tool/error_log');
			$this->data['feed']                        = $this->url->link('extension/feed');
			$this->data['geo_zone']                    = $this->url->link('localisation/geo_zone');
			$this->data['information']                 = $this->url->link('catalog/information');
			$this->data['information_group']           = $this->url->link('catalog/information_group');
			$this->data['language']                    = $this->url->link('localisation/language');
			$this->data['layout']                      = $this->url->link('design/layout');
			$this->data['logout']                      = $this->url->link('common/logout');
			$this->data['contact']                     = $this->url->link('sale/contact');
			$this->data['manufacturer']                = $this->url->link('catalog/manufacturer');
			$this->data['module']                      = $this->url->link('extension/module');
			$this->data['option']                      = $this->url->link('catalog/option');
			$this->data['order']                       = $this->url->link('sale/order');
			$this->data['order_export']                = $this->url->link('sale/order_export');
			$this->data['order_status']                = $this->url->link('localisation/order_status');
			$this->data['payment']                     = $this->url->link('extension/payment');
			$this->data['product']                     = $this->url->link('catalog/product');
			$this->data['livechat']                    = $this->url->link('tool/livechat');
			$this->data['report_sale_order']           = $this->url->link('report/sale_order');
			$this->data['report_sale_tax']             = $this->url->link('report/sale_tax');
			$this->data['report_sale_shipping']        = $this->url->link('report/sale_shipping');
			$this->data['report_sale_return']          = $this->url->link('report/sale_return');
			$this->data['report_sale_coupon']          = $this->url->link('report/sale_coupon');
			$this->data['report_sale_custom']          = $this->url->link('report/sale_custom');
			$this->data['report_sale_custom_products'] = $this->url->link('report/sale_custom_products');
			$this->data['report_product_viewed']       = $this->url->link('report/product_viewed');
			$this->data['report_product_purchased']    = $this->url->link('report/product_purchased');
			$this->data['report_customer_online']      = $this->url->link('report/customer_online');
			$this->data['report_customer_order']       = $this->url->link('report/customer_order');
			$this->data['report_customer_reward']      = $this->url->link('report/customer_reward');
			$this->data['report_customer_credit']      = $this->url->link('report/customer_credit');
			$this->data['report_affiliate_commission'] = $this->url->link('report/affiliate_commission');
			$this->data['review']                      = $this->url->link('catalog/review');
			$this->data['return']                      = $this->url->link('sale/return');
			$this->data['return_action']               = $this->url->link('localisation/return_action');
			$this->data['return_reason']               = $this->url->link('localisation/return_reason');
			$this->data['return_status']               = $this->url->link('localisation/return_status');
			$this->data['shipping']                    = $this->url->link('extension/shipping');
			$this->data['setting']                     = $this->url->link('setting/store');
			$this->data['store']                       = HTTP_STORE;
			$this->data['stock_status']                = $this->url->link('localisation/stock_status');
			$this->data['tax_class']                   = $this->url->link('localisation/tax_class');
			$this->data['tax_rate']                    = $this->url->link('localisation/tax_rate');
			$this->data['total']                       = $this->url->link('extension/total');
			$this->data['user']                        = $this->url->link('user/user');
			$this->data['user_group']                  = $this->url->link('user/user_permission');
			$this->data['voucher']                     = $this->url->link('sale/voucher');
			$this->data['voucher_theme']               = $this->url->link('sale/voucher_theme');
			$this->data['weight_class']                = $this->url->link('localisation/weight_class');
			$this->data['length_class']                = $this->url->link('localisation/length_class');
			$this->data['zone']                        = $this->url->link('localisation/zone');
			$this->data['stores']                      = array();
			$this->load->model('setting/store');
			$results = $this->model_setting_store->getStores();

			foreach ($results as $result)
			{
				$this->data['stores'][] = array(
					'name' => $result['name'],
					'href' => $result['url']
				);
			}
		}

		$this->template = 'template/header.tpl';
		$this->render();
	}
}

?>