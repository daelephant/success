<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<table width="1440px" class="list">
  			<tr>
			<td colspan="15" class='H' align="center"><h3>商品收发明细表<h3></td>
			</tr>
  			<tr>
			<td colspan="15">日期：<?php echo $beginDate;?>至<?php echo $endDate;?></td>
			</tr>
  		</table>
  		<table width="1440px" class="list" border="1">
  			<thead>
  				<tr>
					<th>商品编号</th>
					<th>商品名称</th>
					<th>规格型号</th>
					<th>单位</th>
					<th>日期</th>
					<th>单据号</th>
					<th>业务类型</th>
					<th>往来单位</th>
					<th>仓库</th>
					<th>入库</th>
					<th>单位成本</th>
					<th>入库成本</th>
					<th>出库</th>
					<th>单位成本</th>
					<th>出库成本</th>
					 
  				</tr>
  			</thead>
  			<tbody>
			<?php 
			    $sum1 = $sum2 = $sum3 = $sum4 = 0;
			    foreach($list as $arr=>$row){
					$inqty         = $row['qty']>0 ? abs($row['qty']) : '';              //入库
					$outqty        = $row['qty']<0 ? abs($row['qty']) : '';              //出库
					$inunitCost    = $row['qty']>0 ? round(abs($row['price']),2) : '';   //入库
					$outunitCost   = $row['qty']<0 ? round(abs($row['price']),2) : '';   //出库
					$incost        = $row['qty']>0 ? round(abs($row['amount']),2) : '';  //入库
					$outcost       = $row['qty']<0 ? round(abs($row['amount']),2) : '';  //出库
					$sum1   += $inqty;             //入库数量累加
					$sum2   += $outqty;            //出库数量累加
					$sum3   += $incost;            //入库成本累加
					$sum4   += $outcost;           //出库成本累加
				?>
  				<tr>
				   <td><?php echo $row['invNumber']?></td>
				   <td><?php echo $row['invName']?></td>
				   <td><?php echo $row['invSpec']?></td>
				   <td><?php echo $row['mainUnit']?></td>
				   <td><?php echo $row['billDate']?></td>
				   <td><?php echo $row['billNo']?></td>
  				   <td><?php echo $row['transTypeName']?></td>
				   <td><?php echo $row['contactName']?></td>
  			       <td class="R"><?php echo $row['locationName']?></td>
  			       <td class="R"><?php echo str_money($inqty,$this->systems['qtyPlaces'])?></td>
				   <td class="R"><?php echo str_money($inunitCost,$this->systems['qtyPlaces'])?></td>
				   <td class="R"><?php echo str_money($incost,$this->systems['qtyPlaces'])?></td>
  			       <td class="R"><?php echo str_money($outqty,$this->systems['qtyPlaces'])?></td>
				   <td class="R"><?php echo str_money($outunitCost,$this->systems['qtyPlaces'])?></td>
				   <td class="R"><?php echo str_money($outcost,$this->systems['qtyPlaces'])?></td>
  				</tr>
				<?php  }
				$inunitCost  = $sum1>0 ? $sum3/$sum1 :0;
		        $outunitCost = $sum2>0 ? $sum4/$sum2 :0;
				?>
  				<tr>
					<td colspan="3" class="R B">合计：</td>
					<td class="R B"></td>
					<td class="R B"></td>
					<td class="R B"></td>
					<td class="R B"></td>
					<td class="R B"></td>
					<td class="R B"></td>
					<td class="R B"><?php echo str_money($sum1,$this->systems['qtyPlaces'])?></td>
					<td class="R B"><?php echo str_money($inunitCost,$this->systems['qtyPlaces'])?></td>
					<td class="R B"><?php echo str_money($sum3,$this->systems['qtyPlaces'])?></td>
					<td class="R B"><?php echo str_money($sum2,$this->systems['qtyPlaces'])?></td>
					<td class="R B"><?php echo str_money($outunitCost,$this->systems['qtyPlaces'])?></td>
					<td class="R B"><?php echo str_money($sum4,$this->systems['qtyPlaces'])?></td>
  				</tr> 
  			</tbody>
  		</table>