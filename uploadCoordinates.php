<?php
	include (dirname(__FILE__).'/private_version/connect.php');
	include (dirname(__FILE__).'/functions.php');
	$coordinatesVec = json_decode(@file_get_contents("php://input"));
	if (count($coordinatesVec) == 0) echo 1;
	else {
		$sql = "select MAX(polygonId) as max from polygons";
		$result = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
		if (count($result) == 0) $maxPolygonId = 1;
		else $maxPolygonId = $result['max'] + 1;
		//echo $maxPolygonId;
		$sql = "select MAX(pointId) as max from points";
		$result = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
		if (count($result) == 0) $maxPointId = 0;
		else $maxPointId = $result['max'];
		$sql = "insert into polygons(polygonId) values ('$maxPolygonId')";
		executeSQL_ns($sql);
		for ($i = 0; $i < count($coordinatesVec); ++$i) {
			$latitude = doubleval($coordinatesVec[$i]->latitude);
			$longitude = doubleval($coordinatesVec[$i]->longitude);
			++$maxPointId;
			$sql = "insert into points(latitude, longitude, polygonId, pointId)";
			$sql .= "values ('$latitude', '$longitude', '$maxPolygonId', '$maxPointId')";
			//echo $sql."<br />";
			executeSQL_ns($sql);
		}
		echo 1;
	}
?>