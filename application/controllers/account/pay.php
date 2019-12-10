<?php
class payController extends Controller {
	public function index() {
		$this->load->checkLicense();
		$this->document->setActiveSection('account');
		$this->document->setActiveItem('pay');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('account/pay', $this->data);
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
		
		$this->load->model('invoices');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$ammount = @$this->request->post['ammount'];
				$method = @$this->request->post['method'];
				
				$userid = $this->user->getId();
				
				$invoiceData = array(
					'user_id'			=> $userid,
					'invoice_ammount'	=> $ammount,
					'invoice_method'	=> $method,
					'invoice_status'	=> 0
				);
				$invid = $this->invoicesModel->createInvoice($invoiceData);

				switch($method) {
					case 'card':
					case 'ym': {
						$url = $this->config->url . 'account/pay/yandex/' . $invid;
						break;
					}
					case 'qiwi': {
						// Получаем сумму
						$outsum = explode('.', number_format($ammount, 2));
						if($outsum[1] == null) $outsum[1] = "0";

						// Данные
						$dataFields = array(
							"extra['account']" => $this->config->qiwi_wallet,
							"amountInteger" => $outsum[0],
							"amountFraction" => $outsum[1],
							"extra['comment']" => $invid,
							"currency" => "643",
							"blocked[0]" => "account",
							"blocked[1]" => "comment",
							"blocked[2]" => "sum"
						);

						$url = 'https://qiwi.com/payment/form/99?' . http_build_query($dataFields);
						break;
					}
				}
				$this->data['status'] = "success";
				$this->data['url'] = $url;
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}

	public function yandex($invoiceid = null) {
		$this->load->checkLicense();
		$this->document->setActiveSection('account');
		$this->document->setActiveItem('pay');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		
		$this->load->model('invoices');

		$userid = $this->user->getId();
		
		if(!$this->invoicesModel->getTotalInvoices(array('invoice_id' => (int)$invoiceid, 'user_id' => (int)$userid))) {
			$this->session->data['error'] = "Запрашиваемый счет не существует!";
			$this->response->redirect($this->config->url . 'account/pay');
		}

		$invoice = $this->invoicesModel->getInvoiceById($invoiceid);

		$url = 'https://money.yandex.ru/quickpay/confirm.xml';
		$dataFields = array(
			'receiver' => $this->config->ym_wallet,
			'quickpay-form' => 'shop',
			'targets' => $this->config->title,
			'paymentType' => ($invoice['invoice_method'] == 'card') ? 'AC' : 'PC',
			'sum' => $invoice['invoice_ammount'],
			'label' => $invoice['invoice_id'], // BILL_ID
			'comment' => 'Пополнение баланса аккаунта (ID ' . $userid . ')',
			'successURL' => $this->config->url
		);

		$html = sprintf('<form name="formPost" id="formPost" action="%s" method="post">', $url);
		foreach($dataFields as $key => $value) {
		    $html .= sprintf('<input type="hidden" name="%s" value="%s">', $key, $value);
		}
		$html .= '</form>';
		$html .= "<script>
		    document.formPost.submit();
		</script>";

		die($html);

        return null;
	}

	private function validatePOST() {

		// Заглушка
		return "Доступ запрещен!";

		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$ammount = @$this->request->post['ammount'];
		$method = @$this->request->post['method'];

		if(!in_array($method, array('card','ym','qiwi'))) {
			$result = "Выберите способ пополнения!";
		}
		if(!$validateLib->money($ammount)) {
			$result = "Укажите сумму пополнения в допустимом формате!";
		}
		elseif(10 > $ammount || $ammount > 5000) {
			$result = "Укажите сумму от 10 до 5000 рублей!";
		}
		return $result;
	}
}
?>
