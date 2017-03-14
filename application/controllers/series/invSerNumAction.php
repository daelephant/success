<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class InvSerNumAction extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->action = $this->input->get('action',TRUE);
    }
	
	
	public function index() {
		switch ($this->action) {
			case 'list':
			case 'findSkuForSerNums':
			    $this->findSkuForSerNums();break;
			default:  
			    $this->findSkuForSerNums();	
		}
	}
    
	public function findSkuForSerNums() {
	     
	}
	
	
	 
	 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */