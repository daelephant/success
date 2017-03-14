<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
    }
	
	public function index() {
		$this->load->view('scm/invSa/initSale');
	}
	
	public function sales_search() {
		$this->load->view('sales/sales-search');	
	}
	
	public function salesOrder() {
		$this->load->view('scm/invSo/initSo');	
	}
	
	public function import() {
		 
	}
	
	 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */