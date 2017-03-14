<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview(62);
		$this->action = $this->input->get('action',TRUE);
    }
	
	public function index() {
		switch ($this->action) {
			case 'exporter':
			    $this->exporter();break;  
			default:  
			    str_alert(-1,'非法请求');
		}
	}
 
	public function exporter(){
		$name = 'customer_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出客户:'.$name);
		$skey   = str_replace('输入客户编号/ 名称/ 联系人/ 电话查询','',str_enhtml($this->input->get_post('skey',TRUE)));
		$categoryId   = intval($this->input->get_post('categoryId',TRUE));  
		$where = '(isDelete=0) and type=-10 ';      
		$where .= $this->common_model->get_customer_purview();
		$where .= $categoryId>0 ? ' and cCategory = '.$categoryId.'' : ''; 
		$where .= $skey ? ' and (number like "%'.$skey.'%" or name like "%'.$skey.'%" or linkMans like "%'.$skey.'%")' : '';        
		$data['list'] = $this->mysql_model->get_results('contact',$where,'id desc');   
		$this->load->view('settings/customer-export',$data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */