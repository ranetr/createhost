<?php
class controlController extends Controller {
	public function index($licenseid = null) {
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

		$this->load->model('licenses');
		
		$error = $this->validate($licenseid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/licenses/index');
		}
		
		$license = $this->licensesModel->getLicenseById($licenseid, array('users'));
		$this->data['license'] = $license;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('admin/licenses/control', $this->data);
	}
	
	public function download($licenseid = null) {
		$this->load->checkLicense();
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}

		$this->load->model('licenses');
		
		$error = $this->validate($licenseid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/licenses/index');
		}

		$license = $this->licensesModel->getLicenseById($licenseid);

		$file = '/var/www/sampvoice.ru/application/files/sampvoice.dll';

		if (file_exists($file)) {
		    if (ob_get_level()) {
		      ob_end_clean();
		    }
		    // Открываем файл и заменяем токен
		    $data = file_get_contents($file);
		    $data = str_replace("XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX", hex2bin($license['license_token']), $data);
		    // Отдаем файл
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="'.basename($file).'"');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . strlen($data));
		    echo $data;
		    exit;
		}

		return null;
	}

	public function action($licenseid = null, $action = null) {
		$this->load->checkLicense();
		if(!$this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 2) {
			$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('licenses');
		
		$error = $this->validate($licenseid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		$license = $this->licensesModel->getLicenseById($licenseid);
		
		switch($action) {
			case 'status': {
				if($license['license_status'] == 1) {
					$this->licensesModel->updateLicense($licenseid, array('license_status' => 0));
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно заблокировали лицензию!";
				} elseif($license['license_status'] == 0) {
					$this->licensesModel->updateLicense($licenseid, array('license_status' => 1));
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно разблокировали лицензию!";
				}
				break;
			}
			default: {
				$this->data['status'] = "error";
				$this->data['error'] = "Вы выбрали несуществующее действие!";
				break;
			}
		}
		
		return json_encode($this->data);
	}
	
	public function ajax($licenseid = null) {
		$this->load->checkLicense();
		if(!$this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 2) {
			$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('licenses');
		
		$error = $this->validate($licenseid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST($licenseid);
			if(!$errorPOST) {
				$ip = explode(":", @$this->request->post['ip']);
				if(is_null($ip[1])) $ip[1] = '7777'; // default port
				$userid = @$this->request->post['userid'];
				$token = @$this->request->post['token'];
				$mark = @$this->request->post['mark'];
				$dateend = @$this->request->post['dateend'];
				
				$licenseData = array(
					'user_id'			=> (int)$userid,
					'license_ip'		=> $ip[0],
					'license_token'		=> $token,
					'license_mark'		=> $mark,
					'license_port'		=> (int)$ip[1],
					'license_date_end'	=> $dateend . " 12:00:00"
				);
				$this->licensesModel->updateLicense($licenseid, $licenseData);

				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно отредактировали лицензию!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	private function validate($licenseid) {
		$this->load->checkLicense();
		$result = null;
		
		if(!$this->licensesModel->getTotalLicenses(array('license_id' => (int)$licenseid))) {
			$result = "Запрашиваемая лицензия не существует!";
		}
		return $result;
	}
	
	private function validatePOST($licenseid) {
		$this->load->checkLicense();
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;

		$license = $this->licensesModel->getLicenseById($licenseid);
		
		$ip = explode(":", @$this->request->post['ip']);
		if(is_null($ip[1])) $ip[1] = '7777'; // default port
		$userid = @$this->request->post['userid'];
		$token = @$this->request->post['token'];
		$mark = @$this->request->post['mark'];
		$dateend = @$this->request->post['dateend'];
		
		if(mb_strlen($ip[0]) < 2 || mb_strlen($ip[0]) > 15) {
			$result = "IP должен содержать от 2 до 15 символов!";
		}
		elseif($ip[0] != $license['license_ip'] && $this->licensesModel->getTotalLicenses(array('license_ip' => $ip[0], 'license_port' => (int)$ip[1]))) {
			$result = "Указанный IP уже зарегистрирован!";
		}
		elseif(!$validateLib->hash($token)) {
			$result = "Указанный токен некорректный!";
		}
		elseif($mark != null && (mb_strlen($mark) < 2 || mb_strlen($mark) > 16)) {
			$result = "Пометка должна содержать от 2 до 16 символов!";
		}
		elseif(!$validateLib->date($dateend)) {
			$result = "Формат даты окончания должен быть YYYY-MM-DD!";
		}
		return $result;
	}
}
?>
