<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Money extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
    }
	
	public function index() {
		 
	}
	
	public function money_search() {
		$this->load->view('money/money-search');
	}
	
	public function receipt() {
		$this->load->view('scm/receipt/initReceipt');
	} 
	
	public function payment() {
		$this->load->view('scm/payment/initPay');
	} 
	
	
	public function other_income() {
		$this->load->view('scm/ori/initInc');
	} 
	
	public function other_expense() {
		$this->load->view('scm/ori/initExp');
	}
	 
	public function accountTransfer() {
		$this->load->view('scm/fundTf/initFundTf');
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */