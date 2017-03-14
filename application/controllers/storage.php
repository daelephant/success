<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Storage extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
    }
	
    //��������б�߼�����
	public function other_search() {
		$this->load->view('storage/other-search');	
	}
	
	//�̵�
	public function inventory() {
	    $this->common_model->checkpurview(11);
	    $this->load->view('storage/inventory');	
	}
	
	//��������б�߼�����
	public function transfers_search () {
	    $this->load->view('storage/transfers-search');	
	}
	
	//�̵㵼��
	public function import() {
	    $this->load->view('storage/import');	
	}
	
	//ѡ��ģ��
	public function select_temp() {
	    $this->load->view('storage/select-temp');	
	}
	
	public function transfers() {
	    $this->load->view('scm/invTf/initTf');	
	}
	
	public function other_warehouse() {
	    $this->load->view('scm/invOi/initOi-in');	
	}
	
	public function other_outbound() {
	    $this->load->view('scm/invOi/initOi-out');	
	}
	
	public function adjustment() {
	    $this->load->view('scm/invOi/initOi-cbtz');	
	}
	
	public function assemble() {
	    $this->load->view('scm/invOi/initOi-zz');	
	}
	
	 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */