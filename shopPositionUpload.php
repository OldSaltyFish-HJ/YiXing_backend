<?php
	include (dirname(__FILE__).'/private_version/connect.php');
	$coordinatesVec = json_decode(@file_get_contents("php://input"));
	if (count($coordinatesVec) == 0) echo 1;
	else {
		$sql = "select MAX(shopId) as max from shops";
		$result = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
		if (count($result) == 0) $maxShopId = 0;
		else $maxShopId = $result['max'];
		for ($i = 0; $i < count($coordinatesVec); ++$i) {
			$latitude = doubleval($coordinatesVec[$i]->latitude);
			$longitude = doubleval($coordinatesVec[$i]->longitude);
			echo $latitude;
			++$maxShopId;
			$sql = "insert into shops(latitude, longitude, shopId)";
			$sql .= "values ('$latitude', '$longitude', '$maxShopId')";
			//echo $sql."<br />";
			executeSQL_ns($sql);
		}
		echo 1;
	}
?>