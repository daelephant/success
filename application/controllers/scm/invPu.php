<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class InvPu extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->jxcsys = $this->session->userdata('jxcsys');
		$this->action = $this->input->get('action',TRUE);
    }
 
	public function index() {
		switch ($this->action) {
			case 'initPur':
			    $this->common_model->checkpurview(2);
				$data['billNo'] = str_no('CG');
			    $this->load->view('scm/invPu/initPur',$data);break;  	 
			case 'initPurList':
			    $this->load->view('scm/invPu/initPurList');break;
			case 'list':
			    $this->purList();break;
			case 'toPdf':
			    $this->toPdf();break;
			case 'update':
			    $this->update();break;	
			case 'updateInvPu':
			    $this->updateInvPu();break;	
			case 'checkInvPu':
			    $this->checkInvPu();break;	
			case 'add':
			    $this->add();break;	
			case 'addNew':
			    $this->add();break;	
			case 'delete':
			    $this->delete();break;
			case 'queryDetails':
			    $this->queryDetails();break;	
			case 'exportInvPu':
			    $this->exportInvPu();break;
			case 'batchCheckInvPu':
			    $this->batchCheckInvPu();break;
			case 'rsBatchCheckInvPu':
			    $this->rsBatchCheckInvPu();break;
			case 'findUnhxList':
			    $this->findUnhxList();break;
			case 'getImagesById':
			    $this->getImagesById();break;
			case 'uploadImages':
			    $this->uploadImages();break;
			case 'addImagesToInv':
			    $this->addImagesToInv();break;
			case 'getImage':
			    $this->getImage();break;				
			default: 
			    $this->purList();	
		}
	}
	
	private function purList() {
	    $this->common_model->checkpurview(1); 
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$sidx = str_enhtml($this->input->get_post('sidx',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$billStatus= intval($this->input->get_post('billStatus',TRUE));
		$hxState   = $this->input->get_post('hxState',TRUE);
		$checked   = $this->input->get_post('checked',TRUE);
		$transType = intval($this->input->get_post('transType',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';
		$where = 'a.isDelete=0 and a.billType="PUR"'; 
		$where .= strlen($checked)>0&&$checked==0 ? ' and a.checked=0' : ''; 
		$where .= $checked==1 ? ' and a.checked=1' : ''; 
		$where .= strlen($hxState)>0 ? ' and a.hxStateCode in ('.$hxState.')' : ''; 
		$where .= $transType ? ' and a.transType='.$transType : ''; 
		$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_admin_purview();                               
		$list = $this->data_model->get_invoice($where.' order by '.$order.' limit '.$rows*($page-1).','.$rows);  
		foreach ($list as $arr=>$row) {
		    $v[$arr]['hxStateCode']  = intval($row['hxStateCode']);
		    $v[$arr]['id']           = intval($row['id']);
		    $v[$arr]['checkName']    = $row['checkName'];
			$v[$arr]['checked']      = intval($row['checked']);
			$v[$arr]['billDate']     = $row['billDate'];
		    $v[$arr]['amount']       = (float)abs($row['amount']);
			$v[$arr]['transType']    = intval($row['transType']); 
			$v[$arr]['rpAmount']     = (float)abs($row['hasCheck']);
			$v[$arr]['contactName']  = $row['contactNo'].' '.$row['contactName'];
			$v[$arr]['description']  = $row['description'];
			$v[$arr]['billNo']       = $row['billNo'];
			$v[$arr]['totalAmount']  = (float)abs($row['totalAmount']);
			$v[$arr]['userName']     = $row['userName'];
			$v[$arr]['transTypeName']= $row['transTypeName'];
			$v[$arr]['disEditable']  = 0;
		}
		$json['status']              = 200;
		$json['msg']                 = 'success'; 
		$json['data']['page']        = $page;
		$json['data']['records']     = $this->data_model->get_invoice($where,3);                             
		$json['data']['total']       = ceil($json['data']['records']/$rows);
		$json['data']['rows']        = isset($v) ? $v : array();
		die(json_encode($json));
	}
	
	//导出
	private function exportInvPu(){
	    $this->common_model->checkpurview(5);
		$name = 'purchase_record_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出采购单据:'.$name);
		$sidx = str_enhtml($this->input->get_post('sidx',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$hxState   = $this->input->get_post('hxState',TRUE);
		$checked   = $this->input->get_post('checked',TRUE);
		$transType = intval($this->input->get_post('transType',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';
		$where = 'a.isDelete=0 and a.billType="PUR"'; 
		$where .= strlen($checked)>0&&$checked==0 ? ' and a.checked=0' : ''; 
		$where .= $checked==1 ? ' and a.checked=1' : ''; 
		$where .= strlen($hxState)>0 ? ' and a.hxStateCode in ('.$hxState.')' : ''; 
		$where .= $transType ? ' and a.transType='.$transType : ''; 
		$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_admin_purview();
		$data['list'] = $this->data_model->get_invoice($where.' order by '.$order);  
		$this->load->view('scm/invPu/exportInvPu',$data);	
	}
	
 

	
	
	//新增
	private function add(){
	    $this->common_model->checkpurview(2);
	    $data = $this->input->post('postData',TRUE);
	    //var_dump($data);exit;
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
			$info = elements(array(
						'billNo','billType','transType','transTypeName','buId','billDate','postData','hxStateCode',
						'description','totalQty','amount','arrears','rpAmount','totalAmount','createTime',
						'totalArrears','disRate','disAmount','uid','userName','srcOrderNo','srcOrderId','accId','modifyTime'),$data,NULL);
			$this->db->trans_begin();
			$data['id'] = $this->mysql_model->insert('invoice',$info);   
			$this->invoice_info($data);
			$this->account_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit(); 
				$this->common_model->logs('新增采购 单据编号：'.$info['billNo']);
				str_alert(200,'success',array('id'=>intval($data['id']))); 
			}
		}
		str_alert(-1,'提交的是空数据'); 
    }
	
	 
	
	 
	//修改保存
	private function updateInvPu(){
	    $this->common_model->checkpurview(3);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
			$info = elements(array(
						'billType','transType','transTypeName','buId','billDate','hxStateCode',
						'description','totalQty','amount','arrears','rpAmount',
						'totalAmount','totalArrears','disRate','postData','disAmount','accId','modifyTime'),$data,NULL);//  20170214 'devicenumber',
			$this->db->trans_begin();
			$this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			$this->invoice_info($data);
			$this->account_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('修改采购单 单据编号：'.$data['billNo']);
				str_alert(200,'success',array('id'=>$data['id'])); 
			}
		}
		str_alert(-1,'提交的数据不能为空'); 
    }
	
	//获取修改信息
	private function update() {
	    $this->common_model->checkpurview(1);
	    $id   = intval($this->input->get_post('id',TRUE));
		$data =  $this->data_model->get_invoice('a.isDelete=0 and a.id='.$id.' and a.billType="PUR"',1);
		//var_dump($data);exit;
		if (count($data)>0) {
			$info['status'] = 200;
			$info['msg']    = 'success'; 
			$info['data']['id']                 = intval($data['id']);
			$info['data']['buId']               = intval($data['buId']);
			$info['data']['contactName']        = $data['contactName'];
			$info['data']['date']               = $data['billDate'];
			$info['data']['billNo']             = $data['billNo'];
			$info['data']['billType']           = $data['billType'];
			$info['data']['modifyTime']         = $data['modifyTime'];
			$info['data']['createTime']         = $data['createTime'];
			$info['data']['checked']            = intval($data['checked']); 
			$info['data']['checkName']          = $data['checkName'];
			$info['data']['transType']          = intval($data['transType']);
			$info['data']['totalQty']           = (float)abs($data['totalQty']);
			$info['data']['totalTaxAmount']     = (float)$data['totalTaxAmount'];
			$info['data']['billStatus']         = intval($data['billStatus']);
			$info['data']['disRate']            = (float)$data['disRate'];
			$info['data']['disAmount']          = (float)$data['disAmount'];
			$info['data']['amount']             = (float)abs($data['amount']);
			$info['data']['rpAmount']           = (float)abs($data['rpAmount']);
			$info['data']['arrears']            = (float)abs($data['arrears']);
			$info['data']['userName']           = $data['userName'];
			$info['data']['status']             = intval($data['checked'])==1 ? 'view' : 'edit';    //edit
			$info['data']['totalDiscount']      = (float)$data['totalDiscount'];
			$info['data']['totalTax']           = (float)$data['totalTax'];
			$info['data']['totalAmount']        = (float)abs($data['totalAmount']);
			$info['data']['description']        = $data['description']; 
			$list = $this->data_model->get_invoice_info('a.isDelete=0 and a.iid='.$id.' order by a.id');  
			foreach ($list as $arr=>$row) {
				$v[$arr]['invSpec']             = $row['invSpec'];
				$v[$arr]['srcOrderEntryId']     = $row['srcOrderEntryId'];
				$v[$arr]['srcOrderNo']          = $row['srcOrderNo'];
				$v[$arr]['srcOrderId']          = $row['srcOrderId'];
				$v[$arr]['goods']               = $row['invNumber'].' '.$row['invName'].' '.$row['invSpec'];
				$v[$arr]['invName']             = $row['invNumber'];
				$v[$arr]['qty']                 = (float)abs($row['qty']);
//  20170214 				$v[$arr]['devicenumber']        = $row['devicenumber'];
				$v[$arr]['amount']              = (float)abs($row['amount']);
				$v[$arr]['taxAmount']           = (float)abs($row['taxAmount']);
				$v[$arr]['price']               = (float)$row['price'];
				$v[$arr]['tax']                 = (float)$row['tax'];
				$v[$arr]['taxRate']             = (float)$row['taxRate'];
				$v[$arr]['mainUnit']            = $row['mainUnit'];
				$v[$arr]['deduction']           = (float)$row['deduction'];
				$v[$arr]['invId']               = intval($row['invId']);
				$v[$arr]['invNumber']           = $row['invNumber'];
				$v[$arr]['locationId']          = intval($row['locationId']);
				$v[$arr]['locationName']        = $row['locationName'];
				$v[$arr]['discountRate']        = $row['discountRate'];
				$v[$arr]['unitId']              = intval($row['unitId']);
				$v[$arr]['description']         = $row['description'];
				$v[$arr]['skuId']               = intval($row['skuId']);
				$v[$arr]['skuName']             = '';
			}
			$info['data']['entries']            = isset($v) ? $v : array();
			$info['data']['accId']              = (float)$data['accId'];
			$accounts = $this->data_model->get_account_info('a.isDelete=0 and a.iid='.$id.' order by a.id');  
			foreach ($accounts as $arr=>$row) {
				$s[$arr]['invoiceId']           = intval($id);
				$s[$arr]['billNo']              = $row['billNo'];
				$s[$arr]['buId']                = intval($row['buId']);
			    $s[$arr]['billType']            = $row['billType'];
				$s[$arr]['transType']           = $row['transType'];
				$s[$arr]['transTypeName']       = $row['transTypeName'];
				$s[$arr]['billDate']            = $row['billDate']; 
			    $s[$arr]['accId']               = intval($row['accId']);
				$s[$arr]['account']             = $row['accountNumber'].''.$row['accountName']; 
				$s[$arr]['payment']             = (float)abs($row['payment']); 
				$s[$arr]['wayId']               = (float)$row['wayId']; 
				$s[$arr]['way']                 = $row['categoryName']; 
				$s[$arr]['settlement']          = $row['settlement']; 
		    }  
			$info['data']['accounts']           = isset($s) ? $s : array();
			die(json_encode($info));
		}
		str_alert(-1,'单据不存在、或者已删除');  
    }
	
	//打印
	private function toPdf() {
	    $this->common_model->checkpurview(85);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$entrysPerNote   = intval($this->input->post('entrysPerNote',TRUE));
		$data            = str_enhtml($this->input->post(NULL,TRUE));
		if (count($data)>0) {
			$data['list'] = $this->data_model->get_invoice('a.id in ('.$id.') and a.billType="PUR"',2);  
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
				$data['system']  = $this->common_model->get_option('system');   
				ob_start();
				$this->load->view('scm/invPu/toPdf',$data);
				$content = ob_get_clean();
				require_once('./application/libraries/html2pdf/html2pdf.php');
				try {
					$html2pdf = new HTML2PDF('P', 'A4', 'en');
					$html2pdf->setDefaultFont('javiergb');
					$html2pdf->pdf->SetDisplayMode('fullpage');
					$html2pdf->writeHTML($content, '');
					$html2pdf->Output('invPu_'.date('ymdHis').'.pdf');
				}catch(HTML2PDF_exception $e) {
					echo $e;
					exit;
				}  	  
			}   
		} 
		die('请先选择单据，再进行打印！');   
	}
	
 
	
	//购购单删除
    private function delete() {
	    $this->common_model->checkpurview(4);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$data = $this->mysql_model->get_results('invoice','(isDelete=0) and (id in('.$id.')) and billType="PUR"');  
		if (count($data)>0) {
		    foreach($data as $arr=>$row) {
			    $row['checked'] >0 && str_alert(-1,'其中已有审核的不可删除'); 
				$ids[]           = $row['id'];
				$billNo[]        = $row['billNo'];
				$srcOrderId[]    = $row['srcOrderId'];
				$msg[$arr]['id'] = $row['billNo'];
				$msg[$arr]['isSuccess'] = 1;
				$msg[$arr]['msg'] = '删除成功！';
			}
			$data['id']         = $id         = join(',',$ids);
			$data['billNo']     = $billNo     = join(',',$billNo);
			$data['srcOrderId'] = join(',',array_filter($srcOrderId));
		    $this->db->trans_begin();
			$this->mysql_model->get_count('verifica_info','(isDelete=0) and (billId in('.$id.'))') > 0  && str_alert(-1,'其中有单据已核销，不能对它进行修改或删除反审核！'); 
			$this->mysql_model->update('invoice',array('isDelete'=>1),'(id in('.$id.'))');   
			$this->mysql_model->update('invoice_info',array('isDelete'=>1),'(iid in('.$id.'))');   
			$this->mysql_model->update('account_info',array('isDelete'=>1),'(iid in('.$id.'))');   
			$this->update_order($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除采购订单 单据编号：'.$billNo);
				str_alert(200,$msg); 	 
			}
		}
		str_alert(-1,'单据不存在');  
	}
	
	//单个审核   
	private function checkInvPu() {
	    $this->common_model->checkpurview(86);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
			$data['checked']         = 1;
			$data['checkName']       = $this->jxcsys['name']; 
			$this->db->trans_begin();
			//特殊情况
			if ($data['id']>0) {
			    $info = elements(array(
							'billType','transType','transTypeName','buId','billDate','checked','checkName',
							'description','totalQty','amount','arrears','rpAmount','hxStateCode',
							'totalAmount','totalArrears','disRate','postData','disAmount','accId','modifyTime'),$data,NULL);
			    $this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			} else {
				$info = elements(array(
							'billNo','billType','transType','transTypeName','buId','billDate','postData','srcOrderNo','srcOrderId',
							'description','totalQty','amount','arrears','rpAmount','totalAmount','createTime','hxStateCode',
							'totalArrears','disRate','disAmount','uid','userName','checked','checkName','accId','modifyTime'),$data,NULL);
			    $data['id'] = $this->mysql_model->insert('invoice',$info);
			}
			$this->invoice_info($data);
			$this->account_info($data);
			$this->update_order($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('采购单据编号：'.$data['billNo'].'的单据已被审核！');
				str_alert(200,'success',array('id'=>$data['id'])); 
			}
		}
		str_alert(-1,'提交的数据不能为空'); 
    }
	
	
	//批量审核  
    private function batchCheckInvPu() {
	    $this->common_model->checkpurview(86);
	    $id   = $this->input->post('id',TRUE) ? str_enhtml($this->input->post('id',TRUE)) : 0;
		$data = $this->mysql_model->get_results('invoice','(id in('.$id.')) and billType="PUR" and isDelete=0');  
		if (count($data)>0) {
		    foreach($data as $arr=>$row) {
			    $row['checked'] > 0 && str_alert(-1,'勾选当中已有审核，不可重复审核'); 
			    $ids[]        = $row['id'];
				$billNo[]     = $row['billNo'];
			    $srcOrderId[] = $row['srcOrderId'];
			}
			$data['id']         = $id         = join(',',$ids);
			$data['billNo']     = $billNo     = join(',',$billNo);
			$data['srcOrderId'] = join(',',array_filter($srcOrderId));
			$this->db->trans_begin();
			$this->mysql_model->update('invoice',array('checked'=>1,'checkName'=>$this->jxcsys['name']),'(id in('.$id.'))'); 
			$this->mysql_model->update('invoice_info',array('checked'=>1),'(iid in('.$id.'))');
			$this->update_order($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('采购单编号：'.$billNo.'的单据已被审核！');
				str_alert(200,'单据编号：'.$billNo.'的单据已被审核！');
			}
		}
		str_alert(-1,'审核失败'); 
	}
	
	//批量反审核
    private function rsBatchCheckInvPu() {
	    $this->common_model->checkpurview(87);
	    $id   = $this->input->post('id',TRUE) ? str_enhtml($this->input->post('id',TRUE)) : 0;
		$data = $this->mysql_model->get_results('invoice','(id in('.$id.')) and billType="PUR" and isDelete=0');  
		if (count($data)>0) {
		    foreach($data as $arr=>$row) {
			    $row['checked'] < 1 && str_alert(-1,'勾选当中已有未审核，不可重复反审核'); 
				$ids[]        = $row['id'];
				$billNo[]     = $row['billNo'];
				$srcOrderId[] = $row['srcOrderId'];
			}
			$data['id']         = $id         = join(',',$ids);
			$data['billNo']     = $billNo     = join(',',$billNo);
			$data['srcOrderId'] = join(',',array_filter($srcOrderId));
			$this->db->trans_begin();
			$this->mysql_model->get_count('verifica_info','(isDelete=0) and (billId in('.$id.'))') > 0  && str_alert(-1,'其中有单据已核销，不能对它进行修改或删除反审核！');  
			$this->mysql_model->update('invoice',array('checked'=>0,'checkName'=>''),'(id in('.$id.'))'); 
			$this->mysql_model->update('invoice_info',array('checked'=>0),'(iid in('.$id.'))'); 
			$this->update_order($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('采购单单号：'.$billNo.'的单据已被反审核！');
				str_alert(200,'采购单编号：'.$billNo.'的单据已被反审核！'); 
			}
		}
		str_alert(-1,'反审核失败');  
	}
	
	
	

	//公共验证
	private function validform($data) {
	    $data['id']              = isset($data['id']) ? intval($data['id']) : 0;
		$data['billType']        = 'PUR';
		$data['transTypeName']   = $this->common_model->get_transType($data['transType']); 
		$data['billDate']        = $data['date'];
		$data['buId']            = intval($data['buId']);
		$data['accId']           = intval($data['accId']);
		$data['transType']       = intval($data['transType']);
		$data['disRate']         = (float)$data['disRate'];
		$data['disAmount']       = (float)$data['disAmount'];
		$data['totalQty']        = (float)$data['totalQty'];
		$data['totalArrears']    = (float)$data['totalArrears'];
		$data['accounts']        = isset($data['accounts']) ? $data['accounts'] : array();
		$data['entries']         = isset($data['entries']) ? $data['entries'] : array();
		if ($data['amount']==$data['rpAmount']) {
			$data['hxStateCode'] = 2;
		} else {	
		    $data['hxStateCode'] = $data['rpAmount']!=0 ? 1 : 0;
		}
		$data['uid']             = $this->jxcsys['uid'];
		$data['userName']        = $this->jxcsys['name'];
		$data['modifyTime']      = date('Y-m-d H:i:s');
		$data['createTime']      = $data['modifyTime'];
		
		//基本验证
		count($data['entries']) < 1 && str_alert(-1,'提交的是空数据');
		strlen($data['billNo']) < 1 && str_alert(-1,'单据编号不为空！'); 
		$data['arrears'] < 0 && str_alert(-1,'本次欠款要为数字，请输入有效数字！'); 
		$data['disRate'] < 0 && str_alert(-1,'折扣率要为数字，请输入有效数字！'); 
		$data['rpAmount'] < 0  && str_alert(-1,'本次收款要为数字，请输入有效数字！'); 
		$data['amount'] < $data['rpAmount']  && str_alert(-1,'本次收款不能大于折后金额！'); 
		$data['amount'] < $data['disAmount'] && str_alert(-1,'折扣额不能大于合计金额！'); 
		
		if ($data['transType']==150501) {
			$data['amount']      = abs($data['amount']);
			$data['arrears']     = abs($data['arrears']);
			$data['rpAmount']    = abs($data['rpAmount']);
			$data['totalAmount'] = abs($data['totalAmount']);
		} else {
			$data['amount']      = -abs($data['amount']);
			$data['arrears']     = -abs($data['arrears']);
			$data['rpAmount']    = -abs($data['rpAmount']);
			$data['totalAmount'] = -abs($data['totalAmount']);
		}  
		
		//修改的时候 
		if ($data['id']>0) {
		    $invoice = $this->mysql_model->get_rows('invoice',array('id'=>$data['id'],'billType'=>'PUR','isDelete'=>0));  
			count($invoice)<1 && str_alert(-1,'单据不存在、或者已删除');
			$data['checked'] = $invoice['checked'];	
			$data['billNo']  = $invoice['billNo'];	  
		}
	    
		//选择了结算账户 需要验证 
		foreach ($data['accounts'] as $arr=>$row) {
			(float)$row['payment'] < 0 && str_alert(-1,'结算金额要为数字，请输入有效数字！');
		} 
		
		//供应商验证
		$this->mysql_model->get_count('contact',array('id'=>$data['buId'])) < 1 && str_alert(-1,'采购单位不存在');   
		
		 
		
		//商品录入验证
		$system  = $this->common_model->get_option('system');
		
		//库存验证
		if ($system['requiredCheckStore']==1) {
		    foreach ($data['entries'] as $val) {
				$invId[] = $val['invId'];
				$key = $val['invId'].'_'.$val['locationId'];
				if (!isset($entries[$key])) {
					$entries[$key] = $val;
				} else {
					$entries[$key]['qty'] += abs($val['qty']);
				}
			}
			$data['entries'] = is_array($entries) ? array_values($entries) : array();
		    $inventory = $this->data_model->get_invoice_info_inventory();
		}
		 
		$storage = array_column($this->mysql_model->get_results('storage',array('disable'=>0)),'id');  
		$data['entries'] = array_listsum($data['entries'],'invId','locationId','qty'); 
		foreach ($data['entries'] as $arr=>$row) {
			intval($row['invId'])<1 && str_alert(-1,'请选择商品');    
			(float)$row['qty'] < 0  && str_alert(-1,'商品数量要为数字，请输入有效数字！'); 
			(float)$row['price'] < 0  && str_alert(-1,'商品销售单价要为数字，请输入有效数字！'); 
			(float)$row['discountRate'] < 0  && str_alert(-1,'折扣率要为数字，请输入有效数字！');
			intval($row['locationId']) < 1 && str_alert(-1,'请选择相应的仓库！'); 
			!in_array($row['locationId'],$storage) && str_alert(-1,$row['locationName'].'不存在或不可用！');
			//库存判断 修改不验证
			if ($system['requiredCheckStore']==1 && $data['id']<1) {  
				if ($data['transType']==150502) {                        //退货才验证 
					if (isset($inventory[$row['invId']][$row['locationId']])) {
						$inventory[$row['invId']][$row['locationId']] < $row['qty'] && str_alert(-1,$row['locationName'].$row['invName'].'商品库存不足！'); 
					} else {
						str_alert(-1,$row['invName'].'库存不足！');
					}
				}
			}
			if ($row['srcOrderId']>0) {
				$data['srcOrderNo'] = $row['srcOrderNo']; 
				$data['srcOrderId'] = $row['srcOrderId']; 
			}
		} 
		$data['srcOrderNo'] = isset($data['srcOrderNo']) ? $data['srcOrderNo'] :''; 
		$data['srcOrderId'] = isset($data['srcOrderId']) ? $data['srcOrderId'] :0;  
		$data['postData']   = serialize($data);
		return $data;
	}
	
	
	//组装数据
	private function invoice_info($data) {
		foreach ($data['entries'] as $arr=>$row) {
			$v[$arr]['iid']              = $data['id'];
			$v[$arr]['uid']              = $data['uid'];
			$v[$arr]['billNo']           = $data['billNo'];
			$v[$arr]['buId']             = $data['buId'];
			$v[$arr]['billDate']         = $data['billDate']; 
			$v[$arr]['billType']         = $data['billType'];
			$v[$arr]['transType']        = $data['transType'];
			$v[$arr]['transTypeName']    = $data['transTypeName'];
			$v[$arr]['invId']            = intval($row['invId']);
			$v[$arr]['skuId']            = intval($row['skuId']);
			$v[$arr]['unitId']           = intval($row['unitId']);
			$v[$arr]['locationId']       = intval($row['locationId']);
			$v[$arr]['qty']              = $data['transType']==150501 ? abs($row['qty']) :-abs($row['qty']); 
			$v[$arr]['amount']           = $data['transType']==150501 ? abs($row['amount']) :-abs($row['amount']); 
			$v[$arr]['price']            = abs($row['price']);  
			$v[$arr]['discountRate']     = $row['discountRate'];  
			$v[$arr]['deduction']        = $row['deduction'];  
			$v[$arr]['description']      = $row['description'];
//  20170214 			$v[$arr]['devicenumber']     = $row['devicenumber'];
			if (in_array($this->action,array('checkInvPu'))) {
			    $v[$arr]['checked']   = 1; 	
			}
			if (intval($row['srcOrderId'])>0) {   
			    $v[$arr]['srcOrderEntryId']  = intval($row['srcOrderEntryId']);  
				$v[$arr]['srcOrderId']       = intval($row['srcOrderId']);  
				$v[$arr]['srcOrderNo']       = $row['srcOrderNo']; 
			} else {
			    $v[$arr]['srcOrderEntryId']  = 0;  
				$v[$arr]['srcOrderId']       = 0;  
				$v[$arr]['srcOrderNo']       = ''; 
			}
		}
		if (isset($v)) {
			if ($data['id']) {                              
				$this->mysql_model->delete('invoice_info',array('iid'=>$data['id']));
			}
			$this->mysql_model->insert('invoice_info',$v);
		}
	}
	

	//组装数据
	private function account_info($data) {
		foreach ($data['accounts'] as $arr=>$row) {
			$v[$arr]['iid']               = $data['id'];
			$v[$arr]['uid']               = $data['uid'];
			$v[$arr]['billNo']            = $data['billNo'];
			$v[$arr]['buId']              = $data['buId'];
			$v[$arr]['billType']          = $data['billType'];
			$v[$arr]['transType']         = $data['transType'];
			$v[$arr]['transTypeName']     = $data['transTypeName'];
			$v[$arr]['payment']           = $data['transType']==150501 ? -abs($row['payment']) : abs($row['payment']); 
			$v[$arr]['billDate']          = $data['billDate']; 
			$v[$arr]['accId']             = $row['accId']; 
			$v[$arr]['wayId']             = $row['wayId'];
			$v[$arr]['settlement']        = $row['settlement'];
		} 
		if ($data['id']>0) {                   
			$this->mysql_model->delete('account_info',array('iid'=>$data['id']));
		}
		if (isset($v)) {
			$this->mysql_model->insert('account_info',$v);
		}
	}
	
	
	//审核反审核更新
	private function update_order($data) {
		if ($data['srcOrderId']!=0&&strlen($data['srcOrderId'])>0) {
			$invoice_info = $this->data_model->get_purchasesrcOrder($data['srcOrderId']);
			foreach ($invoice_info as $arr=>$row) {
				$qty['qty'][$row['srcOrderEntryId']][$row['invId']] = abs($row['qty']);
			}
			$order_info = $this->mysql_model->get_results('order_info','(iid in('.$data['srcOrderId'].'))');
			foreach ($order_info as $arr=>$row) {
				if (isset($qty['qty'][$row['srcOrderEntryId']][$row['invId']])) {
					if (abs($qty['qty'][$row['srcOrderEntryId']][$row['invId']])>=abs($row['qty'])) {
						$t1[]     = $row['id'];
						$status[] = array('id'=>$row['id'],'status'=>2);
					} else {
						$t2[]     = $row['id'];
						$status[] = array('id'=>$row['id'],'status'=>1);
					}
				} else {
					$status[] = array('id'=>$row['id'],'status'=>0);
				}
			}

			//变更状态
			if (!isset($qty)) {     //删除完 
			    $this->mysql_model->update('order',array('billStatus'=>0),'(id in('.$data['srcOrderId'].'))');
				$this->mysql_model->update('order_info',array('status'=>0),'(iid in ('.$data['srcOrderId'].'))');
			}
			//变更订单明细状态
			if (isset($status)) {
			    $this->mysql_model->update('order_info',$status,'id');
			}
			
			if (isset($t1)) {
				if (count($order_info)==count($t1)) {
					$this->mysql_model->update('order',array('billStatus'=>2),'(id in('.$data['srcOrderId'].'))');
				} else {	
				    $this->mysql_model->update('order',array('billStatus'=>1),'(id in('.$data['srcOrderId'].'))');
				}
			}
			if (isset($t2)) {
				$this->mysql_model->update('order',array('billStatus'=>1),'(id in('.$data['srcOrderId'].'))');
			}
		} 
	}
	
	
	public function getImagesById() {
	    if (!$this->common_model->checkpurviews(217)){
		    str_alert(-1,'没有上传权限'); 
		}
	    $id = str_enhtml($this->input->post('id',TRUE));
	    $list = $this->mysql_model->get_results('invoice_img',array('isDelete'=>0,'billNo'=>$id));
		foreach ($list as $arr=>$row) {
		    $v[$arr]['pid']          = $row['id'];
			$v[$arr]['status']       = 1;
			$v[$arr]['name']         = $row['name'];
			$v[$arr]['url']          = site_url().'/scm/invPu/getImage?action=getImage&pid='.$row['id'];
			$v[$arr]['thumbnailUrl'] = site_url().'/scm/invPu/getImage?action=getImage&pid='.$row['id'];
			$v[$arr]['deleteUrl']    = '';
			$v[$arr]['deleteType']   = '';
		}
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['files']  = isset($v) ? $v : array();
		die(json_encode($json));  
	}
	
	
	//上传图片信息
	public function uploadImages() {
	    if (!$this->common_model->checkpurviews(216)){
		    str_alert(-1,'没有上传权限'); 
		}
	    require_once './application/libraries/UploadHandler.php';
		$config = array(
			'script_url' => base_url().'inventory/uploadimages',
			'upload_dir' => dirname($_SERVER['SCRIPT_FILENAME']).'/data/upfile/Contract/',
			'upload_url' => base_url().'data/upfile/Contract/',
			'delete_type' =>'',
			'print_response' =>false
		);
		$uploadHandler = new UploadHandler($config);
		$list  = (array)json_decode(json_encode($uploadHandler->response['files'][0]), true); 
		$info  = elements(array('name','size','type','url','thumbnailUrl','deleteUrl','deleteType'),$list,NULL);
		$newid = $this->mysql_model->insert('invoice_img',$info);
		
		 
		$files[0]['pid']          = intval($newid);
		$files[0]['status']       = 1;
		$files[0]['size']         = (float)$list['size'];
		$files[0]['name']         = $list['name'];
		$files[0]['url']          = site_url().'/scm/invPu/getImage?action=getImage&pid='.$newid;
		$files[0]['thumbnailUrl'] = site_url().'/scm/invPu/getImage?action=getImage&pid='.$newid;
		$files[0]['deleteUrl']    = '';
		$files[0]['deleteType']   = '';
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['files']  = $files;
        die(json_encode($json)); 
	}
	
	//保存上传图片信息
	public function addImagesToInv() {
	    if (!$this->common_model->checkpurviews(218)){
		    str_alert(-1,'没有上传权限'); 
		}
	    $data = $this->input->post('postData');
		if (strlen($data)>0) {
		    $v = $s = array();
		    $data = (array)json_decode($data, true); 
			$id   = isset($data['id']) ? $data['id'] : 0;
		    !isset($data['files']) || count($data['files']) < 1 && str_alert(-1,'请先添加图片！'); 
			foreach($data['files'] as $arr=>$row) {
			    if ($row['status']==1) {
					$v[$arr]['id']       = $row['pid'];
					$v[$arr]['billNo']   = $id;
				} else {
				    $s[$arr]['id']       = $row['pid'];
					$s[$arr]['billNo']   = $id;
					$s[$arr]['isDelete'] = 1;
				}
			}
			$this->mysql_model->update('invoice_img',array_values($v),'id');
			$this->mysql_model->update('invoice_img',array_values($s),'id');
			str_alert(200,'success'); 
	    }
		str_alert(-1,'保存失败'); 
	}
	
	//获取图片信息
	public function getImage() {
	    $id = intval($this->input->get_post('pid',TRUE));
	    $data = $this->mysql_model->get_rows('invoice_img',array('id'=>$id));
		if (count($data)>0) {
		    $url     = './data/upfile/Contract/'.$data['name'];
			$info    = getimagesize($url);  
			$imgdata = fread(fopen($url,'rb'),filesize($url));   
			header('content-type:'.$info['mime'].'');  
			echo $imgdata;   
		}	 
	} 

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */