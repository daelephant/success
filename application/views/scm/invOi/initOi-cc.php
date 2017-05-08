<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<title></title>
</head>
<body>
	精确筛选搜索：
	<form method="post" action="../scm/invOi?action=initOi&type=cc"><input type="text" id="selectuse" name="checkused" value=""><button type="submit">查询</button></form>
	当前可用设备编号列表：
	
	<?php

	$link = mysql_connect('localhost','elephant','hello2017');
	mysql_select_db('success0214');
	mysql_query("set names utf8");
	// var_dump($_POST);exit;ok
	//$sql = "select invId as goods,description as nomber from ci_order_info where description !=''";
	if (!$_POST['checkused']) {
			$sql = "select ci_goods.name,ci_order_info.description from ci_goods left join ci_order_info on ci_goods.id = ci_order_info.invId where ci_order_info.description !='' and ci_order_info.tax ='0'";
	}else{
			$checkused = $_POST['checkused'];
		    $sql = "select ci_goods.name,ci_order_info.description from ci_goods left join ci_order_info on ci_goods.id = ci_order_info.invId where ci_order_info.description !='' and ci_order_info.tax ='0' and ci_goods.name like '%".$checkused."%'";
		    //var_dump($sql);exit;

	}
	$res = @mysql_query($sql,$link);
	$num = @mysql_num_rows($res);
	//echo  $num;
	//$row = mysql_fetch_row($res);
	//print_r($row);
	
	echo "<table style='width:284px;margin-left:8px;margin-right:-2px;'>";
	while ($row = @mysql_fetch_row($res)) {
	echo "<tr>";
		echo "<td style='border:solid #add9c0; border-width:0px 1px 1px 0px; padding:0px 0px;'>"."$row[0]"."--->&nbsp;&nbsp"."$row[1]"."<br>";
		echo "</td>";
	 //echo "<td>&nbsp;<a href='delfriend.php?=".$row[1]."' onclick='return confirm()'>使用</a></td>";
	 echo "<td style='width:40px;border:solid #add9c0; border-width:0px 1px 1px 0px; padding:0px 0px;'>&nbsp;<a title='请鼠标双击设备编号再Ctrl+C后点击使用' href='../scm/invOi?action=initOi&type=checkused&num=".$row[1]."' >使用</a></td>";
	 echo "</tr>";
	}
	mysql_free_result($res);
	echo "</table>";
	// echo "</td>";
	//  echo "<td>&nbsp;&nbsp;<a href='delfriend.php?f_nickname=".$f_nickname."' onclick='return confirm()'>删除</a></td>";
	//  echo "</tr>";
	// foreach ($row as $key => $value) {
	// 	echo "$key"."--->"."$value"."<br>";
	// }
	// if ($row) {
	// 	echo "$row[0]"."--->"."$row[1]";
	// }
	//echo "$row[0]"."--->"."$row[1]";
	//var_dump($row);
	// while($row){
	//  echo "hello";
	
	//  }
	//config.inc.php
	// $ip_prefix = '127.0.0.1';
	// if (substr($_SERVER,0,strlen($ip_prefix)) != $ip_prefix) die('access deny');
    
	?>

</body>
</html>
 