<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->jxcsys = $this->session->userdata('jxcsys');
		$this->action = $this->input->get('action',TRUE);
    }
	
	public function index() {
		switch ($this->action) {
			case 'getRecentlyContact':
			    $this->getRecentlyContact();break; 
			case 'list':
			    $this->contactList();break;	
			case 'query':
			    $this->query();break;
			case 'checkName':
			    $this->checkName();break;
			case 'getNextNo':
			    $this->getNextNo();break;
			case 'add':
			    $this->add();break;
			case 'update':
			    $this->update();break;
			case 'delete':
			    $this->delete();break;
			case 'disable':
			    $this->disable();break;	 
			default:  
			    str_alert(-1,'非法请求');
		}
	}
	
    //客户、供应商列表
	private function contactList() {
		$type   = intval($this->input->get('type',TRUE))==10 ? 10 : -10;
		$skey   = str_replace('输入客户编号/ 名称/ 联系人/ 电话查询','',str_enhtml($this->input->get_post('skey',TRUE)));
		$page   = max(intval($this->input->get_post('page',TRUE)),1);
		$categoryid   = intval($this->input->get_post('categoryId',TRUE));
		$rows   = max(intval($this->input->get_post('rows',TRUE)),100);
		$isDelete     = intval($this->input->get_post('isDelete',TRUE));
		$where  = '(a.isDelete=0) and a.type='.$type;
		$where .= $isDelete==0 ? ' and disable=0' : '';    
		$where .= $this->common_model->get_contact_purview();
	    $where .= $skey ? ' and (a.number like "%'.$skey.'%" or a.name like "%'.$skey.'%" or a.linkMans like "%'.$skey.'%")' : '';
		$where .= $categoryid>0 ? ' and a.cCategory = '.$categoryid.'' : '';                
		$list = $this->data_model->get_contact($where.' order by a.id desc limit '.$rows*($page-1).','.$rows);  
		foreach ($list as $arr=>$row) {
		    $v[$arr]['id']           = intval($row['id']);
			$v[$arr]['number']       = $row['number'];
			$v[$arr]['cCategory']    = intval($row['cCategory']);
			$v[$arr]['customerType'] = $row['cCategoryName'];
			$v[$arr]['pinYin']       = $row['pinYin'];
			$v[$arr]['name']         = $row['name'];
			$v[$arr]['type']         = $row['type'];
			$v[$arr]['delete']       = intval($row['disable'])==1 ? true : false;  
			$v[$arr]['cLevel']       = intval($row['cLevel']);
			$v[$arr]['amount']       = (float)$row['amount'];
			$v[$arr]['periodMoney']  = (float)$row['periodMoney'];
			$v[$arr]['difMoney']     = (float)$row['arrears'];
			$v[$arr]['remark']       = $row['remark'];
			$v[$arr]['taxRate']      = (float)$row['taxRate'];
			$v[$arr]['links']        = '';
			if (strlen($row['linkMans'])>0) {                            
				$list = (array)json_decode($row['linkMans'],true);
				foreach ($list as $arr1=>$row1) {
					if ($row1['linkFirst']==1) {
						$v[$arr]['contacter']            = isset($row1['linkName']) ? $row1['linkName'] :'';
						$v[$arr]['mobile']               = isset($row1['linkMobile']) ? $row1['linkMobile'] :'';
						$v[$arr]['telephone']            = isset($row1['linkPhone']) ? $row1['linkPhone'] :'';
						$v[$arr]['linkIm']               = isset($row1['linkIm']) ? $row1['linkIm'] :'';
						$v[$arr]['city']                 = isset($row1['city']) ? $row1['city'] :''; 
						$v[$arr]['county']               = isset($row1['county']) ? $row1['county'] :''; 
			            $v[$arr]['province']             = isset($row1['province']) ? $row1['province'] :''; 
						$v[$arr]['deliveryAddress']      = isset($row1['address']) ? $row1['address'] :''; 
						$v[$arr]['firstLink']['first']   = isset($row1['linkFirst']) ? $row1['linkFirst'] :''; 
					}
				} 
		    }
		}
		$json['status']            = 200;
		$json['msg']               = 'success'; 
		$json['data']['page']      = $page;                                                      
		$json['data']['records']   = $this->data_model->get_contact($where,3);   
		$json['data']['total']     = ceil($json['data']['records']/$rows);   
		$json['data']['rows']      = isset($v) ? array_values($v) : array();
		die(json_encode($json));
	}
	
	//校验客户编号 
	private function getNextNo(){
	     $type = intval($this->input->get('type',TRUE));
		 $skey = intval($this->input->post('skey',TRUE));
		 str_alert(200,'success',array('number'=>$skey)); 
	}
	
	
	//检测客户名称
	private function checkName(){
	    $id   = intval($this->input->post('id',TRUE));
		$name = str_enhtml($this->input->post('name',TRUE));
		$where['name']      = $name;
		$where['isDelete']  = 0;
		$where['id !=']     = $id>0 ? $id :'';
	    $data = $this->mysql_model->get_rows('contact',array_filter($where)); 
		if (count($data)>0) {
		    str_alert(-1,'客户名称重复'); 
		}
		str_alert(200,'success'); 
	}
	
	private function getRecentlyContact(){
		$billType  = str_enhtml($this->input->post('billType',TRUE));
		$transType = intval($this->input->post('transType',TRUE));
		$where = '(isDelete=0) and disable=0'; 
		if (in_array($billType,array('PUR','PO'))) {
		    $where .= ' and type=10';
		}
		if (in_array($billType,array('SALE','SO'))) {
		    $where .= ' and type=-10';
		}
		$where .= $this->common_model->get_contact_purview();
	    $data = $this->mysql_model->get_rows('contact',$where); 
		if (count($data)>0) {
			die('{"status":200,"msg":"success","data":{"contactName":"'.$data['name'].'","buId":'.$data['id'].',"cLevel":0}}');
		}
		str_alert(-1,''); 
	}
 
 
	//获取信息
	private function query() {    
	    $id   = intval($this->input->get_post('id',TRUE));
		$type = intval($this->input->get_post('type',TRUE));
		$data = $this->mysql_model->get_rows('contact',array('isDelete'=>0,'id'=>$id));
		if (count($data)>0) {
			$info['id']           = $id;
			$info['cCategory']    = intval($data['cCategory']);
			$info['cLevel']       = intval($data['cLevel']);
			$info['number']       = $data['number'];
			$info['name']         = $data['name'];
			$info['beginDate']    = $data['beginDate'];
			$info['amount']       = (float)$data['amount'];
			$info['periodMoney']  = (float)$data['periodMoney'];
			$info['remark']       = $data['remark'];
			if ($type==10) {
			    $info['taxRate']  = (float)$data['taxRate'];
			}
			$info['pinYin']       = $data['pinYin'];
			if (strlen($data['linkMans'])>0) {                            
				$list = (array)json_decode($data['linkMans'],true);
				foreach ($list as $arr=>$row) {
					$v[$arr]['address']         = isset($row['address']) ? $row['address'] :'';
					$v[$arr]['city']            = isset($row['address']) ? $row['city'] :'';
					$v[$arr]['contactId']       = time();
					$v[$arr]['county']          = isset($row['county']) ? $row['county'] :'';
					$v[$arr]['email']           = isset($row['email']) ? $row['email'] : '';
					$v[$arr]['first']           = $row['linkFirst']==1 ? true : false; 
					$v[$arr]['id']              = $arr+1;
					$v[$arr]['im']              = isset($row['linkIm']) ? $row['linkIm'] :'';
					$v[$arr]['mobile']          = isset($row['linkMobile']) ? $row['linkMobile'] :''; 
					$v[$arr]['name']            = isset($row['linkName']) ? $row['linkName'] :'';
					$v[$arr]['phone']           = isset($row['linkPhone']) ? $row['linkPhone'] :'';
					$v[$arr]['province']        = isset($row['province']) ? $row['province'] :'';
					$v[$arr]['tempId']          = 0;
				} 
		    }
			$info['links']  = isset($v) ? $v : array();
			$json['status'] = 200;
			$json['msg']    = 'success'; 
			$json['data']   = $info;                                                      
			die(json_encode($json));
		}  
		str_alert(-1,'没有数据');
	}
	
	//新增
	private function add(){
		$data = $this->validform($this->input->post(NULL,TRUE));
		switch ($data['type']) {
			case 10:
				//$this->common_model->checkpurview(59);
				$success = '新增客户:';	
				break;  
			case -10:
				//$this->common_model->checkpurview(64);
				$success = '新增供应商:';	
				break;  			 
			default: 
				str_alert(-1,'参数错误');
		}	
		$this->mysql_model->get_count('contact',array('isDelete'=>0,'type'=>$data['type'],'number'=>$data['number'])) > 0 && str_alert(-1,'编号重复');
		$data = elements(array(
					'name','number','amount','beginDate','cCategory','cCategoryName','cLevel','cLevelName','linkMans'
					,'periodMoney','remark','type'),$data,NULL);
		$sql = $this->mysql_model->insert('contact',$data);
		if ($sql) {
			$data['id'] = $sql;
			$data['cCategory'] = intval($data['cCategory']);
			$data['linkMans']  = (array)json_decode($data['linkMans'],true);
			$this->common_model->logs($success.$data['name']);
			str_alert(200,'success',$data);
		}
		str_alert(-1,'添加失败');
	}
	
	
	//修改
	private function update(){
		$data = $this->validform($this->input->post(NULL,TRUE));
		switch ($data['type']) {
			case 10:
				$this->common_model->checkpurview(60);
				$success = '修改客户:';	
				break;  
			case -10:
				$this->common_model->checkpurview(65);
				$success = '修改供应商:';	
				break;  			 
			default: 
				str_alert(-1,'参数错误');
		}	
		$this->mysql_model->get_count('contact',array('id !='=>$data['id'],'isDelete'=>0,'type'=>$data['type'],'number'=>$data['number'])) > 0 && str_alert(-1,'编号重复');
		$info = elements(array(
					'name','number','amount','beginDate','cCategory','cCategoryName','cLevel','cLevelName','linkMans'
					,'periodMoney','remark','type'),$data,NULL);
		$sql = $this->mysql_model->update('contact',$info,array('id'=>$data['id']));
		if ($sql) {
			$data['cCategory']    = intval($data['cCategory']);
			$data['customerType'] = $data['cCategoryName'];
			$data['linkMans']     = (array)json_decode($data['linkMans'],true);
			$this->common_model->logs($success.$data['name']);
			str_alert(200,'success',$data);
		}
		str_alert(-1,'更新失败');
	}
	
	//删除
	private function delete(){
	    $id   = str_enhtml($this->input->post('id',TRUE));
		$type = intval($this->input->get_post('type',TRUE))==10 ? 10 : -10;
		switch ($type) {
			case 10:
				$this->common_model->checkpurview(61);
				$success = '删除客户:';	
				break;  
			case -10:
				$this->common_model->checkpurview(66);
				$success = '删除供应商:';	
				break;  			 
			default: 
				str_alert(-1,'参数错误');
		}	
		$data = $this->mysql_model->get_results('contact','(id in('.$id.'))'); 
		if (count($data) > 0) {
		    $info['isDelete'] = 1;
		    $this->mysql_model->get_count('invoice','(isDelete=0) and (buId in('.$id.'))')>0 && str_alert(-1,'不能删除有业务往来的客户或供应商！');
		    $sql = $this->mysql_model->update('contact',$info,'(id in('.$id.'))');   
		    if ($sql) {
			    $name = array_column($data,'name'); 
				$this->common_model->logs($success.'ID='.$id.' 名称:'.join(',',$name));
				die('{"status":200,"msg":"success","data":{"msg":"","id":['.$id.']}}');
			}
		}
		str_alert(-1,'客户或供应商不存在');
	}
	
	
	//状态
	private function disable(){
		$disable = intval($this->input->post('disable',TRUE));
		$id = str_enhtml($this->input->post('contactIds',TRUE));
		$data = $this->mysql_model->get_results('contact','(id in('.$id.'))'); 
		if ($disable==1) {
		    if ($data[0]['type']==10) {
			    $this->common_model->checkpurview(95);
			} else {
			    $this->common_model->checkpurview(91);
			}
		    
		} else {
		    if ($data[0]['type']==10) {
			    $this->common_model->checkpurview(94);
			} else {
			    $this->common_model->checkpurview(92);
			}
		}
		
		if (strlen($id) > 0) { 
			$sql = $this->mysql_model->update('contact',array('disable'=>$disable),'(id in('.$id.'))');
		    if ($sql) {
				$this->common_model->logs('客户'.$disable==1?'禁用':'启用'.':ID:'.$id.'');
				str_alert(200,'success');
			}
		}
		str_alert(-1,'操作失败');
	}
	
	//公共验证
	private function validform($data) {
	    $this->load->library('lib_pinyin');
		$data['name']   = isset($data['name']) ? $data['name'] :'';
		$data['number'] = isset($data['number']) ? $data['number'] :'';
	    strlen($data['name']) < 1 && str_alert(-1,'名称不能为空');
		strlen($data['number']) < 1 && str_alert(-1,'编号不能为空');
		$data['id']            = isset($data['id']) ? intval($data['id']) :0;
		$data['cCategory']     = intval($data['cCategory']);
		$data['cLevel']        = isset($data['cLevel']) ? (float)$data['cLevel'] :0;
		$data['cLevelName']    = isset($data['cLevelName']) ? (float)$data['cLevelName'] :'';
		$data['taxRate']       = isset($data['taxRate']) ? (float)$data['taxRate'] :0;
		$data['periodMoney']   = (float)$data['periodMoney'];
		$data['amount']        = (float)$data['amount'];
		$data['difMoney']      = $data['amount'] - $data['periodMoney'];
		$data['linkMans']      = $data['linkMans'] ? $data['linkMans'] :"[]";
		$data['beginDate']     = $data['beginDate'] ? $data['beginDate'] : date('Y-m-d');
		$data['type']          = intval($this->input->get_post('type',TRUE))==10 ? 10 : -10;
		$data['pinYin']        = $this->lib_pinyin->encode($data['name']); 
		$data['contact']       = $data['number'].' '.$data['name'];
		$data['cCategory'] < 1 && str_alert(-1,'类别名称不能为空');
		$data['cCategoryName'] = $this->mysql_model->get_row('category',array('id'=>$data['cCategory']),'name');
		return $data;
	}  
   
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */