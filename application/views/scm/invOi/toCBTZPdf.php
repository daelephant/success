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
				<td align="center" style="font-family:'宋体'; font-size:18px; font-weight:bold;height:25px;">成本调整单</td>
			</tr>
		</table>	
		
		
		<table width="800" align="center">
			<tr height="15" align="left" style="font-family:'宋体'; font-size:12px;">
				<td width="220">单据日期：<?php echo $row['billDate']?> </td>
				<td width="130"></td>
				<td width="120"></td>
				<td width="200">单据编号：<?php echo $row['billNo']?></td>
				<td width="30" ></td>
			</tr>
		</table>	
		
			
		<table width="800" border="1" cellpadding="2" cellspacing="1" align="center" style="border-collapse:collapse;border:solid #000000;border-width:1px 0 0 1px;"  >
			<tr style="border:solid #000000;border-width:0 1px 0px 0;padding:1px; font-family:'宋体'; font-size:14px;height:15px;">
				<td width="30"  align="center">序号</td>
				<td width="380" align="center">商品</td> 
				<td width="80"  align="center">单位</td>
				<td width="100"  align="center">调整金额</td>	
				<td width="100" align="center">仓库</td>
			</tr>
		    <?php 
			$i = ($t-1)*$num + 1;
			foreach($postData['entries'] as $arr1=>$row1) {
				if ($arr1+1>=(($t-1)*$num + 1) && $arr1+1 <=$t*$num) {
			?>
			<tr style="border:solid #000000;height:15px;font-family:'宋体'; font-size:12px;vertical-align:bottom;">
				<td width="30" align="center"><?php echo $arr1+1?></td>
				<td width="380"><?php echo $row1['invNumber'].' '.$row1['invName'].' '.$row1['invSpec'];?></td>
				<td width="80" align="center"><?php echo $row1['invSpec']?></td>
				<td width="100" align="right"><?php echo str_money(abs($row1['amount']),2)?></td>
				<td width="100"><?php echo $row1['locationName']?></td>
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
				<td width="380"></td>
				<td width="80"></td>
				<td width="100"></td>
				<td width="100"></td>
				
			</tr>
			<?php }}?>
				
			<?php if ($t==$countpage) {?>
			<tr style="border:solid #000000;border-width:0 1px 0px 0;height:15px;font-family:'宋体'; font-size:12px;">
				<td colspan="3" width="440" align="right" >合计:</td>
				<td width="100" align="right"><?php echo str_money($row['totalAmount'],2)?></td>
				<td width="100" ></td>
				 
			</tr>
			<?php }?>
			 
		</table>
		

 
		<table  width="800" align="center">
		  <tr align="left">
				<td align="left" width="700" style="font-family:'宋体'; font-size:12px;height:25px;">备注： <?php echo $row['description']?></td>
				<td width="0" ></td>
				<td width="0" ></td>
				<td width="0" ></td>
				<td width="0" ></td>
		  </tr>
		</table>	 
		
		<table  width="800" align="center">
		    <tr align="left">
				<td align="left" width="700" style="font-family:'宋体'; font-size:12px;height:25px;">制单人：<?php echo $row['userName']?></td>
				<td width="0" ></td>
				<td width="0" ></td>
				<td width="0" ></td>
				<td width="0" ></td>
		  </tr>
		</table>
<?php 
	echo $t==$countpage?'':'<br><br><br>';}
}
?>		
		
		
		 
</body>
</html>		
 