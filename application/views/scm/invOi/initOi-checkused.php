<?php
	// header("Content-type:text/html; charset=utf-8");

	//print_r($_GET['num']);exit;ok
	//$num = $GET['num'];
	$num = $_GET['num'];
	$link = @mysql_connect('localhost','elephant','hello2017');
	mysql_select_db('success0214');
	@mysql_query("set names utf8");
	$sql = "update ci_order_info c set c.tax='1' where c.description='".$num."'";
	// mysql_query($sql,$link);
	//var_dump($sql);exit;
	if (@mysql_query($sql,$link)) {
		echo "<script>location.href='../scm/invOi?action=initOi&type=cc';</script>";
		
	}
	
mysql_free_result($res);
// echo <<<STR
// <script>
// 	window.parent.document.getElementById("message").innerHTML = "$message";
// </script>
// STR;

?>