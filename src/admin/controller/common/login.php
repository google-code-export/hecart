<?php
class ControllerCommonLogin extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('common/login');
		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->user->isLogged() && isset($this->request->cookie['token']) && ($this->request->cookie['token'] == $this->session->data['token']))
		{
			$this->redirect($this->url->link('common/home'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
		{
			$this->session->data['token'] = md5(mt_rand());
			wcore_utils::set_cookie('token', $this->session->data['token']);
			wcore_utils::set_cookie('vtoken', md5(substr($this->session->data['token'], 3, 16) . SITE_MD5_KEY));

			if (isset($this->request->post['redirect']))
			{
				$this->redirect($this->request->post['redirect']);
			}
			else
			{
				$this->redirect($this->url->link('common/home'));
			}
		}

		$this->data['heading_title']  = $this->language->get('heading_title');
		$this->data['text_login']     = $this->language->get('text_login');
		$this->data['text_forgotten'] = $this->language->get('text_forgotten');
		$this->data['entry_username'] = $this->language->get('entry_username');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['button_login']   = $this->language->get('button_login');

		if ((isset($this->session->data['token']) && !isset($this->request->cookie['token'])) || ((isset($this->request->cookie['token']) && (isset($this->session->data['token']) && ($this->request->cookie['token'] != $this->session->data['token'])))))
		{
			$this->error['warning'] = $this->language->get('error_token');
		}

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

		$this->data['action'] = $this->url->link('common/login', '', 'SSL');

		if (isset($this->request->post['username']))
		{
			$this->data['username'] = $this->request->post['username'];
		}
		else
		{
			$this->data['username'] = '';
		}

		if (isset($this->request->post['password']))
		{
			$this->data['password'] = $this->request->post['password'];
		}
		else
		{
			$this->data['password'] = '';
		}

		if (isset($this->request->get['route']))
		{
			$route = $this->request->get['route'];

			unset($this->request->get['route']);

			if (isset($this->request->cookie['token']))
			{
				unset($this->request->cookie['token']);
			}

			$url = '';

			if ($this->request->get)
			{
				$url .= http_build_query($this->request->get);
			}

			$this->data['redirect'] = $this->url->link($route, $url, 'SSL');
		}
		else
		{
			$this->data['redirect'] = '';
		}

		$this->data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
		$this->template          = 'template/login.tpl';
		$this->children          = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	private function validate()
	{
		if (isset($this->request->post['username']) && isset($this->request->post['password']) && !$this->user->login($this->request->post['username'], $this->request->post['password']))
		{
			$this->error['warning'] = $this->language->get('error_login');
		}

		return (!$this->error) ? true : false;
	}
}

?>