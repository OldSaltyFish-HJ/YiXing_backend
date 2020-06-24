<?php
function connect() {
	$servername = "localhost";
	$username = "root";
	$password = "8c4f8a7e593b07e7";
	$dbname = "selectCourses";

	$con=mysql_connect($servername,$username,$password);
	if (mysqli_connect_errno($con)) {
		echo "连接失败: " . mysqli_connect_error();
		die();
	}
	mysql_select_db($dbname,$con);
}

$con = connect();

?>