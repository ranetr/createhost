<?php
/*
* @LitePanel
* @Version: 1.0.1
* @Date: 29.12.2012
* @Developed by QuickDevel
*/
class payController extends Controller {
	public function index($licenseid = null) {
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
		
		$this->load->model('licenses');
		
		$error = $this->validate($licenseid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'licenses/index');
		}
		
		$license = $this->licensesModel->getLicenseById($licenseid);
		$this->data['license'] = $license;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('licenses/pay', $this->data);
	}
	
	public function ajax($licenseid = null) {
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

		$error = $this->validate($licenseid);
		if($error) {
	  		$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$months = $this->request->post['months'];
			
			$userid = $this->user->getId();
			$balance = $this->user->getBalance();
			
			// get data
			$license = $this->licensesModel->getLicenseById($licenseid);

			if(date("Y", strtotime($license['license_date_end'])) > 2100) {
		  		$this->data['status'] = "error";
				$this->data['error'] = "Данная лицензия оплачена навсегда!";
				return json_encode($this->data);
			}
			
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
				if($license['license_status'] == 0) {
					$this->licensesModel->updateLicense($licenseid, array('license_status' => 1));
					$this->licensesModel->extendLicense($licenseid, $months, true);
				} else {
					$this->licensesModel->extendLicense($licenseid, $months, false);
				}
				$this->usersModel->downUserBalance($userid, $price);
				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно оплатили лицензию!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = "На Вашем счету недостаточно средств!";
			}
		}

		return json_encode($this->data);
	}
	
	private function validate($licenseid) {
		$this->load->checkLicense();
		$result = null;
		
		$userid = $this->user->getId();
		
		if(!$this->licensesModel->getTotalLicenses(array('license_id' => (int)$licenseid, 'user_id' => (int)$userid))) {
			$result = "Запрашиваемая лицензия не существует!";
		}
		return $result;
	}
}
