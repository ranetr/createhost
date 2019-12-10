<?php
class indexController extends Controller {
	private $limit = 20;
	public function index($page = 1) {
		$this->load->checkLicense();
		$this->document->setActiveSection('licenses');
		$this->document->setActiveItem('index');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->library('pagination');
		$this->load->model('licenses');
		
		$userid = $this->user->getId();
		
		$options = array(
			'start' => ($page - 1) * $this->limit,
			'limit' => $this->limit
		);
		
		$total = $this->licensesModel->getTotalLicenses(array('user_id' => (int)$userid));
		$licenses = $this->licensesModel->getLicenses(array('user_id' => (int)$userid), array(), array(), $options);
		
		$paginationLib = new paginationLibrary();
		
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $this->config->url . 'licenses/index/index/{page}';
		
		$pagination = $paginationLib->render();
		
		$this->data['licenses'] = $licenses;
		$this->data['pagination'] = $pagination;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('licenses/index', $this->data);
	}
}
?>
