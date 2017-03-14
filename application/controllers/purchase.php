<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
    }
	
	public function index() {
	    $id = intval($this->input->get_post('id',TRUE));
		$data['billNo'] = $this->mysql_model->get_row('invoice',array('id'=>$id,'billType'=>'PUR'),'srcOrderNo');  
	    $this->load->view('scm/invPu/initPur',$data);	
	}
	
	public function purchase_search() {
		$this->load->view('purchase/purchase-search');	
	}
	
	public function import() {
	    
	}
	
	public function purchaseOrder() {
	    $id = intval($this->input->get_post('id',TRUE));
		$data['billNo'] = $this->mysql_model->get_row('order',array('id'=>$id,'billType'=>'PUR'),'billNo');  
	    $this->load->view('scm/invPo/initPo',$data);	    
	}
	
	 
	 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */