<?php
class editController extends Controller {
	public function index() {
		$this->load->checkLicense();
		$this->document->setActiveSection('account');
		$this->document->setActiveItem('edit');
		$this->document->addScript('/public/js/jquery.maskedinput.min.js');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->data['user_access_level'] = $this->user->getAccessLevel();
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('account/edit', $this->data);
	}
	
	public function ajax() {
		$this->load->checkLicense();
		if(!$this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 0) {
	  		$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('users');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$firstname = @$this->request->post['firstname'];
				$lastname = @$this->request->post['lastname'];
				$middlename = @$this->request->post['middlename'];
				$phone = @$this->request->post['phone'];

				if(!isset($this->session->data['sendcode'])) {
					$code = 7777; // generate

					// Send SMS

					// Sessions
					$this->session->data['sendphone'] = $phone;
					$this->session->data['sendcode'] = 7777;

					// Response
			  		$this->data['status'] = "sendcode";
					return json_encode($this->data);
				}
				
				$userid = $this->user->getId();
				
				$userData = array(
					'user_firstname'	=> $firstname,
					'user_lastname'		=> $lastname,

				);
				
				if($editpassword) {
					$userData['user_password'] = password_hash($password, PASSWORD_DEFAULT);
				}
				
				$this->usersModel->updateUser($userid, $userData);
				
				$this->data['status'] = "success";
				$this->data['success'] = "Изменения сохранены!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	private function validatePOST() {
	
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$firstname = @$this->request->post['firstname'];
		$lastname = @$this->request->post['lastname'];
		$middlename = @$this->request->post['middlename'];
		$phone = @$this->request->post['phone'];
		$code = @$this->request->post['code'];

		$sendcode = @$this->session->data['sendcode'];
		$sendphone = @$this->session->data['sendphone'];

		$editpassword = @$this->request->post['editpassword'];
		$password = @$this->request->post['password'];
		$password2 = @$this->request->post['password2'];
		
		if(!$validateLib->firstname($firstname)) {
			$result = "Укажите свое реальное имя!";
		}
		elseif(!$validateLib->lastname($lastname)) {
			$result = "Укажите свою реальную фамилию!";
		}
		elseif(!$validateLib->middlename($middlename)) {
			$result = "Укажите свое реальное отчество!";
		}
		elseif(!$validateLib->phone($phone)) {
			$result = "Номер телефона указан неправильно!";
		}
		elseif($this->usersModel->getTotalUsers(array('user_phone' => $phone))) {
			$result = "Указанный телефон уже зарегистрирован!";
		}
		elseif(isset($this->session->data['sendcode'])) {
			if($phone != $this->session->data['sendphone']) {
				$result = "Указанный ранее телефон был изменен!";
			}
			elseif($code != $this->session->data['sendcode']) {
				$result = "Неверный код подтверждения!";
			}
		}
		/*elseif($captcha != $captchahash) {
			$result = "Укажите правильный код с картинки!";
		}*/
		return $result;
	}
}
?>
