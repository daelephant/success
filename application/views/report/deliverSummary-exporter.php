<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<table width="1440px" class="list">
  			<tr><td class='H' colspan="38" align="center"><h3>商品收发汇总表<h3></td></tr>
  			<tr><td colspan="20">日期：<?php echo $beginDate;?>至<?php echo $endDate;?></td></tr>
</table>
  		<table width="1440px" class="list" border="1">
  				<tr>
				<th width="216" rowspan="2">商品分类</th> 
  				<th width="216" rowspan="2">商品编号</th>
  				<th width="216" rowspan="2">商品名称</th>
  				<th width="216" rowspan="2">规格型号</th>
  				<th width="114" rowspan="2">单位</th>
  				<th rowspan="2">仓库</th>
				<th colspan="2">期初</th>
				<th colspan="2">调拨入库</th>
				<th colspan="2">普通采购</th>
				<th colspan="2">销售退回</th>
				<th colspan="2">盘盈</th>
				<th colspan="2">其他入库</th>
				<th colspan="2">成本调整</th>
				<th colspan="2">入库合计</th>
				<th colspan="2">调拨出库</th>
				<th colspan="2">采购退回</th>
				<th colspan="2">普通销售</th>
				<th colspan="2">盘亏</th>
				<th colspan="2">其他出库</th>
				<th colspan="2">出库合计</th>
				<th colspan="2">结存</th> 
  				</tr>
				
				<tr>
				<?php for ($i=0;$i<15;$i++) {?>
  				<td>数量</td>
				<td>成本</td>
				<?php }?>
  				</tr>
				
				<?php 
				for ($i=0;$i<15;$i++) {
				    $sum['qty'.$i]   = 0;  
			        $sum['cost'.$i]  = 0;  
				}
				$qty7   = $qty_7   = $qty13  = $qty_13 = 0; 
		        $cost7  = $cost_7  = $cost13 = $cost_13 = 0; 
				$amount7   = $amount_7   = $amount13  = $amount_13 = 0; 
				foreach($list as $arr=>$row){
				    for ($i=1;$i<7;$i++) {
						$qty_7     += abs($row['qty'.$i]); 
						$amount_7  += $row['amount'.$i];   
					}
					for ($i=8;$i<13;$i++) {
						$qty_13     += abs($row['qty'.$i]);  
						$amount_13  += $row['amount'.$i];    
					}
					
				?>
				<tr>
				   <td><?php echo $row['categoryName']?></td>
  				   <td><?php echo $row['invNumber']?></td>
  				   <td><?php echo $row['invName']?></td>
  				   <td><?php echo $row['invSpec']?></td>
  				   <td><?php echo $row['mainUnit']?></td>
				   <td><?php echo $row['locationName']?></td>
				   <?php for ($i=0;$i<15;$i++) {?>
				       <?php if($i==0) {
					            $inprice0  = $row['inqty0']>0 ? $row['inamount0']/$row['inqty0'] : 0;
								$amount0   = $row['qty0'] * $inprice0;
								$sum['qty0']  += abs($row['qty0']);  
								$sum['cost0'] += abs($amount0);  
					   ?>
					       <td><?php echo str_money(abs($row['qty0']),$this->systems['qtyPlaces'])?></td>
					       <td><?php echo str_money($amount0,2)?></td>
					   <?php } elseif($i==7) {
								$sum['qty7']  += $qty_7;  
								$sum['cost7'] += $amount_7;
					   ?>
					       <td><?php echo str_money($qty_7,$this->systems['qtyPlaces'])?></td>
					       <td><?php echo str_money($amount_7,2)?></td>
					   <?php } elseif($i==13) {
					            $sum['qty13']  += $qty_13;  
							    $sum['cost13'] += $amount_13;  
					   ?>
					       <td><?php echo str_money($qty_13,$this->systems['qtyPlaces'])?></td>
					       <td><?php echo str_money($amount_13,2)?></td>
					   <?php } elseif($i==14) {
					            $inprice14  = $row['inqty14']>0 ? $row['inamount14']/$row['inqty14'] : 0;
								$amount14   = $row['qty14'] * $inprice14;
								$sum['qty14']  += abs($row['qty14']);  
								$sum['cost14'] += abs($amount14);  
					   ?>
					       <td><?php echo str_money(abs($row['qty14']),$this->systems['qtyPlaces'])?></td>
					       <td><?php echo str_money($amount14,2)?></td>	   
					   <?php } else {
					            $sum['qty'.$i]  += abs($row['qty'.$i]);
							    $sum['cost'.$i] += abs($row['amount'.$i]);  
					   ?>
						   <td><?php echo str_money(abs($row['qty'.$i]),$this->systems['qtyPlaces'])?></td>
						   <td><?php echo str_money(abs($row['amount'.$i]),2)?></td>
					   <?php }?>
				   <?php }?>
  				</tr>
  			   <?php 
			   $qty_7 = $cost_7 = $qty_13 = $cost_13 = 0;          
					$amount_7 = $amount_13 = 0;
			   }?>
				<tr>
  				   <td colspan="6">合计:</td>
				   <?php for ($i=0;$i<15;$i++) {?>
				   <td><?php echo str_money($sum['qty'.$i],$this->systems['qtyPlaces'])?></td>
				   <td><?php echo str_money($sum['cost'.$i],2)?></td>
				   <?php }?>
  				</tr>
  		</table>
