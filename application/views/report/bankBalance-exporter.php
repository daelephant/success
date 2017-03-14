<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
  		<table width="1440px" class="list">
  			<tr><td class='H' align="center" colspan="9"><h3>现金银行报表<h3></td></tr>
  			<tr><td colspan="9">日期：<?php echo $beginDate?>至<?php echo $endDate?></td></tr>
  		</table>
  		<table width="1440px" class="list"  border="1">
  			<thead>
  				<tr>
  				<th>账户编号</th>
  				<th>账户名称</th>
  				<th>日期</th>
  				<th>单据编号</th>
  				<th>业务类型</th>
  				<th>收入</th>
  				<th>支出</th>
  				<th>账户余额</th>
  				<th>往来单位</th>
  				</tr>
  			</thead>
  			<tbody>
			    <?php 
				 $sum1 = $sum2 = $sum3 = $sum4 = $sum5 = $sum6 = 0;
				 foreach($list as $arr=>$row){
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
				 ?>
  				 
				<tr> 
  			       <td><?php echo $row['accountNumber']?></td>
  			       <td><?php echo $row['accountName']?></td>
  			       <td><?php echo $row['date']?></td>
  			       <td><?php echo $row['billNo']?></td>
  			       <td><?php echo $row['billType']?></td>
  			       <td class="R"><?php echo str_money(isset($income) ? $income :0,2)?></td>
  			       <td class="R"><?php echo str_money(isset($expenditure) ? $expenditure :0,2)?></td>
  			       <td class="R"><?php echo str_money($balance + $sum1 - $sum2,2)?></td>
  			       <td><?php echo $row['buName']?></td>
  				</tr>
				<?php 
				    $sum6 = $sum5 + $sum3 - $sum4;  
				}
				?>
				
				 
  				<tr>
  				<td colspan="5" class="R B">合计：</td>
  				<td class="R B"><?php echo str_money($sum3,2)?></td>
  				<td class="R B"><?php echo str_money($sum4,2)?></td>
  				<td class="R B"><?php echo str_money($sum6,2)?></td>
  				<td class="R B"></td>
  				</tr>
  			</tbody>
  		</table>




 