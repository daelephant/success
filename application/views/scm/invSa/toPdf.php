<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style></style>
</head>
<body>
<?php 
foreach($list as $arr=>$row) {
	$postData   = unserialize($row['postData']);
	$countpage  = ceil(count($postData['entries'])/$num); 
	for($t=1; $t<=$countpage; $t++){
?>
		<table  width="800"  align="center">
			<tr height="15px">
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:normal;height:50px;"></td>
			</tr> 
		    <tr height="15px">
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:normal;"><?php echo $system['companyName']?></td>
			</tr> 
			<tr height="15px">
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:normal;height:25px;"><?php echo $row['transType']==150601 ? '出库单' :'出库退货单'?></td>
			</tr>
		</table>	
		
		<table width="800" align="center">
			<tr height="15" align="left">
				<td width="220" style="font-family:'宋体'; font-size:12px;height:20px;">接收单位：<?php echo $row['contactNo'].' '.$row['contactName']?> </td>
				<td width="120" style="font-family:'宋体'; font-size:12px;height:20px;">销售人员：<?php echo $row['salesName']?></td>
				<td width="120" style="font-family:'宋体'; font-size:12px;height:20px;">单据日期：<?php echo $row['billDate']?></td>
				<td width="180" style="font-family:'宋体'; font-size:12px;height:20px;">单据编号：<?php echo $row['billNo']?></td>
				<td width="50" style="font-family:'宋体'; font-size:12px;height:20px;">币别：RMB</td>
 
			</tr>
		</table>	
		
		<table width="900" border="1" cellpadding="2" cellspacing="1" align="center" style="border-collapse:collapse;border:solid #000000;border-width:1px 0 0 1px;">   
			<tr style="height:20px">
				<td width="30" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;"  align="center">序号</td>
				<td width="220" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">商品</td> 
				<td width="30" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">单位</td>
				<td width="40" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">数量</td>
				<td width="60" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">销售单价</td>	
				<td width="60" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">折扣率(%)</td>	
				<td width="50" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">折扣额</td>	
				<td width="60" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">销售金额</td>	
				<td width="80" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">仓库</td>	
			</tr>
		    <?php 
			$i = ($t-1)*$num + 1;
			foreach($postData['entries'] as $arr1=>$row1) {
			    if ($arr1+1>=(($t-1)*$num + 1) && $arr1+1 <=$t*$num) {
			?>
			<tr style="border:solid #000000;border-width:0 1px 1px 0;padding:2px;height:15px;font-family:'宋体'; font-size:12px;">
				<td width="30"  align="center"><?php echo $arr1+1?></td>
				<td width="220" style="border:solid #000000;border-width:0 1px 1px 0;height:15px;font-family:'宋体'; font-size:12px;"><?php echo $row1['invNumber'].' '.$row1['invName'].' '.$row1['invSpec'];?></td>
				<td width="30" align="center" style="border:solid #000000;border-width:0 1px 1px 0;height:15px;font-family:'宋体'; font-size:12px;"><?php echo $row1['mainUnit']?></td>
				<td width="40" align="right"><?php echo str_money(abs($row1['qty']),$system['qtyPlaces'])?></td>
				<td width="60" align="right"><?php echo abs($row1['price'])?></td>
				<td width="60" align="right"><?php echo $row1['discountRate']?></td>
				<td width="50" align="right"><?php echo $row1['deduction']?></td>
				<td width="60" align="right"><?php echo str_money(abs($row1['amount']),2)?></td>
				<td width="80"><?php echo $row1['locationName']?></td>
			</tr>
			<?php 
				$s = $arr1+1;
				}
			$i++;
			}
			?>
				
			<?php 
			//补全
			if ($t==$countpage) {
				 for ($m=$s+1;$m<=$t*$num;$m++) {
			?>
			<tr style="border:solid #000000;border-width:0 1px 1px 0;padding:2px;height:15px;font-family:'宋体'; font-size:12px;">
				<td width="30" align="center" style="border:solid #000000;border-width:0 1px 1px 0;height:15px;font-family:'宋体'; font-size:12px;"><?php echo $m?></td>
				<td width="220"></td>
				<td width="30"></td>
				<td width="40"></td>
				<td width="60"></td>
				<td width="60"></td>
				<td width="50"></td>
				<td width="60"></td>
				<td width="80"></td>
			</tr>
			<?php }}?>
				
			<?php if ($t==$countpage) {?>
			<tr style="height:20px">
				<td colspan="3" align="right" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px;height:15px;font-family:'宋体'; font-size:12px;">合计：</td>
				<td width="60" align="right"><?php echo str_money(abs($row['totalQty']),$system['qtyPlaces'])?></td>
				<td width="60" align="center"></td>
				<td width="60" align="center"></td>
				<td width="60" align="center"></td>
				<td width="60" align="right"><?php echo str_money(abs($row['totalAmount']),2)?></td>
				<td width="80" align="center"></td>
			</tr>
 
			<tr target="id">
				<td colspan="9" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:12px;height:15px;">合计 金额大写： <?php echo str_num2rmb(abs($row['totalAmount']))?> </td> 
			</tr>
			<?php }?>
		</table>
		
		
		
		
		<table  width="800" align="center">
		  <tr height="15" align="left">
				<td align="left" width="200" style="font-family:'宋体'; font-size:12px;height:25px;">折扣额：<?php echo str_money(abs($row['disAmount']),2)?></td>
				<td width="150" style="font-family:'宋体'; font-size:12px;height:25px;">折扣后金额：<?php echo str_money(abs($row['amount']),2)?></td>
				<td width="150" style="font-family:'宋体'; font-size:12px;height:25px;"><?php echo $row['transType']==150601 ? '本次收款：' :'本次退款：'?> <?php echo str_money(abs($row['rpAmount']),2)?></td>
				<td width="150" style="font-family:'宋体'; font-size:12px;height:25px;">本次欠款：<?php echo str_money(abs($row['arrears']),2)?></td>
				<td width="50" ></td>
 
		  </tr>
		</table>	
		
		<table  width="800" align="center">
		  <tr height="15" align="left">
				<td align="left" width="700" style="font-family:'宋体'; font-size:12px;height:25px;">备注： <?php echo $row['description']?></td>
				<td width="0" ></td>
				<td width="0" ></td>
				<td width="0" ></td>
				<td width="0" ></td>
 
		  </tr>
		</table>	 
		
		<table  width="800" align="center">
			<tr height="15" align="left">
				<td align="left" width="200" style="font-family:'宋体'; font-size:12px;height:25px;">制单人：<?php echo $row['userName']?> </td>
				<td width="150" style="font-family:'宋体'; font-size:12px;height:25px;">发货人签字：____________</td>
				<td width="150" style="font-family:'宋体'; font-size:12px;height:25px;">接收单位签字：____________</td>
				<td width="150"></td>
				<td width="50" ></td>
 
			</tr>
		</table>	
<?php 
	echo $t==$countpage?'':'<br><br><br>';}
}
?>		
		
		
		 
</body>
</html>		