<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class InvSo extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->jxcsys = $this->session->userdata('jxcsys');
		$this->action = $this->input->get('action',TRUE); 
    }
	
	public function index() {
		switch ($this->action) {
			case 'initSo':
			    $this->load->view('scm/invSo/initSo');break;  
			case 'initSoList':
			    $this->load->view('scm/invSo/initSoList');break; 
			case 'list':
			    $this->soList();break;
			case 'toPdf':
			    $this->toPdf();break;
			case 'add':
			    $this->add();break;	
			case 'addNew':
			    $this->add();break;	
			case 'update':
			    $this->update();break;
			case 'delete':
			    $this->delete();break;
			case 'updateInvSo':
			    $this->updateInvSo();break;
			case 'checkInvSo':
			    $this->checkInvSo();break;
			case 'queryDetails':
			    $this->queryDetails();break;	
			case 'exportInvSo':
			    $this->exportInvSo();break;
			case 'batchCheckInvSo':
			    $this->batchCheckInvSo();break;
			case 'rsBatchCheckInvSo':
			    $this->rsBatchCheckInvSo();break;
			case 'findNearSoEmp':
			    $this->findNearSoEmp();break;
			case 'batchClose':
			    $this->batchClose();break;
			default:  
			    str_alert(-1,'非法请求'); 
		}
	}
	
	private function soList(){
	    $this->common_model->checkpurview(188);
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$sidx = str_enhtml($this->input->get_post('sidx',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$transType = intval($this->input->get_post('transType',TRUE));
		$hxState   = intval($this->input->get_post('hxState',TRUE));
		$billStatus = $this->input->get_post('billStatus',TRUE);
		$checked   = $this->input->get_post('checked',TRUE);
		$salesId   = intval($this->input->get_post('salesId',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';
		$where = 'a.isDelete=0 and a.billType="SALE"'; 
		$where .= strlen($checked)>0&&$checked==0 ? ' and a.checked=0' : ''; 
		$where .= $checked==1 ? ' and a.checked=1' : '';
		$where .= strlen($billStatus)>0 ? ' and a.billStatus in ('.$billStatus.')' : ''; 
		$where .= $transType ? ' and a.transType='.$transType : ''; 
		$where .= $salesId>0    ? ' and a.salesId='.$salesId : ''; 
		$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_admin_purview();
		$offset = $rows * ($page-1);
		$list = $this->data_model->get_order($where.' order by '.$order.' limit '.$offset.','.$rows);  
		foreach ($list as $arr=>$row) {
		    $v[$arr]['checkName']    = $row['checkName'];
			$v[$arr]['checked']      = intval($row['checked']);
			$v[$arr]['salesId']      = intval($row['salesId']);
			$v[$arr]['salesName']    = $row['salesName'];
			$v[$arr]['billDate']     = $row['billDate'];
			$v[$arr]['deliveryDate'] = $row['deliveryDate'];
			$v[$arr]['billStatus']   = intval($row['billStatus']);
			$v[$arr]['billStatusName']  = $this->common_model->get_invSoStatus($row['billStatus']);
			$v[$arr]['totalQty']     = (float)$row['totalQty'];
			$v[$arr]['id']           = intval($row['id']);
		    $v[$arr]['amount']       = (float)abs($row['amount']);
			$v[$arr]['transType']    = intval($row['transType']); 
			$v[$arr]['rpAmount']     = (float)abs($row['rpAmount']);
			$v[$arr]['contactName']  = $row['contactName'];
			$v[$arr]['description']  = $row['description'];
			$v[$arr]['billNo']       = $row['billNo'];
			$v[$arr]['totalAmount']  = (float)abs($row['totalAmount']);
			$v[$arr]['userName']     = $row['userName'];
			$v[$arr]['transTypeName']= $row['transTypeName'];
		}
		$data['status'] = 200;
		$data['msg']    = 'success'; 
		$data['data']['page']        = $page;
		$data['data']['records']   = $this->data_model->get_order($where,3);                               
		$data['data']['total']     = ceil($data['data']['records']/$rows);                                
		$data['data']['rows']      = isset($v) ? $v : array();
		die(json_encode($data));
	}
	
	//导出
	private function exportInvSo(){
	    $this->common_model->checkpurview(192);
		$name = 'sales_order_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出销售订单单据:'.$name);
		$sidx = str_enhtml($this->input->get_post('sidx',TRUE));
		$sord = str_enhtml($this->input->get_post('sord',TRUE));
		$transType = intval($this->input->get_post('transType',TRUE));
		$billStatus = $this->input->get_post('billStatus',TRUE);
		$checked   = $this->input->get_post('checked',TRUE);
		$hxState   = intval($this->input->get_post('hxState',TRUE));
		$salesId   = intval($this->input->get_post('salesId',TRUE));
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$order = $sidx ? $sidx.' '.$sord :' a.id desc';
		$where = 'a.isDelete=0 and a.billType="SALE"'; 
		$where .= strlen($checked)>0&&$checked==0 ? ' and a.checked=0' : ''; 
		$where .= $checked==1 ? ' and a.checked=1' : '';
		$where .= strlen($billStatus)>0 ? ' and a.billStatus in ('.$billStatus.')' : ''; 
		$where .= $transType ? ' and a.transType='.$transType : ''; 
		$where .= $salesId>0 ? ' and a.salesId='.$salesId : ''; 
		$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate   ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_admin_purview();
		$data['list'] = $this->data_model->get_order($where.' order by '.$order);  
		$this->load->view('scm/invSo/exportInvSo',$data);	
	}
	
	
	//新增
	private function add(){
	    $this->common_model->checkpurview(189);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
			$info = elements(array(
						'billNo','billType','transType','transTypeName','buId',
						'billDate','description','totalQty','amount','rpAmount','totalAmount',
						'hxStateCode','totalArrears','disRate','disAmount','postData',
						'salesId','uid','userName','accId','deliveryDate','modifyTime'),$data);
			$this->db->trans_begin();
			$data['id'] = $this->mysql_model->insert('order',$info);
			$this->invso_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误或者提交的是空数据'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('新增销货 单据编号：'.$data['billNo']);
				str_alert(200,'success',array('id'=>intval($data['id']))); 
			}
		}
		str_alert(-1,'提交的是空数据'); 
    }
	
	//新增
	private function addNew(){
	    $this->add();
    }
	
	//修改
	private function updateinvSo(){
	    $this->common_model->checkpurview(190);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
		    $info = elements(array(
						'billNo','billType','transType','transTypeName','buId',
						'billDate','description','totalQty','amount','rpAmount','totalAmount',
						'hxStateCode','totalArrears','disRate','disAmount','postData',
						'salesId','accId','deliveryDate','modifyTime'),$data);
			$this->db->trans_begin();
			$this->mysql_model->update('order',$info,'(id='.$data['id'].')');
			$this->invso_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误或者提交的是空数据'); 
			} else {
			    $this->db->trans_commit(); 
				$this->common_model->logs('修改销货 单据编号：'.$data['billNo']);
				str_alert(200,'success',array('id'=>$data['id'])); 
			}
		}
		str_alert(-1,'提交的数据不为空'); 
    }
	
	//获取修改信息
	private function update() {
	    $this->common_model->checkpurview();
	    $id   = intval($this->input->get_post('id',TRUE));
		$data =  $this->data_model->get_order('a.isDelete=0 and a.id='.$id.' and a.billType="SALE"',1);
		if (count($data)>0) {
			$info['status'] = 200;
			$info['msg']    = 'success'; 
			$info['data']['id']                 = intval($data['id']);
			$info['data']['buId']               = intval($data['buId']);
			$info['data']['cLevel']             = 0;
			$info['data']['deliveryDate']       = $data['deliveryDate'];
			$info['data']['contactName']        = $data['contactName'];
			$info['data']['salesId']            = intval($data['salesId']);
			$info['data']['date']               = $data['billDate'];
			$info['data']['billNo']             = $data['billNo'];
			$info['data']['billType']           = $data['billType'];
			$info['data']['transType']          = intval($data['transType']);
			$info['data']['totalQty']           = (float)$data['totalQty'];
			$info['data']['modifyTime']         = $data['modifyTime'];
			$info['data']['checkName']          = $data['checkName'];
			$info['data']['disRate']            = (float)$data['disRate'];
			$info['data']['billStatus']         = intval($data['billStatus']);
			$info['data']['disAmount']          = (float)$data['disAmount'];
			$info['data']['amount']             = (float)abs($data['amount']);
			$info['data']['rpAmount']           = (float)abs($data['rpAmount']);
			$info['data']['customerFree']       = (float)$data['customerFree'];
			$info['data']['arrears']            = (float)abs($data['arrears']);
			$info['data']['userName']           = $data['userName'];
			$info['data']['checked']            = intval($data['checked']); 
			$info['data']['status']             = intval($data['checked'])==1 ? 'view' : 'edit'; //edit
			$info['data']['totalDiscount']      = (float)$data['totalDiscount'];
			$info['data']['totalAmount']        = (float)abs($data['totalAmount']); 
			$info['data']['description']        = $data['description']; 
			$list = $this->data_model->get_order_info('a.isDelete=0 and a.iid='.$id.' order by a.id');  
			foreach ($list as $arr=>$row) {
				$v[$arr]['invSpec']           = $row['invSpec'];
				$v[$arr]['taxRate']           = (float)$row['taxRate'];
				$v[$arr]['srcOrderEntryId']   = intval($row['srcOrderEntryId']);
				$v[$arr]['srcOrderNo']        = $row['srcOrderNo'];
				$v[$arr]['srcOrderId']        = intval($row['srcOrderId']);
				$v[$arr]['goods']             = $row['invNumber'].' '.$row['invName'].' '.$row['invSpec'];
				$v[$arr]['invName']      = $row['invName'];
				$v[$arr]['qty']          = (float)abs($row['qty']);
				$v[$arr]['locationName'] = $row['locationName'];
				$v[$arr]['amount']       = (float)abs($row['amount']);
				$v[$arr]['taxAmount']    = (float)$row['taxAmount'];
				$v[$arr]['price']        = (float)$row['price'];
				$v[$arr]['tax']          = (float)$row['tax'];
				$v[$arr]['mainUnit']     = $row['mainUnit'];
				$v[$arr]['deduction']    = (float)$row['deduction'];
				$v[$arr]['invId']        = intval($row['invId']);
				$v[$arr]['invNumber']    = $row['invNumber'];
				$v[$arr]['locationId']   = intval($row['locationId']);
				$v[$arr]['locationName'] = $row['locationName'];
				$v[$arr]['discountRate'] = $row['discountRate'];
				$v[$arr]['description']  = $row['description'];
				
				$v[$arr]['unitId']       = intval($row['unitId']);
				$v[$arr]['mainUnit']     = $row['mainUnit'];
			}

			$info['data']['entries']     = isset($v) ? $v : array();
			$info['data']['accId']       = (float)$data['accId'];
			 
			$info['data']['accounts']     = array();
			die(json_encode($info));
		}
		str_alert(-1,'单据不存在、或者已删除');  
    }
	
	//获取修改信息
	private function queryDetails() {
		$id   = intval($this->input->get_post('id',TRUE));
		$data =  $this->data_model->get_order('a.isDelete=0 and a.id='.$id.' and a.billType="SALE"',1);
		if (count($data)>0) {
		    $data['billStatus'] == 2 && str_alert(400,'订单 '.$data['billNo'].' 已全部出库，不能生成销售单！');  
			$info['status']                     = 200;
			$info['msg']                        = 'success'; 
			$info['data']['buId']               = intval($data['buId']);
			$info['data']['contactName']        = $data['contactName'];
			$info['data']['billNo']             = $data['billNo'];
			$info['data']['salesId']            = intval($data['salesId']);
			$info['data']['billType']           = $data['billType'];
			$info['data']['transType']          = intval($data['transType']);
			$info['data']['billStatus']         = intval($data['billStatus']);
			$info['data']['status']             = 'edit';
			$totalQty    = 0;
			$totalAmount = 0;
			$list = $this->data_model->get_salesqueryDetails($id);  
			foreach ($list as $arr=>$row) {
			    if ($row['unQty']>0) {
				    $totalQty                       += (float)abs($row['unQty']);
					$totalAmount                    += (float)abs($row['unQty'])*$row['price'];
					$v[$arr]['srcOrderEntryId']     = $row['srcOrderEntryId'];
					$v[$arr]['srcOrderNo']          = $data['billNo'];
					$v[$arr]['srcOrderId']          = intval($id);
					$v[$arr]['invSpec']             = $row['invSpec'];
					$v[$arr]['goods']               = $row['invNumber'].' '.$row['invName'].' '.$row['invSpec'];
					$v[$arr]['invName']             = $row['invName'];
					$v[$arr]['qty']                 = (float)abs($row['unQty']);
					$v[$arr]['amount']              = (float)abs($row['unQty'])*$row['price'];
					$v[$arr]['taxAmount']           = 0;
					$v[$arr]['price']               = (float)$row['price'];
					$v[$arr]['tax']                 = $row['tax'];
					$v[$arr]['taxRate']             = $row['taxRate'];
					$v[$arr]['mainUnit']            = $row['mainUnit'];
					$v[$arr]['deduction']           = 0;
					$v[$arr]['invId']               = intval($row['invId']);
					$v[$arr]['invNumber']           = $row['invNumber'];
					$v[$arr]['locationId']          = intval($row['locationId']);
					$v[$arr]['locationName']        = $row['locationName'];
					$v[$arr]['discountRate']        = 0;
					$v[$arr]['unitId']              = intval($row['unitId']);
					$v[$arr]['description']         = $row['description'];
					$v[$arr]['skuId']               = $row['skuId'];
					$v[$arr]['skuName']             = '';
				}
			}
			$info['data']['disRate']            = (float)$data['disRate'];
			$info['data']['disAmount']          = ($data['disRate']*$totalAmount)/100;
			$info['data']['amount']             = $totalAmount-$info['data']['disAmount'];
			$info['data']['rpAmount']           = 0; 
			$info['data']['arrears']            = $info['data']['amount'];
			$info['data']['totalQty']           = $totalQty;
			$info['data']['totalTaxAmount']     = 0;
			$info['data']['totalDiscount']      = 0;
			$info['data']['totalTax']           = 0;
			$info['data']['totalAmount']        = $totalAmount;
			$info['data']['entries']            = isset($v) ? array_values($v) : array();
			$info['data']['accId']              = (float)$data['accId'];
			$info['data']['accounts']           = array();
			die(json_encode($info));
		}
		str_alert(-1,'单据不存在、或者已删除');  
    }
 
	
	//打印
	private function toPdf() {
	    $this->common_model->checkpurview(193);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$entrysPerNote   = intval($this->input->post('entrysPerNote',TRUE));
		$data            = str_enhtml($this->input->post(NULL,TRUE));
		if (count($data)>0) {
			$data['list'] = $this->data_model->get_order('a.id in ('.$id.') and a.billType="SALE"',2);  
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
				$this->load->view('scm/invSo/toPdf',$data);
				$content = ob_get_clean();
				require_once('./application/libraries/html2pdf/html2pdf.php');
				try {
					$html2pdf = new HTML2PDF('P', 'A4', 'en');
					$html2pdf->setDefaultFont('javiergb');
					$html2pdf->pdf->SetDisplayMode('fullpage');
					$html2pdf->writeHTML($content, '');
					$html2pdf->Output('invSo_'.date('ymdHis').'.pdf');
				}catch(HTML2PDF_exception $e) {
					echo $e;
					exit;
				}  	  
			}
		} 
		die('请先选择单据，再进行打印！');   
	}
 
	
	//删除 
    private function delete() {
	    $this->common_model->checkpurview(191);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$data = $this->mysql_model->get_results('order','(isDelete=0) and (id in('.$id.')) and billType="SALE"');   
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
			$this->mysql_model->get_count('invoice','(srcOrderId in('.$id.')) and isDelete=0')>0 && str_alert(-1,'有关联的销货单，不能对它进行删除！'); 
		    $this->db->trans_begin();
			$this->mysql_model->delete('order','(id in('.$id.'))');   
			$this->mysql_model->delete('order_info','(iid in('.$id.'))');
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'删除失败'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('删除销货订单编号：'.$billNo);
				str_alert(200,$msg); 	 
			}
		}
		str_alert(-1,'单据不存在、或者已删除');  
	}
	
	private function batchClose() {
	    str_alert(-1,'暂无此功能'); 
	}
	
	//批量审核
    private function batchcheckinvSo() {
	    $this->common_model->checkpurview(194);
	    $id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$data = $this->mysql_model->get_results('order','(id in('.$id.')) and billType="SALE" and checked=0 and isDelete=0');  
		if (count($data)>0) {
		    foreach($data as $arr=>$row) {
			    $row['checked'] > 0 && str_alert(-1,'勾选当中已有审核，不可重复审核'); 
			    $ids[]        = $row['id'];
				$billNo[]     = $row['billNo'];
			}
			$id         = join(',',$ids);
			$billNo     = join(',',$billNo);
		    $this->mysql_model->get_count('invoice','(srcOrderId in('.$id.')) and isDelete=0')>0 && str_alert(-1,'其中有关联的采购单，不能对它进行审核！'); 
			$sql = $this->mysql_model->update('order',array('checked'=>1,'checkName'=>$this->jxcsys['name']),'(id in('.$id.'))'); 
			if ($sql) {
				$this->common_model->logs('销货订单订单编号：'.$billNo.'的单据已被审核！');
				str_alert(200,'订单编号：'.$billNo.'的单据已被审核！');
			} 
			str_alert(-1,'审核失败');
		}
		str_alert(-1,'单据不存在！'); 
	}
	
	//批量反审核
    private function rsbatchcheckinvSo() {
	    $this->common_model->checkpurview(195);
	    $id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$data = $this->mysql_model->get_results('order','(id in('.$id.')) and billType="SALE" and (isDelete=0)');   
		if (count($data)>0) {
		    foreach($data as $arr=>$row) {
			    $row['checked'] < 1 && str_alert(-1,'勾选当中已有未审核，不可重复反审核'); 
				$ids[]        = $row['id'];
				$billNo[]     = $row['billNo'];
			}
			$id         = join(',',$ids);
			$billNo     = join(',',$billNo);
		    $this->mysql_model->get_count('invoice','(srcOrderId in('.$id.')) and isDelete=0')>0 && str_alert(-1,'有关联的销货单，不能对它进行反审核！'); 
			$sql = $this->mysql_model->update('order',array('checked'=>0,'checkName'=>''),'(id in('.$id.'))'); 
			if ($sql) {
				$this->common_model->logs('销货订单：'.$billNo.'的单据已被反审核！');
				str_alert(200,'订单编号：'.$billNo.'的单据已被反审核！'); 
			} 
			str_alert(-1,'反审核失败');  
		}
		str_alert(-1,'单据不存在！'); 
	}
	

	
	//单个审核 
	private function checkInvSo() {
	    $this->common_model->checkpurview(194);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
			$this->mysql_model->get_count('invoice',array('srcOrderId'=>$data['id'],'isDelete'=>0))>0 && str_alert(-1,'有关联的销货单，不能对它进行审核！'); 
			$data['checked']         = 1;
			$data['checkName']       = $this->jxcsys['name']; 
			$this->db->trans_begin();
			//特殊情况
			if ($data['id']>0) {
			    $info = elements(array(
						'billNo','billType','transType','transTypeName','buId','checked','checkName',
						'billDate','description','totalQty','amount','rpAmount','totalAmount',
						'hxStateCode','totalArrears','disRate','disAmount','postData',
						'salesId','accId','deliveryDate','modifyTime'),$data);
				$this->mysql_model->update('order',$info,array('id'=>$data['id']));
			} else {
			    $info = elements(array(
						'billNo','billType','transType','transTypeName','buId','checked',
						'billDate','description','totalQty','amount','rpAmount','totalAmount',
						'hxStateCode','totalArrears','disRate','disAmount','checkName','postData',
						'salesId','uid','userName','accId','deliveryDate','modifyTime'),$data,NULL);
			    $data['id'] = $this->mysql_model->insert('order',$info);   
			}
			$this->invso_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误或者提交的是空数据'); 
			} else {
			    $this->db->trans_commit(); 
				$this->common_model->logs('销货单编号：'.$data['billNo'].'的单据已被审核！');
				str_alert(200,'success',array('id'=>$data['id'])); 
			}
		}
		str_alert(-1,'审核失败'); 
    }
	
 
 
	private function findNearSoEmp() {
		die('{"status":200,"msg":"success","data":{"empId":0}}');
	}
	
	//公共验证
	private function validform($data) {
	    $data['id']              = isset($data['id']) ? intval($data['id']) : 0;
	    $data['buId']            = intval($data['buId']);
		$data['salesId']         = intval($data['salesId']);
		$data['billType']        = 'SALE';
		$data['billDate']        = $data['date'];
		$data['transType']       = intval($data['transType']);
		$data['transTypeName']   = $this->common_model->get_transType($data['transType']); 
		$data['description']     = $data['description'];
		$data['totalQty']        = (float)$data['totalQty'];
		$data['totalTax']        = isset($data['totalTax']) ? (float)$data['totalTax'] : 0;
		$data['totalTaxAmount']  = isset($data['totalTaxAmount']) ? (float)$data['totalTaxAmount'] : 0; 
		$data['amount']          = isset($data['amount']) ? (float)$data['amount'] : 0; 
		$data['totalAmount']     = isset($data['totalAmount']) ? (float)$data['totalAmount'] : 0; 
		$data['disRate']        = (float)$data['disRate'];
		$data['disAmount']      = (float)$data['disAmount'];
		$data['totalDiscount']  = (float)$data['totalDiscount'];
		$data['uid']            = $this->jxcsys['uid'];
		$data['userName']       = $this->jxcsys['name'];  
		$data['modifyTime']     = date('Y-m-d H:i:s');
		$data['accounts']        = isset($data['accounts']) ? $data['accounts'] : array();
		$data['entries']         = isset($data['entries']) ? $data['entries'] : array();
		
		
		//基础验证
		count($data['entries']) < 1 && str_alert(-1,'提交的是空数据'); 
		strlen($data['billNo']) < 1 && str_alert(-1,'单据编号不为空！'); 
		$data['disRate'] < 0  && str_alert(-1,'折扣率要为数字，请输入有效数字！'); 
		abs($data['amount']) < abs($data['disAmount']) && str_alert(-1,'折扣额不能大于合计金额！'); 
		
		if ($data['transType']==150601) {
			$data['amount']      = abs($data['amount']);
			$data['totalAmount'] = abs($data['totalAmount']);
		} else {
			$data['amount']      = -abs($data['amount']);
			$data['totalAmount'] = -abs($data['totalAmount']);
		} 
		
		//修改的时候 
		if ($data['id']>0) {
		    $invoice = $this->mysql_model->get_rows('order',array('id'=>$data['id'],'billType'=>'SALE','isDelete'=>0));  
			count($invoice)<1 && str_alert(-1,'单据不存在、或者已删除');
			$data['checked'] = $invoice['checked'];	
			$data['billNo']  = $invoice['billNo'];	
		 
		}
	 
		
		//供应商验证
		$this->mysql_model->get_count('contact','(id='.intval($data['buId']).')')<1 && str_alert(-1,'客户不存在'); 
			
		//商品录入验证
        $storage   = array_column($this->mysql_model->get_results('storage','(disable=0)'),'id');  
		foreach ($data['entries'] as $arr=>$row) {
			intval($row['invId'])<1 && str_alert(-1,'请选择商品');    
			(float)$row['qty'] < 0  && str_alert(-1,'商品数量要为数字，请输入有效数字！'); 
			(float)$row['price'] < 0  && str_alert(-1,'商品销售单价要为数字，请输入有效数字！'); 
			(float)$row['discountRate'] < 0  && str_alert(-1,'折扣率要为数字，请输入有效数字！');
			intval($row['locationId']) < 1 && str_alert(-1,'请选择相应的仓库！'); 
			!in_array($row['locationId'],$storage) && str_alert(-1,$row['locationName'].'不存在或不可用！');
		}
		$data['postData'] = serialize($data);
		return $data;
		
	}  
	
	
	
	//组装数据
	private function invso_info($data) {
		foreach ($data['entries'] as $arr=>$row) {
			$v[$arr]['iid']           = $data['id'];
			$v[$arr]['billNo']        = $data['billNo'];
			$v[$arr]['billDate']      = $data['billDate']; 
			$v[$arr]['buId']          = $data['buId'];
			$v[$arr]['transType']     = $data['transType'];
			$v[$arr]['transTypeName'] = $data['transTypeName'];
			$v[$arr]['deliveryDate']  = $data['deliveryDate'];
			$v[$arr]['billType']      = $data['billType'];
			$v[$arr]['salesId']       = $data['salesId'];
			$v[$arr]['invId']         = intval($row['invId']);
			$v[$arr]['skuId']         = intval($row['skuId']);
			$v[$arr]['unitId']        = intval($row['unitId']);
			$v[$arr]['locationId']    = intval($row['locationId']);
			$v[$arr]['qty']           = $data['transType']==150601 ? -abs($row['qty']) :abs($row['qty']); 
			$v[$arr]['amount']        = $data['transType']==150601 ? abs($row['amount']) :-abs($row['amount']); 
			$v[$arr]['price']         = abs($row['price']);  
			$v[$arr]['discountRate']  = $row['discountRate'];  
			$v[$arr]['deduction']     = $row['deduction'];  
			$v[$arr]['description']   = $row['description'];
			$v[$arr]['uid']           = $data['uid'];   
			$v[$arr]['srcOrderEntryId']  = $arr+1;  
		} 
		if (isset($v)) {
			if ($data['id']>0) {  
			    $this->mysql_model->delete('order_info','(iid='.$data['id'].')');                    
			}
			$this->mysql_model->insert('order_info',$v);
		}
	}
 
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */