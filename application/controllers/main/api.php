<?php
class apiController extends Controller {
	public function index() {
		$this->load->checkLicense();
		$this->load->library('mail');
		$this->load->model('licenses');
		
		/*$token = @$this->request->get['token'];
		if($this->config->crontoken != $token) {
			return "Access Denied";
		}*/
		
		$mailLib = new mailLibrary();
		
		$mailLib->setFrom($this->config->mail_from);
		$mailLib->setSender($this->config->mail_sender);

		$licenses = $this->licensesModel->getLicenses(array(), array('users'));
		
		$datenow = date_create('now');
		
		foreach($licenses as $item) {
			$licenseid = $item['license_id'];
			$dateend = date_create($item['license_date_end']);
			$diff = date_diff($datenow, $dateend);

			echo '<pre>';
			print_r($diff);
			echo '</pre>';
			continue;
			
			if($diff->invert) {
				if($diff->days >= 365) {
					// Удаление
					$this->serversModel->execServerAction($serverid, 'delete');
					$this->serversModel->deleteServer($serverid);
					$this->serversStatsModel->deleteServerStats($serverid);
					
					echo "gs$item[server_id] - удален.\n";
					
					// Отправка уведомления
					$mailLib->setTo($item['user_email']);
					$mailLib->setSubject("Удаление сервера #$serverid");
					
					$mailData = array();
					$mailData['firstname'] = $item['user_firstname'];
					$mailData['lastname'] = $item['user_lastname'];
					$mailData['serverid'] = $serverid;
					
					$text = $this->load->view('mail/servers/deleted', $mailData);
					
					$mailLib->setText($text);
					$mailLib->send();
				} else {
					// Блокировка
					$this->serversModel->execServerAction($serverid, 'stop');
					$this->serversModel->updateServer($serverid, array('server_status' => 0));
					echo "gs$item[server_id] - заблокирован.\n";
					
					// Отправка уведомления
					$mailLib->setTo($item['user_email']);
					$mailLib->setSubject("Блокировка сервера #$serverid");
			
					$mailData = array();
					$mailData['firstname'] = $item['user_firstname'];
					$mailData['lastname'] = $item['user_lastname'];
					$mailData['serverid'] = $serverid;
			
					$text = $this->load->view('mail/servers/lock', $mailData);
			
					$mailLib->setText($text);
					$mailLib->send();
				}
			} else {
				if($diff->days < 3) {
					echo "gs$item[server_id] - отправлено уведомление.\n";
					
					// Отправка уведомления
					$mailLib->setTo($item['user_email']);
					$mailLib->setSubject("Завершение оплаченного периода сервера #$serverid");
					
					$mailData = array();
					$mailData['firstname'] = $item['user_firstname'];
					$mailData['lastname'] = $item['user_lastname'];
					$mailData['serverid'] = $serverid;
					$mailData['days'] = $diff->days;
			
					$text = $this->load->view('mail/servers/needPay', $mailData);
			
					$mailLib->setText($text);
					$mailLib->send();
				}
			}
		}
		return null;
	}

	public function update() {
		$this->load->checkLicense();
		$this->load->model('licenses');

		$licenses = $this->licensesModel->getLicenses(array('license_plugin_status' => 1));
		
		$datenow = date_create('now');
		
		foreach($licenses as $item) {
			$licenseid = $item['license_id'];
			$dateend = date_create($item['license_stats_end']);
			$diff = date_diff($datenow, $dateend);

			if($diff->i > 1) {
				$this->licensesModel->updateLicense($licenseid, array(
					'license_stats_players'		=> 0,
					'license_stats_svplayers'	=> 0,
					'license_plugin_status' 	=> 0,
					'license_stats_end'			=> 'NOW()'
				));
			}
		}
		return null;
	}
	
