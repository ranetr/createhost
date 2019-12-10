<?php
class createController extends Controller {
	public function index() {
		$this->load->checkLicense();
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('licenses');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url . 'admin/licenses/index');
		}
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('admin/licenses/create', $this->data);
	}
	
	public function ajax() {
		$this->load->checkLicense();
		if(!$this->user->isLogged()) {
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 3) {
			$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('licenses');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$ip = explode(":", @$this->request->post['ip']);
				if(is_null($ip[1])) $ip[1] = '7777'; // default port
				$userid = @$this->request->post['userid'];
				$months = @$this->request->post['months'];
				$status = @$this->request->post['status'];
				$mark = @$this->request->post['mark'];
				
				$token = bin2hex(random_bytes(32)); // generate token
				
				$licenseData = array(
					'user_id'			=> (int)$userid,
					'license_ip'		=> $ip[0],
					'license_token'		=> $token,
					'license_mark'		=> $mark,
					'license_port'		=> (int)$ip[1],
					'license_months'	=> (int)$months,
					'license_status'	=> (int)$status
				);
				$licenseid = $this->licensesModel->createLicense($licenseData);
				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно создали лицензию!";
				$this->data['id'] = $licenseid;
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	private function validatePOST() {
		$this->load->checkLicense();
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$ip = explode(":", @$this->request->post['ip']);
		if(is_null($ip[1])) $ip[1] = '7777'; // default port
		$userid = @$this->request->post['userid'];
		$months = @$this->request->post['months'];
		$status = @$this->request->post['status'];
		$mark = @$this->request->post['mark'];
		
		if(mb_strlen($ip[0]) < 2 || mb_strlen($ip[0]) > 15) {
			$result = "IP должен содержать от 2 до 15 символов!";
		}
		elseif($this->licensesModel->getTotalLicenses(array('license_ip' => $ip[0], 'license_port' => (int)$ip[1]))) {
			$result = "Указанный IP уже зарегистрирован!";
		}
		elseif($status < 0 || $status > 1) {
			$result = "Укажите допустимый статус!";
		}
		elseif($mark != null && (mb_strlen($mark) < 2 || mb_strlen($mark) > 16)) {
			$result = "Пометка должна содержать от 2 до 16 символов!";
		}
		return $result;
	}
}
?>
