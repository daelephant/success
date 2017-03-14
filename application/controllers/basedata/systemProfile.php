<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Systemprofile extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
    }
	
	public function index() {
	    $action = $this->input->get('action',TRUE);
		switch ($action) {
			case 'generateDocNo':
			    $this->generateDocNo();break;
			case 'update':
			    $this->update();break;
			case 'changeSysSkin':
			    $this->changeSysSkin();break;					
			default: 
			    str_alert(-1,'参数错误'); 	
		}
	}
	
	//单据编号
	private function generateDocNo() {
        $billType = str_enhtml($this->input->post('billType',TRUE));
		$info = array(
			'PUR'=>'CG',
			'SALE'=>'XS',
			'TRANSFER'=>'DB',
			'OO'=>'QTCK',
			'PO'=>'CGDD',
			'SO'=>'XSDD',
			'OI'=>'QTRK',
			'CADJ'=>'CBTZ',
			'PAYMENT'=>'FKD',
			'RECEIPT'=>'SKD',
			'QTSR'=>'QTSR',
			'QTZC'=>'QTZC'
		);
		if (isset($info[$billType])) {
		    str_alert(200,'success',array('billNo'=>str_no($info[$billType]))); 
		}
		str_alert(-1,'生成失败'); 
	}	
	
	
	//系统设置
	private function update() {
	    $this->common_model->checkpurview(81);
		$data = str_enhtml($this->input->post(NULL,TRUE));
		if (is_array($data) && count($data)>0) { 
			if ($this->common_model->insert_option('system',$data)) {
			    $this->common_model->logs('系统设置成功');
				str_alert(200,'success');
			}
		}
		str_alert(-1,'设置失败'); 
	}	
	 
	//切换皮肤 
	private function changeSysSkin() {
		$skin = $this->input->post('skin',TRUE) ? $this->input->post('skin',TRUE) : 'green';
		$this->input->set_cookie('skin',$skin,360000); 
		$this->common_model->logs('切换皮肤：'.$skin);
		str_alert(200,'success');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */