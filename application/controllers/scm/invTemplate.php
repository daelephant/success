<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class InvTemplate extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->jxcsys = $this->session->userdata('jxcsys');
		$this->action = $this->input->get('action',TRUE);
    }
	
	public function index() {
		switch ($this->action) {
			case 'list':
			    $this->invTemplateList();break;
			case 'update':
			    $this->update();break;	
			case 'add':
			    $this->add();break;	
			case 'addNew':
			    $this->add();break;	
			case 'delete':
			    $this->delete();break;
			case 'queryDetails':
			    $this->queryDetails();break;	
			default: 
			    $this->InvTemplateList();	
		}
	}
 
	//列表
	public function invTemplateList(){
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$skey = str_enhtml($this->input->get_post('skey',TRUE));
		$type = str_enhtml($this->input->get_post('type',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$templateName = str_enhtml($this->input->get_post('templateName',TRUE));
		$parent   = str_enhtml($this->input->get_post('parent',TRUE));
		$children = str_enhtml($this->input->get_post('children',TRUE));
		$where = '(isDelete=0) '; 
		$where .= $templateName  ? ' and templateName like "%'.$templateName.'%"' : ''; 
		$list = $this->mysql_model->get_results('invtemplate',$where);  
		foreach ($list as $arr=>$row) {
		    $postData = unserialize($row['postData']);
		    foreach ($postData['entries'] as $arr1=>$row1) {
			    if ($arr1==0) {
					$good =  $row1['invNumber'].' '.$row1['invName'].' '.$row1['invSpec'];
					$mainUnit =   $row1['mainUnit'];
				} else {
				    $qtys[$row['id']][]            = abs($row1['qty']);
					$costs[$row['id']][]           = abs($row1['amount']);
					$unitCosts[$row['id']][]       = abs($row1['price']);
					$mainUnits[$row['id']][]       = $row1['mainUnit'];
					$goods[$row['id']][]           = $row1['invNumber'].' '.$row1['invName'].' '.$row1['invSpec'];
					 
				}
			}
		    $v[$arr]['id']                 = intval($row['id']);
			$v[$arr]['billDate']           = $row['billDate'];
			$v[$arr]['qty']                = 1;
			$v[$arr]['qtys']               = $qtys[$row['id']];
			$v[$arr]['good']               = $good;
			$v[$arr]['goods']              = $goods[$row['id']];
			$v[$arr]['cost']               = 0;
			$v[$arr]['costs']              = $costs[$row['id']];
			$v[$arr]['amount']             = 0;
			$v[$arr]['unitCost']           = 0;
			$v[$arr]['unitCosts']          = $unitCosts[$row['id']];
			$v[$arr]['mainUnit']           = $mainUnit;
			$v[$arr]['mainUnits']          = $mainUnits[$row['id']];
			$v[$arr]['description']        = $row['description'];
			$v[$arr]['billNo']             = $row['billNo'];
			$v[$arr]['userName']           = $row['userName']; 
			$v[$arr]['templateName']       = $row['templateName'];

		}
		$json['status']            = 200;
		$json['msg']               = 'success'; 
		$json['data']['page']      = $page;
		$json['data']['records']   = $this->data_model->get_invoice($where,3);   
		$json['data']['total']     = ceil($json['data']['records']/$rows);      
		$json['data']['rows']      = isset($v) ? $v : array();
		die(json_encode($json));
	}
 
	
	//新增
	public function add(){
	    $this->common_model->checkpurview(2);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
			$info = elements(array(
						'billNo','billDate','postData','description','totalQty',
						'amount','totalAmount','type','templateName','uid','userName'),$data,NULL);
			$sql = $this->mysql_model->insert('invtemplate',$info);   
			if ($sql) {
				$this->common_model->logs('新增组装单模板编号：'.$info['billNo']);
				str_alert(200,'success'); 
			} else {
				str_alert(-1,'SQL错误'); 
			}
		}
		str_alert(-1,'提交的是空数据'); 
    }
	

	//获取修改信息
	public function update() {
	    $this->common_model->checkpurview(1);
	    $id   = intval($this->input->get_post('id',TRUE));
		$data = $this->mysql_model->get_rows('invtemplate',array('isDelete'=>0,'id'=>$id));  
		if (count($data)>0) {
			$json['status']                     = 200;
			$json['msg']                        = 'success'; 
			$json['data']['id']                 = intval($data['id']);
			$json['data']['buId']               = 0;
			$json['data']['contactName']        = '(空)';
			$json['data']['date']               = $data['billDate'];
			$json['data']['billNo']             = $data['billNo'];
			$json['data']['billType']           = 153301;
			$json['data']['templateName']       = $data['templateName'];
			$json['data']['totalQty']           = (float)abs($data['totalQty']);
			$json['data']['amount']             = (float)abs($data['amount']);
			$json['data']['userName']           = $data['userName'];
			$json['data']['status']             = 'edit';   
			$json['data']['totalAmount']        = (float)abs($data['totalAmount']);
			$json['data']['description']        = $data['description']; 
		    $postData = unserialize($data['postData']);
			if (isset($postData['entries'])) {
				foreach ($postData['entries'] as $arr=>$row) {
				    $v[$arr]['id']                  = $arr+1;
					$v[$arr]['invSpec']             = $row['invSpec'];
					$v[$arr]['goods']               = $row['invNumber'].' '.$row['invName'].' '.$row['invSpec'];
					$v[$arr]['invName']             = $row['invName'];
					$v[$arr]['qty']                 = (float)abs($row['qty']);
					$v[$arr]['amount']              = 0;
					$v[$arr]['price']               = 0;
					$v[$arr]['mainUnit']            = $row['mainUnit'];
					$v[$arr]['invId']               = intval($row['invId']);
					$v[$arr]['invNumber']           = $row['invNumber'];
					$v[$arr]['locationId']          = intval($row['locationId']);
					$v[$arr]['locationName']        = $row['locationName'];
					$v[$arr]['unitId']              = intval($row['unitId']);
					$v[$arr]['description']         = isset($row['description']) ? $row['description'] : '';
					$v[$arr]['skuId']               = intval($row['skuId']);
					$v[$arr]['skuName']             = '';
				}
			}
			$json['data']['entries']                = isset($v) ? $v : array();
			die(json_encode($json));
		}
		str_alert(-1,'单据不存在、或者已删除');  
    }
	
 
	
	//删除
    public function delete() {
	    $this->common_model->checkpurview(4);
		$id   = intval($this->input->get_post('id',TRUE));
		$data = $this->mysql_model->get_rows('invtemplate',array('isDelete'=>0,'id'=>$id));  
		if (count($data)>0) {
			$sql = $this->mysql_model->update('invtemplate',array('isDelete'=>1),array('id'=>$id));   
			if ($sql) {
				$this->common_model->logs('删除组装单模板 编号：'.$data['billNo']);
				str_alert(200,'success');
			} else {
				str_alert(-1,'删除失败'); 	 
			}
		}
		str_alert(-1,'单据不存在');  
	}
	
	 
	//公共验证
	private function validform($data) {
	    $data['id']              = isset($data['id']) ? intval($data['id']) : 0;
		$data['billDate']        = $data['date'];
		$data['totalQty']        = (float)$data['totalQty'];
		$data['entries']         = isset($data['entries']) ? $data['entries'] : array();
		$data['uid']            = $this->jxcsys['uid'];
		$data['userName']       = $this->jxcsys['name']; 
		 
		$data['type']            = str_enhtml($this->input->get_post('type',TRUE));
		
		//基本验证
		count($data['entries']) < 1 && str_alert(-1,'提交的是空数据');
		strlen($data['billNo']) < 1 && str_alert(-1,'单据编号不为空！'); 
		strlen($data['templateName']) < 1 && str_alert(-1,'模板名称不能为空！'); 
		
 
		//修改的时候 
		if ($data['id']>0) {
		    $invoice = $this->mysql_model->get_rows('invtemplate',array('id'=>$data['id'],'isDelete'=>0));  
			count($invoice)<1 && str_alert(-1,'单据不存在、或者已删除');
			$data['billNo']  = $invoice['billNo'];	  
		}
		
		//商品录入验证 
		$storage = array_column($this->mysql_model->get_results('storage',array('disable'=>0)),'id');  
		foreach ($data['entries'] as $arr=>$row) {
			intval($row['invId'])<1 && str_alert(-1,'请选择商品');    
			(float)$row['qty'] < 0  && str_alert(-1,'商品数量要为数字，请输入有效数字！'); 
			intval($row['locationId']) < 1 && str_alert(-1,'请选择相应的仓库！'); 
			!in_array($row['locationId'],$storage) && str_alert(-1,$row['locationName'].'不存在或不可用！');
		} 
		$data['postData']   = serialize($data);
		return $data;
	}
	
	
	
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */