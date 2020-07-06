<?php	
	function executeSQL_ns($sql) {
		if (mysql_query($sql)) {
			//echo 1;
		} else {
			echo mysql_error();die();
		}
	}
	
	function executeSQL($sql) {
		if ($result = mysql_query($sql)) {
			return $result;
			//echo 1;
		} else {
			echo mysql_error();die();
		}
	}
	
?>