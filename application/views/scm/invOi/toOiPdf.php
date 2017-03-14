<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php 
foreach($list as $arr=>$row) {
	$postData   = unserialize($row['postData']);
	$countpage  = ceil(count($postData['entries'])/$num); 
	for($t=1; $t<=$countpage; $t++){
?>
		<table  width="800"  align="center">
			<tr>
				<td style="height:50px;"></td>
			</tr> 
		    <tr>
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:bold;"><?php echo $system['companyName']?></td>
			</tr> 
			<tr>
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:bold;height:25px;">其他入库单</td>
			</tr>
		</table>	
		
		
		<table width="800" align="center">
			<tr height="15" align="left" style="font-family:'宋体'; font-size:12px;">
				<td width="220" >供应商：<?php echo $row['contactNo'].' '.$row['contactName']?> </td>
				<td width="130" >单据日期：<?php echo $row['billDate']?></td>
				<td width="200" >单据编号：<?php echo $row['billNo']?></td>
				<td width="70"  >币别：RMB</td>
				<td width="150" >业务类型：<?php echo $row['transTypeName']?></td>
			</tr>
		</table>	
		
			
		<table width="800" border="1" cellpadding="2" cellspacing="1" align="center" style="border-collapse:collapse;border:solid #000000;border-width:1px 0 0 1px;"  >
			<tr style="border:solid #000000;border-width:0 1px 0px 0;padding:1px; font-family:'宋体'; font-size:14px;height:15px;">
				<td width="30"  align="center">序号</td>
				<td width="250" align="center">商品</td> 
				<td width="60"  align="center">单位</td>
				<td width="60"  align="center">数量</td>
				<td width="60"  align="center">入库单价</td>	
				<td width="60"  align="center">入库金额</td>	
				<td width="80" align="center">仓库</td>
				<td width="80" align="center">备注</td>	
			</tr>
		    <?php 
			$i = ($t-1)*$num + 1;
			foreach($postData['entries'] as $arr1=>$row1) {
				if ($arr1+1>=(($t-1)*$num + 1) && $arr1+1 <=$t*$num) {
			?>
			<tr style="border:solid #000000;height:15px;font-family:'宋体'; font-size:12px;vertical-align:bottom;">
				<td width="30" align="center"><?php echo $arr1+1?></td>
				<td width="250"><?php echo $row1['invNumber'].' '.$row1['invName'].' '.$row1['invSpec'];?></td>
				<td width="60" align="center"><?php echo $row1['invSpec']?></td>
				<td width="60" align="right"><?php echo str_money(abs($row1['qty']),$system['qtyPlaces'])?></td>
				<td width="60" align="right"><?php echo abs($row1['price'])?></td>
				<td width="60" align="right"><?php echo str_money(abs($row1['amount']),2)?></td>
				<td width="80"><?php echo $row1['locationName']?></td>
				<td width="80"><?php echo $row1['description']?></td>
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
				<td width="30" align="center" style="border:solid #000000;border-width:0 1px 1px 0;height:15px;font-family:'宋体'; font-size:12px;"><?php echo $m?></td>
				<td width="250"></td>
				<td width="60"></td>
				<td width="60"></td>
				<td width="60"></td>
				<td width="60"></td>
				<td width="80"></td>
				<td width="80"></td>
			</tr>
			<?php }}?>
				
			<?php if ($t==$countpage) {?>
			<tr style="border:solid #000000;border-width:0 1px 0px 0;height:15px;font-family:'宋体'; font-size:12px;">
				<td colspan="3" width="340" align="right" >合计:</td>
				<td width="60" align="right"><?php echo str_money($row['totalQty'],$system['qtyPlaces'])?></td>
				<td width="60" ></td>
				<td width="80" align="right"><?php echo str_money($row['totalAmount'],2)?></td>
				<td width="80"></td>
				<td width="80"></td>
			</tr>
			<?php }?>
			 
		</table>
		

 
		<table  width="800" align="center">
		  <tr align="left">
				<td align="left" width="780" style="font-family:'宋体'; font-size:12px;height:25px;">备注： <?php echo $row['description']?></td>
				<td width="0" ></td>
				<td width="0" ></td>
				<td width="0" ></td>
				<td width="0" ></td>
		  </tr>
		</table>	 
		
		<table  width="800" align="center">
			<tr height="15" align="left" style="font-family:'宋体'; font-size:12px;">
				<td align="left" width="250" style="font-family:'宋体'; font-size:12px;">制单人：<?php echo $row['userName']?> </td>
				<td width="250" >收货人签字：____________</td>
				<td width="250" ></td>
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