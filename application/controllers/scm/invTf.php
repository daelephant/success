<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class InvTf extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->jxcsys = $this->session->userdata('jxcsys');
		$this->action = $this->input->get('action',TRUE);
    }
	
	public function index() {
		switch ($this->action) {
			case 'initTf':
			    $this->load->view('scm/invTf/initTf');break;  
			case 'initTfList':
			    $this->load->view('scm/invTf/initTfList');break; 
			case 'list':
			    $this->tfList();break;
			case 'add':
			    $this->add();break;
			case 'addNew':
			    $this->addNew();break;
			case 'updateInvTf':
			    $this->updateInvTf();break;
			case 'update':
			    $this->update();break;
			case 'toPdf':
			    $this->toPdf();break;
			case 'delete':
			    $this->delete();break;
			case 'exportInvTf':
			    $this->exportInvTf();break;
			case 'checkInvTf':
			    $this->checkInvTf();break;
			case 'batchCheckInvTf':
			    $this->batchCheckInvSa();break;
			case 'rsBatchCheckInvTf':
			    $this->rsBatchCheckInvTf();break;
			default: 
			    str_alert(-1,'非法请求');
		}
	}
	
	
		//单个审核   
	private function checkInvTf() {
	    $this->common_model->checkpurview(196);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
			$data['checked']         = 1;
			$data['checkName']       = $this->jxcsys['name']; 
			$this->db->trans_begin();
			//特殊情况
			if ($data['id']>0) {
			    $info = elements(array(
							'billType','transType','transTypeName','billDate',
							'postData','inLocationId','outLocationId','checked','checkName',
							'description','totalQty','modifyTime'),$data,NULL);
				$this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			} else {
			    $info = elements(array(
							'billNo','billType','transType','transTypeName','createTime',
							'billDate','postData','inLocationId','outLocationId','checked','checkName',
							'description','totalQty','uid','userName','modifyTime'),$data,NULL);
			    $data['id'] = $this->mysql_model->insert('invoice',$info); 
			}
			$this->invoice_info($data);
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
    private function rsBatchCheckInvTf() {
	    $this->common_model->checkpurview(197);
	    $id   = $this->input->post('id',TRUE) ? str_enhtml($this->input->post('id',TRUE)) : 0;
		$data = $this->data_model->get_invoice('a.id in ('.$id.') and a.transType=103091',2);  
 
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
	
	//调拨单列表
	private function tfList(){
	    $this->common_model->checkpurview(144); 
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100);
		$matchCon     = str_replace('请输入单据号或客户名或备注','',str_enhtml($this->input->get_post('matchCon',TRUE)));
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$inLocationId    = intval($this->input->get_post('inLocationId',TRUE));
		$outLocationId   = intval($this->input->get_post('outLocationId',TRUE)); 
		$where = 'a.isDelete=0 and a.transType=103091'; 
		$where .= $matchCon  ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $outLocationId>0 ? ' and find_in_set('.$outLocationId.',a.outLocationId)' :'';
		$where .= $inLocationId>0 ? ' and find_in_set('.$inLocationId.',a.inLocationId)' :'';
		$where .= $this->common_model->get_admin_purview();
		$list = $this->data_model->get_invoice($where.' order by a.id desc limit '.$rows*($page-1).','.$rows);  
		foreach ($list as $arr=>$row) {
		    $postData = unserialize($row['postData']);
		    foreach ($postData['entries'] as $arr1=>$row1) {
				$qty[$row['id']][]             = abs($row1['qty']);
				$mainUnit[$row['id']][]        = $row1['mainUnit'];
				$goods[$row['id']][]           = $row1['invNumber'].' '.$row1['invName'].' '.$row1['invSpec'];
				$inLocationName[$row['id']][]  = $row1['inLocationName'];
				$outLocationName[$row['id']][] = $row1['outLocationName'];
			}
		    $v[$arr]['id']                 = intval($row['id']);
			$v[$arr]['billDate']           = $row['billDate'];
			$v[$arr]['qty']                = $qty[$row['id']];
			$v[$arr]['goods']              = $goods[$row['id']];
			$v[$arr]['mainUnit']           = $mainUnit[$row['id']];
			$v[$arr]['description']        = $row['description'];
			$v[$arr]['billNo']             = $row['billNo'];
			$v[$arr]['userName']           = $row['userName']; 
			$v[$arr]['checkName']          = $row['checkName'];
			$v[$arr]['checked']            = intval($row['checked']);
			$v[$arr]['outLocationName']    = $outLocationName[$row['id']];
			$v[$arr]['inLocationName']     = $inLocationName[$row['id']];
		}
		$json['status'] = 200;
		$json['msg']    = 'success'; 
		$json['data']['page']      = $page;
		$json['data']['records']   = $this->data_model->get_invoice($where,3);   
		$json['data']['total']     = ceil($json['data']['records']/$rows);      
		$json['data']['rows']      = isset($v) ? $v : array();
		die(json_encode($json));
	}
	
	//导出
	private function exportInvTf(){
	    $this->common_model->checkpurview(148);
		$name = 'db_record_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出调拨单据:'.$name);
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$inLocationId    = intval($this->input->get_post('inLocationId',TRUE));
		$outLocationId   = intval($this->input->get_post('outLocationId',TRUE)); 
		$where = 'a.isDelete=0 and a.transType=103091'; 
		$where .= $matchCon     ? ' and a.postData like "%'.$matchCon.'%"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $outLocationId>0 ? ' and find_in_set('.$outLocationId.',a.outLocationId)' :'';
		$where .= $inLocationId>0 ? ' and find_in_set('.$inLocationId.',a.inLocationId)' :'';
		$where .= $this->common_model->get_admin_purview();
		$data['list'] = $this->data_model->get_invoice($where.' order by a.id desc');  
		$this->load->view('scm/invTf/exportInvTf',$data);	
	}
	
 
    //新增
	private function add(){
	    $this->common_model->checkpurview(145);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
			$data = $this->validform((array)json_decode($data, true));
			$info = elements(array(
						'billNo','billType','transType','transTypeName','createTime',
						'billDate','postData','inLocationId','outLocationId',
						'description','totalQty','uid','userName','modifyTime'),$data,NULL);
			$this->db->trans_begin();
			$data['id'] = $this->mysql_model->insert('invoice',$info);
			$this->invoice_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('新增调拨单编号：'.$info['billNo']);
				str_alert(200,'success',array('id'=>intval($data['id']))); 
			}
		}
		str_alert(-1,'提交的是空数据'); 
    }
	
	//新增
	private function addNew(){
	    $this->add();
    }
	
	
	//信息
	private function update() {
	    $this->common_model->checkpurview(144);
	    $id   = intval($this->input->get_post('id',TRUE));
		$data = $this->mysql_model->get_rows('invoice',array('id'=>$id,'transType'=>103091,'isDelete'=>0));
		if (count($data)>0) {
			$postData = unserialize($data['postData']);
		    foreach ($postData['entries'] as $arr=>$row) {
				$v[$arr]['invId']           = intval($row['invId']);
				$v[$arr]['invNumber']       = $row['invNumber'];
				$v[$arr]['invSpec']         = $row['invSpec'];
				$v[$arr]['invName']         = $row['invName'];
				$v[$arr]['goods']           = $row['invNumber'].' '.$row['invName'].' '.$row['invSpec'];
				$v[$arr]['qty']             = (float)abs($row['qty']);
				$v[$arr]['mainUnit']        = $row['mainUnit'];
				$v[$arr]['unitId']          = intval($row['unitId']);
				$v[$arr]['inLocationId']    = $row['inLocationId'];
				$v[$arr]['inLocationName']  = $row['inLocationName'];
				$v[$arr]['outLocationId']   = $row['outLocationId'];
				$v[$arr]['outLocationName'] = $row['outLocationName'];
			}
			$json['status']                 = 200;
			$json['msg']                    = 'success'; 
			$json['data']['id']             = intval($data['id']);
			$json['data']['date']           = $data['billDate'];
			$json['data']['billNo']         = $data['billNo'];
			$json['data']['totalQty']       = (float)$data['totalQty']; 
			$json['data']['description']    = $data['description'];
			$json['data']['userName']       = $data['userName']; 
			$json['data']['status']         = intval($data['checked'])==1 ? 'view' : 'edit'; 
			$json['data']['checked']        = intval($data['checked']); 
			$json['data']['checkName']      = $data['checkName']; 
			$json['data']['createTime']     = $data['createTime']; 
			$json['data']['modifyTime']     = $data['modifyTime']; 
			$json['data']['description']    = $data['description']; 
			$json['data']['entries']        = isset($v) ? $v : array();
			die(json_encode($json));
		}
		str_alert(-1,'单据不存在'); 
    }
	
	
	//修改
	private function updateInvTf(){
	    $this->common_model->checkpurview(146);
	    $data = $this->input->post('postData',TRUE);
		if (strlen($data)>0) {
		    $data = $this->validform((array)json_decode($data, true));
			$info = elements(array(
						'billType','transType','transTypeName','billDate',
						'postData','inLocationId','outLocationId', 
						'description','totalQty','modifyTime'),$data,NULL);
			$this->db->trans_begin();
			$this->mysql_model->update('invoice',$info,array('id'=>$data['id']));
			$this->invoice_info($data);
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				str_alert(-1,'SQL错误'); 
			} else {
			    $this->db->trans_commit();
				$this->common_model->logs('修改调拨单编号：'.$data['billNo']);
				str_alert(200,'success',array('id'=>$data['id'])); 
			}
		}
		str_alert(-1,'参数错误'); 
    }
	
 
	//打印
	private function toPdf() {
	    $this->common_model->checkpurview(179);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$data = str_enhtml($this->input->post(NULL,TRUE));
		if (count($data)>0) {
			$data['list'] = $this->data_model->get_invoice('a.id in ('.$id.') and a.transType=103091',2);  
			if (count($data['list'])>0) { 
			    if ($this->input->cookie('entrysPerNote')>0) {
					$data['num'] = $this->input->cookie('entrysPerNote');
				} else {
				    $data['num'] = 20;
				}
				$data['system']  = $this->common_model->get_option('system'); 
				ob_start();
				$this->load->view('scm/invTf/toPdf',$data);
				$content = ob_get_clean();
				require_once('./application/libraries/html2pdf/html2pdf.php');
				try {
					$html2pdf = new HTML2PDF('P', 'A4', 'en');
					$html2pdf->setDefaultFont('javiergb');
					$html2pdf->pdf->SetDisplayMode('fullpage');
					$html2pdf->writeHTML($content, '');
					$html2pdf->Output('invTf_'.date('ymdHis').'.pdf');
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
	    $this->common_model->checkpurview(147);
		$id   = $this->input->get_post('id',TRUE) ? str_enhtml($this->input->get_post('id',TRUE)) : 0;
		$data = $this->mysql_model->get_results('invoice','(isDelete=0) and (id in('.$id.')) and transType=103091');  
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
				$this->common_model->logs('删除调拨单 单据编号：'.$billNo);
				str_alert(200,$msg); 	 
			}
		}
		str_alert(-1,'该单据不存在'); 
	}
	
	//公共验证
	private function validform($data) {
	    $data['id']              = isset($data['id']) ? intval($data['id']) : 0;
		$data['totalQty']        = (float)$data['totalQty']; 
		$data['billType']        = 'TRANSFER';
		$data['transType']       = 103091;
		$data['transTypeName']   = $this->common_model->get_transType($data['transType']); 
		$data['billDate']        = $data['date'];
		$data['description']     = $data['description'];
		$data['uid']             = $this->jxcsys['uid'];
		$data['userName']        = $this->jxcsys['name'];
		$data['modifyTime']      = date('Y-m-d H:i:s');
		$data['createTime']      = $data['modifyTime'];
		$data['accounts']        = isset($data['accounts']) ? $data['accounts'] : array();
		$data['entries']         = isset($data['entries']) ? $data['entries'] : array();
		
		count($data['entries']) < 1 && str_alert(-1,'提交的是空数据'); 
		strlen($data['billNo']) < 1 && str_alert(-1,'编号不能为空'); 
		
		if ($data['id']>0) {
		    $invoice = $this->mysql_model->get_rows('invoice',array('id'=>$data['id'],'transType'=>103091,'isDelete'=>0));  
			count($invoice)<1 && str_alert(-1,'单据不存在、或者已删除');
			$data['checked'] = $invoice['checked'];	
			$data['billNo']  = $invoice['billNo'];	
		 
		}
		
		
		 
		
	    //商品录入验证
		$system    = $this->common_model->get_option('system'); 
		if ($system['requiredCheckStore']==1) {
		    foreach ($data['entries'] as $val) {
				$invId[] = $val['invId'];
				$key = $val['invId'].'_'.$val['outLocationId'];
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
		//$data['entries'] = array_listsum($data['entries'],'invId','locationId','qty');  
		foreach ($data['entries'] as $arr=>$row) {
			(float)$row['qty'] < 0 && str_alert(-1,'商品数量要为数字，请输入有效数字！'); 
			intval($row['outLocationId']) < 1 && str_alert(-1,'请选择调出仓库仓库！'); 
			intval($row['inLocationId']) < 1  && str_alert(-1,'请选择调入仓库仓库！'); 
			intval($row['outLocationId']) == intval($row['inLocationId']) && str_alert(-1,'调出仓库不能与调入仓库相同！'); 
			!in_array($row['outLocationId'],$storage) && str_alert(-1,$row['outLocationName'].'不存在或不可用！');
			!in_array($row['inLocationId'],$storage) && str_alert(-1,$row['inLocationName'].'不存在或不可用！');
				
			//库存判断 修改不验证
			if ($system['requiredCheckStore']==1 && $data['id']<1) {  
				if (isset($inventory[$row['invId']][$row['outLocationId']])) {
					$inventory[$row['invId']][$row['outLocationId']] < (float)$row['qty'] && str_alert(-1,$row['outLocationName'].$row['invName'].'商品库存不足！'); 
				} else {
					str_alert(-1,$row['invName'].'库存不足！');
				}
			}
			$inLocationId[]  = $row['inLocationId'];
			$outLocationId[] = $row['outLocationId'];
		} 
		$data['inLocationId']  = join(',',array_unique($inLocationId));
		$data['outLocationId'] = join(',',array_unique($outLocationId));
		$data['postData'] = serialize($data);
		return $data;	
	} 
	
	//组装数据
	private function invoice_info($data) {
		$profit = $this->data_model->get_profit('and billDate<="'.date('Y-m-d').'"');    
		foreach ($data['entries'] as $arr=>$row) {
		    $price = isset($profit['inprice'][$row['invId']][$row['outLocationId']]) ? $profit['inprice'][$row['invId']][$row['outLocationId']] : 0;  
			$s[$arr]['iid']             = $v[$arr]['iid']             = $data['id'];
			$s[$arr]['uid']             = $v[$arr]['uid']             = $data['uid'];
			$s[$arr]['billNo']          = $v[$arr]['billNo']          = $data['billNo'];
			$s[$arr]['billDate']        = $v[$arr]['billDate']        = $data['billDate'];
			$s[$arr]['invId']           = $v[$arr]['invId']           = intval($row['invId']);
			$s[$arr]['skuId']           = $v[$arr]['skuId']           = intval($row['skuId']);
			$s[$arr]['unitId']          = $v[$arr]['unitId']          = intval($row['unitId']);
			$s[$arr]['billType']        = $v[$arr]['billType']        = $data['billType'];
			$s[$arr]['description']     = $v[$arr]['description']     = $row['description'];  
			$s[$arr]['transTypeName']   = $v[$arr]['transTypeName']   = $data['transTypeName'];
            $s[$arr]['transType']       = $v[$arr]['transType']       = $data['transType'];
			$v[$arr]['locationId']      = intval($row['inLocationId']);
			$v[$arr]['qty']             = abs($row['qty']); 
			$v[$arr]['price']           = $price; 
			$v[$arr]['amount']          = abs($row['qty']) * $price;
			$v[$arr]['entryId']         = 1;
			$s[$arr]['locationId']      = intval($row['outLocationId']);
			$s[$arr]['qty']             = -abs($row['qty']); 
			$s[$arr]['price']           = $price;  
			$s[$arr]['amount']          = -abs($row['qty']) * $price;
			$s[$arr]['entryId']         = 2;
		} 
		if (isset($s) && isset($v)) {
		    if ($data['id']>0) {      
				$this->mysql_model->delete('invoice_info',array('iid'=>$data['id']));
			}
			$this->mysql_model->insert('invoice_info',$v);
			$this->mysql_model->insert('invoice_info',$s);
		}
	}
	 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */