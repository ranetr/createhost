<?php
class indexController extends Controller {
	private $limit = 20;
	public function index($page = 1) {
		$this->load->checkLicense();
		
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('licenses');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->library('pagination');
		$this->load->model('licenses');
		
		$getData = array();
		
		if(isset($this->request->get['userid'])) {
			$getData['licenses.user_id'] = (int)$this->request->get['userid'];
		}

		$getOptions = array(
			'start' => ($page - 1) * $this->limit,
			'limit' => $this->limit
		);
		
		$total = $this->licensesModel->getTotalLicenses($getData);
		$licenses = $this->licensesModel->getLicenses($getData, array('users'), array(), $getOptions);
		
		$paginationLib = new paginationLibrary();
		
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $this->config->url . 'admin/licenses/index/index/{page}';
		
		$pagination = $paginationLib->render();
		
		$this->data['licenses'] = $licenses;
		$this->data['pagination'] = $pagination;

		$this->data['user_access_level'] = $this->user->getAccessLevel();
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('admin/licenses/index', $this->data);
	}
}
?>
