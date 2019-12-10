<?php
/*
* @LitePanel
* @Version: 1.0.1
* @Date: 29.12.2012
* @Developed by QuickDevel
*/
class orderController extends Controller {
	public function index() {
		$this->load->checkLicense();
		$this->document->setActiveSection('licenses');
		$this->document->setActiveItem('order');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('licenses/order', $this->data);
	}
	
	public function ajax() {
		$this->load->checkLicense();
		if(!$this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 1) {
	  		$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('users');
		$this->load->model('licenses');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$ip = explode(":", @$this->request->post['ip']);
				$months = @$this->request->post['months'];
				
				$userid = $this->user->getId();
				$balance = $this->user->getBalance();

				$price = 490; // 1 month RUB
			
				switch($months) {
					case "3":
						$months = 3;
						$price = $price * 0.95 * $months;
						break;
					case "6":
						$months = 6;
						$price = $price * 0.90 * $months;
						break;
					case "12":
						$months = 12;
						$price = $price * 0.85 * $months;
						break;
					case "0":
						$months = 1200; // 100 years
						$price = 8490; // Навсегда RUB
						break;
					default:
						$months = 1;
				}

				if($balance >= $price) {

					$token = bin2hex(random_bytes(32)); // generate token
					
					$licenseData = array(
						'user_id'			=> $userid,
						'license_ip'		=> $ip[0],
						'license_token'		=> $token,
						'license_port'		=> (int)$ip[1],
						'license_months'	=> (int)$months,
						'license_status'	=> 1
					);
				
					$licenseid = $this->licensesModel->createLicense($licenseData);
					$this->usersModel->downUserBalance($userid, $price);
				
					$this->data['status'] = "success";
					$this->data['success'] = "Лицензия успешно активирована!";
					$this->data['id'] = $licenseid;
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "На Вашем счету недостаточно средств!";
				}
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
		$months = @$this->request->post['months'];
		
		if(!$validateLib->ip($ip[0])) {
			$result = "Укажите реальный IP сервера!";
		}
		elseif(!$validateLib->port($ip[1])) {
			$result = "Укажите реальный порт сервера!";
		}
		elseif($this->licensesModel->getTotalLicenses(array('license_ip' => $ip[0], 'license_port' => (int)$ip[1]))) {
			$result = "Указанная лицензия уже существует!";
		}
		return $result;
	}
}
?>
