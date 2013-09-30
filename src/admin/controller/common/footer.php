<?php
class ControllerCommonFooter extends Controller
{
	protected function index()
	{
		$this->load->language('common/footer');
		$this->data['text_footer'] = sprintf($this->language->get('text_footer'), VERSION);
		$this->template            = 'template/footer.tpl';
		$this->render();
	}
}

?>