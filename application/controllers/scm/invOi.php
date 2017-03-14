<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class InvOi extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->jxcsys = $this->session->userdata('jxcsys');
		$this->action = $this->input->get('action',TRUE);  
    }
	
	public function index() {
	    $type   = $this->input->get('type',TRUE);
		switch ($this->action) {
			case 'initOi':
			    $this->load->view('scm/invOi/initOi-'.$type);break;  
			case 'initOiList':
			    $this->load->view('scm/invOi/initOiList-'.$type);break; 
			case 'listIn':
			    $this->listIn();break;	
			case 'updateIn':
			    $this->updateIn();break;	
			case 'deleteIn':
			    $this->deleteIn();break;	
			case 'updateOi':
			    $this->updateOi();break;	
			case 'checkInvOi':
			    $this->checkInvOi();break;	
			case 'addNew':
			    $this->addNew();break;			
			case 'exportInvOi':
			    $this->exportInvOi();break;	
			case 'toOiPdf':
			    $this->toOiPdf();break;	
			case 'batchCheckInvOi':
			    $this->batchCheckInvOi();break;	
			case 'rsBatchCheckInvOi':
			    $this->rsBatchCheckInvOi();break;	
			case 'listOut':
			    $this->listOut();break;	
			case 'add':
			    $this->add();break;
			case 'addOo':
			    $this->addOo();break;
			case 'addNewOo':
			    $this->addNewOo();break;	
			case 'updateOo':
			    $this->updateOo();break;
			case 'updateOut':
			    $this->updateOut();break;	
			case 'deleteOut':
			    $this->deleteOut();break;		
			case 'exportInvOo':
			    $this->exportInvOi();break;	
			case 'toOoPdf':
			    $this->toOoPdf();break;	
			case 'checkInvOo':
			    $this->checkInvOo();break;		
			case 'batchCheckInvOo':
			    $this->batchCheckInvOo();break;	
			case 'rsBatchCheckInvOo':
			    $this->rsBatchCheckInvOo();break;	
			case 'queryTransType':
			    $this->queryTransType();break;	
			case 'listCbtz':
			    $this->listCbtz();break;
			case 'deleteCbtz':
			    $this->deleteCbtz();break;	
			case 'exportInvCadj':
			    $this->exportInvCadj();break;	
			case 'toCBTZPdf':
			    $this->toCBTZPdf();break;	
			case 'updateCbtz':
			    $this->updateCbtz();break;	
			case 'addCADJ':
			    $this->addCADJ();break;	
			case 'addNewCADJ':
			    $this->addNewCADJ();break;	
			case 'updateCADJ':
			    $this->updateCADJ();break;
			case 'listZz':
			    $this->listZz();break;	
			case 'addZz':
			    $this->addZz();break;	
			case 'deleteZz':
			    $this->deleteZz();break;	
			case 'updateZz':
			    $this->updateZz();break;
			case 'updateZzd':
			    $this->updateZzd();break;		
			case 'exportInvZzd':
			    $this->exportInvZzd();break;	
			case 'toZzdPdf':
			    $this->toZzdPdf();break;			
			case 'checkInvZz':
			    $this->checkInvZz();break;	
			case 'batchCheckInvZz':
			    $this->batchCheckInvZz();break;	
			case 'rsBatchCheckInvZz':
			    $this->rsBatchCheckInvZz();break;		
			default: 
			    str_alert(-1,'参数错误'); 	
		}
	}
	
	
	//单个审核   
	public function checkInvOi() {
	    $this->common_model->checkpurview(100);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->Oi_validform((array)json_decode($data, true));	
			$data['checked']         = 1;
			$data['checkName']       = $this->jxcsys['name']; 
			$this->db->trans_begin();
			//特殊情况
			if ($data['id']>0) {
			    $info = elements(array(
							'transType','transTypeName','buId','postData','inLocationId',
							'billDate','description','totalQty','totalAmount','checked','checkName','modifyTime'),$data,NULL);
				$this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			} else {
			    $info = elements(array(
							'billNo','billType','transType','transTypeName','buId','inLocationId','checked','checkName', 
							'billDate','description','totalQty','totalAmount','postData','createTime','uid','userName','modifyTime'),$data,NULL);
			    $data['id'] = $this->mysql_model->insert('invoice',$info);
			}
			$this->Oi_invoice_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('单据编号：'.$data['billNo'].'的单据已被审核！');
				str_alert(200,'success',array('id'=>$data['id'])); 
			}
		}
		str_alert(-1,'提交的数据不能为空'); 
    }
	 
	
	//批量反审核
    public function rsBatchCheckInvOi() {
	    $this->common_model->checkpurview(101);
	    $id   = str_enhtml($this->input->post('id',TRUE));
		$data = $this->mysql_model->get_results('invoice','(isDelete=0) and (id in('.$id.')) and billType="OI"');   
		 
		if (count($data)>0) {
			foreach($data as $arr=>$row) {
			    $row['checked'] < 1 && str_alert(-1,'勾选当中已有未审核，不可重复反审核'); 
				$ids[]        = $row['id'];
				$billNo[]     = $row['billNo'];
			}
			$id         = join(',',$ids);
			$billNo     = join(',',$billNo);
			$sql = $this->mysql_model->update('invoice',array('checked'=>0,'checkName'=>''),'(id in('.$id.'))'); 
			if ($sql) {
				$this->common_model->logs('单据编号：'.$billNo.'的单据已被反审核！');
				str_alert(200,'单据编号：'.$billNo.'的单据已被反审核！'); 
			} 
			str_alert(-1,'反审核失败');  
		}
		str_alert(-1,'单据不存在！'); 
	}
	
	
	//单个审核   
	public function checkInvOo() {
	    $this->common_model->checkpurview(103);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->Oo_validform((array)json_decode($data, true));
			$data['checked']         = 1;
			$data['checkName']       = $this->jxcsys['name']; 
			$this->db->trans_begin();
			//特殊情况
			if ($data['id']>0) {
			    $info = elements(array(
							'transType','transTypeName','buId','postData','outLocationId',
							'billDate','description','totalQty','totalAmount','checked','checkName','modifyTime'),$data,NULL); 
				$this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			} else {
			    $info = elements(array(
							'billNo','billType','transType','transTypeName','buId','createTime','checked','checkName', 
							'billDate','description','totalQty','totalAmount','outLocationId','uid','userName','modifyTime','postData'),$data);
			    $data['id'] = $this->mysql_model->insert('invoice',$info);
			}
			$this->Oo_invoice_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('单据编号：'.$data['billNo'].'的单据已被审核！');
				$this->get_updateOut($data['id']);
			}
		}
		str_alert(-1,'提交的数据不能为空'); 
    }
	
	
	//批量反审核
    public function rsBatchCheckInvOo() {
	    $this->common_model->checkpurview(104);
	    $id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
 
		$data = $this->mysql_model->get_results('invoice','(isDelete=0) and (id in('.$id.')) and billType="OO"');  
		if (count($data)>0) {
		    foreach($data as $arr=>$row) {
			    $row['checked'] < 1 && str_alert(-1,'勾选当中已有未审核，不可重复反审核'); 
				$ids[]        = $row['id'];
				$billNo[]     = $row['billNo'];
				$srcOrderId[] = $row['srcOrderId'];
			}
			$id         = join(',',$ids);
			$billNo     = join(',',$billNo);
			$sql = $this->mysql_model->update('invoice',array('checked'=>0,'checkName'=>''),'(id in('.$id.'))'); 
			if ($sql) {
				$this->common_model->logs('单据编号：'.$billNo.'的单据已被反审核！');
				str_alert(200,'单据编号：'.$billNo.'的单据已被反审核！'); 
			} 
			str_alert(-1,'反审核失败');  
		}
		str_alert(-1,'单据不存在！'); 
	} 
	
	
	
	
	//其他入库
	public function listIn() {
	    $this->common_model->checkpurview(14);
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$matchCon     = str_replace('请输入单据号或客户名或备注','',str_enhtml($this->input->get_post('matchCon',TRUE)));
		 
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$transTypeId  = intval($this->input->get_post('transTypeId',TRUE));
		$locationId   = intval($this->input->get_post('locationId',TRUE));
		$where = '(a.isDelete=0) and a.billType="OI"';
		$where .= $matchCon     ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $transTypeId>0 ? ' and a.transType='.$transTypeId.'' : ''; 
		$where .= $locationId>0 ? ' and find_in_set('.$locationId.',inLocationId)' : ''; 
		$where .= $this->common_model->get_admin_purview();                          
		$list = $this->data_model->get_invoice($where.' order by id desc limit '.$rows*($page-1).','.$rows);  
		foreach ($list as $arr=>$row) {
		    $v[$arr]['checkName']    = $row['checkName'];
			$v[$arr]['checked']      = intval($row['checked']);
			$v[$arr]['billDate']     = $row['billDate'];
			$v[$arr]['billType']     = $row['billType'];
			$v[$arr]['id']           = intval($row['id']);
		    $v[$arr]['amount']       = (float)abs($row['totalAmount']);
			$v[$arr]['transType']    = intval($row['transType']);;
			$v[$arr]['contactName']  = $row['contactName'];
			$v[$arr]['description']  = $row['description'];
			$v[$arr]['billNo']       = $row['billNo'];
			$v[$arr]['totalAmount']  = (float)abs($row['totalAmount']);
			$v[$arr]['userName']     = $row['userName'];
			$v[$arr]['transTypeName']= $row['transTypeName']; 
		}
		$json['status'] = 200;
		$json['msg']    = 'success'; 
		$json['data']['page']      = $page;
		$json['data']['records']   = $this->data_model->get_invoice($where,3);                           
		$json['data']['total']     = ceil($json['data']['records']/$rows);  
		$json['data']['rows']      = isset($v) ? $v : array();
		die(json_encode($json));
	}
	
	//其他入库导出
	private function exportInvOi() { 
	    $this->common_model->checkpurview(102);
		$name = 'qtrk_record_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出其他入库单:'.$name);
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$transTypeId  = intval($this->input->get_post('transTypeId',TRUE));
		$locationId   = intval($this->input->get_post('locationId',TRUE));
		$where = 'a.isDelete=0 and a.billType="OI"';
		$where .= $matchCon     ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $transTypeId>0 ? ' and a.transType='.$transTypeId.'' : ''; 
		$where .= $locationId>0 ? ' and find_in_set('.$locationId.',inLocationId)' : ''; 
		$where .= $this->common_model->get_admin_purview();
		$data['list'] = $this->data_model->get_invoice($where.' order by id desc');  
		$this->load->view('scm/invOi/exportInvOi',$data);
	}

	//类型
	public function queryTransType(){
	    $type   = $this->input->get_post('type',TRUE) == 'out' ? 'out' : 'in';
		$list = $this->mysql_model->get_results('invoice_type',array('type'=>$type),'id desc');  
		foreach ($list as $arr=>$row) {
		    $v[$arr]['acctId']       = 0;
			$v[$arr]['calCost']      = 1;
			$v[$arr]['commission']   = false;
			$v[$arr]['direction']    = 1;
		    $v[$arr]['free']         = false;
			$v[$arr]['id']           = intval($row['number']);
			$v[$arr]['inOut']        = 1;
			$v[$arr]['name']         = $row['name'];
			$v[$arr]['process']      = false;
			$v[$arr]['sysDefault']   = true;
			$v[$arr]['sysDelete']    = false;
			$v[$arr]['tableName']    = 't_scm_inventryoi';
			$v[$arr]['typeId']       = 1507;
			$v[$arr]['voucher']      = true;
		}
		$json['status'] = 200;
		$json['msg']    = 'success'; 
		$json['data']['totalsize']   = count($list);    
		$json['data']['items']       = isset($v) ? $v : array();
		die(json_encode($json));
    }
	
	
	//其他入库新增
	public function add(){
	    $this->common_model->checkpurview(15);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->Oi_validform((array)json_decode($data, true));
			$info = elements(array(
						'billNo','billType','transType','transTypeName','buId','inLocationId',
						'billDate','description','totalQty','totalAmount','postData','createTime',
						'uid','userName','modifyTime'),$data,NULL);
			$this->db->trans_begin();
			$data['id'] = $this->mysql_model->insert('invoice',$info);
			$this->Oi_invoice_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误回滚'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('新增其他入库 单据编号：'.$data['billNo']);
				str_alert(200,'success',array('id'=>intval($data['id']))); 
			}
		}
		str_alert(-1,'提交的是空数据'); 
    }
	
	//新增
	public function addnew(){
	    $this->add();
    }
	
	 
	//修改
	public function updateOi(){
	    $this->common_model->checkpurview(16);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->Oi_validform((array)json_decode($data, true));
			$info = elements(array(
						'transType','transTypeName','buId','postData','inLocationId', 
						'billDate','description','totalQty','totalAmount','modifyTime'),$data,NULL);
			$this->db->trans_begin();
			$this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			$this->Oi_invoice_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误回滚'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('修改其他入库 单据编号：'.$data['billNo']);
				str_alert(200,'success',array('id'=>$data['id'])); 
			}
		}
		str_alert(-1,'提交的是空数据'); 
    }
	
	
	//获取修改信息
	public function updateIn() {
	    $this->common_model->checkpurview(14);
	    $id   = intval($this->input->get_post('id',TRUE)); 
		$data = $this->data_model->get_invoice('a.isDelete=0 and a.id='.$id.' and a.billType="OI"',1);
		if (count($data)>0) {
			$list = $this->data_model->get_invoice_info('a.isDelete=0 and a.iid='.$id.'  order by a.id desc');   
			foreach ($list as $arr=>$row) {
				$v[$arr]['invSpec']      = $row['invSpec'];
				$v[$arr]['goods']        = $row['invNumber'].' '.$row['invName'].' '.$row['invSpec'];
				$v[$arr]['invName']      = $row['invName'];
				$v[$arr]['qty']          = (float)abs($row['qty']);
				$v[$arr]['amount']       = (float)abs($row['amount']);
				$v[$arr]['price']        = (float)abs($row['price']);
				$v[$arr]['mainUnit']     = $row['mainUnit'];
				$v[$arr]['description']  = $row['description'];
				$v[$arr]['invId']        = intval($row['invId']);
				$v[$arr]['invNumber']    = $row['invNumber'];
				$v[$arr]['locationId']   = intval($row['locationId']);
				$v[$arr]['locationName'] = $row['locationName'];
				$v[$arr]['unitId']       = intval($row['unitId']);
				$v[$arr]['skuId']        = intval($row['skuId']);
				$v[$arr]['skuName']      = '';
			}
			$json['status']              = 200;
			$json['msg']                 = 'success'; 
			$json['data']['id']          = intval($data['id']);
			$json['data']['buId']        = intval($data['buId']);
			$json['data']['contactName'] = $data['contactName'];
			$json['data']['date']        = $data['billDate'];
			$json['data']['billNo']      = $data['billNo'];
			$json['data']['billType']    = $data['billType'];
			$json['data']['modifyTime']  = $data['modifyTime'];
			$json['data']['createTime']  = $data['createTime']; 
			$json['data']['transType']   = intval($data['transType']);
			$json['data']['totalQty']    = (float)$data['totalQty'];
			$json['data']['totalAmount'] = (float)$data['totalAmount'];
			$json['data']['userName']    = $data['userName'];
			$json['data']['description'] = $data['description']; 
			$json['data']['amount']      = (float)abs($data['totalAmount']);
			$json['data']['checked']     = intval($data['checked']); 
			$json['data']['status']      = intval($data['checked'])==1 ? 'view' : 'edit'; 
			$json['data']['entries']     = isset($v) ? $v : array();
			die(json_encode($json));
		}
		str_alert(-1,'提交的是空数据'); 
    }
	
	
	//打印
	public function toOiPdf() {
	    $this->common_model->checkpurview(203);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$entrysPerNote   = intval($this->input->post('entrysPerNote',TRUE));
		$data            = str_enhtml($this->input->post(NULL,TRUE));
		if (count($data)>0) {
			$data['list'] = $this->data_model->get_invoice('a.id in ('.$id.') and a.billType="OI"',2);  
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
				$this->load->view('scm/invOi/toOiPdf',$data);
				$content = ob_get_clean();
				require_once('./application/libraries/html2pdf/html2pdf.php');
				try {
					$html2pdf = new HTML2PDF('P', 'A4', 'en');
					$html2pdf->setDefaultFont('javiergb');
					$html2pdf->pdf->SetDisplayMode('fullpage');
					$html2pdf->writeHTML($content, '');
					$html2pdf->Output('toOiPdf_'.date('ymdHis').'.pdf');
				}catch(HTML2PDF_exception $e) {
					echo $e;
					exit;
				}  	  
			}	   
		} 
		str_alert(-1,'单据不存在、或者已删除');    
	}
	
     
	
	 
	
	
	//删除
    public function deletein() {
	    $this->common_model->checkpurview(17);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$data = $this->mysql_model->get_results('invoice','(isDelete=0) and (id in('.$id.')) and billType="OI"');   
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
			$this->mysql_model->update('invoice_info',array('isDelete'=>1),'(iid in('.$id.'))'); 
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除单据编号：'.$billNo);
				str_alert(200,$msg); 	 
			}
		}
		str_alert(-1,'单据不存在！'); 
	}
	

	//其他出库列表
	public function listout() {
	    $this->common_model->checkpurview(18);
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$matchCon     = str_replace('请输入单据号或客户名或备注','',str_enhtml($this->input->get_post('matchCon',TRUE)));
		 
		$beginDate    = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate      = str_enhtml($this->input->get_post('endDate',TRUE));
		$locationId   = intval($this->input->get_post('locationId',TRUE));
		$transTypeId  = intval($this->input->get_post('transTypeId',TRUE));
		$where = 'a.isDelete=0 and a.billType="OO"';
		
		$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $transTypeId>0 ? ' and a.transType='.$transTypeId.'' : ''; 
		$where .= $locationId>0 ? ' and find_in_set('.$locationId.',outLocationId)' : ''; 
		$where .= $this->common_model->get_admin_purview();               
		$list = $this->data_model->get_invoice($where.' order by id desc limit '.$rows*($page-1).','.$rows);  
		foreach ($list as $arr=>$row) {
		    $v[$arr]['checkName']    = $row['checkName'];
			$v[$arr]['checked']      = intval($row['checked']);
			$v[$arr]['billDate']     = $row['billDate'];
			$v[$arr]['billType']     = $row['billType'];
			$v[$arr]['id']           = intval($row['id']);
		    $v[$arr]['amount']       = (float)abs($row['totalAmount']);
			$v[$arr]['transType']    = intval($row['transType']);;
			$v[$arr]['contactName']  = $row['contactName'];
			$v[$arr]['description']  = $row['description'];
			$v[$arr]['billNo']       = $row['billNo'];
			$v[$arr]['totalAmount']  = (float)abs($row['totalAmount']);
			$v[$arr]['userName']     = $row['userName'];
			$v[$arr]['transTypeName']= $row['transTypeName'];
		}
		$json['status'] = 200;
		$json['msg']    = 'success'; 
		$json['data']['page']        = $page;
		$json['data']['records']     = $this->data_model->get_invoice($where,3);                           
		$json['data']['total']       = ceil($json['data']['records']/$rows);               
		$json['data']['rows']        = isset($v) ? $v : array();
		die(json_encode($json));
	} 
	
	//其他出库导出
	public function exportInvOo() { 
	    $this->common_model->checkpurview(105);
		$name = 'qtck_record_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出其他出库单:'.$name);
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$transTypeId  = intval($this->input->get_post('transTypeId',TRUE));
		$locationId   = intval($this->input->get_post('locationId',TRUE));
		$where = 'a.isDelete=0 and a.billType="OO"';
		$where .= $matchCon     ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $transTypeId>0 ? ' and a.transType='.$transTypeId.'' : ''; 
		$where .= $locationId>0 ? ' and find_in_set('.$locationId.',outLocationId)' : '';
		$where .= $this->common_model->get_admin_purview();
		$data['list'] = $this->data_model->get_invoice($where.' order by id desc');  
		$this->load->view('scm/invOi/exportInvOo',$data);
	}
	
	//新增
	public function addOo(){
	    $this->common_model->checkpurview(19);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->Oo_validform((array)json_decode($data, true));
			$info = elements(array(
						'billNo','billType','transType','transTypeName','buId','createTime',
						'billDate','description','totalQty','totalAmount','outLocationId',
						'uid','userName','modifyTime','postData'),$data);
			$this->db->trans_begin();
			$data['id'] = $this->mysql_model->insert('invoice',$info);
			$this->Oo_invoice_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误回滚'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('新增其他出库 单据编号：'.$data['billNo']);
				$this->get_updateOut($data['id']);
			}
		}
		str_alert(-1,'提交的是空数据'); 
    }
	
	//新增
	public function addnewOo(){
	    $this->addOo();
    }
	
	 
	//修改
	public function updateOo(){
	    $this->common_model->checkpurview(20);
	    $postData = $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->Oo_validform((array)json_decode($data, true));
			$info = elements(array(
						'transType','transTypeName','buId','postData','outLocationId', 
						'billDate','description','totalQty','totalAmount','modifyTime'),$data);
			$this->db->trans_begin();
			$this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			$this->Oo_invoice_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误回滚'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('修改单据：'.$data['billNo']);
				$this->get_updateOut($data['id']);
			}
		}
		str_alert(-1,'提交数据不为空'); 
    }
	
	
	//获取修改信息
	public function updateOut() {
	    $this->common_model->checkpurview(18);
	    $id   = intval($this->input->get_post('id',TRUE));
		$this->get_updateOut($id);
    }
	
	
	//获取修改信息
	private function get_updateOut($id) {
	    $data = $this->data_model->get_invoice('a.isDelete=0 and a.id='.$id.' and a.billType="OO"',1);
		if (count($data)>0) {
			$list = $this->data_model->get_invoice_info('a.isDelete=0 and a.iid='.$id.'  order by a.id desc');   
			foreach ($list as $arr=>$row) {
			    $v[$arr]['invSpec']      = $row['invSpec'];
				$v[$arr]['goods']        = $row['invNumber'].' '.$row['invName'].' '.$row['invSpec'];
				$v[$arr]['invName']      = $row['invName'];
				$v[$arr]['qty']          = (float)abs($row['qty']);
				$v[$arr]['amount']       = (float)abs($row['amount']);
				$v[$arr]['price']        = (float)$row['price'];
				$v[$arr]['mainUnit']     = $row['mainUnit'];
				$v[$arr]['description']  = $row['description'];
				//$v[$arr]['devicenumber'] = $row['devicenumber'];
				$v[$arr]['invId']        = intval($row['invId']);
				$v[$arr]['invNumber']    = $row['invNumber'];
				$v[$arr]['locationId']   = intval($row['locationId']);
				$v[$arr]['locationName'] = $row['locationName'];
				$v[$arr]['unitId']       = intval($row['unitId']);
				$v[$arr]['skuId']        = intval($row['skuId']);
				$v[$arr]['skuName']      = '';
			}
			$json['status']              = 200;
			$json['msg']                 = 'success'; 
			$json['data']['id']          = intval($data['id']);
			$json['data']['buId']        = intval($data['buId']);
			$json['data']['contactName'] = $data['contactName'];
			$json['data']['date']        = $data['billDate'];
			$json['data']['billNo']      = $data['billNo'];
			$json['data']['billType']    = $data['billType'];
			$json['data']['modifyTime']  = $data['modifyTime'];
			$json['data']['createTime']  = $data['createTime']; 
			$json['data']['transType']   = intval($data['transType']);
			$json['data']['totalQty']    = (float)$data['totalQty'];
			$json['data']['totalAmount'] = (float)abs($data['totalAmount']);
			$json['data']['userName']    = $data['userName'];
			$json['data']['description'] = $data['description']; 
			//$json['data']['devicenumber'] = $data['devicenumber']; 
			$json['data']['amount']      = (float)abs($data['totalAmount']);
			$json['data']['checked']     = intval($data['checked']); 
			$json['data']['status']      = intval($data['checked'])==1 ? 'view' : 'edit'; 
			$json['data']['entries']     = isset($v) ? $v : array();
			die(json_encode($json));
		}
		str_alert(-1,'单据不存在'); 
	} 
	
	  
	
	//打印
	public function toOoPdf() {
	    $this->common_model->checkpurview(204);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$data = str_enhtml($this->input->post(NULL,TRUE));
		if (count($data)>0) {
			$data['list'] = $this->data_model->get_invoice('a.id in ('.$id.') and a.billType="OO"',2);  
			if (count($data['list'])>0) { 
			    if ($this->input->cookie('entrysPerNote')>0) {
					$data['num'] = $this->input->cookie('entrysPerNote');
				} else {
				    $data['num'] = 20;
				}
				$data['system']  = $this->common_model->get_option('system');   
				ob_start();
				$this->load->view('scm/invOi/toOoPdf',$data);
				$content = ob_get_clean();
				require_once('./application/libraries/html2pdf/html2pdf.php');
				try {
					$html2pdf = new HTML2PDF('P', 'A4', 'en');
					$html2pdf->setDefaultFont('javiergb');
					$html2pdf->pdf->SetDisplayMode('fullpage');
					$html2pdf->writeHTML($content, '');
					$html2pdf->Output('toOoPdf_'.date('ymdHis').'.pdf');
				}catch(HTML2PDF_exception $e) {
					echo $e;
					exit;
				}  	  
			}   
		} 
		str_alert(-1,'单据不存在、或者已删除');  	
	}
	
     
	
	//删除
    public function deleteOut() {
	    $this->common_model->checkpurview(21);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$data = $this->mysql_model->get_results('invoice','(isDelete=0) and (id in('.$id.')) and billType="OO"'); 
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
			$this->mysql_model->update('invoice_info',array('isDelete'=>1),'(iid in('.$id.'))');   
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除单据编号：'.$billNo);
				str_alert(200,$msg); 	 
			}
		}
		str_alert(-1,'单据不存在'); 
	}
	
	//成本调整单
	public function listCbtz() {
	    $this->common_model->checkpurview(151);
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$matchCon     = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate    = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate      = str_enhtml($this->input->get_post('endDate',TRUE));
		$locationId   = intval($this->input->get_post('locationId',TRUE));
		$where = 'a.isDelete=0 and transType=150807';
		$where .= $matchCon     ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $locationId>0 ? ' and find_in_set('.$locationId.',locationId)' : ''; 
		$where .= $this->common_model->get_admin_purview();
		$offset = $rows * ($page-1);                        
		$list = $this->data_model->get_invoice($where.' order by id desc limit '.$offset.','.$rows);  
		foreach ($list as $arr=>$row) {
		    $v[$arr]['checkName']    = $row['checkName'];
			$v[$arr]['checked']      = intval($row['checked']);
			$v[$arr]['billDate']     = $row['billDate'];
			$v[$arr]['billType']     = $row['billType'];
			$v[$arr]['id']           = intval($row['id']);
		    $v[$arr]['amount']       = (float)$row['totalAmount'];
			$v[$arr]['transType']    = intval($row['transType']); 
			$v[$arr]['contactName']  = $row['contactName'];
			$v[$arr]['description']  = $row['description'];
			$v[$arr]['billNo']       = $row['billNo'];
			$v[$arr]['totalAmount']  = (float)$row['totalAmount'];
			$v[$arr]['userName']     = $row['userName'];
			$v[$arr]['transTypeName']= $row['transTypeName'];
		}
		$json['status'] = 200;
		$json['msg']    = 'success'; 
		$json['data']['page']        = $page;
		$json['data']['records']     = $this->data_model->get_invoice($where,3);  
		$json['data']['total']       = ceil($json['data']['records']/$rows);      
		$json['data']['rows']        = isset($v) ? $v : array();
		die(json_encode($json));
	}
	
	
	public function exportInvCadj() {
	    $this->common_model->checkpurview(205);
	    $name = 'adjustment_record_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出成本调整单:'.$name);
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$locationId   = intval($this->input->get_post('locationId',TRUE));
		$where = 'a.isDelete=0 and transType=150807';
		$where .= $matchCon     ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $locationId>0 ? ' and find_in_set('.$locationId.',locationId)' : ''; 
		$where .= $this->common_model->get_admin_purview();
		$data['list'] = $this->data_model->get_invoice($where.' order by id desc');  
		$this->load->view('scm/invOi/exportInvCadj',$data);  
	} 
	
	
	public function addCADJ() {
	    $this->common_model->checkpurview(152);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->cadj_validform((array)json_decode($data, true));
			$info = elements(array(
						'transType','transTypeName','postData','locationId','createTime',
						'billDate','description','totalAmount','billNo','billType',
						'uid','userName','modifyTime'),$data);
			$this->db->trans_begin();
			$data['id'] = $this->mysql_model->insert('invoice',$info);
			$this->cadj_invoice_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误回滚'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('新增成本调整 单据编号：'.$data['billNo']);
				str_alert(200,'success',$data); 
			 }
		}
		str_alert(-1,'提交的是空数据'); 
	} 
	
	public function addNewCADJ() {
	     $this->addCADJ();
	} 
	
	
	public function updateCbtz() {
	    $this->common_model->checkpurview(151);
	    $id   = intval($this->input->get_post('id',TRUE));
		$data = $this->data_model->get_invoice('a.isDelete=0 and a.id='.$id.' and a.transType=150807',1);
		if (count($data)>0) {
			$list = $this->data_model->get_invoice_info('a.isDelete=0 and a.iid='.$id.'  order by a.id desc');   
			foreach ($list as $arr=>$row) {
			    $v[$arr]['invSpec']      = $row['invSpec'];
				$v[$arr]['goods']        = $row['invNumber'].' '.$row['invName'].' '.$row['invSpec'];
				$v[$arr]['invName']      = $row['invName'];
				$v[$arr]['amount']       = (float)$row['amount'];
				$v[$arr]['mainUnit']     = $row['mainUnit'];
				$v[$arr]['description']  = $row['description'];
				$v[$arr]['invId']        = intval($row['invId']);
				$v[$arr]['invNumber']    = $row['invNumber'];
				$v[$arr]['locationId']   = intval($row['locationId']);
				$v[$arr]['locationName'] = $row['locationName'];
				$v[$arr]['unitId']       = intval($row['unitId']);
				$v[$arr]['skuId']        = intval($row['skuId']);
				$v[$arr]['skuName']      = '';
			}
			$json['status']              = 200;
			$json['msg']                 = 'success'; 
			$json['data']['id']          = intval($data['id']);
			$json['data']['date']        = $data['billDate'];
			$json['data']['billNo']      = $data['billNo'];
			$json['data']['billType']    = $data['billType'];
			$json['data']['modifyTime']  = $data['modifyTime'];
			$json['data']['createTime']  = $data['createTime']; 
			$json['data']['transType']   = intval($data['transType']);
			$json['data']['totalQty']    = (float)$data['totalQty'];
			$json['data']['totalAmount'] = (float)$data['totalAmount'];
			$json['data']['userName']    = $data['userName'];
			$json['data']['description'] = $data['description']; 
			$json['data']['amount']      = (float)$data['totalAmount'];
			$json['data']['checked']     = intval($data['checked']); 
			$json['data']['status']      = intval($data['checked'])==1 ? 'view' : 'edit'; 
			$json['data']['entries']     = isset($v) ? $v :'';
			die(json_encode($json));
		}
		str_alert(-1,'单据不存在'); 
	} 
 
 
	public function updateCADJ() {
	    $this->common_model->checkpurview(153);
	    $postData = $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->cadj_validform((array)json_decode($data, true));
			$info = elements(array(
						'transType','transTypeName','postData','locationId', 
						'billDate','description','totalAmount','billNo','billType','modifyTime'),$data);
			$this->db->trans_begin();
			$this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			$this->cadj_invoice_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误回滚'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('修改成本调整 单据编号：'.$data['billNo']);
				die('{"status":200,"msg":"success","data":'.$postData.'}');
			}
		}
		str_alert(-1,'提交数据不为空'); 
	} 
	
	
	
	//打印
	public function toCBTZPdf() {
	    $this->common_model->checkpurview(206);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$data = str_enhtml($this->input->post(NULL,TRUE));
		if (count($data)>0) {
			$data['list'] = $this->data_model->get_invoice('a.id in ('.$id.') and a.transType=150807',2);  
			if (count($data['list'])>0) { 
			    if ($this->input->cookie('entrysPerNote')>0) {
					$data['num'] = $this->input->cookie('entrysPerNote');
				} else {
				    $data['num'] = 20;
				}
				$data['system']  = $this->common_model->get_option('system');   
				ob_start();
				$this->load->view('scm/invOi/toCBTZPdf',$data);
				$content = ob_get_clean();
				require_once('./application/libraries/html2pdf/html2pdf.php');
				try {
					$html2pdf = new HTML2PDF('P', 'A4', 'en');
					$html2pdf->setDefaultFont('javiergb');
					$html2pdf->pdf->SetDisplayMode('fullpage');
					$html2pdf->writeHTML($content, '');
					$html2pdf->Output('toCBTZPdf_'.date('ymdHis').'.pdf');
				}catch(HTML2PDF_exception $e) {
					echo $e;
					exit;
				}  	  
			} 	   
		} 
		str_alert(-1,'单据不存在、或者已删除'); 
	}
	
     
	
	//删除
    public function deleteCbtz() {
	    $this->common_model->checkpurview(154);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$data = $this->mysql_model->get_results('invoice','(isDelete=0) and (id in('.$id.')) and transType=150807');  
		if (count($data)>0) {
		    foreach($data as $arr=>$row) {
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
			$this->mysql_model->update('invoice_info',array('isDelete'=>1),'(iid in('.$id.'))');   
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除单据编号：'.$billNo);
				str_alert(200,$msg); 	 
			}
		}
		str_alert(-1,'单据不存在'); 
	}
	
	 
 
	//盘点查询
	public function queryToPD() {
	    $this->common_model->checkpurview(11);
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$showZero   = intval($this->input->get_post('showZero',TRUE));
		$categoryId = intval($this->input->get_post('categoryId',TRUE));
		$locationId = intval($this->input->get_post('locationId',TRUE));
		$goods = str_enhtml($this->input->get_post('goods',TRUE));
		$where = '(a.isDelete=0)';
		$where .= strlen($goods)>0 ? ' and (b.name like "%'.$goods.'%")' : '';
		$where .= $locationId>0 ? ' and a.locationId='.$locationId.'' : ''; 
		if ($categoryId > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(1=1) and find_in_set('.$categoryId.',path)'),'id'); 
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and b.categoryId in('.$cid.')';
			} 
		}
		$where .= $this->common_model->get_location_purview();
		$having = $showZero == 1 ? ' HAVING qty=0' : '';   
		$list = $this->data_model->get_inventory($where.' GROUP BY a.invId,a.locationId '.$having.' limit '.$rows*($page-1).','.$rows);  
		foreach ($list as $arr=>$row) {
		    $v[$arr]['assistName']    = $row['categoryName'];
			$v[$arr]['invSpec']       = $row['invSpec'];
			$v[$arr]['locationId']    = $locationId > 0 ? intval($row['locationId']) : 0;
			$v[$arr]['skuName']       = '';
		    $v[$arr]['qty']           = (float)$row['qty'];
			$v[$arr]['locationName']  = $row['locationName'];
			$v[$arr]['assistId']      = 0;
			$v[$arr]['invCost']       = 0;
			$v[$arr]['unitName']      = $row['unitName']; 
			$v[$arr]['skuId']         = 0;
			$v[$arr]['invId']         = intval($row['invId']);
			$v[$arr]['invNumber']     = $row['invNumber']; 
			$v[$arr]['invName']       = $row['invName']; 	 
		}
		$json['status'] = 200;
		$json['msg']    = 'success'; 
		$json['data']['page']         = $page;
		$json['data']['records']      = $this->data_model->get_inventory($where.' GROUP BY a.invId,a.locationId'.$having,3);    
		$json['data']['total']        = ceil($json['data']['records']/$rows);                           
		$json['data']['rows']         = isset($v) ? $v : array();
		die(json_encode($json));
	}
	
	//导出盘点单据
	public function exportToPD() {
	    $this->common_model->checkpurview(13);
		$name = 'pdReport_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出盘点单据:'.$name);
		$showZero   = intval($this->input->get_post('showZero',TRUE));
		$categoryId = intval($this->input->get_post('categoryId',TRUE));
		$data['locationId'] = $locationId = intval($this->input->get_post('locationId',TRUE));
		$goods = str_enhtml($this->input->get_post('goods',TRUE));
		$where = '(a.isDelete=0)';
		$where .= strlen($goods)>0 ? ' and (b.name like "%'.$goods.'%")' : '';
		$where .= $locationId>0 ? ' and a.locationId='.$locationId.'' : ''; 
		if ($categoryId > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(1=1) and find_in_set('.$categoryId.',path)'),'id'); 
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and b.categoryId in('.$cid.')';
			} 
		}
		$where .= $this->common_model->get_location_purview();
		$having = $showZero == 1 ? ' HAVING qty=0' : '';
		$data['list'] = $this->data_model->get_inventory($where.' GROUP BY a.invId,a.locationId'.$having); 
		$this->load->view('scm/invOi/exportToPD',$data); 
	}
	
	//生成盘点单据
	public function generatorPD() {
	    $this->common_model->checkpurview(12);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
		     $data = (array)json_decode($data, true); 
			 if (is_array($data['entries'])) {
			     foreach ($data['entries'] as $arr=>$row) {
				     intval($row['locationId']) < 1 && str_alert(-1,'必须选择某一仓库进行盘点'); 
					 if (intval($row['invId'])>0) {
						 if (intval($row['change'])>0) {  //盘盈
							 $v[$arr]['goods']         = $row['invNumber'].' '.$row['invName'].' '.$row['invSpec'];
							 $v[$arr]['description']   = '';
							 $v[$arr]['invId']         = intval($row['invId']);
							 $v[$arr]['invNumber']     = $row['invNumber'];
							 $v[$arr]['invName']       = $row['invName'];
							 $v[$arr]['invSpec']       = $row['invSpec'];
							 $v[$arr]['skuId']         = intval($row['skuId']);
							 $v[$arr]['skuName']       = $row['skuName'];
							 $v[$arr]['unitId']        = intval($row['unitId']);
							 $v[$arr]['amount']        = 0;
							 $v[$arr]['price']         = 0;
							 $v[$arr]['qty']           = (float)abs($row['change']);
							 $v[$arr]['mainUnit']      = $row['mainUnit'];
							 $v[$arr]['locationId']    = intval($row['locationId']);
							 $v[$arr]['locationName']  = $row['locationName']; 
						 } elseif(intval($row['change'])<0) {	 //盘亏 
							 $s[$arr]['goods']         = $row['invNumber'].' '.$row['invName'].' '.$row['invSpec'];
							 $s[$arr]['description']   = '';
							 $s[$arr]['invId']         = intval($row['invId']);
							 $s[$arr]['invNumber']     = $row['invNumber'];
							 $s[$arr]['invName']       = $row['invName'];
							 $s[$arr]['invSpec']       = $row['invSpec'];
							 $s[$arr]['skuId']         = intval($row['skuId']);
							 $s[$arr]['skuName']       = $row['skuName'];
							 $s[$arr]['unitId']        = intval($row['unitId']);
							 $s[$arr]['amount']        = 0;
							 $s[$arr]['price']         = 0;
							 $s[$arr]['qty']           = (float)abs($row['change']);
							 $s[$arr]['mainUnit']      = $row['mainUnit'];
							 $s[$arr]['locationId']    = intval($row['locationId']);
							 $s[$arr]['locationName']  = $row['locationName'];
						 }
					 }
				}  
				
				if (isset($v) || isset($s)) {
					$json['status'] = 200;
					$json['msg']    = 'success'; 
					if (isset($v)) {
						$json['data']['items'][0]['id']          = -1;
						$json['data']['items'][0]['billType']    = 'OI';
						$json['data']['items'][0]['transType']   = 150701;
						$json['data']['items'][0]['description'] = '';
						$json['data']['items'][0]['buId']        = 0;
						$json['data']['items'][0]['billNo']      = str_no('QTRK');
						$json['data']['items'][0]['totalAmount'] = 0;
						$json['data']['items'][0]['userName']    = '';
						$json['data']['items'][0]['totalQty']    = 1;
						$json['data']['items'][0]['date']        = date('Y-m-d');
						$json['data']['items'][0]['entries']     = array_merge(array(),$v);
					}
					if (isset($s)) {
						$json['data']['items'][1]['id']          = -1;
						$json['data']['items'][1]['billType']    = 'OO';
						$json['data']['items'][1]['transType']   = 150801;
						$json['data']['items'][1]['description'] = '';
						$json['data']['items'][1]['buId']        = 0;
						$json['data']['items'][1]['billNo']      = str_no('QTCK');
						$json['data']['items'][1]['totalAmount'] = 0;
						$json['data']['items'][1]['userName']    = '';
						$json['data']['items'][1]['totalQty']    = 1;
						$json['data']['items'][1]['date']        = date('Y-m-d');
						$json['data']['items'][1]['entries']     = array_merge(array(),$s); 
					}
					$json['data']['totalsize']                   = isset($v)&&isset($s) ? 2 :1;
					$json['data']['items']                       = array_values($json['data']['items']);
		            die(json_encode($json));
				} else {
				    str_alert(-1,'请先进行盘点！'); 
				}
			}
		}
		str_alert(-1,'提交的是空数据'); 
    }
 
	
	//其他入库公共
	private function Oi_validform($data) {
	    $data['id']              = isset($data['id']) ? intval($data['id']) : 0;
		$data['buId']            = intval($data['buId']);
		$data['transType']       = intval($data['transTypeId']);
		$data['totalQty']        = (float)$data['totalQty'];
		$data['billType']        = 'OI';
		$data['billDate']        = $data['date'];   
		$data['transTypeName']   = $data['transTypeName'];
	    $data['uid']             = $this->jxcsys['uid'];
		$data['userName']        = $this->jxcsys['name'];
		$data['modifyTime']      = date('Y-m-d H:i:s');
		$data['createTime']      = $data['modifyTime'];
		$data['accounts']        = isset($data['accounts']) ? $data['accounts'] : array();
		$data['entries']         = isset($data['entries']) ? $data['entries'] : array();
		
		count($data['entries']) < 1 && str_alert(-1,'提交的是空数据'); 
		strlen($data['billNo']) < 1 && str_alert(-1,'编号不能为空'); 
		//修改的时候 
		if ($data['id']>0) {
		    $invoice = $this->mysql_model->get_rows('invoice',array('id'=>$data['id'],'billType'=>'OI','isDelete'=>0));  
			count($invoice)<1 && str_alert(-1,'单据不存在、或者已删除');
			$data['checked'] = $invoice['checked'];	
			$data['billNo']  = $invoice['billNo'];	
		}

		//商品录入验证
		$system    = $this->common_model->get_option('system'); 
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
		$storage   = array_column($this->mysql_model->get_results('storage','(disable=0)'),'id');  
		$data['entries'] = array_listsum($data['entries'],'invId','locationId','qty'); 
		foreach ($data['entries'] as $arr=>$row) {
		    intval($row['invId']) < 1 && str_alert(-1,'请选择商品');    
			(float)$row['qty'] < 0  && str_alert(-1,'商品数量要为数字，请输入有效数字！'); 
			(float)$row['price'] < 0  && str_alert(-1,'商品销售单价要为数字，请输入有效数字！'); 
			intval($row['locationId']) < 1 && str_alert(-1,'请选择相应的仓库！'); 
			!in_array($row['locationId'],$storage) && str_alert(-1,$row['locationName'].'不存在或不可用！');

			//库存判断
			if ($system['requiredCheckStore']==1 && $data['id']<1) {  
				if (intval($data['transType'])==150806) {                        //其他出库才验证 
					if (isset($inventory[$row['invId']][$row['locationId']])) {
						$inventory[$row['invId']][$row['locationId']] < (float)$row['qty'] && str_alert(-1,$row['locationName'].$row['invName'].'商品库存不足！'); 
					} else {
						str_alert(-1,$row['invName'].'库存不足！');
					}
				}
			}
			if ($data['transType']==150706) {  	
				$inLocationId[]  = $row['locationId'];
			} else {
				$outLocationId[] = $row['locationId'];
			}
		}
		if ($data['transType']==150706) {  	
			$data['inLocationId']  = join(',',array_unique($inLocationId));
		} else {
			$data['outLocationId'] = join(',',array_unique($outLocationId));
		}
		$data['postData'] = serialize($data);
		return $data;  
	}  
	
	
	//组装数据1(备注和设备编号)
	private function Oi_invoice_info($data) {

	    foreach ($data['entries'] as $arr=>$row) {
			$v[$arr]['iid']           = $data['id'];
			$v[$arr]['billNo']        = $data['billNo'];
			$v[$arr]['buId']          = $data['buId'];
			$v[$arr]['transType']     = $data['transType'];
			$v[$arr]['transTypeName'] = $data['transTypeName'];
			$v[$arr]['billDate']      = $data['billDate']; 
			$v[$arr]['billType']      = $data['billType'];
			$v[$arr]['invId']         = intval($row['invId']);
			$v[$arr]['skuId']         = intval($row['skuId']);
			$v[$arr]['unitId']        = intval($row['unitId']);
			$v[$arr]['locationId']    = intval($row['locationId']);
			$v[$arr]['qty']           = abs($row['qty']); 
			$v[$arr]['amount']        = abs($row['amount']); 
			$v[$arr]['price']         = abs($row['price']); 
			//$v[$arr]['assembler']     = $row['assembler'];   
			//$v[$arr]['description']   = $row['description'];
			
			//$v[$arr]['devicenumber']  = $row['devicenumber'];
			
		} 
		if (isset($v)) {  
		    if ($data['id']>0) {      
				$this->mysql_model->delete('invoice_info',array('iid'=>$data['id'])); 
			}
			$this->mysql_model->insert('invoice_info',$v);
		}
	}
	
	//其他出库公共
	private function Oo_validform($data) {
	    $data['id']              = isset($data['id']) ? intval($data['id']) : 0;
		$data['buId']            = intval($data['buId']);
		$data['transType']       = intval($data['transTypeId']);
		$data['totalQty']        = (float)$data['totalQty'];
		$data['billType']        = 'OO';
		$data['billDate']        = $data['date'];   
		$data['transTypeName']   = $data['transTypeName'];
	    $data['uid']             = $this->jxcsys['uid'];
		$data['userName']        = $this->jxcsys['name'];
		$data['modifyTime']      = date('Y-m-d H:i:s');
		$data['createTime']      = $data['modifyTime'];
		$data['accounts']        = isset($data['accounts']) ? $data['accounts'] : array();
		$data['entries']         = isset($data['entries']) ? $data['entries'] : array();
		
		count($data['entries']) < 1 && str_alert(-1,'提交的是空数据'); 
		strlen($data['billNo']) < 1 && str_alert(-1,'编号不能为空'); 
		
		//修改的时候 
		if ($data['id']>0) {
		    $invoice = $this->mysql_model->get_rows('invoice',array('id'=>$data['id'],'billType'=>'OO','isDelete'=>0));  
			count($invoice)<1 && str_alert(-1,'单据不存在、或者已删除');
			$data['checked'] = $invoice['checked'];	
			$data['billNo'] = $invoice['billNo'];	
		}
		
		
		

		//商品录入验证
		$system    = $this->common_model->get_option('system'); 
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
		$storage = array_column($this->mysql_model->get_results('storage','(disable=0)'),'id'); 
		$profit  = $this->data_model->get_profit('and billDate<="'.date('Y-m-d').'"');   
		$data['entries'] = array_listsum($data['entries'],'invId','locationId','qty');  
		foreach ($data['entries'] as $arr=>$row) {
		    $price = isset($profit['inprice'][$row['invId']][$row['locationId']]) ? $profit['inprice'][$row['invId']][$row['locationId']] : 0;  
			$data['entries'][$arr]['price']  =  $price;
			$data['entries'][$arr]['amount'] =  $price * $row['qty'];
		    intval($row['invId']) < 1 && str_alert(-1,'请选择商品');    
			(float)$row['qty'] < 0  && str_alert(-1,'商品数量要为数字，请输入有效数字！'); 
			(float)$row['price'] < 0  && str_alert(-1,'商品销售单价要为数字，请输入有效数字！'); 
			intval($row['locationId']) < 1 && str_alert(-1,'请选择相应的仓库！'); 
			!in_array($row['locationId'],$storage) && str_alert(-1,$row['locationName'].'不存在或不可用！');

			//库存判断
			if ($system['requiredCheckStore']==1 && $data['id']<1) {  
				if (intval($data['transType'])==150806) {                        //其他出库才验证 
					if (isset($inventory[$row['invId']][$row['locationId']])) {
						$inventory[$row['invId']][$row['locationId']] < (float)$row['qty'] && str_alert(-1,$row['locationName'].$row['invName'].'商品库存不足！'); 
					} else {
						str_alert(-1,$row['invName'].'库存不足！');
					}
				}
			}
			if ($data['transType']==150706) {  	
				$inLocationId[]  = $row['locationId'];
			} else {
				$outLocationId[] = $row['locationId'];
			}
		}
		$data['postData'] = serialize($data);
		if ($data['transType']==150706) {  	
			$data['inLocationId']  = join(',',array_unique($inLocationId));
		} else {
			$data['outLocationId'] = join(',',array_unique($outLocationId));
		}
		return $data;  
	}  
	
	
	//组装数据2
	private function Oo_invoice_info($data) {
	    $amount = 0;
		$profit = $this->data_model->get_profit('and billDate<="'.date('Y-m-d').'"');    
		foreach ($data['entries'] as $arr=>$row) {
		    $price = isset($profit['inprice'][$row['invId']][$row['locationId']]) ? $profit['inprice'][$row['invId']][$row['locationId']] : 0;  
			$amount   += -abs($row['qty']) * $price; 
			$v[$arr]['iid']           = $data['id'];
			$v[$arr]['billNo']        = $data['billNo'];
			$v[$arr]['buId']          = $data['buId'];
			$v[$arr]['transType']     = $data['transType'];
			$v[$arr]['transTypeName'] = $data['transTypeName'];
			$v[$arr]['billDate']      = $data['billDate']; 
			$v[$arr]['billType']      = $data['billType'];
			$v[$arr]['invId']         = intval($row['invId']);
			$v[$arr]['skuId']         = intval($row['skuId']);
			$v[$arr]['unitId']        = intval($row['unitId']);
			$v[$arr]['locationId']    = intval($row['locationId']);
			$v[$arr]['qty']           = -abs($row['qty']); 
			$v[$arr]['amount']        = -abs($row['qty']) * $price; 
			$v[$arr]['price']         = $price; 
			$v[$arr]['assembler']     = $row['assembler'];
			$v[$arr]['version_number']= $row['version_number'];
			$v[$arr]['checker']       = $row['checker'];
			$v[$arr]['check_time']    = $row['check_time'];
			$v[$arr]['fire_time']    = $row['fire_time'];
			$v[$arr]['firer']         = $row['firer'];
			//$v[$arr]['devicenumber']  = $row['devicenumber'];
			//$v[$arr]['description']   = $row['description'];  
			//$v[$arr]['assembler']     = $arr>0 ? $row['assembler'] :'';   
			
		} 
		if (isset($v)) {
		    if ($data['id']>0) {      
				$this->mysql_model->delete('invoice_info',array('iid'=>$data['id']));
			}
			$this->mysql_model->insert('invoice_info',$v);
			$this->mysql_model->update('invoice',array('totalAmount'=>$amount),array('id'=>$data['id']));
		}
	}

	//公共验证
	private function cadj_validform($data) {
	    $data['id']              = isset($data['id']) ? intval($data['id']) : 0;
		$data['totalAmount']     = (float)$data['totalAmount'];
		$data['billNo']          = str_no('CBTZ');
		$data['billDate']        = $data['date'];   
		$data['transType']       = 150807;
		$data['billType']        = 'CADJ';
		$data['transTypeName']   = $this->common_model->get_transType($data['transType']); 
	    $data['uid']             = $this->jxcsys['uid'];
		$data['userName']        = $this->jxcsys['name'];
		$data['modifyTime']      = date('Y-m-d H:i:s');
		$data['createTime']      = $data['modifyTime'];
		$data['entries']         = isset($data['entries']) ? $data['entries'] : array();
		count($data['entries']) < 1 && str_alert(-1,'提交的是空数据'); 
		$storage   = array_column($this->mysql_model->get_results('storage','(disable=0)'),'id');  
		foreach ($data['entries'] as $arr=>$row) {
		    intval($row['invId'])<1 && str_alert(-1,'选择商品');    
			intval($row['locationId']) < 1 && str_alert(-1,'请选择相应的仓库！'); 
			!in_array($row['locationId'],$storage) && str_alert(-1,$row['locationName'].'不存在或不可用！');
			$locationId[]    = $row['locationId'];
		}
		$data['postData']    = serialize($data);	
		$data['locationId']  = join(',',array_unique($locationId));
		return $data;
		  
	}  
	
	private function cadj_invoice_info($data) {
		foreach ($data['entries'] as $arr=>$row) {
			$v[$arr]['iid']           = $data['id'];
			$v[$arr]['billNo']        = $data['billNo'];
			$v[$arr]['transType']     = $data['transType'];
			$v[$arr]['transTypeName'] = $data['transTypeName'];
			$v[$arr]['billDate']      = $data['billDate']; 
			$v[$arr]['billType']      = $data['billType'];
			$v[$arr]['invId']         = intval($row['invId']);
			$v[$arr]['skuId']         = intval($row['skuId']);
			$v[$arr]['unitId']        = intval($row['unitId']);
			$v[$arr]['locationId']    = intval($row['locationId']); 
			$v[$arr]['amount']        = (float)$row['amount']; 
			$v[$arr]['description']   = $row['description'];  
			//$v[$arr]['assembler']     = $row['assembler'];    
		} 
		if (isset($v)) {
		    if ($data['id']>0) {      
				$this->mysql_model->delete('invoice_info',array('iid'=>$data['id'])); 
			}
			$this->mysql_model->insert('invoice_info',$v);
		}
	}
	
	
		//验证
	private function zz_validform($data) {
	    $data['id']              = isset($data['id']) ? intval($data['id']) : 0;
		$data['amount']          = (float)$data['amount'];
		$data['totalAmount']     = (float)$data['totalAmount'];
		$data['totalQty']        = (float)$data['totalQty'];
		$data['billNo']          = str_no('ZZD');
		$data['billDate']        = $data['date'];   
		$data['transType']       = 153301;
		$data['billType']        = 'ZZD';
		$data['transTypeName']   = $this->common_model->get_transType($data['transType']); 
	    $data['uid']             = $this->jxcsys['uid'];
		$data['userName']        = $this->jxcsys['name'];
		$data['modifyTime']      = date('Y-m-d H:i:s');
		$data['createTime']      = $data['modifyTime'];
		
		$data['entries']         = isset($data['entries']) ? $data['entries'] : array();

		count($data['entries']) < 1 && str_alert(-1,'提交的是空数据'); 
		strlen($data['billNo']) < 1 && str_alert(-1,'编号不能为空'); 
		
		//商品录入验证
		$system    = $this->common_model->get_option('system'); 
		
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
		
		$amount = 0;
		$profit = $this->data_model->get_profit('and billDate<="'.date('Y-m-d').'"'); 
		
		$storage   = array_column($this->mysql_model->get_results('storage','(disable=0)'),'id');  
		$data['entries'] = array_listsum($data['entries'],'invId','locationId','qty'); 
		foreach ($data['entries'] as $arr=>$row) {
		    intval($row['invId'])<1 && str_alert(-1,'选择商品');    
			intval($row['locationId']) < 1 && str_alert(-1,'请选择相应的仓库！'); 
			!in_array($row['locationId'],$storage) && str_alert(-1,$row['locationName'].'不存在或不可用！');
			if ($arr>0) {
			//库存判断 修改不验证
			if ($system['requiredCheckStore']==1 && $data['id']<1) {                        
				if (isset($inventory[$row['invId']][$row['locationId']])) {
					$inventory[$row['invId']][$row['locationId']] < $row['qty'] && str_alert(-1,$row['locationName'].$row['invName'].'商品库存不足！'); 
				} else {
					str_alert(-1,$row['invName'].'库存不足！');
				}
			}
			$locationId[]    = $row['locationId'];
			}
			
			if ($arr>0) { 
				$data['entries'][$arr]['price']   = isset($profit['inprice'][$row['invId']][$row['locationId']]) ? $profit['inprice'][$row['invId']][$row['locationId']] : 0;  
				$data['entries'][$arr]['amount']  = abs($row['qty']) * $data['entries'][$arr]['price']; 
			}
		}
		$data['postData']    = serialize($data);	
		$data['locationId']  = join(',',array_unique($locationId));
		return $data;
		  
	}  
	
	private function zz_invoice_info($data) {
		//var_dump($data['entries']);exit;
	    $amount = 0;
		$profit = $this->data_model->get_profit('and billDate<="'.date('Y-m-d').'"'); 

		foreach ($data['entries'] as $arr=>$row) {
		   
		    if ($arr>0) { 
				$price = isset($profit['inprice'][$row['invId']][$row['locationId']]) ? $profit['inprice'][$row['invId']][$row['locationId']] : 0;  
				$amount   += -abs($row['qty']) * $price; 
			}
			$v[$arr]['iid']           = $data['id'];
			$v[$arr]['billNo']        = $data['billNo'];
			$v[$arr]['transType']     = $data['transType'];
			$v[$arr]['transTypeName'] = $data['transTypeName'];
			$v[$arr]['billDate']      = $data['billDate']; 
			$v[$arr]['billType']      = $data['billType'];
			$v[$arr]['invId']         = intval($row['invId']);
			$v[$arr]['skuId']         = intval($row['skuId']);
			$v[$arr]['unitId']        = intval($row['unitId']);
			$v[$arr]['locationId']    = intval($row['locationId']);
			$v[$arr]['qty']           = $arr>0 ? -abs($row['qty']) : abs($row['qty']); 
			$v[$arr]['amount']        = $arr>0 ? -abs($row['qty']) * $price : abs($row['amount']); 
			$v[$arr]['price']         = $arr>0 ? $price : abs($row['price']); 
			$v[$arr]['description']   = $arr>0 ? $row['description'] :'';  
			//$v[$arr]['devicenumber']  = $arr>0 ? $row['devicenumber'] :'';  //20170214
			//var_dump($row['description']);exit;
			//ok 新增信息，组装人，测试人，
			$v[$arr]['assembler']          = $arr<1 ? $row['assembler'] :''; 
			$v[$arr]['version_number']     = $arr<1 ? $row['version_number'] :''; 
			$v[$arr]['checker']            = $arr<1 ? $row['checker'] :''; 
			$v[$arr]['check_time']         = $arr<1 ? $row['check_time'] :''; 
			$v[$arr]['fire_time']          = $arr<1 ? $row['fire_time'] :''; 
			$v[$arr]['firer']              = $arr<1 ? $row['firer'] :''; 
		} 
		if (isset($v)) {
		    if ($data['id']>0) {      
				$this->mysql_model->delete('invoice_info',array('iid'=>$data['id']));
			}
			$this->mysql_model->insert('invoice_info',array_values($v));
		}
	}
	
	
	
	//组装单
	public function listZz() {
	    $this->common_model->checkpurview(223);
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$matchCon     = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate    = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate      = str_enhtml($this->input->get_post('endDate',TRUE));
		$locationId   = intval($this->input->get_post('locationId',TRUE));
		$where = ' a.isDelete=0 and a.transType=153301';
		$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_admin_purview(); 
		$list = $this->data_model->get_invoice($where.' order by id desc limit '.$rows*($page-1).','.$rows);                       
		  
		foreach ($list as $arr=>$row) {
		    $postData = unserialize($row['postData']);
		    foreach ($postData['entries'] as $arr1=>$row1) {
			    if ($arr1==0) {
					$good     =  $row1['invNumber'].' '.$row1['invName'].' '.$row1['invSpec'];
					$mainUnit =  $row1['mainUnit'];
					$unitCost =  $row1['price'];
					$cost     =  $row1['amount'];
					$qty      =  $row1['qty'];
				} else {
				    $qtys[$row['id']][]            = abs($row1['qty']);
					$costs[$row['id']][]           = $row1['amount'];
					$mainUnits[$row['id']][]       = $row1['mainUnit'];
					$goods[$row['id']][]           = $row1['invNumber'].' '.$row1['invName'].' '.$row1['invSpec'];
					$unitCosts[$row['id']][]       = $row1['price'];
				}
 
			}
		    $v[$arr]['id']                 = intval($row['id']);
			$v[$arr]['billDate']           = $row['billDate'];
			//避免查询组装单信息为空。把不必要的隐藏，不必赋值。要不然会报以下错：A PHP Error was encountered

// Severity: Notice

// Message: Undefined offset: 19

// Filename: scm/invOi.php

// Line Number: 1576
			// $v[$arr]['qtys']               = $qtys[$row['id']];
			// $v[$arr]['goods']              = $goods[$row['id']];
			// $v[$arr]['costs']              = $costs[$row['id']];
			// $v[$arr]['mainUnits']          = $mainUnits[$row['id']];
			// $v[$arr]['unitCosts']          = $unitCosts[$row['id']];
			$v[$arr]['unitCost']           = $unitCost;
			$v[$arr]['mainUnit']           = $mainUnit;
			$v[$arr]['good']               = $good;
			$v[$arr]['cost']               = $cost;
			$v[$arr]['qty']                = $qty;
			$v[$arr]['description']        = $row['description'];
			$v[$arr]['billNo']             = $row['billNo'];
			$v[$arr]['userName']           = $row['userName']; 
			$v[$arr]['checkName']          = $row['checkName'];
			$v[$arr]['checked']            = intval($row['checked']);
			 
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
	public function exportInvZzd() { 
	    $this->common_model->checkpurview(223);
		$name = 'qtrk_record_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出组装单:'.$name);
		$matchCon     = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate    = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate      = str_enhtml($this->input->get_post('endDate',TRUE));
		$locationId   = intval($this->input->get_post('locationId',TRUE));
		$where = ' a.isDelete=0 and a.transType=153301';
		$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_admin_purview(); 
		$data['list'] = $this->data_model->get_invoice($where.' order by id desc');
		$this->load->view('scm/invOi/exportInvZzd',$data);
	}
	
    //新增
	public function addZz() {
	    $this->common_model->checkpurview(224);
	    $data = $this->input->post('postData',TRUE);
	    //var_dump($data);exit;//得到新增加所有数据
		if (strlen($data)>0) {
			$data = $this->zz_validform((array)json_decode($data, true));
			//var_dump($data);exit;//得到新增加所有数据
			$info = elements(array(
						'transType','transTypeName','postData','amount','createTime',
						'billDate','description','totalAmount','billNo','billType','totalQty',
						'uid','userName','modifyTime'),$data);
			$this->db->trans_begin();
			$data['id'] = $this->mysql_model->insert('invoice',$info);
			$this->zz_invoice_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误回滚'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('新增组装单 单据编号：'.$data['billNo']);
				str_alert(200,'success',$data); 
			 }
		}
		str_alert(-1,'提交的是空数据'); 
	} 
	
	public function addNewZz() {
	     $this->addZz();
	} 
	
	
	public function updateZzd() {
	    $this->common_model->checkpurview(223);
	    $id   = intval($this->input->get_post('id',TRUE));
		$this->get_updateZzd($id);
	} 
	
	public function get_updateZzd($id) {
		$data = $this->data_model->get_invoice('a.isDelete=0 and a.transType=153301 and a.id='.$id,1);
		if (count($data)>0) {
			$postData   = unserialize($data['postData']);
			if (isset($postData['entries'])) {
				foreach ($postData['entries'] as $arr=>$row) {
					$v[$arr]['invSpec']      = $row['invSpec'];
					$v[$arr]['goods']        = $row['invNumber'].' '.$row['invName'].' '.$row['invSpec'];
					$v[$arr]['invName']      = $row['invName'];
					$v[$arr]['isSerNum']     = 0;
					$v[$arr]['amount']       = (float)$row['amount'];
					$v[$arr]['mainUnit']     = $row['mainUnit'];
					$v[$arr]['description']  = isset($row['description']) ? $row['description'] :'';
					$v[$arr]['assembler']    	  = isset($row['assembler']) ? $row['assembler'] :'';//ok 组装人显示
					$v[$arr]['version_number']    = isset($row['version_number']) ? $row['version_number'] :'';
					$v[$arr]['checker']   		  = isset($row['checker']) ? $row['checker'] :'';
					$v[$arr]['check_time']        = isset($row['check_time']) ? $row['check_time'] :'';
					$v[$arr]['fire_time']         = isset($row['fire_time']) ? $row['fire_time'] :'';
					$v[$arr]['firer'] 		      = isset($row['firer']) ? $row['firer'] :'';//组单新增结束
					//20170214 $v[$arr]['devicenumber'] 	  = isset($row['devicenumber']) ? $row['devicenumber'] :'';//小单新增结束
					$v[$arr]['id']           = $arr+1;
					$v[$arr]['qty']          = (float)$row['qty'];
					$v[$arr]['price']        = (float)$row['price'];
					$v[$arr]['amount']       = (float)$row['amount'];
					$v[$arr]['invId']        = intval($row['invId']);
					$v[$arr]['invNumber']    = $row['invNumber'];
					$v[$arr]['locationId']   = intval($row['locationId']);
					$v[$arr]['locationName'] = $row['locationName'];
					$v[$arr]['unitId']       = intval($row['unitId']);
					$v[$arr]['skuId']        = intval($row['skuId']);
					$v[$arr]['skuName']      = '';
				}
			}
			$json['status']              = 200;
			$json['msg']                 = 'success'; 
			$json['data']['id']          = intval($data['id']);
			$json['data']['buId']        = 0;
			$json['data']['contactName'] = '';
			$json['data']['amount']      = (float)$data['amount'];
			$json['data']['date']        = $data['billDate'];
			$json['data']['billNo']      = $data['billNo'];
			$json['data']['billType']    = $data['billType'];
			$json['data']['modifyTime']  = $data['modifyTime'];
			$json['data']['createTime']  = $data['createTime']; 
			$json['data']['transType']   = intval($data['transType']);
			$json['data']['totalQty']    = (float)$data['totalQty'];
			$json['data']['totalAmount'] = (float)$data['totalAmount'];
			$json['data']['userName']    = $data['userName'];
			$json['data']['description'] = $data['description']; 
			$json['data']['checked']     = intval($data['checked']); 
			$json['data']['status']      = intval($data['checked'])==1 ? 'view' : 'edit'; 
			$json['data']['entries']     = isset($v) ? $v : '';
			die(json_encode($json));
		}
		str_alert(-1,'单据不存在'); 
	} 
 
    //组装单修改
	public function updateZz() {
	    $this->common_model->checkpurview(225);
	    $postData = $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->zz_validform((array)json_decode($data, true));
			
			if ($data['id']<0) {
			   $this->addZz();
			}
			
			$info = elements(array(
				'transType','transTypeName','postData','totalQty','amount',
				'billDate','description','totalAmount','billNo','billType','modifyTime'),$data);
			$this->db->trans_begin();
			$this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			$this->zz_invoice_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误回滚'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('修改组装单 单据编号：'.$data['billNo']);
				$this->get_updateZzd($data['id']);
			}
		}
		str_alert(-1,'提交数据不为空'); 
	} 
	
	
	
	//单个审核   
	public function checkInvZz() {
	    $this->common_model->checkpurview(86);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->zz_validform((array)json_decode($data, true));
			$data['checked']         = 1;
			$data['checkName']       = $this->jxcsys['name']; 
			$this->db->trans_begin();
			//特殊情况
			if ($data['id']>0) {
			    $info = elements(array(
							'billType','transType','transTypeName','billDate','checked','checkName',
							'description','totalQty','amount','totalAmount','postData','modifyTime'),$data,NULL);
			    $this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			} else {
				$info = elements(array(
							'billNo','billType','transType','transTypeName','billDate','postData',
							'description','totalQty','amount','totalAmount','createTime','uid','userName','checked','checkName','modifyTime'),$data,NULL);
			    $data['id'] = $this->mysql_model->insert('invoice',$info);
			}
			$this->update_checkInvZz($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('组装单据编号：'.$data['billNo'].'的单据已被审核！');
				$this->get_updateZzd($data['id']);
			}
		}
		str_alert(-1,'提交的数据不能为空'); 
    }
	
	
	//批量审核  
    public function batchCheckInvZz() {
	    $this->common_model->checkpurview(86);
	    $id   = $this->input->post('id',TRUE) ? str_enhtml($this->input->post('id',TRUE)) : 0;
		$data = $this->data_model->get_invoice('a.isDelete=0 and a.transType=153301 and a.id in('.$id.')',1); 
		if (count($data)>0) {
		    foreach($data as $arr=>$row) {
			    $row['checked'] > 0 && str_alert(-1,'勾选当中已有审核，不可重复审核'); 
			    $ids[]        = $row['id'];
				$billNo[]     = $row['billNo'];
			}
			$data['id']         = $id         = join(',',$ids);
			$data['billNo']     = $billNo     = join(',',$billNo);
			$this->db->trans_begin();
			$this->mysql_model->update('invoice',array('checked'=>1,'checkName'=>$this->jxcsys['name']),'(id in('.$id.'))'); 
			$this->mysql_model->update('invoice_info',array('checked'=>1),'(billId in('.$id.'))');
			$this->update_batchCheckInvZz($id);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('组装单编号：'.$billNo.'的单据已被审核！');
				str_alert(200,'单据编号：'.$billNo.'的单据已被审核！');
			}
		}
		str_alert(-1,'审核失败'); 
	}
	
	//批量反审核
    public function rsBatchCheckInvZz() {
	    $this->common_model->checkpurview(87);
	    $id   = $this->input->post('id',TRUE) ? str_enhtml($this->input->post('id',TRUE)) : 0;
		$data = $this->data_model->get_invoice('a.isDelete=0 and a.transType=153301 and a.id in('.$id.')'); 
		if (count($data)>0) {
		    foreach($data as $arr=>$row) {
			    $row['checked'] < 1 && str_alert(-1,'勾选当中已有未审核，不可重复反审核'); 
				$ids[]        = $row['id'];
				$billNo[]     = $row['billNo'];
			}
			$data['id']       = $id         = join(',',$ids);
			$data['billNo']   = $billNo     = join(',',$billNo);
			$this->db->trans_begin();
			$this->mysql_model->update('invoice',array('checked'=>0,'checkName'=>''),'(id in('.$id.'))'); 
			$this->mysql_model->update('invoice_info',array('checked'=>0),'(billId in('.$id.'))'); 
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('组装单单号：'.$billNo.'的单据已被反审核！');
				str_alert(200,'组装单编号：'.$billNo.'的单据已被反审核！'); 
			}
		}
		str_alert(-1,'反审核失败');  
	}
	
 
	
	//打印
    public function toZzdPdf() {
	    $this->common_model->checkpurview(223);
	    $id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$entrysPerNote   = intval($this->input->post('entrysPerNote',TRUE));
		$data            = str_enhtml($this->input->post(NULL,TRUE));
		if (count($data)>0) {
		    $data['list'] = $this->data_model->get_invoice('a.isDelete=0 and a.transType=153301 and a.id in('.$id.')'); 
  
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
				$this->load->view('scm/invOi/toZzdPdf',$data);
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
		str_alert(-1,'单据不存在、或者已删除');  	
	}

	
	//删除
	public function deleteZz() {
	    $this->common_model->checkpurview(226);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$data = $this->data_model->get_invoice('a.isDelete=0 and a.transType=153301 and a.id in('.$id.')'); 
 
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
		    $this->db->trans_begin();
			$this->mysql_model->update('invoice',array('isDelete'=>1),'(id in('.$id.'))');   
			$this->mysql_model->update('invoice_info',array('isDelete'=>1),'(iid in('.$id.'))');   
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除组装单 单据编号：'.$billNo);
				str_alert(200,$msg); 	 
			}
		}
		str_alert(-1,'单据不存在');  
	}
 
	
	
	
	
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */