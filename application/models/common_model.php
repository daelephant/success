<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends CI_Model{

	public function __construct(){
  		parent::__construct();
		$this->jxcsys = $this->session->userdata('jxcsys');
	}
	
	//获取采购订单状态
	public function get_invPoStatus($key) { 
	    $data = array('0'=>'未入库','1'=>'部分入库','2'=>'全部入库');  
		return  isset($data[$key]) ? $data[$key] :''; 
	}
	
	//获取销售订单状态
	public function get_invSoStatus($key) { 
	    $data = array('0'=>'未出库','1'=>'部分出库','2'=>'全部出库');  
		return  isset($data[$key]) ? $data[$key] :''; 
	}
	
	//获取单据类型
	public function get_transType($key) { 
	    $transType = array(
						'150501'=>'购货',
						'150502'=>'退货',
						'150601'=>'销货',
						'150602'=>'销退',
						'103091'=>'调拨',
						'150806'=>'其它出库',
						'150706'=>'其它入库',
						'150801'=>'盘亏',
						'150701'=>'盘盈',
						'150807'=>'成本调整',
						'153101'=>'付款单',
						'153001'=>'收款单',
						'153301'=>'组装单',
						'153302'=>'拆卸单',
						'153401'=>'其他收入',
						'153402'=>'其他支出',
						'150901'=>'资金转账单'
		);   
		return  isset($transType[$key]) ? $transType[$key] :''; 
	}
	
	//获取用户信息
	public function get_admin() { 
		return $this->mysql_model->get_rows('admin',array('uid'=>$this->jxcsys['uid'],'status'=>1)); 
	}
	
	//用于价格管控
	public function isAdmin($id=0) { 
		if ($id>0) {
			$data = $this->get_admin(); 
			if (count($data)>0) {  
			    if ($data['roleid']==0) return 1;
				if (in_array($id,explode(',',$data['lever']))) return 1; 	
			}
			return 0; 	
		}
	}
	
	
	public function checkpurviews($id=0) { 
	    !$this->jxcsys && redirect(site_url('login'));
		if ($id>0) {
			$data = $this->get_admin(); 
			if (count($data)>0) {  
			    if ($data['roleid']==0) return true;
				if (in_array($id,explode(',',$data['lever']))) return true; 	
			}
			return false; 	
		}
	}
	 
	//检测是否有权限
	public function checkpurview($id=0) { 
	    !$this->jxcsys && redirect(site_url('login'));
		if ($id>0) {
			$data = $this->get_admin(); 
			if (count($data)>0) {  
			    if ($data['roleid']==0) return true;
				if (in_array($id,explode(',',$data['lever']))) return true; 	
			}
			str_alert(-1,'对不起，您没有此页面的管理权');
		}
	}
	
	//获取权限
	public function get_admin_rights() { 
		$data = $this->get_admin();  
		if (count($data)>0) {  
			if ($data['roleid']==0) { 
				$list = $this->mysql_model->get_results('menu','(1=1)'); 
			} else {
			    $data['lever'] = strlen($data['lever'])>0 ? $data['lever'] : 0;
				$list = $this->mysql_model->get_results('menu','(id in('.$data['lever'].'))'); 
			}
			foreach($list as $arr=>$row){
				$json[$row['module']] = true;
			}
		}
		return isset($json) ? json_encode($json) : '{}'; 
	}
	
	//制单人权限
	public function get_admin_purview($type='') { 
	    $data = $this->get_admin();  
		$rightids = explode(',',$data['rightids']);	
		if ($type==1) {
			$where = in_array(8,$rightids) && $data['roleid']>0 && strlen($data['righttype8'])>0  ? ' and uid in('.$data['righttype8'].')' : '';
		} else {	
			$where = in_array(8,$rightids) && $data['roleid']>0 && strlen($data['righttype8'])>0  ? ' and a.uid in('.$data['righttype8'].')' : '';
		}
	    return $where; 
	}
	
	//仓库权限
	public function get_location_purview($type='') { 
	    $data     = $this->get_admin(); 
		$rightids = explode(',',$data['rightids']);	
		if ($type==1) {
			$where = in_array(1,$rightids) && $data['roleid']>0 && strlen($data['righttype1'])>0  ? ' and id in('.$data['righttype1'].')' : '';
		} else {	
			$where = in_array(1,$rightids) && $data['roleid']>0 && strlen($data['righttype1'])>0 ? ' and a.locationId in('.$data['righttype1'].')' : '';
		}
	    return $where; 
	}
	
	//供应商权限
	public function get_vendor_purview() { 
	    $data     = $this->get_admin(); 
		$rightids = explode(',',$data['rightids']);	
		$where    = in_array(4,$rightids) && $data['roleid']>0 && strlen($data['righttype4'])>0 ? ' and id in('.$data['righttype4'].')' : '';
	    return $where; 
	}
	
	//客户权限
	public function get_customer_purview() { 
	    $data     = $this->get_admin(); 
		$rightids = explode(',',$data['rightids']);	
		$where    = in_array(2,$rightids) && $data['roleid']>0 && strlen($data['righttype2'])>0 ? ' and id in('.$data['righttype2'].')' : '';
	    return $where; 
	}
	
	 
	//客户或者供应商权限
	public function get_contact_purview() { 
	    $data  = $this->get_admin(); 
		$rightids = explode(',',$data['rightids']);	
		$arr[] = $data['righttype2'];
		$arr[] = $data['righttype4'];
		$id  = isset($arr) ? join(',',array_filter($arr)) : 0;
		$id  = strlen($id)>0 ? $id : 0;
		$where = $data['roleid']>0 && (in_array(2,$rightids) || in_array(4,$rightids)) ? ' and id in('.$id.')' : '';
	    return $where; 
	}
	
	//写入日志
	public function logs($info) {
		$data['userId']     =  $this->jxcsys['uid'];
		$data['name']       =  $this->jxcsys['name'];
		$data['ip']         =  $this->input->ip_address();
		$data['log']        =  $info;
		$data['loginName']  =  $this->jxcsys['username'];
		$data['adddate']    =  date('Y-m-d H');
		$data['modifyTime'] =  date('Y-m-d H:i:s');
		$this->mysql_model->insert('log',$data);		
	}	
	
	//写入配置
	public function insert_option($key,$val) {
		if ($this->mysql_model->get_count('options',array('option_name'=>$key))<1) {
			$data['option_name']  = $key;
			$data['option_value'] = $val ? serialize($val) :'';
			return $this->mysql_model->insert('options',$data);
		}
		return $this->update_option($key,$val);
	}
	
	//更新配置
	public function update_option($key,$val) {
		$data['option_value'] = $val ? serialize($val) :'';
		return $this->mysql_model->update('options',$data,array('option_name'=>$key));
	}
 
	//获取配置
	public function get_option($key) {
		$option_value = $this->mysql_model->get_row('options',array('option_name'=>$key),'option_value'); 
		return $option_value ? unserialize($option_value) : ''; 
	}
	
	
	
}