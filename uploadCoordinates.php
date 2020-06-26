<?php
	include (dirname(__FILE__).'/private_version/connect.php');
	$coordinatesVec = json_decode(@file_get_contents("php://input"));
	$sql = "select MAX(polygonId) as max from polygon";
	$result = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
	$maxPolygonId = $result['max'];
	if ($maxpolygon == null) $maxPolygonId = 1;
	else $maxPolygonId = $maxPolygonId + 1;
	$sql = "select MAX(pointId) as max from polygon";
	$result = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
	$maxPointId = $result['max'];
	if ($maxPointId == null) $maxPointId = 1;
	for ($i = 0; $i < count($coordinatesVec); ++$i) {
		$latitude = doubleval($coordinatesVec[$i]->latitude);
		$longitude = doubleval($coordinatesVec[$i]->longitude);
		++$maxPointId;
		$sql = "insert into polygon(latitude, longitude, polygonId, pointId)";
		$sql .= "values ('$latitude', '$longitude', '$maxPolygonId', '$maxPointId')";
		//echo $sql."<br />";
		executeSQL_ns($sql);
	}
	echo 1;
?>