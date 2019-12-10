<?php
class yandexController extends Controller {
	public function index() {
		$this->load->checkLicense();
		$this->load->model('users');
		$this->load->model('invoices');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$ammount = $this->request->post['withdraw_amount'];
				$invid = $this->request->post['label'];
				
				$invoice = $this->invoicesModel->getInvoiceById($invid);

				if($invoice['invoice_ammount'] != $ammount) {
					return "Error: Invalid ammount!";
				}

				$userid = $invoice['user_id'];
				
				$this->usersModel->upUserBalance($userid, $ammount);
				$this->invoicesModel->updateInvoice($invid, array('invoice_status' => 1));
				return "OK$invid\n";
			} else {
				return "Error: $errorPOST";
			}
		} else {
			return "Error: Invalid request!";
		}
	}
	
	private function validatePOST() {
		$this->load->checkLicense();
		$result = null;
		
        $mysign = sha1($this->request->post['notification_type'] . '&' . $this->request->post['operation_id'] . '&'
                    . $this->request->post['amount'] . '&' . $this->request->post['currency'] . '&'
                    . $this->request->post['datetime'] . '&' . $this->request->post['sender'] . '&'
                    . $this->request->post['codepro'] . '&' . $this->config->yandex_secret . '&'
                    . $this->request->post['label']);
		
		if(!$this->invoicesModel->getTotalInvoices(array('invoice_id' => (int)$this->request->post['label'], 'invoice_status' => 0))) {
			$result = "Invalid invoice!";
		}
		elseif($this->request->post['sha1_hash'] != $mysign) {
			$result = "Invalid signature!";
		}
		elseif($this->request->post['unaccepted'] == 'true') {
			$result = "Translation not yet credited!";
		}
		return $result;
	}
}
?>