	public function monitor() {
		$this->load->checkLicense();
		$this->load->model('licenses');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$token = @$this->request->post['token'];
				$version = @$this->request->post['version'];
				$maxplayers = @$this->request->post['slots'];
				$players = @$this->request->post['players'];
				$svplayers = @$this->request->post['svplayers'];
				$port = @$this->request->post['port'];
				$ip = $this->getIP();

				// get data
				$data = $this->licensesModel->getLicenseByIpToken($ip, $token);

				$licenseData = array();

				if(isset($this->request->post['close'])) {
					$licenseData = array('license_stats_players' => 0, 'license_stats_svplayers' => 0, 'license_plugin_status' => 0, 'license_stats_end' => 'NOW()');
				} else {
					// first start
					if(isset($version) && isset($maxplayers)) {
						$licenseData['license_stats_maxplayers'] = $maxplayers;
						$licenseData['license_plugin_version'] = $version;
						$licenseData['license_stats_start'] = "NOW()";
					}

					// update data
					$licenseData['license_stats_players'] = $players;
					$licenseData['license_stats_svplayers'] = $svplayers;
					$licenseData['license_plugin_status'] = 1;
					$licenseData['license_stats_end'] = "NOW()";
				}

				$this->licensesModel->updateLicense($data['license_id'], $licenseData);

				$this->data['status'] = "success";
				$this->data['success'] = "OK";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}

	public function check() {
		$this->load->checkLicense();
		$this->load->library('validate');
		$this->load->model('licenses');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$token = @$this->request->post['token'];
			$port = @$this->request->post['port'];
			$ip = $this->getIP();

			$validateLib = new validateLibrary();

			$error = null;
			
			// validate
			if(!$validateLib->hash($token)) {
				$error = "Invalid token format";
			}
			elseif(!$validateLib->port($port)) {
				$error = "Invalid port format";
			}
			elseif(!$validateLib->ip($ip)) {
				$error = "Invalid IP format";
			}
			elseif(!$this->licensesModel->getTotalLicenses(array('license_ip' => $ip, 'license_port' => $port, 'license_token' => $token, 'license_status' => 1))) {
				$error = "Not found active license";
			}

			if($error) { // logging
				$this->request->post['ip'] = $ip;
				$this->request->post['error'] = $error;
				file_put_contents(APPLICATION_DIR . '/log_check.txt', date("Y-m-d H:i:s") . print_r($this->request->post, 1), FILE_APPEND);
				//error_log($error, 3, APPLICATION_DIR . "/check-errors.log");
			}
			else return $this->config->token;
		}

		die(http_response_code(403));

		return null;
	}

	private function validatePOST() {
		$this->load->checkLicense();
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$token = @$this->request->post['token'];
		$version = @$this->request->post['version'];
		$maxplayers = @$this->request->post['slots'];
		$players = @$this->request->post['players'];
		$svplayers = @$this->request->post['svplayers'];
		$port = @$this->request->post['port'];
		$ip = $this->getIP();
		
		if(!$validateLib->hash($token)) {
			$error = "Invalid token format";
		}
		elseif(isset($version) && !$validateLib->money($version)) {
			$result = "Invalid version format";
		}
		elseif(isset($maxplayers) && (0 > $maxplayers || $maxplayers > 1000)) {
			$result = "Invalid slots format";
		}
		elseif(0 > $players || $players > 1000) {
			$result = "Invalid players format";
		}
		elseif(0 > $svplayers || $svplayers > 1000) {
			$result = "Invalid svplayers format";
		}
		elseif(!$validateLib->ip($ip)) {
			$result = "Invalid IP format";
		}
		elseif(!$this->licensesModel->getTotalLicenses(array('license_ip' => $ip, 'license_token' => $token))) {
			$result = "Not found license";
		}
		return $result;
	}

	private function getIP() {
	    if (isset($this->request->server['HTTP_CF_CONNECTING_IP'])) return $this->request->server['HTTP_CF_CONNECTING_IP'];
	    if (isset($this->request->server['HTTP_X_FORWARDED_FOR'])) return $this->request->server['HTTP_X_FORWARDED_FOR'];
	    if (isset($this->request->server['HTTP_X_REAL_IP'])) return $this->request->server['HTTP_X_REAL_IP'];
	    return $this->request->server['REMOTE_ADDR'];
	}
}
?>
