<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

  		<table width="1128px" class="list" >
  			<tr><td class='H' align="center" colspan="14"><h3>采购订单跟踪表<h3></td></tr>
  			<tr><td colspan="14">日期：<?php echo $beginDate?>至<?php echo $endDate?></td></tr>
  		</table>
  		<table width="1128px" class="list" border="1">
  			<thead>
  				<tr>
	  				<th>商品编号</th>
	  				<th width="150px">商品名称</th>
	  				<th>规格型号</th>
	  				<th>单位</th>
	  				<th>订单日期</th>
	  				<th>采购订单编号</th>
	  				<th>供应商</th>
					<th>状态</th>
					<th>数量</th>
					<th>采购额</th>
					<th>未入库数量</th>
	  				<th>预计交货日期</th>
					<th>入库日期</th>
					<th>备注</th>
  				</tr>
  			</thead>
  			<tbody>
 
				<?php 
				 $sum1 = $sum2 = $sum3 = 0;
				 foreach($list as $arr=>$row){
				?>
  				<tr>
  			       <td><?php echo $row['invNo']?></td>
  			       <td><?php echo $row['invName']?></td>
  			       <td><?php echo $row['spec']?></td>
  			       <td><?php echo $row['unit']?></td>
  			       
				   
				   <td><?php echo $row['date']?></td>
				   <td><?php echo $row['billNo']?></td>
				   <td><?php echo $row['buName']?></td>
				   <td><?php echo $row['status']?></td>
				   <td><?php echo $row['qty']?></td>
				   <td><?php echo $row['amount']?></td>
				   <td><?php echo $row['unQty']?></td>
  			       <td><?php echo $row['deliveryDate']?></td>
				   <td><?php echo $row['inDate']?></td>
  			       <td><?php echo $row['description']?></td>
  				</tr>
  			    <?php 
					 if (strlen($row['amount'])>0) {
						 $sum1 += $row['unQty'];  
						 $sum2 += $row['qty'];
						 $sum3 += $row['amount']; 
					 }
				 }
				?>
  				 
  			    <tr>
  				<td colspan="8" class="R B" align="right">合计：</td>
  				<td class="R B"><?php echo $sum2?></td>
  				<td class="R B"><?php echo str_money($sum3,$this->systems['qtyPlaces'])?></td>
  				<td class="R B"><?php echo $sum1?></td>
  				</tr>
				
  				 
  			</tbody>
  		</table>





