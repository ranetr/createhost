<?php
class registerController extends Controller {
	public function index() {
		$this->load->checkLicense();
		$this->document->setActiveSection('account');
		$this->document->setActiveItem('register');
		
		if($this->user->isLogged()) {
			$this->session->data['error'] = "Вы уже авторизированы!";
			$this->response->redirect($this->config->url);
		}

		$this->data['email'] = $this->request->get['email'] ?? null;

		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('account/register', $this->data);
	}

	public function complete($password = true) {
		$this->load->checkLicense();
		$this->document->setActiveSection('account');
		$this->document->setActiveItem('register');
		
		if($this->user->isLogged()) {
			$this->session->data['error'] = "Вы уже авторизированы!";
			$this->response->redirect($this->config->url);
		}
        if($this->session->data['register']['password'] != $password) {
			$this->session->data['error'] = "Неверная ссылка активации!";
			$this->response->redirect($this->config->url . 'account/register');
        }
		
		$this->load->model('users');
		
		// Get Email
		$email = $this->session->data['register']['email'];

		// Delete Data Sessions
        unset($this->session->data['register']);
		
		// Insert User
		$userData = array(
			'user_email'		=> $email,
			'user_password'		=> password_hash($password, PASSWORD_DEFAULT),
			'user_status'		=> 1,
			'user_balance'		=> 0,
			'user_access_level'	=> 0
		);
		$this->usersModel->createUser($userData);
		$this->user->login($email, $password);

		// Send Mail
		$mailLib = new mailLibrary();
		
		$mailLib->setFrom($this->config->mail_from);
		$mailLib->setSender($this->config->mail_sender);
		$mailLib->setTo($email);
		$mailLib->setSubject('Данные для входа в аккаунт');
		
		$text = $this->load->view('mail/account/newpassword', array('email' => $email, 'password' => $password));
		
		$mailLib->setText($text);
		$mailLib->send();

		// Redirect
		$this->response->redirect($this->config->url . 'account/edit');
		
		return null;
	}
	
	public function ajax() {
		$this->load->checkLicense();
		if($this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы уже авторизированы!";
			return json_encode($this->data);
		}
		
		$this->load->library('mail');
		$this->load->model('users');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$email = @$this->request->post['email'];
				$password = bin2hex(random_bytes(4)); // generate
				
				// Sessions
				$this->session->data['register']['email'] = $email;
                $this->session->data['register']['password'] = $password;
				
				// Send Mail
				$mailLib = new mailLibrary();
				
				$mailLib->setFrom($this->config->mail_from);
				$mailLib->setSender($this->config->mail_sender);
				$mailLib->setTo($email);
				$mailLib->setSubject('Подтверждение регистрации аккаунта');
				
				$text = $this->load->view('mail/account/register', array('url' => $this->config->url, 'password' => $password));
				
				$mailLib->setText($text);
				$mailLib->send();
				
				$this->data['status'] = "success";
				$this->data['success'] = "Письмо со ссылкой активации было отправлено!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		if(isset($this->request->post['noajax'])) {
			$this->session->data[$this->data['status']] = $this->data[$this->data['status']];
			$this->response->redirect($this->config->url . 'account/register?email=' . $this->request->post['email']);
		}

		return json_encode($this->data);
	}
	
	private function validatePOST() {
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$email = @$this->request->post['email'];
		/*$lastname = @$this->request->post['lastname'];
		$firstname = @$this->request->post['firstname'];
		$password = @$this->request->post['password'];
		$password2 = @$this->request->post['password2'];
		$captcha = @$this->request->post['captcha'];
		
		$captchahash = @$this->session->data['captcha'];
		unset($this->session->data['captcha']);*/
		
		if(!$validateLib->email($email)) {
			$result = "Укажите свой реальный E-Mail!";
		}
		/*elseif(!$validateLib->firstname($firstname)) {
			$result = "Укажите свое реальное имя!";
		}
		elseif(!$validateLib->lastname($lastname)) {
			$result = "Укажите свою реальную фамилию!";
		}
		elseif(!$validateLib->password($password)) {
			$result = "Пароль должен содержать от 6 до 32 латинских букв, цифр и знаков <i>,.!?_-</i>!";
		}
		elseif($password != $password2) {
			$result = "Введенные вами пароли не совпадают!";
		}
		elseif($captcha != $captchahash) {
			$result = "Укажите правильный код с картинки!";
		}*/
		elseif($this->usersModel->getTotalUsers(array('user_email' => $email))) {
			$result = "Указанный E-Mail уже зарегистрирован!";
		}
		return $result;
	}
}
?>
