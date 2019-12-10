<?php
class controlController extends Controller {
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
		return $this->load->view('licenses/control', $this->data);
	}

	public function download($licenseid = null) {
		$this->load->checkLicense();
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 1) {
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

				$this->licensesModel->updateLicense($licenseid, array('license_ip' => $ip[0], 'license_port' => (int)$ip[1]));

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
		
		$userid = $this->user->getId();
		
		if(!$this->licensesModel->getTotalLicenses(array('license_id' => (int)$licenseid, 'user_id' => (int)$userid))) {
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
		
		if(!$validateLib->ip($ip[0])) {
			$result = "Укажите реальный IP сервера!";
		}
		elseif(!$validateLib->port($ip[1])) {
			$result = "Укажите реальный порт сервера!";
		}
		elseif($this->licensesModel->getTotalLicenses(array('license_ip' => $ip[0], 'license_port' => (int)$ip[1]))) {
			$result = "Указанный адрес уже кем-то используется!";
		}
		elseif($license['license_plugin_status'] == 1) {
			$result = "Для начала нужно выключить сервер!";
		}
		return $result;
	}
}
?>
