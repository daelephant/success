<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->jxcsys  = $this->session->userdata('jxcsys');
		$this->systems = $this->common_model->get_option('system');  
		$this->action  = $this->input->get('action',TRUE);
    }
	
	public function index() {
	    //库存总量 成本
		$inventory1  = $inventory2 = 0;
		$list   = $this->data_model->get_invBalance('a.isDelete=0 and a.billDate<="'.date('Y-m-d').'" '.$this->common_model->get_location_purview().' group by a.invId'); 
		foreach ($list as $arr=>$row) {
		    $inprice  = $row['inqty']>0 ? $row['inamount']/$row['inqty'] : 0;
			$amount   = $row['qty'] * $inprice;
			$inventory1 += $row['qty']; 
			$inventory2 += $amount;
		}
		$inventory1 = round($inventory1,$this->systems['qtyPlaces']); 
		$inventory2 = round($inventory2,2); 
	
	    //现金、银行存款
		$fund1 = $fund2 = 0;
	    $list = $this->data_model->get_account('a.isDelete=0 '.$this->common_model->get_admin_purview());
		foreach ($list as $arr=>$row) {
		    if ($row['type']==1) {
			    $fund1 += $row['amountOver'];
			} else {
			    $fund2 += $row['amountOver'];
			} 
		}
		$fund1 = round($fund1,2); 
		$fund2 = round($fund2,2); 
		
		//客户欠款
		$contact1 = $contact2 = 0;
		$list = $this->data_model->get_contact('a.isDelete=0 '.$this->common_model->get_contact_purview());
		foreach ($list as $arr=>$row) {
		    if ($row['type']==-10) {
			    $contact1 += $row['arrears'];    //供应商
			} elseif ($row['type']==10) {
			    $contact2 += $row['arrears'];    //客户 
			} else {	
			    $contact1 = 0; 
		        $contact2 = 0; 
			}
		}
		$contact1 = round($contact1,2); 
		$contact2 = round($contact2,2); 

		//采购金额
		$list = $this->data_model->get_invoice_infosum('a.isDelete=0 and a.billType="PUR" and billDate>="'.date('Y-m-1').'" and billDate<="'.date('Y-m-d').'" '.$this->common_model->get_location_purview().' group by a.invId');
		$purchase1 = 0;
		foreach ($list as $arr=>$row) {
			$purchase1 += $row['sumamount']; 
		}
		$purchase2 = count($list);
		$purchase1 = round($purchase1,2); 
		$purchase2 = round($purchase2,$this->systems['qtyPlaces']); 
		
		
		//销售收入 
		//单位成本
	    $profit = $this->data_model->get_profit('and billDate<="'.date('Y-m-d').'"'); 
		$sales1 = $sales2 = 0;   
		$list   = $this->data_model->get_invoice_infosum('a.isDelete=0 and billType="SALE" and billDate>="'.date('Y-m-1').'" and billDate<="'.date('Y-m-d').'" '.$this->common_model->get_location_purview().' group by a.invId,locationId'); 
		foreach ($list as $arr=>$row) {
		    $qty = $row['sumqty']>0 ? -abs($row['sumqty']) : abs($row['sumqty']);    
			$amount = $row['sumamount'];                  //销售收入
			$price = isset($profit['inprice'][$row['invId']][$row['locationId']]) ? $profit['inprice'][$row['invId']][$row['locationId']] : 0;
			$cost = $price * $qty;                        //销售成本
			$saleProfit = $amount - $cost;                //销售毛利
			$sales1 += $amount;                           //销售收入
			$sales2 += $saleProfit;                       //销售毛利
		}
		$sales1 = round($sales1,2); 
		$sales2 = round($sales2,2); 
		$data['status'] = 200;
		$data['msg']    = 'success';
		$data['data']['items'] =  array(
									array('mod'=>'inventory','total1'=>$inventory1,'total2'=>$inventory2),
									array('mod'=>'fund','total1'=>$fund1,'total2'=>$fund2),
									array('mod'=>'contact','total1'=>$contact1,'total2'=>$contact2),
									array('mod'=>'sales','total1'=>$sales1,'total2'=>$sales2),
									array('mod'=>'purchase','total1'=>$purchase1,'total2'=>$purchase2)
		);
		$data['totalsize'] = 5;
		die(json_encode($data));
	}
	
	//采购订单跟踪表 
	public function pu_order_tracking() {
	    $this->common_model->checkpurview(22);
		$data['beginDate']  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = str_enhtml($this->input->get_post('endDate',TRUE));
		$this->load->view('report/pu-order-tracking',$data);	
	}
	
	public function purchaseOrder() {
	    switch ($this->action) {
			case 'detail':
			    $this->purchaseOrder_detail();break;  
			case 'detailExporter':
			    $this->purchaseOrder_detailExporter();break; 
			default:  
			    $this->purchaseOrder_detail();	
		}
	}
	
	
	public function purchaseOrder_detail() { 
        $this->common_model->checkpurview(210);
		$sum1 = $sum2 = $sum3 = 0;
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$categoryId         = str_enhtml($this->input->get_post('categoryId',TRUE));
		$fromDeliveryDate   = str_enhtml($this->input->get_post('fromDeliveryDate',TRUE));
		$endDeliveryDate    = str_enhtml($this->input->get_post('endDeliveryDate',TRUE));
		$status             = str_enhtml($this->input->get_post('status',TRUE));
		$billNo             = str_enhtml($this->input->get_post('billNo',TRUE));
		$beginDeliveryDate  = str_enhtml($this->input->get_post('beginDeliveryDate',TRUE));
		$toDeliveryDate     = str_enhtml($this->input->get_post('toDeliveryDate',TRUE));
		
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		
		$where = $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $billNo ? ' and a.billNo="'.$billNo.'"' : ''; 
		$where .= $categoryId>0 ? ' and c.cCategory='.$categoryId.'' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		
		$where .= $fromDeliveryDate ? ' and a.deliveryDate>="'.$fromDeliveryDate.'"' : ''; 
		$where .= $endDeliveryDate ? ' and a.deliveryDate<="'.$endDeliveryDate.'"' : ''; 
		$where .= $status=='0' ? ' and a.status=0' : ''; 
		$where .= $status=='1' ? ' and a.status=1' : ''; 
		$where .= $status=='2' ? ' and a.status=2' : ''; 
		$where .= strlen($status)>1 ? ' and a.status in('.$status.')' : ''; 
		
		$where .= $this->common_model->get_admin_purview();
		$where .= $this->common_model->get_location_purview();
		$list   = $this->data_model->get_purchaseOrder($where); 
		foreach ($list as $arr=>$row) {
		    $v[$arr]['spec']          = $row['spec']; 
			$v[$arr]['status']        = $row['status']; 
			$v[$arr]['inDate']        = $row['inDate']; 
			$v[$arr]['invName']       = $row['invName'];   
			$v[$arr]['qty']           = $row['qty']; 
			$v[$arr]['date']          = $row['date']; 
			$v[$arr]['deleted']       = ''; 
			$v[$arr]['invNo']         = $row['invNo'];  
			$v[$arr]['amount']        = $row['amount'];  
			$v[$arr]['unit']          = $row['unit']; 
			$v[$arr]['fdesc']         = '';
			$v[$arr]['billNo']        = $row['billNo'];
			$v[$arr]['unQty']         = $row['unQty']; 
			$v[$arr]['billId']        = intval($row['billId']);
			$v[$arr]['deliveryDate']  = $row['deliveryDate']; 
			$v[$arr]['buName']        = $row['buName'];
			$v[$arr]['description']   = $row['description']; 
			if (strlen($row['amount'])>0) {
				$sum1 += $row['unQty'];  
				$sum2 += $row['qty'];
				$sum3 += $row['amount']; 
			}
			if ($arr+1==count($list)) {
			    $v[$arr+1]['spec']          = ''; 
				$v[$arr+1]['status']        = '合计';
				$v[$arr+1]['inDate']        = ''; 
				$v[$arr+1]['invName']       = '';   
				$v[$arr+1]['qty']           = round($sum2,2);
				$v[$arr+1]['date']          = ''; 
				$v[$arr+1]['deleted']       = ''; 
				$v[$arr+1]['invNo']         = '';  
				$v[$arr+1]['amount']        = round($sum3,2); 
				$v[$arr+1]['unit']          = ''; 
				$v[$arr+1]['fdesc']         = '';
				$v[$arr+1]['billNo']        = '';
				$v[$arr+1]['unQty']         = round($sum1,2); 
				$v[$arr+1]['billId']        = '';
				$v[$arr+1]['deliveryDate']  = ''; 
				$v[$arr+1]['buName']        = '';
				$v[$arr+1]['description']   = ''; 
			} 
		}
		$data['status']                          = 200;
		$data['msg']                             = 'success';
		$data['data']['rows']                    = isset($v) ? $v :array();
		$data['data']['userdata']['invNo']       = '';
		$data['data']['userdata']['invName']     = '';
		$data['data']['userdata']['spec']        = '';
		$data['data']['userdata']['unit']        = '';
		$data['data']['userdata']['date']        = '';
		$data['data']['userdata']['billNo']      = '';
		$data['data']['userdata']['buName']      = '';
		$data['data']['userdata']['status']      = '合计';
		$data['data']['userdata']['deliveryDate']= '';
		$data['data']['userdata']['inDate']      = '';
		$data['data']['userdata']['qty']         = $sum2;
		$data['data']['userdata']['amount']      = $sum3;
		$data['data']['userdata']['unQty']       = $sum1;
		die(json_encode($data));  	
	}
	
	public function purchaseOrder_detailExporter() {
	    $this->common_model->checkpurview(211);
		$name = 'purchase_order_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('采购订单跟踪表导出:'.$name);
		$categoryId         = str_enhtml($this->input->get_post('categoryId',TRUE));
		$fromDeliveryDate   = str_enhtml($this->input->get_post('fromDeliveryDate',TRUE));
		$endDeliveryDate    = str_enhtml($this->input->get_post('endDeliveryDate',TRUE));
		$status             = str_enhtml($this->input->get_post('status',TRUE));
		$billNo             = str_enhtml($this->input->get_post('billNo',TRUE));
		$beginDeliveryDate  = str_enhtml($this->input->get_post('beginDeliveryDate',TRUE));
		$toDeliveryDate     = str_enhtml($this->input->get_post('toDeliveryDate',TRUE));
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$data['beginDate']  = $beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $billNo ? ' and a.billNo="'.$billNo.'"' : ''; 
		$where .= $categoryId>0 ? ' and c.cCategory='.$categoryId.'' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $fromDeliveryDate ? ' and a.deliveryDate>="'.$fromDeliveryDate.'"' : ''; 
		$where .= $endDeliveryDate ? ' and a.deliveryDate<="'.$endDeliveryDate.'"' : ''; 
		
		$where .= strlen($status)>1 ? ' and a.status in('.$status.')' : ''; 
		
		$where .= $this->common_model->get_admin_purview();
		$where .= $this->common_model->get_location_purview();
		$data['list'] =  $this->data_model->get_purchaseOrder($where); 
		$this->load->view('report/purchaseOrder-detailExporter',$data);	     	
	}

    
	//采购明细表  
	public function pu_detail_new() {
	    $this->common_model->checkpurview(22);
		$data['beginDate']  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = str_enhtml($this->input->get_post('endDate',TRUE));
		$this->load->view('report/pu-detail-new',$data);	
	}
	
	public function puDetail() {
	    switch ($this->action) {
			case 'detail':
			    $this->puDetail_detail();break;  
			case 'detailExporter':
			    $this->puDetail_detailExporter();break; 
			case 'inv':
			    $this->puDetail_inv();break;  
			case 'invExporter':
			    $this->puDetail_invExporter();break; 
			case 'supply':
			    $this->puDetail_supply();break;  
			case 'supplyExporter':
			    $this->puDetail_supplyExporter();break; 	
			default:  
			    $this->puDetail_detail();	
		}
	}
	
	//采购明细表 (接口)
	public function puDetail_detail() {
	    $this->common_model->checkpurview(22);
		$sum1 = $sum2 = $sum3 = 0;
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$categoryId = intval($this->input->get_post('categoryId',TRUE));
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and a.billType="PUR"';
		$where .= $categoryId>0 ? ' and c.cCategory='.$categoryId.'' : ''; 
		$where .= $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_admin_purview();
		$where .= $this->common_model->get_location_purview();
		$list   = $this->data_model->get_invoice_info($where.' order by a.billDate,a.id'); 
		foreach ($list as $arr=>$row) {
			$v[$arr]['billId']        = intval($row['iid']);
		    $v[$arr]['billNo']        = $row['billNo'];
			$v[$arr]['billType']      = $row['billType']; 
			$v[$arr]['date']          = $row['billDate']; 
			$v[$arr]['buId']          = intval($row['buId']);
			$v[$arr]['buName']        = $row['contactName'];
			$v[$arr]['invNo']         = $row['invNumber'];  
			$v[$arr]['invName']       = $row['invName'];   
			$v[$arr]['spec']          = $row['invSpec']; 
			$v[$arr]['unit']          = $row['mainUnit']; 
			$v[$arr]['location']      = $row['locationName']; 
			$v[$arr]['description']   = $row['description']; 
			$v[$arr]['baseQty']       = 0; 
			$v[$arr]['skuId']         = 0; 
			$v[$arr]['cost']          = 0;
			$v[$arr]['unitCost']      = 0;
			$v[$arr]['transType']     = $row['transTypeName'];
			$sum1 += $v[$arr]['qty']        = (float)$row['qty']; 
			$sum2 += $v[$arr]['unitPrice']  = (float)$row['price'];
		    $sum3 += $v[$arr]['amount']     = (float)$row['amount']; 
		}
		$data['status'] = 200;
		$data['msg']    = 'success';
		$data['data']['list']      = isset($v) ? $v :array();
		$data['data']['total']['amount']      = '';
		$data['data']['total']['baseQty']     = 'PUR';
		$data['data']['total']['billId']      = '';
		$data['data']['total']['billNo']      = '';
		$data['data']['total']['billType']    = '';
		$data['data']['total']['buName']      = '';
		$data['data']['total']['buNo']        = '';
		$data['data']['total']['date']        = '';
		$data['data']['total']['invName']     = '';
		$data['data']['total']['location']    = '';
		$data['data']['total']['locationNo']  = '';
		$data['data']['total']['spec']        = '';
		$data['data']['total']['unit']        = '';
		$data['data']['total']['transType']   = '';
		$data['data']['total']['skuId']       = '';
		$data['data']['total']['qty']         = $sum1;
		$data['data']['total']['unitPrice']   = $sum1>0 ? $sum3/$sum1 : 0;
		$data['data']['total']['amount']      = $sum3;
		$data['data']['total']['cost']        = '';
		$data['data']['total']['unitCost']    = '';
		die(json_encode($data));
	}

	//采购明细表(导出明细)
	public function puDetail_detailExporter() {
	    $this->common_model->checkpurview(23);
		$name = 'pu_detail_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('采购明细表导出:'.$name);
		$categoryId = intval($this->input->get_post('categoryId',TRUE));
		$storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$data['beginDate']  = $beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and a.billType="PUR"';
		$where .= $categoryId>0 ? ' and c.cCategory='.$categoryId.'' : ''; 
		$where .= $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_admin_purview();
		$where .= $this->common_model->get_location_purview();
		$data['list'] = $this->data_model->get_invoice_info($where.' order by a.billDate,a.id'); 
		$this->load->view('report/puDetail-detailExporter',$data);	
	}
	
	//采购汇总表（按商品）
	public function pu_summary_new() {
	    $this->common_model->checkpurview(25);
		$data['beginDate']  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = str_enhtml($this->input->get_post('endDate',TRUE));
		$this->load->view('report/pu-summary-new',$data);	
	}
	
	 
	
	
	//采购汇总表（按商品接口）
	public function puDetail_inv() {
	    $this->common_model->checkpurview(25);
		$sum1 = $sum2 = $sum3 = 0;
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$catId      = intval($this->input->get_post('catId',TRUE));
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and a.billType="PUR"';
		if ($catId > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$catId.',path)'),'id'); 
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and b.categoryId in('.$cid.')';
			} 
		}  
		$where .= $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_location_purview();
		$list   = $this->data_model->get_invoice_infosum($where.' group by a.invId, a.locationId'); 
		foreach ($list as $arr=>$row) {
			$v[$arr]['billId']        = intval($row['iid']);
		    $v[$arr]['billNo']        = $row['billNo'];
			$v[$arr]['billType']      = $row['billType']; 
			$v[$arr]['date']          = $row['billDate']; 
			$v[$arr]['buId']          = intval($row['buId']);
			$v[$arr]['buName']        = $row['contactName'];
			$v[$arr]['invNo']         = $row['invNumber'];  
			$v[$arr]['invName']       = $row['invName'];   
			$v[$arr]['spec']          = $row['invSpec']; 
			$v[$arr]['unit']          = $row['mainUnit']; 
			$v[$arr]['location']      = $row['locationName']; 
			$v[$arr]['assistName']    = $row['categoryName']; 
			$v[$arr]['baseQty']       = 0; 
			$v[$arr]['skuId']         = 0; 
			$v[$arr]['cost']          = 0;
			$v[$arr]['unitCost']      = 0;
			$v[$arr]['transType']     = $row['transTypeName'];
			$sum1 += $v[$arr]['qty']        = (float)$row['sumqty']; 
			$sum2 += $v[$arr]['unitPrice']  = (float)$row['sumqty']!=0 ? (float)abs($row['sumamount']/$row['sumqty']) : 0;
		    $sum3 += $v[$arr]['amount']     = (float)$row['sumamount']; 
		}
		$data['status'] = 200;
		$data['msg']    = 'success';
		$data['data']['list']      = isset($v) ? $v :array();
		$data['data']['total']['amount']      = '';
		$data['data']['total']['baseQty']     = 'PUR';
		$data['data']['total']['billId']      = '';
		$data['data']['total']['billNo']      = '';
		$data['data']['total']['billType']    = '';
		$data['data']['total']['buName']      = '';
		$data['data']['total']['buNo']        = '';
		$data['data']['total']['date']        = '';
		$data['data']['total']['invName']     = '';
		$data['data']['total']['location']    = '';
		$data['data']['total']['locationNo']  = '';
		$data['data']['total']['spec']        = '';
		$data['data']['total']['unit']        = '';
		$data['data']['total']['transType']   = '';
		$data['data']['total']['skuId']       = '';
		$data['data']['total']['qty']         = $sum1;
		$data['data']['total']['unitPrice']   = $sum1!=0 ? abs($sum3/$sum1) : 0;
		$data['data']['total']['amount']      = $sum3;
		$data['data']['total']['cost']        = '';
		$data['data']['total']['unitCost']    = '';
		die(json_encode($data)); 
	}
	
	//采购明细表(导出明细)
	public function puDetail_invExporter() {
	    $this->common_model->checkpurview(26);
		$name = 'pu_summary_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('采购明细表(按商品)导出:'.$name);
		$catId      = intval($this->input->get_post('catId',TRUE));
		$storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$data['beginDate']  = $beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and a.billType="PUR"';
		if ($catId > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$catId.',path)'),'id'); 
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and b.categoryId in('.$cid.')';
			} 
		}  
		$where .= $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_location_purview();
		$data['list'] = $this->data_model->get_invoice_infosum($where.' group by a.invId, a.locationId'); 
		$this->load->view('report/puDetail-invExporter',$data);	
	}
	
	//采购汇总表（按供应商）
	public function pu_summary_supply_new() {
	    $this->common_model->checkpurview(28);
		$data['beginDate']  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = str_enhtml($this->input->get_post('endDate',TRUE));
		$this->load->view('report/pu-summary-supply-new',$data);	
	}
	
	
	//采购汇总表（按供应商接口）
	public function puDetail_supply() {
	    $this->common_model->checkpurview(28);
		$sum1 = $sum2 = $sum3 = 0;
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$categoryId = intval($this->input->get_post('categoryId',TRUE));
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and a.billType="PUR"';
		$where .= $categoryId>0 ? ' and c.cCategory='.$categoryId.'' : ''; 
		$where .= $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_location_purview();
		$list   = $this->data_model->get_invoice_infosum($where.' group by a.buId,a.invId, a.locationId');  
		foreach ($list as $arr=>$row) {
			$v[$arr]['billId']        = intval($row['iid']);
		    $v[$arr]['billNo']        = $row['billNo'];
			$v[$arr]['billType']      = $row['billType']; 
			$v[$arr]['date']          = $row['billDate']; 
			$v[$arr]['buId']          = intval($row['buId']);
			$v[$arr]['buName']        = $row['contactName'];
			$v[$arr]['invNo']         = $row['invNumber'];  
			$v[$arr]['invName']       = $row['invName'];   
			$v[$arr]['spec']          = $row['invSpec']; 
			$v[$arr]['unit']          = $row['mainUnit']; 
			$v[$arr]['location']      = $row['locationName']; 
			$v[$arr]['assistName']    = $row['cCategoryName']; 
			$v[$arr]['baseQty']       = 0; 
			$v[$arr]['skuId']         = 0; 
			$v[$arr]['cost']          = 0;
			$v[$arr]['unitCost']      = 0;
			$v[$arr]['transType']     = $row['transTypeName'];
			$sum1 += $v[$arr]['qty']        = (float)$row['sumqty']; 
			$sum2 += $v[$arr]['unitPrice']  = (float)$row['sumqty']!=0 ? (float)round(abs($row['sumamount']/$row['sumqty']),2) : 0;
		    $sum3 += $v[$arr]['amount']     = (float)$row['sumamount']; 
		}
		$data['status'] = 200;
		$data['msg']    = 'success';
		$data['data']['list']      = isset($v) ? $v :array();
		$data['data']['total']['amount']      = '';
		$data['data']['total']['baseQty']     = 'PUR';
		$data['data']['total']['billId']      = '';
		$data['data']['total']['billNo']      = '';
		$data['data']['total']['billType']    = '';
		$data['data']['total']['buName']      = '';
		$data['data']['total']['buNo']        = '';
		$data['data']['total']['date']        = '';
		$data['data']['total']['invName']     = '';
		$data['data']['total']['location']    = '';
		$data['data']['total']['locationNo']  = '';
		$data['data']['total']['spec']        = '';
		$data['data']['total']['unit']        = '';
		$data['data']['total']['transType']   = '';
		$data['data']['total']['skuId']       = '';
		$data['data']['total']['qty']         = $sum1;
		$data['data']['total']['unitPrice']   = $sum1!=0 ? round(abs($sum3/$sum1),2) : 0;
		$data['data']['total']['amount']      = $sum3;
		$data['data']['total']['cost']        = '';
		$data['data']['total']['unitCost']    = '';
		die(json_encode($data)); 
	}
	
	
	//采购汇总表（按供应商）
	public function puDetail_supplyExporter() {
	    $this->common_model->checkpurview(29);
		$name = 'pu_supply_summary_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('采购明细表(按供应商)导出:'.$name);
		$categoryId = intval($this->input->get_post('categoryId',TRUE));
		$storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$data['beginDate']  = $beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and a.billType="PUR"';
		$where .= $categoryId>0 ? ' and c.cCategory='.$categoryId.'' : ''; 
		$where .= $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_location_purview();
		$data['list'] = $this->data_model->get_invoice_infosum($where.' group by a.buId,a.invId, a.locationId'); 
		$this->load->view('report/puDetail-supplyExporter',$data);	
	}
	
	
	
	
    //销售订单跟踪表 
	public function sales_order_tracking() {
	    $this->common_model->checkpurview(213);
		$data['beginDate']  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = str_enhtml($this->input->get_post('endDate',TRUE));
		$this->load->view('report/sales-order-tracking',$data);	
	}
	
	public function salesOrder() {
	    switch ($this->action) {
			case 'detail':
			    $this->salesOrder_detail();break;  
			case 'detailExporter':
			    $this->salesOrder_detailExporter();break; 
			default:  
			    $this->salesOrder_detail();	
		}
	}
	
	
	public function salesOrder_detail() { 
        $this->common_model->checkpurview(213);
		$sum1 = $sum2 = $sum3 = 0;
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$salesId            = str_enhtml($this->input->get_post('salesId',TRUE));
		$categoryId         = str_enhtml($this->input->get_post('categoryId',TRUE));
		$fromDeliveryDate   = str_enhtml($this->input->get_post('fromDeliveryDate',TRUE));
		$endDeliveryDate    = str_enhtml($this->input->get_post('endDeliveryDate',TRUE));
		$status             = str_enhtml($this->input->get_post('status',TRUE));
		$billNo             = str_enhtml($this->input->get_post('billNo',TRUE));
		$beginDeliveryDate  = str_enhtml($this->input->get_post('beginDeliveryDate',TRUE));
		$toDeliveryDate     = str_enhtml($this->input->get_post('toDeliveryDate',TRUE));
		
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		
		
		$where = $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $billNo ? ' and a.billNo="'.$billNo.'"' : ''; 
		$where .= $salesId ? ' and e.number="'.$salesId.'"' : ''; 
		$where .= $categoryId>0 ? ' and c.cCategory='.$categoryId.'' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		
		$where .= $fromDeliveryDate ? ' and a.deliveryDate>="'.$fromDeliveryDate.'"' : ''; 
		$where .= $endDeliveryDate ? ' and a.deliveryDate<="'.$endDeliveryDate.'"' : ''; 
		 
		$where .= strlen($status)>1 ? ' and a.status in('.$status.')' : ''; 
		
		$where .= $this->common_model->get_admin_purview();
		$where .= $this->common_model->get_location_purview();
		$list   = $this->data_model->get_salesOrder($where); 
		foreach ($list as $arr=>$row) {
		    $v[$arr]['spec']          = $row['spec']; 
			$v[$arr]['status']        = $row['status']; 
			$v[$arr]['inDate']        = $row['inDate']; 
			$v[$arr]['invName']       = $row['invName'];   
			$v[$arr]['qty']           = $row['qty']>0 ? -abs($row['qty']) : abs($row['qty']);  
			$v[$arr]['date']          = $row['date']; 
			$v[$arr]['salesName']     = $row['salesName']; 
			$v[$arr]['deleted']       = ''; 
			$v[$arr]['invNo']         = $row['invNo'];  
			$v[$arr]['amount']        = $row['amount']>0 ? abs($row['amount']) : -abs($row['amount']);  
			$v[$arr]['unit']          = $row['unit']; 
			$v[$arr]['fdesc']         = '';
			$v[$arr]['billNo']        = $row['billNo'];
			$v[$arr]['unQty']         = $row['unQty']>0 ? -abs($row['unQty']) : abs($row['unQty']);   
			$v[$arr]['billId']        = intval($row['billId']);
			$v[$arr]['deliveryDate']  = $row['deliveryDate']; 
			$v[$arr]['buName']        = $row['buName'];
			$v[$arr]['description']   = $row['description']; 
			if (strlen($row['amount'])>0) {
				$sum1 += $row['unQty']>0 ? -abs($row['unQty']) : abs($row['unQty']);   
				$sum2 += $row['qty']>0 ? -abs($row['qty']) : abs($row['qty']);  
				$sum3 += $row['amount']>0 ? abs($row['amount']) : -abs($row['amount']);  
			}
			if ($arr+1==count($list)) {
			    $v[$arr+1]['spec']          = ''; 
				$v[$arr+1]['status']        = '合计';
				$v[$arr+1]['inDate']        = ''; 
				$v[$arr+1]['invName']       = '';   
				$v[$arr+1]['qty']           = round($sum2,2);
				$v[$arr+1]['date']          = ''; 
				$v[$arr+1]['salesName']     = ''; 
				$v[$arr+1]['deleted']       = ''; 
				$v[$arr+1]['invNo']         = '';  
				$v[$arr+1]['amount']        = round($sum3,2); 
				$v[$arr+1]['unit']          = ''; 
				$v[$arr+1]['fdesc']         = '';
				$v[$arr+1]['billNo']        = '';
				$v[$arr+1]['unQty']         = round($sum1,2); 
				$v[$arr+1]['billId']        = '';
				$v[$arr+1]['deliveryDate']  = ''; 
				$v[$arr+1]['buName']        = '';
				$v[$arr+1]['description']   = ''; 
			} 
		}
		$data['status']                          = 200;
		$data['msg']                             = 'success';
		$data['data']['rows']                    = isset($v) ? $v :array();
		$data['data']['userdata']['invNo']       = '';
		$data['data']['userdata']['invName']     = '';
		$data['data']['userdata']['spec']        = '';
		$data['data']['userdata']['unit']        = '';
		$data['data']['userdata']['date']        = '';
		$data['data']['userdata']['billNo']      = '';
		$data['data']['userdata']['buName']      = '';
		$data['data']['userdata']['status']      = '';
		$data['data']['userdata']['deliveryDate']= '';
		$data['data']['userdata']['inDate']      = '';
		$data['data']['userdata']['qty']         = '';
		$data['data']['userdata']['amount']      = '';
		$data['data']['userdata']['unQty']       = '';
		die(json_encode($data));  	
	}
	
	public function salesOrder_detailExporter() {
	    $this->common_model->checkpurview(214);
		$name = 'sales_order_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('销售订单跟踪表导出:'.$name);
		$salesId            = str_enhtml($this->input->get_post('salesId',TRUE));
		$categoryId         = str_enhtml($this->input->get_post('categoryId',TRUE));
		$fromDeliveryDate   = str_enhtml($this->input->get_post('fromDeliveryDate',TRUE));
		$endDeliveryDate    = str_enhtml($this->input->get_post('endDeliveryDate',TRUE));
		$status             = str_enhtml($this->input->get_post('status',TRUE));
		$billNo             = str_enhtml($this->input->get_post('billNo',TRUE));
		$beginDeliveryDate  = str_enhtml($this->input->get_post('beginDeliveryDate',TRUE));
		$toDeliveryDate     = str_enhtml($this->input->get_post('toDeliveryDate',TRUE));
		
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$data['beginDate']  = $beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate = str_enhtml($this->input->get_post('endDate',TRUE));
		
		$where = $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $billNo ? ' and a.billNo="'.$billNo.'"' : ''; 
		$where .= $categoryId>0 ? ' and c.cCategory='.$categoryId.'' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $salesId ? ' and e.number="'.$salesId.'"' : ''; 
		$where .= $fromDeliveryDate ? ' and a.deliveryDate>="'.$fromDeliveryDate.'"' : ''; 
		$where .= $endDeliveryDate ? ' and a.deliveryDate<="'.$endDeliveryDate.'"' : ''; 
		$where .= $status=='0' ? ' and a.status=0' : ''; 
		$where .= $status=='1' ? ' and a.status=1' : ''; 
		$where .= $status=='2' ? ' and a.status=2' : ''; 
		$where .= strlen($status)>1 ? ' and a.status in('.$status.')' : ''; 
		
		$where .= $this->common_model->get_admin_purview();
		$where .= $this->common_model->get_location_purview();
		$data['list'] = $this->data_model->get_salesOrder($where); 
		$this->load->view('report/salesOrder-detailExporter',$data);	     	     	
	}
	
	
	
	
	
	//销售明细表
	public function sales_detail() {
	    $this->common_model->checkpurview(31);
		$this->load->view('report/sales-detail');	
	}
	
	public function salesDetail() {
	    switch ($this->action) {
			case 'detail':
			    $this->salesDetail_detail();break;  
			case 'detailExporter':
			    $this->salesDetail_detailExporter();break; 
			case 'inv':
			    $this->salesDetail_inv();break;  
			case 'invExporter':
			    $this->salesDetail_invExporter();break;
			case 'customer':
			    $this->salesDetail_customer();break;  
			case 'customerExporter':
			    $this->salesDetail_customerExporter();break;		
			default:  
			    $this->salesDetail_detail();	
		}
	}
	
	//销售明细表接口
	public function salesDetail_detail() {
	    $this->common_model->checkpurview(31);
		$sum1 = $sum2 = $sum3 = $sum4 = $sum5 = $sum6 = $sum7 = 0;
	    $data['status'] = 200;
		$data['msg']    = 'success';
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$profit     = intval($this->input->get_post('profit',TRUE));
		$categoryId = intval($this->input->get_post('categoryId',TRUE));
		$salesId    = str_enhtml($this->input->get_post('salesId',TRUE));
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and a.billType="SALE"';
		$where .= $categoryId>0 ? ' and c.cCategory='.$categoryId.'' : ''; 
		$where .= $salesId ? ' and e.number  in('.str_quote($salesId).')' : ''; 
		$where .= $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_admin_purview();
		$where .= $this->common_model->get_location_purview();
		$offset = $rows * ($page-1);
		$data['data']['page']      = $page;
		$data['data']['records']   = $this->data_model->get_invoice_info($where.' order by a.billDate,a.id',3);               
		$data['data']['total']     = ceil($data['data']['records']/$rows);                           
		$list   = $this->data_model->get_invoice_info($where.' order by a.billDate,a.id'); 
		foreach ($list as $arr=>$row) {
		    $sum1 += $qty    = $row['qty']>0 ? -abs($row['qty']) : abs($row['qty']);   //销售在数据库中是负数 在统计的时候应该是正数
			$sum3 += $amount = $row['amount'];                      //销售收入
			$v[$arr]['billId']        = intval($row['iid']);
		    $v[$arr]['billNo']        = $row['billNo'];
			$v[$arr]['billType']      = $row['billType']; 
			$v[$arr]['date']          = $row['billDate']; 
			$v[$arr]['buId']          = intval($row['buId']);
			$v[$arr]['buName']        = $row['contactName'];
			$v[$arr]['invNo']         = $row['invNumber'];  
			$v[$arr]['invName']       = $row['invName'];   
			$v[$arr]['spec']          = $row['invSpec']; 
			$v[$arr]['unit']          = $row['mainUnit']; 
			$v[$arr]['location']      = $row['locationName']; 
			$v[$arr]['description']   = $row['description']; 
			$v[$arr]['skuId']         = 0; 
			$v[$arr]['cost']          = '';   //销售成本
			$v[$arr]['unitCost']      = '';   //单位成本
			$v[$arr]['saleProfit']    = '';   //销售毛利
			$v[$arr]['salepPofitRate']= '';   //销售毛利率
			$v[$arr]['salesName']     = $row['salesName'];
			$v[$arr]['transType']     = $row['transTypeName'];
			$v[$arr]['unitPrice']     = round($row['price'],$this->systems['qtyPlaces']);
			$v[$arr]['qty']           = round($qty,$this->systems['qtyPlaces']);
		    $v[$arr]['amount']        = round($row['amount'],2);
		}
		$data['data']['rows']      = isset($v) ? $v : array();
		$data['data']['userdata']['billId']      = 0;
		$data['data']['userdata']['billType']    = 'SALE';
		$data['data']['userdata']['skuId']       = 0;
		$data['data']['userdata']['qty']         = round($sum1,$this->systems['qtyPlaces']);
		$data['data']['userdata']['unitPrice']   = $sum1>0 ? round($sum3/$sum1,$this->systems['qtyPlaces']) : 0;
		$data['data']['userdata']['amount']      = round($sum3,2);
		$data['data']['userdata']['cost']        = '';
		$data['data']['userdata']['unitCost']    = '';
		$data['data']['userdata']['saleProfit']      = '';
		$data['data']['userdata']['salepPofitRate']  = '';
		die(json_encode($data));
	}
	
	//销售明细表（导出）
	public function salesDetail_detailExporter() {
	    $this->common_model->checkpurview(32);
		$name = 'sales_detail_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('销售明细表导出:'.$name);
		$categoryId = intval($this->input->get_post('categoryId',TRUE));
		$salesId    = str_enhtml($this->input->get_post('salesId',TRUE));
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$data['beginDate']  = $beginDate = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate   = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and a.billType="SALE"';
		$where .= $categoryId>0 ? ' and c.cCategory='.$categoryId.'' : ''; 
		$where .= $salesId ? ' and e.number  in('.str_quote($salesId).')' : ''; 
		$where .= $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_admin_purview();
		$where .= $this->common_model->get_location_purview();
		$data['list'] = $this->data_model->get_invoice_info($where.' order by a.id'); 
		$this->load->view('report/salesDetail-detailExporter',$data);	
	} 
	
	//销售汇总表（按商品）
	public function sales_summary() {
	    $this->common_model->checkpurview(34);
		$this->load->view('report/sales-summary');	
	}
	
	//销售汇总表（按商品）接口
	public function salesDetail_inv() {
	    $this->common_model->checkpurview(34);
		$sum1 = $sum2 = $sum3 = $sum4 = $sum5 = $sum6 = $sum7 = 0;
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$profit     = intval($this->input->get_post('profit',TRUE));
		$catId      = intval($this->input->get_post('catId',TRUE));
		$salesId    = str_enhtml($this->input->get_post('salesId',TRUE));
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and a.billType="SALE"';
		if ($catId > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$catId.',path)'),'id'); 
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and b.categoryId in('.$cid.')';
			} 
		}  
		$where .= $salesId ? ' and e.number  in('.str_quote($salesId).')' : ''; 
		$where .= $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_location_purview();
		$offset = $rows * ($page-1);  
		if ($profit==1) {
			$info  = $this->data_model->get_profit('and billDate<="'.$endDate.'"');  
		}                         
		$list  = $this->data_model->get_invoice_infosum($where.' group by a.invId, a.locationId'); 
		foreach ($list as $arr=>$row) {
		    $sum1 += $qty = $row['sumqty']>0 ? -abs($row['sumqty']):abs($row['sumqty']);   //销售在数据库中是负数 在统计的时候应该是正数
			$sum3 += $amount = $row['sumamount'];                      //销售收入
			$unitPrice = $qty!=0 ? $amount/$qty : 0;                   //单位成本
			if ($profit==1) {
				$sum4 += $unitcost = isset($info['inprice'][$row['invId']][$row['locationId']]) ? $info['inprice'][$row['invId']][$row['locationId']] : 0;   //单位成本
				$sum5 += $cost = $unitcost * $qty;                     //销售成本
				$sum6 += $saleProfit = $amount - $cost;                //销售毛利
				$sum7 += $salepPofitRate = $amount>0 ? ($saleProfit/$amount)*100 :0;   //销售毛利率
			} 
			$v[$arr]['billId']        = intval($row['id']);
		    $v[$arr]['billNo']        = $row['billNo'];
			$v[$arr]['billType']      = $row['billType']; 
			$v[$arr]['date']          = $row['billDate']; 
			$v[$arr]['buId']          = intval($row['buId']);
			$v[$arr]['buName']        = $row['contactName'];
			$v[$arr]['invNo']         = $row['invNumber'];  
			$v[$arr]['invName']       = $row['invName'];   
			$v[$arr]['spec']          = $row['invSpec']; 
			$v[$arr]['unit']          = $row['mainUnit']; 
			$v[$arr]['location']      = $row['locationName']; 
			$v[$arr]['locationNo']    = $row['locationNo']; 
			$v[$arr]['assistName']    = $row['categoryName']; 
			$v[$arr]['skuId']         = 0; 
			if ($profit==1) {
				$v[$arr]['cost']          = round($cost,2);
				$v[$arr]['unitCost']      = round($unitcost,2);
				$v[$arr]['saleProfit']    = round($saleProfit,2);
				$v[$arr]['salepPofitRate']= round($salepPofitRate,2).'%';
			} 
			$v[$arr]['salesName']     = $row['salesName'];
			$v[$arr]['transType']     = $row['transTypeName'];
			$v[$arr]['qty']           = round($qty,$this->systems['qtyPlaces']); 
		    $v[$arr]['amount']        = round($amount,2); 
			$v[$arr]['unitPrice']     = round($unitPrice,2);  
		}
		
		$data['status'] = 200;
		$data['msg']    = 'success';
		$data['data']['page']      = $page;
		$data['data']['records']   = $this->data_model->get_invoice_infosum($where.' group by a.invId, a.locationId',3);  
		$data['data']['total']     = ceil($data['data']['records']/$rows);                 
		$data['data']['rows']      = isset($v) ? $v : array();
		$data['data']['userdata']['billId']      = 0;
		$data['data']['userdata']['billType']    = 'SALE';
		$data['data']['userdata']['skuId']       = 0;
		$data['data']['userdata']['qty']         = round($sum1,$this->systems['qtyPlaces']);
		$data['data']['userdata']['unitPrice']   = $sum1>0 ? round($sum3/$sum1,2) : 0; 
		$data['data']['userdata']['amount']      = round($sum3,2);
		if ($profit==1) {
			$data['data']['userdata']['cost']        = round($sum5,2);
			$data['data']['userdata']['unitCost']    = round($sum4,2);
			$data['data']['userdata']['saleProfit']      = round($sum6,2);
			$data['data']['userdata']['salepPofitRate']  = round($sum7,2).'%';
		} 
		die(json_encode($data));
	}
	
	//销售汇总表（按商品）导出
	public function salesDetail_invExporter() {
	    $this->common_model->checkpurview(35);
		$name = 'sales_inv_summary_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出销售汇总表(按商品):'.$name);
		$data['profit'] = $profit     = intval($this->input->get_post('profit',TRUE));
		$catId      = intval($this->input->get_post('catId',TRUE));
		$salesId    = str_enhtml($this->input->get_post('salesId',TRUE));
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$data['beginDate'] = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']   = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and a.billType="SALE"';
		if ($catId > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$catId.',path)'),'id'); 
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and b.categoryId in('.$cid.')';
			} 
		}  
		$where .= $salesId ? ' and e.number  in('.str_quote($salesId).')' : ''; 
		$where .= $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $data['beginDate'] ? ' and a.billDate>="'.$data['beginDate'].'"' : ''; 
		$where .= $data['endDate'] ? ' and a.billDate<="'.$data['endDate'].'"' : ''; 
		$where .= $this->common_model->get_location_purview();
		if ($profit==1) {
			$data['info'] = $this->data_model->get_profit('and billDate<="'.$data['endDate'].'"');      
		}
		$data['list'] = $this->data_model->get_invoice_infosum($where.' group by invId, locationId'); 
		$this->load->view('report/salesDetail_invExporter',$data);	
	}
	
	//销售汇总表（按客户）
	public function sales_summary_customer_new() {
	    $this->common_model->checkpurview(37);
		$this->load->view('report/sales-summary-customer-new');	
	}
	
	//销售汇总表（按客户接口）
	public function salesDetail_customer() {
	    $this->common_model->checkpurview(37);
		$sum1 = $sum2 = $sum3 = $sum4 = $sum5 = $sum6 = $sum7 = 0;
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$profit     = intval($this->input->get_post('profit',TRUE));
		$salesId    = str_enhtml($this->input->get_post('salesId',TRUE));
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and a.billType="SALE"';
		$where .= $salesId ? ' and e.number  in('.str_quote($salesId).')' : ''; 
		$where .= $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_location_purview();
		if ($profit==1) {
			$info  = $this->data_model->get_profit('and billDate<="'.$endDate.'"');         
		}
		$list   = $this->data_model->get_invoice_infosum($where.' group by buId,invId,locationId'); 
		foreach ($list as $arr=>$row) {
		    $sum1 += $qty = $row['sumqty']>0 ? -abs($row['sumqty']):abs($row['sumqty']);   //销售在数据库中是负数 在统计的时候应该是正数
			$sum3 += $amount = $row['sumamount'];                      //销售收入
			$unitPrice = $qty!=0 ? $amount/$qty : 0;                   //单位成本
			if ($profit==1) {
				$sum4 += $unitcost = isset($info['inprice'][$row['invId']][$row['locationId']]) ? $info['inprice'][$row['invId']][$row['locationId']] : 0;   //单位成本
				$sum5 += $cost = $unitcost * $qty;                     //销售成本
				$sum6 += $saleProfit = $amount - $cost;                //销售毛利
				$sum7 += $salepPofitRate = $amount>0 ? ($saleProfit/$amount)*100 :0;   //销售毛利率
			} 
			$v[$arr]['billId']        = intval($row['iid']);
		    $v[$arr]['billNo']        = $row['billNo'];
			$v[$arr]['billType']      = $row['billType']; 
			$v[$arr]['date']          = $row['billDate']; 
			$v[$arr]['buId']          = intval($row['buId']);
			$v[$arr]['buName']        = $row['contactName'];
			$v[$arr]['invNo']         = $row['invNumber'];  
			$v[$arr]['invName']       = $row['invName'];   
			$v[$arr]['spec']          = $row['invSpec']; 
			$v[$arr]['unit']          = $row['mainUnit']; 
			$v[$arr]['location']      = $row['locationName']; 
			$v[$arr]['locationNo']    = $row['locationNo']; 
			$v[$arr]['assistName']    = $row['categoryName']; 
			$v[$arr]['baseQty']       = 0; 
			$v[$arr]['skuId']         = 0; 
			if ($profit==1) {
				$v[$arr]['cost']          = round($cost,2);
				$v[$arr]['unitCost']      = round($unitcost,2);
				$v[$arr]['saleProfit']    = round($saleProfit,2);
				$v[$arr]['salepPofitRate']= round($salepPofitRate,2);
			} 
			$v[$arr]['salesName']     = $row['salesName'];
			$v[$arr]['transType']     = $row['transTypeName'];
			$v[$arr]['qty']           = round($qty,$this->systems['qtyPlaces']); 
		    $v[$arr]['amount']        = round($amount,2); 
			$v[$arr]['unitPrice']     = round($unitPrice,2); 
		}
		$data['status'] = 200;
		$data['msg']    = 'success';
		$data['data']['list']      = isset($v) ? $v : array(); 
		$data['data']['total']['amount']      = '';
		$data['data']['total']['baseQty']     = 'SALE';
		$data['data']['total']['billId']      = '';
		$data['data']['total']['billNo']      = '';
		$data['data']['total']['billType']    = '';
		$data['data']['total']['buName']      = '';
		$data['data']['total']['buNo']        = '';
		$data['data']['total']['date']        = '';
		$data['data']['total']['invName']     = '';
		$data['data']['total']['location']    = '';
		$data['data']['total']['locationNo']  = '';
		$data['data']['total']['spec']        = '';
		$data['data']['total']['unit']        = '';
		$data['data']['total']['transType']   = '';
		$data['data']['total']['skuId']       = '';
		
		$data['data']['total']['qty']         = round($sum1,$this->systems['qtyPlaces']);
		$data['data']['total']['unitPrice']   = $sum1>0 ? $sum3/$sum1 : 0; 
		$data['data']['total']['amount']      = round($sum3,2);
        if ($profit==1) {
			$data['data']['total']['cost']        = round($sum5,2);
			$data['data']['total']['unitCost']    = round($sum4,2);
			$data['data']['total']['saleProfit']      = round($sum6,2);
			$data['data']['total']['salepPofitRate']  = round($sum7,2);
		}
		die(json_encode($data));
	}
	

	//销售汇总表（按客户)导出
	public function salesDetail_customerExporter() {
	    $this->common_model->checkpurview(38);
		$name = 'sales_customer_summary_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出销售汇总表(按客户):'.$name);
		$data['profit'] = $profit     = intval($this->input->get_post('profit',TRUE));
		$salesId    = str_enhtml($this->input->get_post('salesId',TRUE));
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$customerNo = str_enhtml($this->input->get_post('customerNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$data['beginDate']  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and a.billType="SALE"';
		$where .= $salesId ? ' and e.number  in('.str_quote($salesId).')' : ''; 
		$where .= $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $customerNo ? ' and c.number in('.str_quote($customerNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $data['beginDate'] ? ' and a.billDate>="'.$data['beginDate'].'"' : ''; 
		$where .= $data['endDate'] ? ' and a.billDate<="'.$data['endDate'].'"' : '';
		$where .= $this->common_model->get_location_purview();
		if ($profit==1) {
			$data['info'] = $this->data_model->get_profit('and billDate<="'.$data['endDate'].'"');                  
		}
		$data['list'] = $this->data_model->get_invoice_infosum($where.' group by buId,invId,locationId'); 
		$this->load->view('report/salesDetail-customerExporter',$data);	
	}
	
	
	//往来单位欠款表
	public function contact_debt_new() {
	    $this->common_model->checkpurview(49);
		$this->load->view('report/contact-debt-new');	
	}
	
	public function contactDebt() {
	    switch ($this->action) {
			case 'detail':
			    $this->contactDebt_detail();break;  
			case 'exporter':
			    $this->contactDebt_exporter();break; 
			default:  
			    $this->contactDebt_detail();	
		}
	}
	
	//往来单位欠款表(接口)
	public function contactDebt_detail() {
	    $this->common_model->checkpurview(49);
		$v = array();
		$sum1 = $sum2 = 0;
	    $data['status'] = 200;
		$data['msg']    = 'success';
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$supplier  = str_enhtml($this->input->get_post('supplier',TRUE));
		$customer  = str_enhtml($this->input->get_post('customer',TRUE));
		$where = 'a.isDelete=0';
		if ($supplier && $customer) {
		} else {
			$where .= $supplier ? ' and a.type=10' : '';
			$where .= $customer ? ' and a.type=-10' : '';
		}
		$where .= $matchCon ? ' and (a.name like "%'.$matchCon.'%" or a.number like "%'.$matchCon.'%")' : '';
		$where .= $this->common_model->get_contact_purview(); 
		$list = $this->data_model->get_contact($where. ' order by a.type desc');
		foreach ($list as $arr=>$row) {
		    $v[$arr]['dbid']        = 0;
			$v[$arr]['debt']        = 0; 
			$v[$arr]['displayName'] = $row['type']==10 ? '供应商' : '客户'; 
			$v[$arr]['buId']        = intval($row['id']);
			$v[$arr]['name']        = $row['name'];
			$v[$arr]['number']      = $row['number'];   
			$sum1 += $v[$arr]['payable']     = $row['type']==10  ? $row['arrears'] : 0; 
			$sum2 += $v[$arr]['receivable']  = $row['type']==-10 ? $row['arrears'] : 0; 
			$v[$arr]['type']        = $row['type']; 
		}
		$data['data']['list']      = $v;
		$data['data']['total']['buid']        = 0;
		$data['data']['total']['dbid']        = 0;
		$data['data']['total']['debt']        = '';
		$data['data']['total']['displayName'] = '';
		$data['data']['total']['name']        = '';
		$data['data']['total']['number']      = '';
		$data['data']['total']['payable']     = $sum1;
		$data['data']['total']['receivable']  = $sum2;
		$data['data']['total']['type']        = '';
		die(json_encode($data));
	}
	
	//往来单位欠款表导出
	public function contactDebt_exporter() {
	    $this->common_model->checkpurview(50);
		$name = 'contact_debt_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('导出往来单位欠款表:'.$name);
		$matchCon  = str_enhtml($this->input->get_post('matchCon',TRUE));
		$supplier  = str_enhtml($this->input->get_post('supplier',TRUE));
		$customer  = str_enhtml($this->input->get_post('customer',TRUE));
		$where = 'a.isDelete=0';
		if ($supplier && $customer) {
		} else {
			$where .= $supplier ? ' and a.type=10' : '';
			$where .= $customer ? ' and a.type=-10' : '';
		}
		$where .= $matchCon ? ' and (a.name like "%'.$matchCon.'%" or a.number like "%'.$matchCon.'%")' : '';
		$where .= $this->common_model->get_contact_purview(); 
		$data['list'] = $this->data_model->get_contact($where. ' order by a.type desc');
		$this->load->view('report/contactDebt-exporter',$data);	
	}
	
	
	//商品库存余额表
	public function goods_balance() {
	    $this->common_model->checkpurview(40);
		$this->load->view('report/goods-balance');	
	}
	
	public function invBalance() {
	    switch ($this->action) {
			case 'detail':
			    $this->invBalance_detail();break;  
			case 'exporter':
			    $this->invBalance_exporter();break; 
			default:  
			    $this->invBalance_detail();	
		}
	}
	
    //商品库存余额表(接口)
	public function invBalance_detail() {
	    $this->common_model->checkpurview(40);
	    $i = 2;
		$select = '';
		$qty_1  = $cost_1 = $cost1 = 0;
		$stoNames = array();
		$colNames = array();
		$colIndex = array();
		$catId      = intval($this->input->get_post('catId',TRUE));
		$showZero   = intval($this->input->get_post('showZero',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		$having = $showZero == 1 ? ' HAVING qty=0' : '';   
		$where  = 'a.isDelete=0';
		if ($catId > 0) {
		    $catId = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$catId.',path)'),'id'); 
			if (count($catId)>0) {
			    $catId = join(',',$catId);
			    $where .= ' and b.categoryid in('.$catId.')';
			} 
		} 
		$where .= $goodsNo ? ' and b.number="'.$goodsNo.'"' : ''; 
		$where .= $storageNo ? ' and c.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_location_purview(); 
		$where1 = $storageNo ? ' and locationNo in('.str_quote($storageNo).')' : ''; 
		$where1 .= $this->common_model->get_location_purview(1); 
		$storage = $this->mysql_model->get_results('storage','(isDelete=0) '.$where1);
		foreach($storage as $arr=>$row) {
		    $qty['qty'.$i]  = $qty['cost'.$i] = 0;
		    $stoNames[] = $row['name'];
			$colNames[] = '数量';
			//$colNames[] = '成本';
			$colIndex[] = 'qty_'.$i;
			//$colIndex[] = 'cost_'.$i;
			$select .= 'sum(case when a.locationId='.$row['id'].' then qty else 0 end ) as qty'.$i.',';
		    $i++;
		}
		array_unshift($stoNames,'所有仓库');
		array_unshift($colNames,'商品编号','商品名称','规格型号','单位','数量','成本');
		array_unshift($colIndex,'invNo','invName','spec','unit','qty_1','cost_1');             
		$list   = $this->data_model->get_invBalance($where.' group by a.invId '.$having,$select); 
		foreach ($list as $arr=>$row) {
		    $inprice  = $row['inqty']>0 ? $row['inamount']/$row['inqty'] : 0;
			$amount   = $row['qty'] * $inprice;
			$v[$arr]['invNo']         = $row['invNumber'];  
			$v[$arr]['invName']       = $row['invName'];   
			$v[$arr]['spec']          = $row['invSpec']; 
			$v[$arr]['unit']          = $row['mainUnit']; 
			$v[$arr]['qty_1']         = round($row['qty'],$this->systems['qtyPlaces']);  
			$v[$arr]['cost_1']        = round($amount,2);  
			$i = 2;
			foreach($storage as $arr1=>$row1) {
				$v[$arr]['qty_'.$i]   = round($row['qty'.$i],$this->systems['qtyPlaces']);  
				$qty['qty'.$i] += $row['qty'.$i];
			    $i++;
			}
			$qty_1  += $row['qty'];  
			$cost_1 += $amount;  
		}
		$json['status'] = 200;
		$json['msg']    = 'success'; 
		$json['data']['stoNames'] = $stoNames;
		$json['data']['colNames'] = $colNames;
		$json['data']['colIndex'] = $colIndex;
		$json['data']['total'] = 1;
		$json['data']['page']  = 1;
		$json['data']['records'] = 200;
		$json['data']['rows']  = isset($v) ? $v : array();
		$json['data']['userdata']['invNo']    = '';
		$json['data']['userdata']['invName']  = '';
		$json['data']['userdata']['spec']     = '';
		$json['data']['userdata']['unit']     = '';
		$json['data']['userdata']['qty_1']    = round($qty_1,$this->systems['qtyPlaces']);  
		$json['data']['userdata']['cost_1']   = round($cost_1,2); 
		$i = 2;
		foreach($storage as $arr1=>$row1) {
			$json['data']['userdata']['qty_'.$i]  = round($qty['qty'.$i],$this->systems['qtyPlaces']);   
		    $i++;
		}
		die(json_encode($json));
	}
	
	
	//商品库存余额表(导出)
	public function invBalance_exporter() {
	    $this->common_model->checkpurview(41);
		sys_csv('inv_balance_'.date('YmdHis').'.xls');
		$i = 2;
		$select = '';
		$catId      = intval($this->input->get_post('catId',TRUE));
		$showZero   = intval($this->input->get_post('showZero',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$data['beginDate'] = $beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']   = $endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		$having = $showZero == 1 ? ' HAVING qty=0' : '';   
		$where  = 'a.isDelete=0';
		if ($catId > 0) {
		    $catId = array_column($this->mysql_model->get_results('category','(1=1) and find_in_set('.$catId.',path)'),'id'); 
			if (count($catId)>0) {
			    $catId = join(',',$catId);
			    $where .= ' and b.categoryid in('.$catId.')';
			} 
		} 
		$where .= $goodsNo ? ' and b.number="'.$goodsNo.'"' : ''; 
		$where .= $storageNo ? ' and c.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : ''; 
		$where .= $this->common_model->get_location_purview(); 
		$where1 = $storageNo ? ' and locationNo in('.str_quote($storageNo).')' : ''; 
		$where1 .= $this->common_model->get_location_purview(1); 
		$data['storage'] = $storage = $this->mysql_model->get_results('storage','(isDelete=0) '.$where1);
		foreach($storage as $arr=>$row) {
			$select .= 'sum(case when a.locationId='.$row['id'].' then qty else 0 end ) as qty'.$i.',';
		    $i++;
		}                     
		$data['list']   = $this->data_model->get_invBalance($where.' group by a.invId '.$having,$select); 
		$this->load->view('report/invBalance_exporter',$data);	
	}
	
	
	//商品收发明细表
	public function goods_flow_detail() {
	    $this->common_model->checkpurview(43);
		$this->load->view('report/goods-flow-detail');	
	}
	
	
	public function deliverDetail() {
	    switch ($this->action) {
		    case 'detail':
			    $this->deliverDetail_detail();break;  
			case 'exporter':
			    $this->deliverDetail_exporter();break; 
			default:  
			    $this->deliverDetail_detail();	
		}
	}
	
 
	//商品收发明细表(接口)
	public function deliverDetail_detail() {
	    $this->common_model->checkpurview(43);
		$sum1 = $sum2 = $sum3 = $sum4 = $sum5 = 0;
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and a.transType>0';
		

		$catId  = intval($this->input->get_post('catId',TRUE));
		if ($catId > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$catId.',path)'),'id'); 
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and b.categoryId in('.$cid.')';
			} 
		}    
		
		$where .= $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		$where .= $this->common_model->get_location_purview();               
		$list   = $this->data_model->get_invoice_info($where.' order by a.billDate,a.id'); 
		foreach ($list as $arr=>$row) {
			$inqty         = $row['qty']>0 ? abs($row['qty']) : '';    //入库
			$outqty        = $row['qty']<0 ? abs($row['qty']) : '';    //出库
			$inunitCost    = $row['qty']>0 ? round(abs($row['price']),2) : '';  //入库
			$outunitCost   = $row['qty']<0 ? round(abs($row['price']),2) : '';  //出库
			$incost        = $row['qty']>0 ? round(abs($row['amount']),2) : '';  //入库
			$outcost       = $row['qty']<0 ? round(abs($row['amount']),2) : '';  //出库
			$sum1   += $inqty;             //入库数量累加
			$sum2   += $outqty;            //出库数量累加
			$sum3   += $incost;            //入库成本累加
			$sum4   += $outcost;           //出库成本累加
			$v[$arr]['date']          = $row['billDate'];  
			$v[$arr]['billNo']        = $row['billNo'];  
			$v[$arr]['billId']        = $row['iid'];  
			$v[$arr]['billType']      = $row['billType'];  
			$v[$arr]['buName']        = $row['contactName'];  
			$v[$arr]['transType']     = $row['transTypeName'];  
			$v[$arr]['transTypeId']   = $row['transType'];  
			$v[$arr]['invNo']         = $row['invNumber'];  
			$v[$arr]['invName']       = $row['invName'];   
			$v[$arr]['spec']          = $row['invSpec']; 
			$v[$arr]['assistName']    = $row['categoryName'];
			$v[$arr]['unit']          = $row['mainUnit']; 
			$v[$arr]['entryId']       = ''; 
			$v[$arr]['location']      = $row['locationName']; 
			$v[$arr]['locationNo']    = $row['locationNo']; 
			$v[$arr]['inout']         = 0;
			$v[$arr]['qty']           = 0;
			$v[$arr]['baseQty']       = 0;
			$v[$arr]['unitCost']      = 0;
			$v[$arr]['cost']          = 0;
			$v[$arr]['inqty']         = round($inqty,$this->systems['qtyPlaces']);
			$v[$arr]['outqty']        = round($outqty,$this->systems['qtyPlaces']);
			$v[$arr]['totalqty']      = 0;
			$v[$arr]['totalcost']     = 0; 
			$v[$arr]['totalunitCost'] = 0; 	
			$v[$arr]['outunitCost']   = $outunitCost; 
			$v[$arr]['inunitCost']    = $inunitCost; 
			$v[$arr]['incost']        = $incost; 
			$v[$arr]['outcost']       = $outcost; 
		}
		$data['status']            = 200;
		$data['msg']               = 'success'; 
		$data['data']['page']      = $page;
		$data['data']['records']   = 1;   
		$data['data']['total']     = ceil($data['data']['records']/$rows); 
		$data['data']['rows']      = isset($v) ? array_values($v) : array();
		$data['data']['userdata']['date']       = '';
		$data['data']['userdata']['billNo']     = '';
		$data['data']['userdata']['billId']     = '';
		$data['data']['userdata']['billType']   = '';
		$data['data']['userdata']['buName']     = '';
		$data['data']['userdata']['type']       = '';
		$data['data']['userdata']['transTypeId']= '';
		$data['data']['userdata']['invNo']      = '';
		$data['data']['userdata']['invName']    = '';
		$data['data']['userdata']['spec']       = '';
		$data['data']['userdata']['unit']       = '';
		$data['data']['userdata']['location']   = '';
		$data['data']['userdata']['locationNo'] = '';
		$data['data']['userdata']['inout']      = '';
		$data['data']['userdata']['qty']        = 0;
		$data['data']['userdata']['baseQty']    = '';
		$data['data']['userdata']['cost_5']     = '';
		$data['data']['userdata']['inqty']      = round($sum1,$this->systems['qtyPlaces']);
		$data['data']['userdata']['outqty']     = round($sum2,$this->systems['qtyPlaces']);
		$inunitCost  = $sum1>0 ? $sum3/$sum1 :0;
		$outunitCost = $sum2>0 ? $sum4/$sum2 :0;
		$data['data']['userdata']['incost']      = round($sum3,$this->systems['qtyPlaces']);
		$data['data']['userdata']['inunitCost']  = round($inunitCost,$this->systems['qtyPlaces']);
		$data['data']['userdata']['outcost']     = round($sum4,$this->systems['qtyPlaces']);
		$data['data']['userdata']['outunitCost'] = round($outunitCost,$this->systems['qtyPlaces']);
		$data['data']['userdata']['totalqty']    = 0;
		die(json_encode($data));
	}
	
	//商品收发明细表(导出)
	public function deliverDetail_exporter() {
	    $this->common_model->checkpurview(44);
		$name = 'deliver_Detail_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('商品收发明细表导出:'.$name); 
	    $storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$data['beginDate'] = $beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']   = $endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and a.transType>0';
		$catId  = intval($this->input->get_post('catId',TRUE));
		if ($catId > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$catId.',path)'),'id'); 
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and b.categoryId in('.$cid.')';
			} 
		}    
		$where .=  $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $storageNo ? ' and d.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		$where .= $this->common_model->get_location_purview();               
		$data['list'] = $this->data_model->get_invoice_info($where.' order by a.billDate,a.id'); 
		$this->load->view('report/deliverDetail-exporter',$data);	
	}
	
	//商品收发汇总表
	public function goods_flow_summary() {
	    $this->common_model->checkpurview(46);
		$this->load->view('report/goods-flow-summary');	
	}
	
	public function deliverSummary() {
	    switch ($this->action) {
			case 'detail':
			    $this->deliverSummary_detail();break;  
			case 'exporter':
			    $this->deliverSummary_exporter();break; 
			default:  
			    $this->deliverSummary_detail();	
		}
	}
	
	//商品收发汇总表接口
	public function deliverSummary_detail() {
	    $this->common_model->checkpurview(46);
		for ($i=0;$i<15;$i++) {
			$sum['qty'.$i]   = 0;  
			$sum['cost'.$i]  = 0;  
		}
		$qty7   = $qty_7   = $qty13  = $qty_13 = 0; 
		$amount7   = $amount_7   = $amount13  = $amount_13 = 0; 
		$page   = max(intval($this->input->get_post('page',TRUE)),1);
		$rows   = max(intval($this->input->get_post('rows',TRUE)),100); 
		$catId  = intval($this->input->get_post('catId',TRUE));
		$storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		$where  = 'a.isDelete=0';
		if ($catId > 0) {
		    $cid = array_column($this->mysql_model->get_results('category','(isDelete=0) and find_in_set('.$catId.',path)'),'id'); 
			if (count($cid)>0) {
			    $cid = join(',',$cid);
			    $where .= ' and b.categoryId in('.$cid.')';
			} 
		}    
		$where .= $storageNo ? ' and c.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		$where .= $this->common_model->get_location_purview();   
		$list  = $this->data_model->get_deliverSummary($where.' group by a.invId,a.locationId',$beginDate,$endDate); 
		foreach ($list as $arr=>$row) {
		     //期初数量
		    for ($i=1;$i<7;$i++) {
				$qty_7     += abs($row['qty'.$i]); 
				$amount_7  += $row['amount'.$i];   
			}
			for ($i=8;$i<13;$i++) {
				$qty_13     += abs($row['qty'.$i]);  
				$amount_13  += $row['amount'.$i];    
			}
			$v[$arr]['assistName']    = $row['categoryName'];  
			$v[$arr]['invNo']         = $row['invNumber'];  
			$v[$arr]['invName']       = $row['invName'];   
			$v[$arr]['spec']          = $row['invSpec']; 
			$v[$arr]['unit']          = $row['mainUnit']; 
			$v[$arr]['location']      = $row['locationName']; 
			$v[$arr]['locationNo']    = $row['locationNo'];
			for ($i=0; $i<15; $i++) {  
			    if ($i==0) {
					$inprice0  = $row['inqty0']>0 ? $row['inamount0']/$row['inqty0'] : 0;
					$amount0   = $row['qty0'] * $inprice0;
				    $v[$arr]['qty_0']    = round(abs($row['qty0']),$this->systems['qtyPlaces']);         
					$v[$arr]['cost_0']   = round(abs($amount0),2);   
					$sum['qty0']  += abs($row['qty0']);  
					$sum['cost0'] += abs($amount0);                                  
				} elseif($i==7) {
				    $v[$arr]['qty_7']    = round($qty_7,$this->systems['qtyPlaces']);         //入库合计   
					$v[$arr]['cost_7']   = round($amount_7,2);   
					$sum['qty7']  += $qty_7;  
					$sum['cost7'] += $amount_7;                  
				} elseif($i==13) {
				    $v[$arr]['qty_13']   = round($qty_13,$this->systems['qtyPlaces']);        //出库合计 
					$v[$arr]['cost_13']  = round(abs($amount_13),2);   
					$sum['qty13']  += $qty_13;  
					$sum['cost13']  += $amount_13;     
				} elseif($i==14) {
				    $inprice14  = $row['inqty14']>0 ? $row['inamount14']/$row['inqty14'] : 0;
					$amount14   = $row['qty14'] * $inprice14;
				    $v[$arr]['qty_14']   = round(abs($row['qty14']),$this->systems['qtyPlaces']);    
					$v[$arr]['cost_14']  = round(abs($amount14),2);  
					$sum['qty14']  += abs($row['qty14']);  
					$sum['cost14'] += abs($amount14);       	                
				} else {
					$v[$arr]['qty_'.$i]   = round(abs($row['qty'.$i]),$this->systems['qtyPlaces']);   
					$v[$arr]['cost_'.$i]  = round(abs($row['amount'.$i]),$this->systems['qtyPlaces']);   
					$sum['qty'.$i]  += abs($row['qty'.$i]);
					$sum['cost'.$i] += abs($row['amount'.$i]);   
				}
			}
			$qty_7 = $cost_7 = $qty_13 = $cost_13 = 0;         //停止累加 初始化值
			$amount_7 = $amount_13 = 0;
		}
		$json['status'] = 200;
		$json['msg']    = 'success'; 
		$json['data']['stoNames']  = array("期初","调拨入库","普通采购","销售退回","盘盈","其他入库","成本调整","入库合计","调拨出库","采购退回","普通销售","盘亏","其他出库","出库合计","结存");
		$json['data']['colNames']  = array("商品类别","商品编号","商品名称","规格型号","单位","仓库","数量","成本","数量","成本","数量","成本","数量","成本","数量","成本","数量","成本","数量","成本","数量","成本","数量","成本","数量","成本","数量","成本","数量","成本","数量","成本","数量","成本","数量","成本");
		$json['data']['colIndex']  = array("assistName","invNo","invName","spec","unit","locationNo","qty_0","cost_0","qty_1","cost_1","qty_2","cost_2","qty_3","cost_3","qty_4","cost_4","qty_5","cost_5","qty_6","cost_6","qty_7","cost_7","qty_8","cost_8","qty_9","cost_9","qty_10","cost_10","qty_11","cost_11","qty_12","cost_12","qty_13","cost_13","qty_14","cost_14");
		$json['data']['page']      = $page;
		$json['data']['records']   = 1;   
		$json['data']['total']     = ceil($json['data']['records']/$rows);   
		$json['data']['rows']      = isset($v) ? $v : array();
		$json['data']['userdata']['assistName'] = '';
		$json['data']['userdata']['invNo']      = '';
		$json['data']['userdata']['invName']    = '';
		$json['data']['userdata']['spec']       = '';
		$json['data']['userdata']['unit']       = '';
		$json['data']['userdata']['location']   = '';
		$json['data']['userdata']['locationNo'] = '';
		for ($i=0;$i<15;$i++) {
			$json['data']['userdata']['qty_'.$i]   = round($sum['qty'.$i],$this->systems['qtyPlaces']);   
			$json['data']['userdata']['cost_'.$i]  = round($sum['cost'.$i],2); 
		}
		die(json_encode($json));
	}
	
	
	//商品收发汇总表(导出)
	public function deliverSummary_exporter() {
	    $this->common_model->checkpurview(47);
		$name = 'deliver_summary_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('商品收发汇总表导出:'.$name); 
		$storageNo  = str_enhtml($this->input->get_post('storageNo',TRUE));
		$goodsNo    = str_enhtml($this->input->get_post('goodsNo',TRUE));
		$data['beginDate'] = $beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']   = $endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		$where  = 'a.isDelete=0';
		$where .= $storageNo ? ' and c.locationNo in('.str_quote($storageNo).')' : ''; 
		$where .= $goodsNo ? ' and b.number in('.str_quote($goodsNo).')' : ''; 
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		$where .= $this->common_model->get_location_purview(); 
		$data['list']   = $this->data_model->get_deliverSummary($where.' group by a.invId,a.locationId',$beginDate,$endDate); 
		$this->load->view('report/deliverSummary-exporter',$data);	
	}
	
	 
	//现金银行报表
	public function cash_bank_journal_new() {
	    $this->common_model->checkpurview(106);
	    $data['accountNo']  = $accountNo   = intval($this->input->get_post('accountNo',TRUE));
		$data['beginDate']  = $beginDate   = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate     = str_enhtml($this->input->get_post('endDate',TRUE));
		$this->load->view('report/cash-bank-journal-new', $data);	
	}
	
	
	public function bankBalance() {
	    switch ($this->action) {
			case 'detail':
			    $this->bankBalance_detail();break;  
			case 'exporter':
			    $this->bankBalance_exporter();break; 
			default:  
			    $this->bankBalance_detail();	
		}
	}
	
	
	//现金银行报表
	public function bankBalance_detail() {
	    $this->common_model->checkpurview(106);
		$info = $v = array();
		$sum1 = $sum2 = $sum3 = $sum4 = $sum5 = $sum6 = 0;
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$accountNo   = str_enhtml($this->input->get_post('accountNo',TRUE));
		$beginDate   = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate     = str_enhtml($this->input->get_post('endDate',TRUE));
		$where1      = $accountNo ? ' and d.number in('.str_quote($accountNo).')' : ''; 
		$where2      = $accountNo ? ' and a.number in('.str_quote($accountNo).')' : ''; 
		$list = $this->data_model->get_bankBalance($beginDate,$endDate,$where1,$where2);
		foreach ($list as $arr=>$row) {
		    if (intval($row['billId'])>0) {
				$sum1 += $income      = $row['balance']>0 ? abs($row['balance']) : 0;       //收入
				$sum2 += $expenditure = $row['balance']<0 ? abs($row['balance']) : 0;       //支出
				$sum3 += $income;     
				$sum4 += $expenditure;       
			} else {
			    $sum1 = $sum2 = 0;
			    $income = $expenditure = 0;
			    $sum5 += $balance = $row['balance'];
			}
			$v[$arr]['accountName']   = $row['accountName'];
		    $v[$arr]['accountNumber'] = $row['accountNumber'];
			$v[$arr]['billType']      = $row['billType'];
			$v[$arr]['date']          = $row['date']; 
			$v[$arr]['buId']          = '';
			$v[$arr]['buName']        = $row['buName']; 
			$v[$arr]['billTypeNo']    = '';
			$v[$arr]['balance']       = $balance + $sum1 - $sum2; 
			$v[$arr]['billId']        = 0;   
			$v[$arr]['billNo']        = '';   
			$v[$arr]['expenditure']   = isset($expenditure) ? $expenditure :0; 
			$v[$arr]['income']        = isset($income) ? $income :0; 
			$v[$arr]['type']          = 0;
			$sum6 = $sum5 + $sum3 - $sum4; 
		}
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['data']['list']                    = isset($v) ? $v :'';
		$json['data']['total']['accountName']    = '';
		$json['data']['total']['accountNumber']  = '';
		$json['data']['total']['balance']        = $sum6;
		$json['data']['total']['billNo']         = '';
		$json['data']['total']['billTypeNo']     = '';
		$json['data']['total']['billId']         = '';
		$json['data']['total']['billType']       = '';
		$json['data']['total']['buName']         = '';
		$json['data']['total']['buNo']           = '';
		$json['data']['total']['date']           = '';
		$json['data']['total']['expenditure']    = $sum4;          
		$json['data']['total']['income']         = $sum3;
		$json['data']['total']['type']           = '';
		$json['data']['params']['startTime']     = '';
		$json['data']['params']['numberFilter']  = '';
		$json['data']['params']['keyword']       = '';
		$json['data']['params']['dbid']          = '';
		$json['data']['params']['endDate']       = $endDate;
		$json['data']['params']['beginDate']     = $beginDate;
		die(json_encode($json));
	}
	
	
	//现金银行报表(导出)
	public function bankBalance_exporter() {
	    $this->common_model->checkpurview(107);
		$name = 'BankBalanc_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('现金银行报表导出:'.$name);
	    $data['accountNo']  = $accountNo   = str_enhtml($this->input->get_post('accountNo',TRUE));
		$data['beginDate']  = $beginDate   = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate     = str_enhtml($this->input->get_post('endDate',TRUE));
		$where1             = $accountNo ? ' and d.number in('.str_quote($accountNo).')' : ''; 
		$where2             = $accountNo ? ' and a.number in('.str_quote($accountNo).')' : ''; 
		$data['list'] = $this->data_model->get_bankBalance($beginDate,$endDate,$where1,$where2);
		$this->load->view('report/bankBalance-exporter', $data);	
	}
 
	//应付账款明细表
	public function account_pay_detail_new() {
	    $this->common_model->checkpurview(52);
	    $data['accountNo']  = $accountNo  = intval($this->input->get_post('accountNo',TRUE));
		$data['beginDate']  = $beginDate   = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate     = str_enhtml($this->input->get_post('endDate',TRUE));
		$this->load->view('report/account-pay-detail-new', $data);	
	}
	
	
	public function fundBalance() {
	    switch ($this->action) {
		    case 'detail':
			    $this->fundBalance_detail();break;  
			case 'exporter':
			    $this->fundBalance_exporter();break; 
			case 'detailSupplier':
			    $this->fundBalance_detailSupplier();break;  
			case 'exporterSupplier':
			    $this->fundBalance_exporterSupplier();break; 
			default:  
			    $this->fundBalance_detailSupplier();	
		}
	}
	 
	 
	 
	 
	//应付账款明细表 
	public function fundBalance_detailSupplier() {
	    $this->common_model->checkpurview(52);
		$sum0 = $sum1 = $sum2 = $sum3 = $sum4 = $sum5 = 0;
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$type = intval($this->input->get_post('type',TRUE)); 
		$categoryId  = intval($this->input->get_post('categoryId',TRUE));
		$accountNo   = str_enhtml($this->input->get_post('accountNo',TRUE));
		$beginDate   = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate     = str_enhtml($this->input->get_post('endDate',TRUE));
		$where1 = '';
		$where1 .= $accountNo ? ' and b.number in('.str_quote($accountNo).')' : ''; 
		$where1 .= $categoryId>0 ? ' and b.cCategory='.$categoryId.'' : '';
		$where1 .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		$where1 .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		$where2 = '';
		$where2 .= $accountNo ? ' and number in('.str_quote($accountNo).')' : ''; 
		$list = $this->data_model->get_fundBalance_detailSupplier($where1,$where2,$beginDate);
		foreach ($list as $arr=>$row) {
		    $sum0 += $row['id']==0 ? $row['arrears'] :0;  
		    $sum1 += $income = $row['billType']=='PUR' ? $row['arrears'] : 0;                 //采购
			$sum2 += $expenditure = $row['billType']=='PAYMENT' ? abs($row['arrears']) : 0;   //支付
			if ($row['id']==0) {
			    $balance = $row['arrears'];
			} elseif ($row['billNo']=='小计'){
			    $income      = $sum1;   
				$expenditure = $sum2;   
				$balance     = $row['arrears'] + $sum0;
				$sum3 += $income; 
				$sum4 += $expenditure;
				$sum5 += $sum0 + $sum1 - $sum2; 
				$sum0 = $sum1 = $sum2 = 0;  
			} else {	
			    $balance = $sum0 + $sum1 - $sum2; 
			}
			$v[$arr]['balance']     = $balance;
		    $v[$arr]['billId']      = $row['id']; 
			$v[$arr]['billNo']      = $row['billNo'];
			$v[$arr]['billType']    = $row['billType'];
			$v[$arr]['date']        = $row['billDate'];
			$v[$arr]['billTypeNo']  = $row['billType'];
			$v[$arr]['buId']        = $row['buId'];
			$v[$arr]['buName']      = $row['contactName'];
			$v[$arr]['number']      = $row['contactNo'];
			$v[$arr]['transType']   = $row['transTypeName']; 
			$v[$arr]['type']        = -1;  
			$v[$arr]['expenditure'] = $expenditure; 
			$v[$arr]['income']      = $income;  
		} 
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['data']['total']['balance']        = $sum5;
		$json['data']['total']['billId']         = '';
		$json['data']['total']['billNo']         = '';
		$json['data']['total']['billTypeNo']     = '';
		$json['data']['total']['buId']           = '';
		$json['data']['total']['buName']         = '';
		$json['data']['total']['date']           = '';
		$json['data']['total']['income']         = $sum3;
		$json['data']['total']['expenditure']    = $sum4;
		$json['data']['total']['number']         = '';
		$json['data']['total']['transType']      = '';
		$json['data']['total']['type']           = '';
		$json['data']['list']                    = isset($v) ? array_values($v) :'';
		$json['data']['params']['startTime']     = '';
		$json['data']['params']['numberFilter']  = '';
		$json['data']['params']['categoryId']    = '';
		$json['data']['params']['keyword']       = '';
		$json['data']['params']['dbid']          = '';
		$json['data']['params']['table']         = '';
		$json['data']['params']['serviceType']   = '';
		$json['data']['params']['customer']      = '';
		$json['data']['params']['type']          = '';
		$json['data']['params']['supplier']      = '';
		$json['data']['params']['endDate']       = $endDate;
		$json['data']['params']['beginDate']     = $beginDate;
		die(json_encode($json)); 
	} 
	 
	//应付账款明细表 (导出)
	public function fundBalance_exporterSupplier() {
	    $this->common_model->checkpurview(53);
		$name = 'pay_balance_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('应付账款明细表导出:'.$name); 
		$type = intval($this->input->get_post('type',TRUE)); 
		$categoryId  = intval($this->input->get_post('categoryId',TRUE));
		$accountNo   = str_enhtml($this->input->get_post('accountNo',TRUE));
		$data['beginDate']  = $beginDate   = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate     = str_enhtml($this->input->get_post('endDate',TRUE));
		$where1 = '';
		$where1 .= $accountNo ? ' and b.number in('.str_quote($accountNo).')' : ''; 
		$where1 .= $categoryId>0 ? ' and b.cCategory='.$categoryId.'' : '';
		$where1 .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		$where1 .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		$where2 = '';
		$where2 .= $accountNo ? ' and number in('.str_quote($accountNo).')' : ''; 
		$data['list'] = $this->data_model->get_fundBalance_detailSupplier($where1,$where2,$beginDate);
		$this->load->view('report/fundBalance-exporterSupplier',$data);
	}
	
	
	 
	
 
	//应收账款明细表
	public function account_proceeds_detail_new() {
	    $this->common_model->checkpurview(55);
	    $data['accountNo']  = $accountNo  = intval($this->input->get_post('accountNo',TRUE));
		$data['beginDate']  = $beginDate   = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate     = str_enhtml($this->input->get_post('endDate',TRUE));
		$this->load->view('report/account-proceeds-detail-new',$data);	
	}
	
	
	//应收账款明细表
	public function fundBalance_detail() {
	    $this->common_model->checkpurview(55);
		$sum0 = $sum1 = $sum2 = $sum3 = $sum4 = $sum5 = 0;
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$type = intval($this->input->get_post('type',TRUE)); 
		$categoryId  = intval($this->input->get_post('categoryId',TRUE));
		$accountNo   = str_enhtml($this->input->get_post('accountNo',TRUE));
		$beginDate   = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate     = str_enhtml($this->input->get_post('endDate',TRUE));
		$where1 = '';
		$where1 .= $accountNo ? ' and b.number in('.str_quote($accountNo).')' : ''; 
		$where1 .= $categoryId>0 ? ' and b.cCategory='.$categoryId.'' : '';
		$where1 .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		$where1 .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		$where2 = '';
		$where2 .= $accountNo ? ' and number in('.str_quote($accountNo).')' : ''; 
		$list = $this->data_model->get_fundBalance_detail($where1,$where2,$beginDate);
		foreach ($list as $arr=>$row) {
		    $sum0 += $row['id']==0 ? $row['arrears'] :0;  
		    $sum1 += $income = $row['billType']=='SALE' ? $row['arrears'] : 0;                 //销售
			$sum2 += $expenditure = $row['billType']=='RECEIPT' ? abs($row['arrears']) : 0;    //收款
			if ($row['id']==0) {
			    $balance = $row['arrears'];
			} elseif ($row['billNo']=='小计'){
			    $income      = $sum1;   
				$expenditure = $sum2;   
				$balance     = $row['arrears'] + $sum0;
				$sum3 += $income; 
				$sum4 += $expenditure;
				$sum5 += $sum0 + $sum1 - $sum2; 
				$sum0 = $sum1 = $sum2 = 0;  
			} else {	
			    $balance = $sum0 + $sum1 - $sum2; 
			}
			$v[$arr]['balance']     = $balance;
		    $v[$arr]['billId']      = $row['id']; 
			$v[$arr]['billNo']      = $row['billNo'];
			$v[$arr]['billType']    = $row['billType'];
			$v[$arr]['date']        = $row['billDate'];
			$v[$arr]['billTypeNo']  = $row['billType'];
			$v[$arr]['buId']        = $row['buId'];
			$v[$arr]['buName']      = $row['contactName'];
			$v[$arr]['number']      = $row['contactNo'];
			$v[$arr]['transType']   = $row['transTypeName']; 
			$v[$arr]['type']        = -1;  
			$v[$arr]['expenditure'] = $expenditure; 
			$v[$arr]['income']      = $income;  
		} 
		$json['status'] = 200;
		$json['msg']    = 'success';
		$json['data']['total']['balance']        = $sum5;
		$json['data']['total']['billId']         = '';
		$json['data']['total']['billNo']         = '';
		$json['data']['total']['billTypeNo']     = '';
		$json['data']['total']['buId']           = '';
		$json['data']['total']['buName']         = '';
		$json['data']['total']['date']           = '';
		$json['data']['total']['income']         = $sum3;
		$json['data']['total']['expenditure']    = $sum4;
		$json['data']['total']['number']         = '';
		$json['data']['total']['transType']      = '';
		$json['data']['total']['type']           = '';
		$json['data']['list']                    = isset($v) ? array_values($v) :'';
		$json['data']['params']['startTime']     = '';
		$json['data']['params']['numberFilter']  = '';
		$json['data']['params']['categoryId']    = '';
		$json['data']['params']['keyword']       = '';
		$json['data']['params']['dbid']          = '';
		$json['data']['params']['table']         = '';
		$json['data']['params']['serviceType']   = '';
		$json['data']['params']['customer']      = '';
		$json['data']['params']['type']          = '';
		$json['data']['params']['supplier']      = '';
		$json['data']['params']['endDate']       = $endDate;
		$json['data']['params']['beginDate']     = $beginDate;
		die(json_encode($json));
	}
	
	
	//应收账款明细表(导出)
	public function fundBalance_exporter() {
	    $this->common_model->checkpurview(56);
		$name = 'receive_balance_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('应收账款明细表导出:'.$name);
		$type = intval($this->input->get_post('type',TRUE)); 
		$categoryId  = intval($this->input->get_post('categoryId',TRUE));
		$accountNo   = str_enhtml($this->input->get_post('accountNo',TRUE));
		$data['beginDate']  = $beginDate   = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate     = str_enhtml($this->input->get_post('endDate',TRUE));
		$where1 = '';
		$where1 .= $accountNo ? ' and b.number in('.str_quote($accountNo).')' : ''; 
		$where1 .= $categoryId>0 ? ' and b.cCategory='.$categoryId.'' : '';
		$where1 .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		$where1 .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		$where2 = '';
		$where2 .= $accountNo ? ' and number in('.str_quote($accountNo).')' : ''; 
		$data['list'] = $this->data_model->get_fundBalance_detail($where1,$where2,$beginDate);
		$this->load->view('report/fundBalance-exporter',$data);	
	}
	
	
 
	//客户对账单
	public function customers_reconciliation_new() {
	    $this->common_model->checkpurview(109);
	    $data['customerId'] = $customerId  = intval($this->input->get_post('customerId',TRUE));
		$data['customerName']  = str_enhtml($this->input->get_post('customerName',TRUE));
		$data['beginDate']  = $beginDate   = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate     = str_enhtml($this->input->get_post('endDate',TRUE));
		$this->load->view('report/customers-reconciliation-new',$data);	
	}
	
	
	public function customerBalance() {
	    switch ($this->action) {
		    case 'detail':
			    $this->customerBalance_detail();break;  
			case 'exporter':
			    $this->customerBalance_exporter();break; 
			default:  
			    $this->customerBalance_detail();	
		}
	}
 
	//客户对账单
	public function customerBalance_detail() {
	    $this->common_model->checkpurview(109);
		$info = array();
		$sum1 = $sum2 = $sum3 = $sum4 = $sum5 = $sum6 = 0;
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$showDetail   = str_enhtml($this->input->get_post('showDetail',TRUE));
		$customerId   = intval($this->input->get_post('customerId',TRUE));
		$customerName = str_enhtml($this->input->get_post('customerName',TRUE));
		$beginDate    = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate      = str_enhtml($this->input->get_post('endDate',TRUE));
		$where1 = 'a.isDelete=0';
		$where1 .= ' and a.id='.$customerId;
		
		$where2 = 'a.isDelete=0 and (a.billType="SALE" or a.billType="RECEIPT")';
		$where2 .= ' and a.buId='.$customerId;
		$where2 .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		$where2 .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		$where2 .= $this->common_model->get_admin_purview();
		foreach($this->data_model->get_invoice($where2.' order by a.billDate') as $arr=>$row) {
			$info[$row['buId']][]= $row;
		}
		$contact = $this->data_model->get_contact_ini($where1.' order by a.id desc',$beginDate);
		foreach ($contact as $arr=>$row) {
		    $arrears = $row['arrears'];
			$v[$arr]['amount']        = 0;
			$v[$arr]['billId']        = 0; 
			$v[$arr]['billNo']        = '期初余额'; 
			$v[$arr]['billType']      = 'BAL'; 
			$v[$arr]['date']          = '';
			$v[$arr]['disAmount']     = 0;
			$v[$arr]['entryId']       = 0; 
			$v[$arr]['inAmount']      = $row['arrears'];                   //期初应收款余额
			$v[$arr]['invName']       = ''; 
			$v[$arr]['invNo']         = ''; 
			$v[$arr]['price']         = '';  
			$v[$arr]['qty']           = '';  
			$v[$arr]['spec']          = '';
			$v[$arr]['rpAmount']      = 0;  
			$v[$arr]['totalAmount']   = 0;  
			$v[$arr]['transType']     = '';   
			$v[$arr]['type']          = -1;   
			$v[$arr]['unit']          = ''; 
			if (isset($info[$row['id']])) {
				foreach ($info[$row['id']] as $arr1=>$row1) {
					$arr1 = time() + $arr1 + $row['id'];
					$sum1 += $row1['arrears'];              //应收余款
					$sum2 += $row1['amount'];               //应收金额
					$sum3 += $row1['totalAmount'];          //销售金额
					$sum4 += $row1['rpAmount'];             //实际收款金额
					$sum5 += $row1['disAmount'];            //折扣率
					$v[$arr1]['amount']      = (float)$row1['amount']; //应收金额
					$v[$arr1]['billId']      = intval($row1['id']); 
					$v[$arr1]['billNo']      = $row1['billNo']; 
					$v[$arr1]['billType']    = $row1['billType']; 
					$v[$arr1]['date']        = $row1['billDate'];
					$v[$arr1]['disAmount']   = $row1['disAmount'];
					$v[$arr1]['entryId']     = 0; 
					$v[$arr1]['inAmount']    = $sum1 + $arrears;       //应收款余额
					$v[$arr1]['invName']     = ''; 
					$v[$arr1]['invNo']       = ''; 
					$v[$arr1]['price']       = '';  
					$v[$arr1]['qty']         = '';  
					$v[$arr1]['spec']        = '';
					$v[$arr1]['rpAmount']    = $row1['rpAmount'];  
					$v[$arr1]['totalAmount'] = $row1['totalAmount'];        //销售金额
					$v[$arr1]['transType']   = $row1['transTypeName'];   
					$v[$arr1]['type']        = 1;   
					$v[$arr1]['unit']        = ''; 
					if ($showDetail == "true") {
						if ($row1['billType']=='SALE') {
							$postData = unserialize($row1['postData']);
							foreach ($postData['entries'] as $arr2=>$row2) {
								$arr2 = time() + $arr2 + $row1['id'];
								$v[$arr2]['amount']       = 0;
								$v[$arr2]['billId']       = intval($row1['id']); 
								$v[$arr2]['billNo']       = ''; 
								$v[$arr2]['billType']     = ''; 
								$v[$arr2]['date']         = '';
								$v[$arr2]['disAmount']    = 0;
								$v[$arr2]['entryId']      = 1; 
								$v[$arr2]['inAmount']     = $sum1 + $arrears;  
								$v[$arr2]['invName']      = isset($row2['invName']) ? $row2['invName'] :'';
								$v[$arr2]['invNo']        = isset($row2['invNumber']) ? $row2['invNumber'] :'';
								$v[$arr2]['price']        = isset($row2['price']) ? $row2['price'] :'';   
								$v[$arr2]['qty']          = isset($row2['qty']) ? $row2['qty'] :0;   
								$v[$arr2]['rpAmount']     = 0;  
								$v[$arr2]['spec']         = isset($row2['invSpec']) ? $row2['invSpec'] :'';    
								$v[$arr2]['totalAmount']  = isset($row2['amount']) ? $row2['amount'] :0;   
								$v[$arr2]['transType']    = '';   
								$v[$arr2]['type']         = '';   
								$v[$arr2]['unit']         = 0; 
							}
						}
					} 
				}	
			}
		}	
		$data['status'] = 200;
		$data['msg']    = 'success';
		$data['data']['customerId']              = $customerId;
		$data['data']['showDetail']              = (bool)$showDetail;
		$data['data']['total']['amount']         = $sum2;
		$data['data']['total']['billNo']         = '';
		$data['data']['total']['billTypeNo']     = '';
		$data['data']['total']['billId']         = '';
		$data['data']['total']['billType']       = '';
		$data['data']['total']['buName']         = '';
		$data['data']['total']['buNo']           = '';
		$data['data']['total']['date']           = '';
		$data['data']['total']['disAmount']      = $sum5;
		$data['data']['total']['inAmount']       = $arrears + $sum1;
		$data['data']['total']['entryId']        = '';
		$data['data']['total']['invName']        = '';
		$data['data']['total']['invNo']          = '';
		$data['data']['total']['price']          = '';
		$data['data']['total']['qty']            = '';
		$data['data']['total']['rpAmount']       = $sum4;
		$data['data']['total']['spec']           = '';
		$data['data']['total']['totalAmount']    = $sum3;
		$data['data']['total']['transType']      = '';
		$data['data']['total']['type']           = '';
		$data['data']['total']['unit']           = '';
		$data['data']['list']                    = isset($v) ? array_values($v) : array();  
		die(json_encode($data));	
	}
	
	//客户对账单(导出)
	public function customerBalance_exporter() {
	    $this->common_model->checkpurview(110);
		$name = 'contact_balance_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('客户对账单导出:'.$name);
		$data['showDetail'] = $showDetail   = str_enhtml($this->input->get_post('showDetail',TRUE));
	    $data['customerId'] = $customerId  = intval($this->input->get_post('customerId',TRUE));
		$data['customerName']  = str_enhtml($this->input->get_post('customerName',TRUE));
		$data['beginDate']  = $beginDate   = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate     = str_enhtml($this->input->get_post('endDate',TRUE));
		
		$where1 = 'a.isDelete=0';
		$where1 .= ' and a.id='.$customerId;
		
		$where2 = 'a.isDelete=0 and (a.billType="SALE" or a.billType="RECEIPT")';
		$where2 .= ' and a.buId='.$customerId;
		$where2 .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		$where2 .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		$where2 .= $this->common_model->get_admin_purview();
		$data['list1'] = $this->data_model->get_contact_ini($where1.' order by a.id desc',$beginDate,1);
		$data['list2'] = $this->data_model->get_invoice($where2.' order by a.billDate');
		$this->load->view('report/customerBalance-exporter',$data);	
	}
	
	
	 
	//供应商对账单
	public function suppliers_reconciliation_new() {
	    $this->common_model->checkpurview(112);
	    $data['supplierId'] = $supplierId  = intval($this->input->get_post('supplierId',TRUE));
		$data['supplierName']  = str_enhtml($this->input->get_post('supplierName',TRUE));
		$data['beginDate']  = $beginDate   = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate     = str_enhtml($this->input->get_post('endDate',TRUE));
		$this->load->view('report/suppliers-reconciliation-new',$data);	
	}
	
	
	public function supplierBalance() {
	    switch ($this->action) {
		    case 'detail':
			    $this->supplierBalance_detail();break;  
			case 'exporter':
			    $this->supplierBalance_exporter();break; 
			default:  
			    $this->supplierBalance_detail();	
		}
	}
	
	
	//供应商对账单
	public function supplierBalance_detail() {
	    $this->common_model->checkpurview(112);
		$sum1 = $sum2 = $sum3 = $sum4 = $sum5 = $sum6 = 0;
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$showDetail   = str_enhtml($this->input->get_post('showDetail',TRUE));
		$supplierId   = intval($this->input->get_post('supplierId',TRUE));
		$supplierName = str_enhtml($this->input->get_post('supplierName',TRUE));
		$beginDate    = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate      = str_enhtml($this->input->get_post('endDate',TRUE));
		
		$where1 = 'a.isDelete=0';
		$where1 .= ' and a.id='.$supplierId;
		
		$where2 = 'a.isDelete=0 and (a.billType="PUR" or a.billType="PAYMENT")';
		$where2 .= ' and a.buId='.$supplierId;
		$where2 .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		$where2 .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		$where2 .= $this->common_model->get_admin_purview();
		
		$invoice = $this->data_model->get_invoice($where2.' order by a.billDate');
		foreach($invoice as $arr=>$row) {
			$info[$row['buId']][]= $row;
		}
		$contact = $this->data_model->get_contact_ini($where1.' order by a.id desc',$beginDate);
		foreach ($contact as $arr=>$row) {
		    $arrears = $row['arrears'];
			$v[$arr]['amount']        = 0;
			$v[$arr]['billId']        = 0; 
			$v[$arr]['billNo']        = '期初余额'; 
			$v[$arr]['billType']      = 'BAL'; 
			$v[$arr]['date']          = '';
			$v[$arr]['disAmount']     = 0;
			$v[$arr]['entryId']       = 0; 
			$v[$arr]['inAmount']      = $row['arrears'];                   //期初应收款余额
			$v[$arr]['invName']       = ''; 
			$v[$arr]['invNo']         = ''; 
			$v[$arr]['price']         = '';  
			$v[$arr]['qty']           = '';  
			$v[$arr]['spec']          = '';
			$v[$arr]['rpAmount']      = 0;  
			$v[$arr]['totalAmount']   = 0;  
			$v[$arr]['transType']     = '';   
			$v[$arr]['type']          = -1;   
			$v[$arr]['unit']          = ''; 
			if (isset($info[$row['id']])) {
				foreach ($info[$row['id']] as $arr1=>$row1) {
					$arr1 = time() + $arr1 + $row['id'];
					$sum1 += $row1['arrears'];                              //应收余款
					$sum2 += $row1['amount'];                               //应收金额
					$sum3 += $row1['totalAmount'];                          //销售金额
					$sum4 += $row1['rpAmount'];                             //实际收款金额
					$sum5 += $row1['disAmount'];                            //折扣率
					$v[$arr1]['amount']      = (float)$row1['amount'];      //应收金额
					$v[$arr1]['billId']      = intval($row1['id']); 
					$v[$arr1]['billNo']      = $row1['billNo']; 
					$v[$arr1]['billType']    = $row1['billType']; 
					$v[$arr1]['date']        = $row1['billDate'];
					$v[$arr1]['disAmount']   = $row1['disAmount'];
					$v[$arr1]['entryId']     = 0; 
					$v[$arr1]['inAmount']    = $sum1 + $arrears;            //应收款余额
					$v[$arr1]['invName']     = ''; 
					$v[$arr1]['invNo']       = ''; 
					$v[$arr1]['price']       = '';  
					$v[$arr1]['qty']         = '';  
					$v[$arr1]['spec']        = '';
					$v[$arr1]['rpAmount']    = $row1['rpAmount'];  
					$v[$arr1]['totalAmount'] = $row1['totalAmount'];        //销售金额
					$v[$arr1]['transType']   = $row1['transTypeName'];   
					$v[$arr1]['type']        = 1;   
					$v[$arr1]['unit']        = ''; 
					if ($showDetail == "true") {
						if ($row1['billType']=='PUR') {
							$postData = unserialize($row1['postData']);
							foreach ($postData['entries'] as $arr2=>$row2) {
								$arr2 = time() + $arr2 + $row1['id'];
								$v[$arr2]['amount']       = 0;
								$v[$arr2]['billId']       = intval($row1['id']); 
								$v[$arr2]['billNo']       = ''; 
								$v[$arr2]['billType']     = ''; 
								$v[$arr2]['date']         = '';
								$v[$arr2]['disAmount']    = 0;
								$v[$arr2]['entryId']      = 1; 
								$v[$arr2]['inAmount']     = $sum1 + $arrears;  
								$v[$arr2]['invName']      = isset($row2['invName']) ? $row2['invName'] :'';
								$v[$arr2]['invNo']        = isset($row2['invNumber']) ? $row2['invNumber'] :'';
								$v[$arr2]['price']        = isset($row2['price']) ? $row2['price'] :'';   
								$v[$arr2]['qty']          = isset($row2['qty']) ? $row2['qty'] :0;   
								$v[$arr2]['rpAmount']     = 0;  
								$v[$arr2]['spec']         = isset($row2['invSpec']) ? $row2['invSpec'] :'';    
								$v[$arr2]['totalAmount']  = isset($row2['amount']) ? $row2['amount'] :0;   
								$v[$arr2]['transType']    = '';   
								$v[$arr2]['type']         = '';   
								$v[$arr2]['unit']         = 0; 
							}
						}
					} 
				}	
			}
		}	
		
		$data['status'] = 200;
		$data['msg']    = 'success';
		$data['data']['customerId']              = $supplierId;
		$data['data']['showDetail']              = (bool)$showDetail;
		$data['data']['total']['amount']         = $sum2;
		$data['data']['total']['billNo']         = '';
		$data['data']['total']['billTypeNo']     = '';
		$data['data']['total']['billId']         = '';
		$data['data']['total']['billType']       = '';
		$data['data']['total']['buName']         = '';
		$data['data']['total']['buNo']           = '';
		$data['data']['total']['date']           = '';
		$data['data']['total']['disAmount']      = $sum5;
		$data['data']['total']['inAmount']       = $arrears + $sum1;
		$data['data']['total']['entryId']        = '';
		$data['data']['total']['invName']        = '';
		$data['data']['total']['invNo']          = '';
		$data['data']['total']['price']          = '';
		$data['data']['total']['qty']            = '';
		$data['data']['total']['rpAmount']       = $sum4;
		$data['data']['total']['spec']           = '';
		$data['data']['total']['totalAmount']    = $sum3;
		$data['data']['total']['transType']      = '';
		$data['data']['total']['type']           = '';
		$data['data']['total']['unit']           = '';
		$data['data']['list']                    = isset($v) ? array_values($v) : array();  
		die(json_encode($data));	
	}
	
	
	//供应商对账单(导出)
	public function supplierBalance_exporter() {
	    $this->common_model->checkpurview(113);
		$name = 'supplier_balance_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('供应商对账单导出:'.$name);
		$data['showDetail'] = $showDetail   = str_enhtml($this->input->get_post('showDetail',TRUE));
	    $data['supplierId'] = $supplierId  = intval($this->input->get_post('supplierId',TRUE));
		$data['supplierName']  = str_enhtml($this->input->get_post('supplierName',TRUE));
		$data['beginDate']  = $beginDate   = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate     = str_enhtml($this->input->get_post('endDate',TRUE));
		
		$where1 = 'a.isDelete=0';
		$where1 .= ' and a.id='.$supplierId;
		
		$where2 = 'a.isDelete=0 and (a.billType="PUR" or a.billType="PAYMENT")';
		$where2 .= ' and a.buId='.$supplierId;
		$where2 .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		$where2 .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		$where2 .= $this->common_model->get_admin_purview();
		$data['list1'] = $this->data_model->get_contact_ini($where1.' order by a.id desc',$beginDate,1);
		$data['list2'] = $this->data_model->get_invoice($where2.' order by a.billDate');
		$this->load->view('report/supplierBalance-exporter',$data);	
	}
	
	
	//其他收支明细表
	public function other_income_expense_detail() {
	    $this->common_model->checkpurview(115);
	    $data['supplierId'] = $supplierId  = intval($this->input->get_post('supplierId',TRUE));
		$data['beginDate']  = $beginDate   = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = $endDate     = str_enhtml($this->input->get_post('endDate',TRUE));
		$this->load->view('report/other-income-expense-detail',$data);	
	}
	
	public function oriDetail() {
	    switch ($this->action) {
		    case 'detail':
			    $this->oriDetail_detail();break;  
			case 'export':
			    $this->oriDetail_export();break; 
			default:  
			    $this->oriDetail_detail();	
		}
	}
	
	//其他收支明细表(接口)
	public function oriDetail_detail() {
	    $this->common_model->checkpurview(115);
		$payment1 = $payment2 = 0;
		$page = max(intval($this->input->get_post('page',TRUE)),1);
		$rows = max(intval($this->input->get_post('rows',TRUE)),100); 
		$transType    = str_enhtml($this->input->get_post('transType',TRUE));
	    $typeName  = str_enhtml($this->input->get_post('typeName',TRUE));
		$beginDate  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$endDate    = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and (a.transType=153401 or a.transType=153402)';
		$where .= $transType ? ' and a.transType='.$transType : ''; 
		$where .= $typeName  ? ' and c.name="'.$typeName.'"' : ''; 
		$where .= $beginDate ? ' and a.billDate>="'.$beginDate.'"' : '';
		$where .= $endDate ? ' and a.billDate<="'.$endDate.'"' : '';
		$where .= $this->common_model->get_admin_purview();
		$offset = $rows * ($page-1);                     
		$list   = $this->data_model->get_account_info($where.' order by a.billDate'); 
		foreach ($list as $arr=>$row) {
		    $v[$arr]['date']           = $row['billDate'];
			$v[$arr]['billId']         = intval($row['iid']);
		    $v[$arr]['billNo']         = $row['billNo'];
			$v[$arr]['transType']      = $row['transType'];
			$v[$arr]['transTypeName']  = $row['transTypeName'];
			$v[$arr]['contactNumber']  = $row['contactNo'];
			$v[$arr]['contactName']    = $row['contactName'];
			$v[$arr]['desc']           = $row['remark'];  
			$v[$arr]['typeName']       = $row['categoryName'];  
			if ($row['transType']==153401) {
				$payment1 += $v[$arr]['amountIn']       = $row['payment'];        //收入
			} else {
				$payment2 += $v[$arr]['amountOut']      = abs($row['payment']);   //支出
			}
		}
		$data['status'] = 200;
		$data['msg']    = 'success';
		$data['data']['page']      = $page;
		$data['data']['records']   = $this->data_model->get_account_info($where,3);   
		$data['data']['total']     = ceil($data['data']['records']/$rows);       
		$data['data']['rows']                      = isset($v) ? $v : array();
		$data['data']['userdata']['date']          = '';
		$data['data']['userdata']['billId']        = '';
		$data['data']['userdata']['billNo']        = '';
		$data['data']['userdata']['transType']     = '';
		$data['data']['userdata']['transTypeName'] = '';
		$data['data']['userdata']['contactNumber'] = '';
		$data['data']['userdata']['contactName']   = '';
		$data['data']['userdata']['desc']          = '';
		$data['data']['userdata']['typeName']      = '';
		$data['data']['userdata']['amountIn']      = $payment1;
		$data['data']['userdata']['amountOut']     = $payment2;
		die(json_encode($data));
	}
 
	 
	//其他收支明细表(导出)
	public function oriDetail_export() {
	    $this->common_model->checkpurview(116);
		$name = 'ori_detail_'.date('YmdHis').'.xls';
		sys_csv($name);
		$this->common_model->logs('其他收支明细表导出:'.$name);
	    $data['transType']  = str_enhtml($this->input->get_post('transType',TRUE));
	    $data['typeName']   = str_enhtml($this->input->get_post('typeName',TRUE));
		$data['beginDate']  = str_enhtml($this->input->get_post('beginDate',TRUE));
		$data['endDate']    = str_enhtml($this->input->get_post('endDate',TRUE));
		$where = 'a.isDelete=0 and (a.transType=153401 or a.transType=153402)';
		$where .= $data['transType'] ? ' and a.transType='.$data['transType'] : ''; 
		$where .= $data['typeName']  ? ' and c.name="'.$data['typeName'].'"' : ''; 
		$where .= $data['beginDate'] ? ' and a.billDate>="'.$data['beginDate'].'"' : '';
		$where .= $data['endDate'] ? ' and a.billDate<="'.$data['endDate'].'"' : '';
		$where .= $this->common_model->get_admin_purview();
		$data['list'] = $this->data_model->get_account_info($where.' order by a.billDate'); 
		$this->load->view('report/oriDetail_export',$data);	
	}
	 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */