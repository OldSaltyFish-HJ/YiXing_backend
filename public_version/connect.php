<?php
	function connect() {
		$servername = "localhost";
		$username = "***";
		$password = "***";//出于安全考虑，以星号代替
		$dbname = "2020girl_hackathon";
		$con=mysql_connect($servername,$username,$password);
		if (mysqli_connect_errno($con)) {
			echo "连接失败: " . mysqli_connect_error();
			die();
		}
		mysql_select_db($dbname,$con);
	}
	$con = connect();
	
	function executeSQL_ns($sql) {
		if (mysql_query($sql)) {
			//echo 1;
		} else {
			echo mysql_error();die();
		}
	}

?>