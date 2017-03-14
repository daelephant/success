<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<table width="1440px" class="list">
  			<tr><td class='H' align="center" colspan="7"><h3>应付账款明细表<h3></td></tr>
  			<tr><td colspan="7">日期：<?php echo $beginDate;?>至<?php echo $endDate;?></td></tr>
  		</table>
  		<table width="1440px" class="list" border="1">
  			<thead>
  				<tr>
				<th>供应商</th>
  				<th>单据日期</th>
  				<th>单据编号</th>
  				<th>业务类别</th>
  				
  				<th>增加应付款</th>
  				<th>增加预付款</th>
  				<th>应付款余额</th>
  				 
  				</tr>
  			</thead>
  			<tbody>
			<?php 
				$sum0 = $sum1 = $sum2 = $sum3 = $sum4 = $sum5 = 0;
				foreach($list as $arr=>$row){
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
				 ?>
  				<tr class="link" data-id="0" data-type="BAL">
  				   <td><?php echo $row['contactName']?></td>
				   <td><?php echo $row['billDate']?></td>
  				   <td><?php echo $row['billNo']?></td>
  				   <td><?php echo $row['transTypeName']?></td>
  				   
 
  			       <td class="R"><?php echo $income?></td>
  			       <td class="R"><?php echo $expenditure?></td>
  			       <td class="R"><?php echo $balance?></td>
  				</tr>
                
				 
				<?php  }?>
  				<tr>
  				<td colspan="4" class="R B">合计：</td>
  				<td class="R B"><?php echo str_money($sum3,2)?></td>
  				<td class="R B"><?php echo str_money($sum4,2)?></td>
  				<td class="R B"><?php echo str_money($sum5,2)?></td>
  				</tr>
				
  				
  			</tbody>
  		</table>