<?php
  	include (dirname(__FILE__).'/private/connect.php');
	$a = $_GET['a'];
	$sql = "insert into test(a) values ('$a')";
	if (mysql_query($sql)) {
		echo 1;
	} else {
		echo mysql_error();die();
	}
	mysql_close();
?>