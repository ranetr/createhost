<?php
class indexController extends Controller {
	public function index() {
		$this->load->checkLicense();
		$this->document->setActiveSection('main');
		$this->document->setActiveItem('index');

		if($this->user->isLogged()) {
			$this->response->redirect($this->config->url . 'servers/index');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}

		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('main/index', $this->data);
	}
}
?>