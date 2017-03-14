<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<table width="1500px" class="list">
  			<tr><td class='H' align="center" colspan="17"><h3>组装单记录</h3></td></tr>
  		</table>
		<table class="table" width="1500"  border="1">
			<thead>
				<tr>
				    <th width="100" align="center">单据日期</th>
				    <th width="150" align="center">单据编号</th>
				    <th width="120" align="center">组合件</th>
					<th width="120" align="center">组合件数量</th>
					<th width="60" align="center">单位</th>
				    <th width="60" align="center">组合件单位成本量</th>
					<th width="60" >仓库</th>
					<th width="100" align="center">组装费用</th>
					<th width="100" align="center">制单人</th>
					<th width="60" align="center">单据备注</th>
					
					
					<th width="60" align="center">子件</th>	
					<th width="80" align="center">子件数量</th>		
					<th width="80" align="center">单位</th>	
					<th width="80" align="center">子件单位成本</th>	
					<th width="80" align="center">子件成本</th>	
					<th width="60" align="center">仓库</th>	
					<th width="100" align="center">备注</th>	
				</tr>
			</thead>
			<tbody>
			    <?php 
				  $i = 1;
				  $n = 1;
				  $qty = $amount = 0;
				  foreach($list as $arr=>$row) {
				      $postData = unserialize($row['postData']);
				      $n = isset($postData['entries']) ? count($postData['entries']) : 1;
				?>
				<tr target="id">
				    <td rowspan="<?php echo $n?>" ><?php echo $row['billDate']?></td>
					<td rowspan="<?php echo $n?>" ><?php echo $row['billNo']?></td>
					<td rowspan="<?php echo $n?>" ><?php echo $postData['entries'][0]['invNumber'].' '.$postData['entries'][0]['invName'].' '.$postData['entries'][0]['invSpec']?></td>
					<td rowspan="<?php echo $n?>" ><?php echo round($postData['entries'][0]['qty'],2);?></td>
					<td rowspan="<?php echo $n?>" ><?php echo $postData['entries'][0]['mainUnit'];?></td>
					<td rowspan="<?php echo $n?>" ><?php echo round($postData['entries'][0]['price'],2);?></td>
					<td rowspan="<?php echo $n?>" ><?php echo $postData['entries'][0]['locationName'];?></td>
					<td rowspan="<?php echo $n?>" ><?php echo $row['amount']?></td>
					<td rowspan="<?php echo $n?>" ><?php echo $row['userName']?></td>
					<td rowspan="<?php echo $n?>" ><?php echo $row['description']?></td>
				<?php 
				foreach($postData['entries'] as $arr1=>$row1) {
					if ($arr1==1) {
					   $qty    += abs($row1['qty']);
					   $amount += abs($row1['amount']);
				?>
					<td ><?php echo $row1['invNumber'].' '.$row1['invName'].' '.$row1['invSpec']?></td>
					<td ><?php echo round(abs($row1['qty']),2)?></td>
					<td ><?php echo $row1['mainUnit']?></td>
					<td ><?php echo round($row1['price'],2)?></td>
					<td ><?php echo round(abs($row1['amount']),2)?></td>
					<td ><?php echo $row1['locationName']?></td>
					<td ><?php echo $row1['description']?></td>
				</tr>
				<?php } elseif($arr1>0) {
				      $qty    += abs($row1['qty']);
					  $amount += abs($row1['amount']);
				?>
				<tr target="id">
					<td ><?php echo $row1['invNumber'].' '.$row1['invName'].' '.$row1['invSpec']?></td>
					<td ><?php echo round(abs($row1['qty']),2)?></td>
					<td ><?php echo $row1['mainUnit']?></td>
					<td ><?php echo round($row1['price'],2)?></td>
					<td ><?php echo round(abs($row1['amount']),2)?></td>
					<td ><?php echo $row1['locationName']?></td>
					<td ><?php echo $row1['description']?></td>
				</tr>
				<?php }}?>
				<tr target="id">
					<td >合计</td>
					<td ><?php echo $qty?></td>
					<td ></td>
					<td ></td>
					<td ><?php echo $amount?></td>
					<td ></td>
					<td ></td>

	
				</tr>
				 
				<?php $qty = $amount = 0;$n = 1;}?>
				 
				 
				
 </tbody>
</table>	


 