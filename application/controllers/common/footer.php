<?php
class footerController extends Controller {
	public function index() {
		$this->load->checkLicense();
		return $this->load->view('common/footer', $this->data);
	}
}
?>
