<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->jxcsys = $this->session->userdata('jxcsys');
		$this->action = $this->input->get('action',TRUE); 
    }
	
	public function index() {
		switch ($this->action) {
			case 'initPay':
			    $this->load->view('scm/payment/initPay');break;  	
			case 'initUnhxList':
			    $this->load->view('scm/payment/initUnhxList');break; 
			case 'initPayList':
			    $this->load->view('scm/payment/initPayList');break;
			case 'list':
			    $this->payList();break;	
			case 'add':
			    $this->add();break;	
			case 'addNew':
			    $this->addNew();break;
			case 'update':
			    $this->update();break;
			case 'delete':
			    $this->delete();break;
			case 'toPdf':
			    $this->toPdf();break;
			case 'export':
			    $this->export();break;
			case 'updatePayment':
			    $this->updatePayment();break;
			case 'checkPayment':
			    $this->checkPayment();break;	
			case 'rsbatchCheckPayment':
			    $this->rsbatchCheckPayment();break;
			case 'batchCheckPayment':
			    $this->batchCheckPayment();break;
			default:  
			    str_alert(-1,'非法请求');
		}
	}
	
	private function payList(){ 
	    $this->common_model->checkpurview(129);
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate  = str_enhtml($this->input->get_post('endDate',TRUE));
		$where  = 'a.isDelete=0 and a.transType=153101';  
		$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_admin_purview();
		$list = $this->data_model->get_invoice($where.' order by id desc limit '.$rows*($page-1).','.$rows);  
		foreach ($list as $arr=>$row) {
		    $v[$arr]['amount']       = (float)$row['rpAmount'];     //收款金额
			$v[$arr]['adjustRate']   = (float)$row['discount'];     //整单折扣
			$v[$arr]['deAmount']     = (float)$row['payment'];      //本次预收款
			$v[$arr]['billDate']     = $row['billDate'];
			$v[$arr]['bDeAmount']    = (float)$row['hxAmount'];     //本次核销
			$v[$arr]['id']           = intval($row['id']);
			$v[$arr]['hxAmount']     = (float)$row['hxAmount'];     //本次核销
			$v[$arr]['contactName']  = $row['contactName'];
			$v[$arr]['description']  = $row['description'];
			$v[$arr]['billNo']       = $row['billNo'];
			$v[$arr]['checked']      = intval($row['checked']); 
			$v[$arr]['checkName']    = $row['checkName'];
			$v[$arr]['userName']     = $row['userName'];
		}
		$json['status']              = 200;
		$json['msg']                 = 'success';
		$json['data']['page']        = $page;
		$json['data']['records']     = $this->data_model->get_invoice($where,3);    
		$json['data']['total']       = ceil($json['data']['records']/$rows);                                 
		$json['data']['rows']        = isset($v) ? $v : array();
		die(json_encode($json));
	}
	
	
	private function export(){
	    $this->common_model->checkpurview(133);
		$name = 'payment_record_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出付款单:'.$name);
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$locationId   = intval($this->input->get_post('locationId',TRUE));
		$where  = 'a.isDelete=0 and a.transType=153101';  
		$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_admin_purview();
		$data['list'] = $this->data_model->get_invoice($where.' order by id desc'); 
		$data['account']  = array_column($this->mysql_model->get_results('account','(isDelete=0)'),'name','id'); 
		$data['category'] = array_column($this->mysql_model->get_results('category','(typeNumber="PayMethod")'),'name','id'); 
		$this->load->view('scm/payment/export',$data);  
	}
	
	//新增
	private function add(){
	    $this->common_model->checkpurview(130);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
		    $data = $this->validform((array)json_decode($data, true)); 
			$info = elements(array(
						'billNo','billType','transType','transTypeName','buId','billDate','createTime','arrears',
						'description','uid','postData','userName','rpAmount','hxAmount','discount','payment','modifyTime'),$data,NULL);
			$this->db->trans_begin();
			$data['id'] = $this->mysql_model->insert('invoice',$info);
			$this->account_info($data);

			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误回滚'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('新增付款单 单据编号：'.$data['billNo']);
				str_alert(200,'success',array('id'=>$data['id'])); 
			}
		}
		str_alert(-1,'提交的是空数据'); 
    } 
	
	
	private function addNew(){
	    $this->add();
    } 
	
	
 
	//修改
	private function updatePayment(){
	    $this->common_model->checkpurview(131);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));  
			$info = elements(array(
						'billType','transType','transTypeName','buId','billDate','description', 
						'postData','rpAmount','arrears','hxAmount','discount','payment','modifyTime'),$data,NULL);
			$this->db->trans_begin();
			$this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			$this->account_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误回滚'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('修改收款单 单据编号：'.$data['billNo']);
				str_alert(200,'success',array('id'=>$data['id'])); 
			}
		}
		str_alert(-1,'参数错误'); 
    }	
    
	//信息 
    private function update() {
	    $this->common_model->checkpurview(129);
	    $id   = intval($this->input->get_post('id',TRUE));
		$data = $this->data_model->get_invoice('a.isDelete=0 and a.id='.$id.' and a.transType=153101',1); 
		if (count($data)>0) {
			$list = $this->data_model->get_account_info('a.iid='.$id);  
			foreach ($list as $arr=>$row) {
			    $v[$arr]['id']            = intval($arr+1);
			    $v[$arr]['accId']         = intval($row['accId']);
				$v[$arr]['accName']       = $row['accountNumber'].' '.$row['accountName']; 
				$v[$arr]['payment']       = (float)$row['payment']>0 ? -abs($row['payment']) : abs($row['payment']); //特殊情况
				$v[$arr]['wayId']         = (float)$row['wayId']; 
				$v[$arr]['remark']        = $row['remark'];
				$v[$arr]['wayName']       = $row['categoryName']; 
				$v[$arr]['settlement']    = $row['settlement']; 
		    } 
			$list = $this->mysql_model->get_results('verifica_info','(iid='.$id.')');
			foreach ($list as $arr=>$row) {
				$s[$arr]['buId']          = $row['buId'];
				$s[$arr]['billId']        = $row['billId'];
				$s[$arr]['billNo']        = $row['billNo'];
				$s[$arr]['billType']      = $row['billType'];
				$s[$arr]['billPrice']     = $row['billPrice'];
				$s[$arr]['transType']     = $row['transType'];
				$s[$arr]['billDate']      = $row['billDate']; 
				$s[$arr]['hasCheck']      = $row['hasCheck'];
				$s[$arr]['notCheck']      = $row['notCheck']; 
				$s[$arr]['nowCheck']      = $row['nowCheck'];
		    }  
			$json['status']               = 200;
			$json['msg']                  = 'success'; 
			$json['data']['id']           = intval($data['id']);
			$json['data']['buId']         = intval($data['buId']);
			$json['data']['modifyTime']   = $data['modifyTime'];
			$json['data']['createTime']   = $data['createTime'];
			$json['data']['contactName']  = $data['contactName'];
			$json['data']['date']         = $data['billDate'];
			$json['data']['billNo']       = $data['billNo'];
			$json['data']['checked']      = intval($data['checked']); 
			$json['data']['checkName']    = $data['checkName'];
			$json['data']['userName']     = $data['userName'];
			$json['data']['description']  = $data['description'];
			$json['data']['discount']     = (float)$data['discount'];
			$json['data']['payment']      = (float)$data['payment'];
			$json['data']['status']       = intval($data['checked'])==1 ? 'view' : 'edit';     
			$json['data']['accounts']     = isset($v) ? $v : array();
			$json['data']['entries']      = isset($s) ? $s : array();
			die(json_encode($json));
		}
		str_alert(-1,'参数错误'); 
    }
	
	//打印
	private function toPdf() {
	    $this->common_model->checkpurview(208);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$entrysPerNote   = intval($this->input->post('entrysPerNote',TRUE));
		$data            = str_enhtml($this->input->post(NULL,TRUE));
		if (count($data)>0) {
			$data['list'] = $this->data_model->get_invoice('a.id in ('.$id.') and a.transType=153101',2);  
			if (count($data['list'])>0) { 
			    if ($this->input->cookie('entrysPerNote')>0) {
				    if ($entrysPerNote>0) {
					    $data['num'] = $entrysPerNote;
						$this->input->set_cookie('entrysPerNote',$entrysPerNote,360000);
					} else {
						$data['num'] = $this->input->cookie('entrysPerNote');
					}
				} else {
				    $data['num'] = $entrysPerNote;
				    $this->input->set_cookie('entrysPerNote',$entrysPerNote,360000);
				}
				$data['system']   = $this->common_model->get_option('system'); 
				$data['account']  = array_column($this->mysql_model->get_results('account','(isDelete=0)'),'name','id'); 
		        $data['category'] = array_column($this->mysql_model->get_results('category','(typeNumber="PayMethod")'),'name','id');  
				ob_start();
				$this->load->view('scm/payment/toPdf',$data);
				$content = ob_get_clean();
				require_once('./application/libraries/html2pdf/html2pdf.php');
				try {
					$html2pdf = new HTML2PDF('P', 'A4', 'en');
					$html2pdf->setDefaultFont('javiergb');
					$html2pdf->pdf->SetDisplayMode('fullpage');
					$html2pdf->writeHTML($content, '');
					$html2pdf->Output('payment_'.date('ymdHis').'.pdf');
				}catch(HTML2PDF_exception $e) {
					echo $e;
					exit;
				}  	  
			} 
		} 
		str_alert(-1,'单据不存在、或者已删除');  	    
	}
	
	
	//删除
    private function delete() {
	    $this->common_model->checkpurview(132);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$data = $this->mysql_model->get_results('invoice','(id in('.$id.')) and transType=153101 and isDelete=0');  
		if (count($data)>0) {
		    foreach($data as $arr=>$row) {
			    $row['checked'] >0 && str_alert(-1,'其中已有审核的不可删除'); 
				$ids[]           = $row['id'];
				$billNo[]        = $row['billNo'];
				$msg[$arr]['id'] = $row['billNo'];
				$msg[$arr]['isSuccess'] = 1;
				$msg[$arr]['msg'] = '删除成功！';
			}
			$id     = join(',',$ids);
			$billNo = join(',',$billNo); 
		    $this->db->trans_begin();
			$this->mysql_model->update('invoice',array('isDelete'=>1),'(id in('.$id.'))');   
			$this->mysql_model->update('account_info',array('isDelete'=>1),'(iid in('.$id.'))');     
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除收款单 单据编号：'.$billNo);
				str_alert(200,$msg); 	 
			}
		}
		str_alert(-1,'单据不存在,或已被删除'); 
	}
	
 
	
	//公共验证
	private function validform($data) {
	    $data['id']            = isset($data['id']) ? intval($data['id']) : 0;
		$data['buId']          = intval($data['buId']);
		$data['billDate']      = $data['date'] ? $data['date'] : date('Y-m-d');
		$data['billType']      = 'PAYMENT';
		$data['transType']     = 153101;
		$data['transTypeName'] = $this->common_model->get_transType($data['transType']); 
		$data['uid']           = $this->jxcsys['uid'];
		$data['userName']      = $this->jxcsys['name'];
		$data['modifyTime']    = date('Y-m-d H:i:s');
		$data['createTime']    = $data['modifyTime'];
        $data['hxAmount']      = $data['rpAmount']      = 0;
		$data['entries']       = isset($data['entries']) ? $data['entries'] : array();
		$data['accounts']      = isset($data['accounts']) ? $data['accounts'] : array();
		count($data['accounts']) < 1 && str_alert(-1,'提交的是空数据');
		strlen($data['billNo']) < 1 && str_alert(-1,'编号不能为空');  
		
		//修改的时候 
		if ($data['id']>0) {
		    $invoice = $this->mysql_model->get_rows('invoice',array('id'=>$data['id'],'billType'=>'PAYMENT','isDelete'=>0));  
			count($invoice)<1 && str_alert(-1,'单据不存在、或者已删除');
			$data['billNo']  = $invoice['billNo'];
			$data['checked'] = $invoice['checked'];			
		 
		}
		
		$this->mysql_model->get_count('contact',array('id'=>$data['buId']))<1 && str_alert(-1,'请选择供应商，供应商不能为空！'); 

		//数据验证
		foreach ($data['accounts'] as $arr=>$row) {
		    (float)$row['payment'] < 0 && str_alert(-1,'付款金额不能为负数！'); 
			$data['rpAmount'] += abs($row['payment']);
		} 
        foreach ($data['entries'] as $arr=>$row) {
		    (float)$row['nowCheck'] < 0 && str_alert(-1,'核销金额不能为负数！'); 
			$data['hxAmount'] += abs($row['nowCheck']);
		} 
		$data['arrears']  = -$data['rpAmount'];
		$data['postData'] = serialize($data);
		return $data;	
	}   
	
	private function account_info($data) {
	    foreach ($data['accounts'] as $arr=>$row) {
			$v[$arr]['iid']           = $data['id'];
			$v[$arr]['uid']           = $data['uid'];
			$v[$arr]['billNo']        = $data['billNo'];
			$v[$arr]['buId']          = $data['buId'];
			$v[$arr]['billType']      = $data['billType'];
			$v[$arr]['transType']     = $data['transType']; 
			$v[$arr]['transTypeName'] = $data['transTypeName'];
			$v[$arr]['billDate']      = $data['billDate']; 
			$v[$arr]['accId']         = $row['accId'] ;
			$v[$arr]['payment']       = -abs($row['payment']); 
			$v[$arr]['wayId']         = $row['wayId'];
			$v[$arr]['settlement']    = $row['settlement'];
			$v[$arr]['remark']        = $row['remark'];
		}
		
		if (isset($v)) {   
			if ($data['id']>0) {  
				$this->mysql_model->delete('account_info',array('iid'=>$data['id']));
			}
			$this->mysql_model->insert('account_info',$v);
		}   
    }
	
	 

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */