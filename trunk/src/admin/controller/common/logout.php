<?php
class ControllerCommonLogout extends Controller
{
	public function index()
	{
		$this->user->logout();

		unset($this->session->data['token']);
		wcore_utils::set_cookie('token', null);
		wcore_utils::set_cookie('vtoken', null);

		$this->redirect($this->url->link('common/login', '', 'SSL'));
	}
}

?>