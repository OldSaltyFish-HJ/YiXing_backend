<?php
	include (dirname(__FILE__).'/private_version/connect.php');
	$sql = "select points.polygonId, latitude, longitude, risk from points left join polygons on points.polygonId = polygons.polygonId order by pointId";
	if ($result = mysql_query($sql)) {
		$arr = Array();
		while ($rows = mysql_fetch_array($result,MYSQL_ASSOC)){
			$arr[] = $rows;
		}
		echo json_encode($arr);
	} else {
		echo mysql_error();die();
	}
?>