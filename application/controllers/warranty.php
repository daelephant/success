<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Warranty extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->action = $this->input->get('action',TRUE);
    }
	
	public function index() {
		switch ($this->action) {
			case 'getBatchNoList':
			    $this->getBatchNoList();break; 
			case 'getAdvancedList':
			    $this->getAdvancedList();break; 
			default:  
			    $this->getBatchNoList();	
		}
	}
	
	public function getBatchNoList() {
	    
	}
	
	public function getAdvancedList() {
	    
	}
	 
	 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */