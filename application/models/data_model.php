<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_model extends CI_Model{

	public function __construct(){
  		parent::__construct();
	}
	
	 
	public function get_inventory($where='',$type=2) {
	    $sql = 'select 
					a.invId,a.locationId,sum(a.qty) as qty,   
		            b.name as invName, b.number as invNumber,b.spec as invSpec,b.categoryId ,b.categoryName,b.unitName,b.unitid,
					if(d.lowQty>=0,d.lowQty,b.lowQty) as lowQty,
					if(d.highQty>=0,d.highQty,b.highQty) as highQty,
					(sum(a.qty) - if(d.highQty>=0,d.highQty,b.highQty)) as qty1,
					(sum(a.qty) - if(d.lowQty>=0,d.lowQty,b.lowQty)) as qty2,
					c.name as locationName
		        from '.$this->db->dbprefix('invoice_info').' as a 
					left join 
						(select id,name,number,spec,unitName,unitid,lowQty,highQty,categoryId,categoryName from '.$this->db->dbprefix('goods').' where isDelete=0) as b 
					on a.invId=b.id 
					left join 
						(select id,name,locationNo from '.$this->db->dbprefix('storage').' where isDelete=0) as c 
					on a.locationId=c.id 
					left join 
						(select id,lowQty,highQty,locationId,invId from '.$this->db->dbprefix('warehouse').' group by invId,locationId) as d
					on a.invId=d.invId and a.locationId=d.locationId
				where '.$where;
		return $this->mysql_model->query($sql,$type);		
	}	


	 
	public function get_invoice_info_inventory() {
		$list = $this->mysql_model->query('select invId,locationId,sum(qty) as qty from '.$this->db->dbprefix('invoice_info').' where isDelete=0 group by invId,locationId',2);
		foreach($list as $arr=>$row){
		    $v[$row['invId']][$row['locationId']] = $row['qty'];
		}		
		return isset($v) ? $v :array();				
	}		
	
	
	 
	public function get_order($where='',$type=2) {
	    $sql = 'select 
		            a.*,
					b.name as contactName,b.number as contactNo,   
					c.number as salesNo ,c.name as salesName, 
					d.number as accountNumber ,d.name as accountName
				from '.$this->db->dbprefix('order').' as a 
					left join 
						(select id,number, name from '.$this->db->dbprefix('contact').' where isDelete=0) as b
					on a.buId=b.id 
					left join 
						(select id,name,number from '.$this->db->dbprefix('staff').' where isDelete=0) as c
					on a.salesId=c.id 
					left join 
					    (select id,name,number from '.$this->db->dbprefix('account').' where isDelete=0) as d 
					on a.accId=d.id 
				where '.$where;
		return $this->mysql_model->query($sql,$type);	
	}	
	
	 
	public function get_order_info($where='',$type=2) {
	    $sql = 'select 
		            a.*, 
					b.name as invName, b.number as invNumber, b.spec as invSpec, 
					b.unitName as mainUnit,b.pinYin,b.purPrice,b.quantity,b.salePrice,b.unitId,
					c.number as contactNo, c.name as contactName,
					d.name as locationName ,d.locationNo ,
					e.number as salesNo ,e.name as salesName
				from '.$this->db->dbprefix('order_info').' as a 
					left join 
						(select id,name,number,spec,unitName,unitId,pinYin,purPrice,quantity,salePrice from '.$this->db->dbprefix('goods').' where isDelete=0) as b 
					on a.invId=b.id 
					left join 
						(select id,number, name from '.$this->db->dbprefix('contact').' where isDelete=0) as c
					on a.buId=c.id 	
					left join 
						(select id,name,locationNo from '.$this->db->dbprefix('storage').' where isDelete=0) as d 
					on a.locationId=d.id 
					left join 
						(select id,name,number from '.$this->db->dbprefix('staff').' where isDelete=0) as e
					on a.salesId=e.id 	
				where '.$where;
		return $this->mysql_model->query($sql,$type); 	
	}
	
	
	 
	public function get_invoice($where='',$type=2) {
	    $sql = 'select 
		            a.*,
					b.name as contactName,b.number as contactNo,   
					c.number as salesNo ,c.name as salesName, 
					d.number as accountNumber ,d.name as accountName,
					(a.rpAmount + ifnull(e.nowCheck,0)) as hasCheck
				from '.$this->db->dbprefix('invoice').' as a 
					left join 
						(select id,number, name from '.$this->db->dbprefix('contact').' where isDelete=0) as b
					on a.buId=b.id 
					left join 
						(select id,name,number from '.$this->db->dbprefix('staff').' where isDelete=0) as c
					on a.salesId=c.id 
					left join 
					    (select id,name,number from '.$this->db->dbprefix('account').' where isDelete=0) as d 
					on a.accId=d.id 
					left join 
					    (select billId,sum(nowCheck) as nowCheck from '.$this->db->dbprefix('verifica_info').' where isDelete=0 and checked=1 group by billId) as e
					on a.id=e.billId
				where '.$where;
		return $this->mysql_model->query($sql,$type);	
	}	
	
	
	 
	public function get_profit($where) {
	    $sql = 'select 
					invId,locationId,billDate,sum(qty) as qty,
					sum(case when transType=150501 or transType=150502 or transType=150807 or transType=150706 or billType="INI" or transType=103091 then amount else 0 end) as inamount,
					sum(case when transType=150501 or transType=150502 or transType=150706 or billType="INI" or transType=103091 then qty else 0 end) as inqty
				from '.$this->db->dbprefix('invoice_info').' where isDelete=0 '.$where.' group by invId,locationId';
	    $list = $this->mysql_model->query($sql,2); 	
		foreach($list as $arr=>$row){
		    $v['qty'][$row['invId']][$row['locationId']]      = $row['qty'];                      
			$v['inqty'][$row['invId']][$row['locationId']]    = $row['inqty'];    
			$v['inamount'][$row['invId']][$row['locationId']] = $row['inamount'];   
			$v['inprice'][$row['invId']][$row['locationId']]  = $row['inqty']>0 ? $row['inamount']/$row['inqty'] :0;   
		}		
		return isset($v) ? $v :array();	
	}
	
	 
	public function get_invoice_infosum($where='',$type=2) {
	    $sql = 'select 
		            a.*,sum(a.qty) as sumqty,sum(a.amount) as sumamount,  
					b.name as invName, b.number as invNumber, b.spec as invSpec, 
					b.unitName as mainUnit,b.pinYin,b.purPrice,b.quantity,b.salePrice,b.unitId,b.categoryId,b.categoryName,
					c.number as contactNo, c.name as contactName,c.cCategory,c.cCategoryName,
					d.name as locationName ,d.locationNo ,
					e.number as salesNo ,e.name as salesName
				from '.$this->db->dbprefix('invoice_info').' as a 
					left join 
						(select id,name,number,spec,unitName,unitId,pinYin,purPrice,quantity,salePrice,categoryId,categoryName from '.$this->db->dbprefix('goods').' where isDelete=0) as b 
					on a.invId=b.id 
					left join 
						(select id,number, name,cCategory,cCategoryName from '.$this->db->dbprefix('contact').' where isDelete=0) as c
					on a.buId=c.id 	
					left join 
						(select id,name,locationNo from '.$this->db->dbprefix('storage').' where isDelete=0) as d 
					on a.locationId=d.id 
					left join 
						(select id,name,number from '.$this->db->dbprefix('staff').' where isDelete=0) as e
					on a.salesId=e.id 	
				where '.$where;
		return $this->mysql_model->query($sql,$type); 	
	}
	
	 
	public function get_invoice_info($where='',$type=2) {
	    $sql = 'select 
		            a.*, 
					b.name as invName, b.number as invNumber, b.spec as invSpec, b.categoryName,
					b.categoryId,
					b.unitName as mainUnit,b.pinYin,b.purPrice,b.quantity,b.salePrice,b.unitId,b.id as invId,
					c.number as contactNo, c.name as contactName,c.cCategory,c.cCategoryName,
					d.name as locationName ,d.locationNo ,d.id as locationId,
					e.number as salesNo ,e.name as salesName
				from '.$this->db->dbprefix('invoice_info').' as a 
					left join  '.$this->db->dbprefix('goods').' as b 
					on a.invId=b.id 
					left join '.$this->db->dbprefix('contact').' as c
					on a.buId=c.id 	
					left join '.$this->db->dbprefix('storage').' as d 
					on a.locationId=d.id 
					left join '.$this->db->dbprefix('staff').' as e
					on a.salesId=e.id 	
				where '.$where;
		return $this->mysql_model->query($sql,$type); 	
	}
	
	
	
	 
 
	
	
	 
	public function get_goods_beginning($where='',$beginDate,$type=2) {
	    $sql = 'select 
					a.id,a.categoryName,a.name as invName, a.number as invNumber, a.spec as invSpec,b.qty
				from '.$this->db->dbprefix('goods').' as a 
				left join 
					(select invId,sum(qty) as qty from '.$this->db->dbprefix('invoice_info').' where isDelete=0 and billDate<"'.$beginDate.'" group by invId) as b 
				on a.id=b.invId  
				where '.$where;
		return $this->mysql_model->query($sql,$type); 	 
	}
	
	 //检验单修改后入库
	public function get_purqueryDetails($id,$type=2) {
	    $sql = 'select 
		            a.srcOrderEntryId, a.price,a.tax,a.taxRate,a.deduction,a.skuId,a.discountRate,
					a.billNo,a.invId,a.qty,a.amount,a.description, a.locationId,
					b.name as invName, b.number as invNumber, b.spec as invSpec,
					b.unitName as mainUnit,b.unitId, 
					c.name as locationName,
				    (abs(a.qty) - abs(ifnull(d.qty,0))) as unQty 
				from '.$this->db->dbprefix('order_info').' as a 
				left join 
					(select id,name,number,spec,unitId,unitName,salePrice,purPrice from '.$this->db->dbprefix('goods').' where isDelete=0) as b 
				on a.invId=b.id 
				left join 
					(select id,name,locationNo from '.$this->db->dbprefix('storage').' where isDelete=0) as c 
				on a.locationId=c.id 	
				left join 
					(select id,invId,srcOrderId,srcOrderEntryId,sum(qty) as qty,billDate from '.$this->db->dbprefix('invoice_info').' where isDelete=0 and checked=1 and srcOrderId>0 and billType="PUR" group by invId,srcOrderId,srcOrderEntryId) as d 
				on a.invId=d.invId and a.iid=d.srcOrderId and a.srcOrderEntryId=d.srcOrderEntryId
				where a.isDelete=0 and a.billType="PUR" and a.iid='.$id.'';
 
		return $this->mysql_model->query($sql,$type);//  20170214 207行剪切  a.devicenumber,
	}
	
	 
	public function get_salesqueryDetails($id,$type=2) {
	    $sql = 'select 
		            a.srcOrderEntryId, a.price,a.tax,a.taxRate,a.deduction,a.skuId,a.discountRate,
					a.billNo,a.invId,a.qty,a.amount,a.description, a.locationId,
					b.name as invName, b.number as invNumber, b.spec as invSpec,
					b.unitName as mainUnit,b.unitId, 
					c.name as locationName,
				    (abs(a.qty) - abs(ifnull(d.qty,0))) as unQty 
				from '.$this->db->dbprefix('order_info').' as a 
				left join 
					(select id,name,number,spec,unitId,unitName,salePrice,purPrice from '.$this->db->dbprefix('goods').' where isDelete=0) as b 
				on a.invId=b.id 
				left join 
					(select id,name,locationNo from '.$this->db->dbprefix('storage').' where isDelete=0) as c 
				on a.locationId=c.id 	
				left join 
					(select id,invId,srcOrderId,srcOrderEntryId,sum(qty) as qty,billDate from '.$this->db->dbprefix('invoice_info').' where isDelete=0 and checked=1 and srcOrderId>0 and billType="SALE" group by invId,srcOrderId,srcOrderEntryId) as d 
				on a.invId=d.invId and a.iid=d.srcOrderId and a.srcOrderEntryId=d.srcOrderEntryId
				where a.isDelete=0 and a.billType="SALE" and a.iid='.$id.'';
 
		return $this->mysql_model->query($sql,$type);
	}
	
	 
	public function get_purchasesrcOrder($srcOrderId,$type=2) {
	    $sql = 'select 
		            id,invId,srcOrderId,srcOrderEntryId,sum(qty) as qty,billDate 
				from '.$this->db->dbprefix('invoice_info').' 
				where isDelete=0 and checked=1 and srcOrderId in('.$srcOrderId.') and billType="PUR" group by invId,srcOrderId,srcOrderEntryId';
		return $this->mysql_model->query($sql,$type); 	
	}
	
	
	 
	public function get_salessrcOrder($srcOrderId,$type=2) {
	    $sql = 'select 
		            id,invId,srcOrderId,srcOrderEntryId,sum(qty) as qty,billDate 
				from '.$this->db->dbprefix('invoice_info').' 
				where isDelete=0 and checked=1 and srcOrderId in('.$srcOrderId.') and billType="SALE" group by invId,srcOrderId,srcOrderEntryId';
		return $this->mysql_model->query($sql,$type); 	
	}
	
	
	
	 
	public function get_purchaseOrder($where='',$type=2) {
	    $sql = 'select 
					a.id,a.billNo,a.deliveryDate,a.amount,a.billDate as date,a.iid as billId,a.description, a.locationId,a.invId,
					b.name as invName, b.number as invNo, b.spec,b.unitName as unit, b.purPrice as price,b.unitId, 
					c.name as buName,a.qty,
				    (a.qty - ifnull(d.qty,0)) as unQty,d.billDate as inDate,
				    case status
					    WHEN 0 THEN "未入库"
					    WHEN 1 THEN "部分入库"
						WHEN 2 THEN "已入库"
					end as status
				from '.$this->db->dbprefix('order_info').' as a 
				left join 
					(select id,name,number,spec,unitId,unitName,salePrice,purPrice from '.$this->db->dbprefix('goods').' where isDelete=0) as b 
				on a.invId=b.id 
				left join 
					(select id,number,cCategory,name from '.$this->db->dbprefix('contact').' where isDelete=0) as c
				on a.buId=c.id 	
				left join 
					(select id,invId,srcOrderId,srcOrderEntryId,sum(qty) as qty,billDate from '.$this->db->dbprefix('invoice_info').' where isDelete=0 and checked=1 and srcOrderId>0 and billType="PUR" group by invId,srcOrderId,srcOrderEntryId) as d 
				on a.invId=d.invId and a.iid=d.srcOrderId and a.srcOrderEntryId=d.srcOrderEntryId
				where a.isDelete=0 and a.billType="PUR" '.$where.' 
				
				union all
				
				select 
					"","","","","","","","",a.invId,"", "", "","", "","","",sum(a.qty) as qty,(sum(a.qty) - sum(ifnull(d.qty,0))) as unQty,"","小计"
				from '.$this->db->dbprefix('order_info').' as a 
				left join 
					(select id,name,number,spec,unitId,unitName,salePrice,purPrice from '.$this->db->dbprefix('goods').' where isDelete=0) as b 
				on a.invId=b.id 
				left join 
					(select id,number,cCategory,name from '.$this->db->dbprefix('contact').' where isDelete=0) as c
				on a.buId=c.id 	
				left join 
					(select id,invId,srcOrderId,srcOrderEntryId,sum(qty) as qty,billDate from '.$this->db->dbprefix('invoice_info').' where isDelete=0 and checked=1 and srcOrderId>0 and billType="PUR" group by invId,srcOrderId,srcOrderEntryId) as d 
				on a.invId=d.invId and a.iid=d.srcOrderId and a.srcOrderEntryId=d.srcOrderEntryId
				where a.isDelete=0 and a.billType="PUR" '.$where.' group by a.invId 
				ORDER BY invId ,id desc';
        
		return $this->mysql_model->query($sql,$type); 	
	}
	
	 
	public function get_salesOrder($where='',$type=2) {
	    $sql = 'select 
		            a.id,a.billNo,a.deliveryDate,a.amount,a.billDate as date,a.iid as billId,a.description, a.locationId,a.invId, 
					b.name as invName, b.number as invNo, b.spec,b.unitName as unit,   
					c.name as buName,
					a.qty,
				    (a.qty - ifnull(d.qty,0)) as unQty,d.billDate as inDate,
					e.name as salesName,
				    case status
					    WHEN 0 THEN "未出库"
					    WHEN 1 THEN "部分出库"
						WHEN 2 THEN "已出库"
					end as status
				from '.$this->db->dbprefix('order_info').' as a 
				left join 
					(select id,name,number,spec,unitName,salePrice from '.$this->db->dbprefix('goods').' where isDelete=0) as b 
				on a.invId=b.id 
				left join 
					(select id,number,cCategory,name from '.$this->db->dbprefix('contact').' where isDelete=0) as c
				on a.buId=c.id 	
				left join 
					(select id,invId,srcOrderId,srcOrderEntryId,sum(qty) as qty,billDate from '.$this->db->dbprefix('invoice_info').' where isDelete=0 and checked=1 and srcOrderId>0 and billType="SALE" group by invId,srcOrderId,srcOrderEntryId) as d 
				on a.invId=d.invId and a.iid=d.srcOrderId and a.srcOrderEntryId=d.srcOrderEntryId
				left join 
					(select id,name,number from '.$this->db->dbprefix('staff').' where isDelete=0) as e
				on a.salesId=e.id 
				where a.isDelete=0 and a.billType="SALE" '.$where.'
				
				union all
				
				select 
					"","","","","","","","",a.invId,"", "", "","", "",sum(a.qty) as qty,(sum(a.qty) - sum(ifnull(d.qty,0))) as unQty,"","","小计"
				from '.$this->db->dbprefix('order_info').' as a 
				left join 
					(select id,name,number,spec,unitId,unitName,salePrice,purPrice from '.$this->db->dbprefix('goods').' where isDelete=0) as b 
				on a.invId=b.id 
				left join 
					(select id,number,cCategory,name from '.$this->db->dbprefix('contact').' where isDelete=0) as c
				on a.buId=c.id 	
				left join 
					(select id,invId,srcOrderId,srcOrderEntryId,sum(qty) as qty,billDate from '.$this->db->dbprefix('invoice_info').' where isDelete=0 and checked=1 and srcOrderId>0 and billType="SALE" group by invId,srcOrderId,srcOrderEntryId) as d 
				on a.invId=d.invId and a.iid=d.srcOrderId and a.srcOrderEntryId=d.srcOrderEntryId
				where a.isDelete=0 and a.billType="SALE" '.$where.' group by a.invId 
				ORDER BY invId ,id desc';	
		return $this->mysql_model->query($sql,$type); 	
	}
	
	
	
	 
	public function get_invBalance($where='',$select='',$type=2) {
	    $sql = 'select 
		            a.invId,a.locationId,sum(a.qty) as qty,
					'.$select.'
					sum(case when (a.transType=150501 or a.transType=150502 or a.transType=150807 or a.transType=150706 or a.billType="INI" or a.transType=103091) then amount else 0 end) as inamount,
					sum(case when (a.transType=150501 or a.transType=150502 or a.transType=150706 or a.billType="INI" or a.transType=103091) then qty else 0 end) as inqty,
					b.name as invName, b.number as invNumber, b.spec as invSpec, b.unitName as mainUnit, b.categoryId,b.salePrice,
					c.locationNo
				from '.$this->db->dbprefix('invoice_info').' as a 
					left join 
						(select id,name,number,spec,unitName,categoryId,salePrice from '.$this->db->dbprefix('goods').' where isDelete=0) as b 
					on a.invId=b.id 
					left join 
						(select id,name,locationNo from '.$this->db->dbprefix('storage').' where isDelete=0) as c 
					on a.locationId=c.id 
				where '.$where;	
		return $this->mysql_model->query($sql,$type); 
	}
	
	 
	public function get_deliverSummary($where='',$beginDate,$endDate,$type=2) {
	    $sql = 'select 
		            sum(case when a.billDate<"'.$beginDate.'" then qty else 0 end ) as qty0,
					sum(case when (a.transType=150501 or a.transType=150502 or a.transType=150807 or a.transType=150706 or a.billType="INI" or a.transType=103091) and a.billDate<"'.$beginDate.'" then amount else 0 end) as inamount0,
					sum(case when (transType=150501 or transType=150502 or transType=150706 or billType="INI" or transType=103091) and a.billDate<"'.$beginDate.'" then qty else 0 end) as inqty0,
					
		            sum(qty) as qty14,
					sum(case when (a.transType=150501 or a.transType=150502 or a.transType=150807 or a.transType=150706 or a.billType="INI" or a.transType=103091) and a.billDate<"'.$endDate.'" then amount else 0 end) as inamount14,
					sum(case when (a.transType=150501 or a.transType=150502 or a.transType=150706 or a.billType="INI" or a.transType=103091) and a.billDate<"'.$endDate.'" then qty else 0 end) as inqty14,
					
					sum(case when a.transType=150501 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then qty else 0 end ) as qty2,
					sum(case when a.transType=150502 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then qty else 0 end ) as qty9,
					sum(case when a.transType=150601 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then qty else 0 end ) as qty10,
					sum(case when a.transType=150602 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then qty else 0 end ) as qty3,
					sum(case when a.transType=150701 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then qty else 0 end ) as qty4,
					sum(case when a.transType=150801 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then qty else 0 end ) as qty11,
					sum(case when a.transType=103091 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" and qty>0 then qty else 0 end ) as qty1,
					sum(case when a.transType=103091 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" and qty<0 then qty else 0 end ) as qty8,
					sum(case when a.transType=150807 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then qty else 0 end ) as qty6,
					sum(case when a.transType=150706 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'"then qty else 0 end ) as qty5,
					sum(case when a.transType=150806 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then qty else 0 end ) as qty12,
					
					sum(case when a.transType=150501 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then amount else 0 end ) as amount2,
					sum(case when a.transType=150502 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then amount else 0 end ) as amount9,
					sum(case when a.transType=150601 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then amount else 0 end ) as amount10,
					sum(case when a.transType=150602 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then amount else 0 end ) as amount3,
					sum(case when a.transType=150701 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then amount else 0 end ) as amount4,
					sum(case when a.transType=150702 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then amount else 0 end ) as amount3,
					sum(case when a.transType=150801 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then amount else 0 end ) as amount11,
					sum(case when a.transType=103091 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" and qty>0 then amount else 0 end ) as amount1,
					sum(case when a.transType=103091 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" and qty<0 then amount else 0 end ) as amount8,
					sum(case when a.transType=150807 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then amount else 0 end ) as amount6,
					sum(case when a.transType=150706 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'"then amount else 0 end ) as amount5,
					sum(case when a.transType=150806 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" then amount else 0 end ) as amount12,
					
					
					a.invId, a.locationId,
					b.name as invName, b.number as invNumber, b.spec as invSpec, b.unitName as mainUnit,b.categoryName,
					c.name as locationName ,c.locationNo
				from '.$this->db->dbprefix('invoice_info').' as a 
				    left join 
						(select id,name,number,spec,unitName,categoryName,categoryId from '.$this->db->dbprefix('goods').' where isDelete=0) as b 
					on a.invId=b.id 
					left join 
						(select id,name,locationNo from '.$this->db->dbprefix('storage').' where isDelete=0) as c
					on a.locationId=c.id 
				where '.$where;
		return $this->mysql_model->query($sql,2); 
	}
	

	
	 
	public function get_contact($where='',$type=2) {
	    $sql = 'select 
					a.*,(difMoney+ifnull(b.arrears,0)) as arrears
				from '.$this->db->dbprefix('contact').' as a 
				left join 
					(select buId,billType,sum(arrears) as arrears from '.$this->db->dbprefix('invoice').' where isDelete=0 group by buId) as b 
			    on a.id=b.buId  
				where '.$where;
		return $this->mysql_model->query($sql,$type); 	
	}
	
	 
	public function get_contact_ini($where='',$beginDate,$type=2) {
	    $sql = 'select 
					a.*,(difMoney+ifnull(b.arrears,0)) as arrears
				from '.$this->db->dbprefix('contact').' as a 
				left join 
					(select buId,billType,sum(arrears) as arrears from '.$this->db->dbprefix('invoice').' where isDelete=0 and billDate<"'.$beginDate.'" group by buId) as b 
			    on a.id=b.buId  
				where '.$where;
				
		return $this->mysql_model->query($sql,$type); 	
	}
	
	
	 
	public function get_account($where='',$type=2) {
	    $sql = 'select 
		            a.*,(a.amount+ifnull(b.payment,0)) as amountOver
		        from '.$this->db->dbprefix('account').' as a 
				left join 
				    (select accId,billDate,sum(payment) as payment from '.$this->db->dbprefix('account_info').' where isDelete=0 group by accId) as b 
			    on a.id=b.accId  
				where '.$where;	
				
		return $this->mysql_model->query($sql,$type);		
	}	
	
	 
	public function get_account_ini($where='',$beginDate,$type=2) {
	    $sql = 'select 
		            a.*,(a.amount+ifnull(b.payment,0)) as amountOver
		        from '.$this->db->dbprefix('account').' as a 
				left join 
				    (select accId,billDate,sum(payment) as payment from '.$this->db->dbprefix('account_info').' where isDelete=0 and billDate<"'.$beginDate.'" group by accId) as b 
			    on a.id=b.accId 
				where '.$where;	
				
		return $this->mysql_model->query($sql,$type);		
	}	
 

	 

	 
	public function get_account_info($where='',$type=2) {
	    $sql = 'select 
		            a.id,a.iid,a.accId,a.buId,a.isDelete,a.billType,a.billNo,a.remark,a.billDate,a.payment,a.wayId,a.settlement,a.transType,a.transTypeName,
					b.name as contactName,b.number as contactNo,
					c.name as categoryName,
					d.name as accountName,d.number as accountNumber
				from '.$this->db->dbprefix('account_info').' as a 
				left join 
					(select id,name,number from '.$this->db->dbprefix('contact').' where isDelete=0) as b 
				on a.buId=b.id 
				left join 
					(select id,name from '.$this->db->dbprefix('category').' where isDelete=0) as c 
				on a.wayId=c.id 
				left join 
					(select id,name,number from '.$this->db->dbprefix('account').' where isDelete=0) as d 
				on a.accId=d.id 
				where '.$where;	
 
		return $this->mysql_model->query($sql,$type); 	
	}
	
	
	
	 
	public function get_bankBalance($beginDate,$endDate,$where1='',$where2='',$type=2) {
	    $sql = 'select 
					a.id,a.iid as billId,a.accId,a.billNo,a.payment as balance,a.transTypeName as billType,a.billDate as date,
					b.name as buName,		
					d.name as accountName,d.number as accountNumber
				from '.$this->db->dbprefix('account_info').' as a 
				left join 
					  (select id,name,number from '.$this->db->dbprefix('contact').'  where isDelete=0) as b 
				on a.buId=b.id 
				left join 
						(select id,name,number from '.$this->db->dbprefix('account').' where isDelete=0) as d 
				on a.accId=d.id 
				where a.isDelete=0 and a.billDate>="'.$beginDate.'" and a.billDate<="'.$endDate.'" '.$where1.' 
				
				union all
				
				select 
					"","",id as accId,"",(a.amount+ifnull(b.payment,0)) as balance,"期初余额","","",name as accountName,number as accountNumber
				from '.$this->db->dbprefix('account').' as a 
				left join 
					(select accId,billDate,sum(payment) as payment from '.$this->db->dbprefix('account_info').' where isDelete=0 and billDate<"'.$beginDate.'" group by accId) as b 
					on a.id=b.accId 
				where a.isDelete=0 '.$where2.' 
				order by accId,id';	
		return $this->mysql_model->query($sql,$type); 	
	}


	 
	public function get_goods($where='',$type=2) {
	    $sql = 'select 
					a.*,b.iniqty,b.iniunitCost,b.iniamount,b.totalqty
				from '.$this->db->dbprefix('goods').' as a 
				left join 
					(select 
						invId,
						sum(qty) as totalqty, 
						sum(case when billType="INI" then qty else 0 end) as iniqty,
						sum(case when billType="INI" then price else 0 end) as iniunitCost,
						sum(case when billType="INI" then amount else 0 end) as iniamount
					from '.$this->db->dbprefix('invoice_info').' 
					where isDelete=0 group by invId) as b 
				on a.id=b.invId  where '.$where;
		return $this->mysql_model->query($sql,$type); 	
	}	
	
	
	//用于应付账款明细表 
	public function get_fundBalance_detailSupplier($where1='',$where2='',$beginDate,$type=2) {
	    $sql = 'SELECT *
					FROM
						(SELECT a.id,a.billNo,a.billDate,a.billType, a.buId, a.transTypeName, a.arrears,b.name AS contactName, b.number AS contactNo
						FROM '.$this->db->dbprefix('invoice').'  AS a
						LEFT JOIN
							(SELECT id,number,name,cCategory FROM '.$this->db->dbprefix('contact').' WHERE isDelete=0) AS b 
						ON a.buId=b.id
					WHERE isDelete=0 AND (billType="PUR" OR billType="PAYMENT") '.$where1.' ORDER BY a.billDate) AS G
                UNION ALL
					SELECT 0,"期初余额","","",a.id,"",(difMoney+ifnull(b.arrears,0)) AS arrears,a.name AS contactName,""
					FROM '.$this->db->dbprefix('contact').' AS a
					LEFT JOIN
						(SELECT buId,billType,sum(arrears) AS arrears FROM '.$this->db->dbprefix('invoice').'  WHERE isDelete=0 and billDate<"'.$beginDate.'" GROUP BY buId) AS b 
					ON a.id=b.buId
					WHERE isDelete=0  AND type=10 '.$where2.'
                UNION ALL
					SELECT 
					    max(a.id)+1, "小计", "", "",a.buId, "", sum(a.arrears) AS arrears, "", ""
					FROM '.$this->db->dbprefix('invoice').' AS a
					LEFT JOIN
						(SELECT id,number,name,cCategory FROM '.$this->db->dbprefix('contact').' WHERE isDelete=0) AS b 
					ON a.buId=b.id
					WHERE isDelete=0 AND (billType="PUR" OR billType="PAYMENT") '.$where1.' GROUP BY a.buId
                ORDER BY buId, id';    
		 
		 return $this->mysql_model->query($sql,$type); 	
	}
	
	//用于应收账款明细表
	public function get_fundBalance_detail($where1='',$where2='',$beginDate,$type=2) {
	    $sql = 'SELECT *
					FROM
						(SELECT a.id,a.billNo,a.billDate,a.billType, a.buId, a.transTypeName, a.arrears,b.name AS contactName, b.number AS contactNo
						FROM '.$this->db->dbprefix('invoice').'  AS a
						LEFT JOIN
							(SELECT id,number,name,cCategory FROM '.$this->db->dbprefix('contact').' WHERE isDelete=0) AS b 
						ON a.buId=b.id
					WHERE isDelete=0 AND (billType="SALE" OR billType="RECEIPT") '.$where1.' ORDER BY a.billDate) AS G
                UNION ALL
					SELECT 0,"期初余额","","",a.id,"",(difMoney+ifnull(b.arrears,0)) AS arrears,a.name AS contactName,""
					FROM '.$this->db->dbprefix('contact').' AS a
					LEFT JOIN
						(SELECT buId,billType,sum(arrears) AS arrears FROM '.$this->db->dbprefix('invoice').'  WHERE isDelete=0 and billDate<"'.$beginDate.'" GROUP BY buId) AS b 
					ON a.id=b.buId
					WHERE isDelete=0  AND type=-10 '.$where2.'
                UNION ALL
					SELECT 
					    max(a.id)+1, "小计", "", "",a.buId, "", sum(a.arrears) AS arrears, "", ""
					FROM '.$this->db->dbprefix('invoice').' AS a
					LEFT JOIN
						(SELECT id,number,name,cCategory FROM '.$this->db->dbprefix('contact').' WHERE isDelete=0) AS b 
					ON a.buId=b.id
					WHERE isDelete=0 AND (billType="SALE" OR billType="RECEIPT") '.$where1.' GROUP BY a.buId
                ORDER BY buId, id';    
		 
		 return $this->mysql_model->query($sql,$type); 	
	}
 
 
}