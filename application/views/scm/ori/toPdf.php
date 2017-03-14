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
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:normal;height:25px;"><?php echo $row['transType']==153402 ? '其他支出单' :'其他收入单'?></td>
			</tr>
		</table>	
		
		
		<table width="800" align="center">
			<tr height="15" align="left" >
				<td width="250" style="font-family:'宋体'; font-size:14px;height:20px;">采购单位：<?php echo $row['contactNo'].' '.$row['contactName']?> </td>
				<td width="10" ></td>
				<td width="150" >单据日期：<?php echo $row['billDate']?></td>
				<td width="250" >单据编号：<?php echo $row['billNo']?></td>
				<td width="60" > </td>
			</tr>
		</table>	
		
			
		<table width="800" border="1" cellpadding="2" cellspacing="1" align="center" style="border-collapse:collapse;border:solid #000000;border-width:1px 0 0 1px;">
		    <tr style="height:20px">
				<td width="50" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;"  align="center">序号</td>
				<td width="250" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center"><?php echo $row['transType']==153402 ? '支出类别' :'收入类别'?></td> 
				<td width="150" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">金额</td>
				<td width="250" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;" align="center">备注</td>
 
			</tr>
				
			 
		    <?php 
			$i = ($t-1)*$num + 1;
			foreach($postData['entries'] as $arr1=>$row1) {
			    if ($arr1+1>=(($t-1)*$num + 1) && $arr1+1 <=$t*$num) {
			?>
			<tr style="height:20px">
				<td width="30"  style="border:solid #000000;border-width:0 1px 1px 0;height:15px;font-family:'宋体'; font-size:12px;" align="center"><?php echo $arr1+1?></td>
				 
				<td width="250"><?php echo $category[$row1['categoryId']];?></td>
				<td width="150" align="right"><?php echo str_money($row1['amount'],2)?></td>
				<td width="250" align="left"><?php echo $row1['description']?></td>
				 
			</tr>
			<?php 
				$s = $arr1+1;
				}
			$i++;
			}

			//补全
			if ($t==$countpage) {
				 for ($m=$s+1;$m<=$t*$num;$m++) {
			?>
			<tr style="border:solid #000000;border-width:0 1px 1px 0;padding:2px;height:15px;font-family:'宋体'; font-size:12px;">    
				<td width="30" style="border:solid #000000;border-width:0 1px 1px 0;height:15px;font-family:'宋体'; font-size:12px;" align="center"><?php echo $m?></td>
				<td width="250" ></td>
				<td width="150" align="center"></td>
				<td width="250" align="center"></td>
				 
			</tr>
			<?php }}?>
				
			<?php if ($t==$countpage) {?>
			<tr style="height:20px">
				<td colspan="2" align="right" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px;height:15px;font-family:'宋体'; font-size:12px;">合计：</td>
				<td width="30" align="right" ><?php echo str_money(abs($row['totalAmount']),2)?></td>
				<td width="80" align="center"></td>
 
                
			</tr>
 
			<tr target="id">
				<td colspan="4" style="border:solid #000000;border-width:0 1px 1px 0;padding:2px; font-family:'宋体'; font-size:14px;height:15px;">合计 金额大写： <?php echo str_num2rmb(abs($row['totalAmount']))?> </td> 
			</tr>
			<?php }?>
		</table>
 
		
		<table  width="800" align="center">
			<tr height="25" align="left">
				<td align="left" width="250" style="font-family:'宋体'; font-size:14px;height:25px;">制单人：<?php echo $row['userName']?> </td>
				<td width="250" style="font-family:'宋体'; font-size:14px;height:25px;">结算账户：<?php echo $account[$row['accId']]?></td>
				<td width="250" style="font-family:'宋体'; font-size:14px;height:25px;"><?php echo $row['transType']==153402 ? '付款金额' :'收款金额'?>：<?php echo str_money(abs($row['totalAmount']),2)?></td>
				<td width="100" ></td>
				<td width="100" ></td>
			</tr>
		</table>	
<?php 
	echo $t==$countpage?'':'<br><br><br>';}
}
?>	
		
		 
</body>
</html>		