<?php
	include (dirname(__FILE__).'/private_version/connect.php');
	include (dirname(__FILE__).'/functions.php');
	
	class Point {
		var $latitude;
		var $longitude;
		function __construct($_latitude, $_longitude) {
			$this->latitude = $_latitude;
			$this->longitude = $_longitude;
		}
		function sub($point) {
			return new Point($this->latitude - $point->latitude,
						$this->longitude - $point->longitude);
		}
	}
	function cross($point1, $point2) {
		return $point1->latitude * $point2->longitude
				- $point1->longitude * $point2->latitude;
	}
	function isCross($point0, $point1, $point2) {
		if ($point1->longitude > $point2->longitude) {
			$tmp = $point1;
			$point1 = $point2;
			$point2 = $tmp;
		}
		if ($point0->longitude <= $point1->longitude 
			|| $point0->longitude >= $point2->longitude)
			return false;
		if (cross($point0->sub($point1), $point2->sub($point1)) >= 0) return true;
		return false;
	}
	function isInThePolygon($latitude, $longitude, $polygonId) {
		$point0 = new Point($latitude, $longitude);
		$sql = "select latitude, longitude from points where polygonId = '$polygonId' order by pointId";
		$result = executeSQL($sql);
		$pre = -1;
		$first = -1;
		$crossCnt = 0;
		while ($rows = mysql_fetch_array($result, MYSQL_ASSOC)) {
			//echo json_encode($rows);
			$now = new Point($rows['latitude'], $rows['longitude']);
			if ($pre == -1) {
				$pre = $now;
				$first = $now;
				continue;
			}
			//echo json_encode($now);
			if (isCross($point0, $pre, $now)) ++$crossCnt;
			$pre = $now;
		}
		if (isCross($point0, $pre, $first)) ++$crossCnt;
		//echo "(".$polygonId.")";
		//echo $crossCnt." ";
		//echo json_encode($arr);
		if ($crossCnt % 2 == 1) return true;
		return false;
	}
	function getPolygonOfThePoint($prePolygonId, $latitude, $longitude) {
		if ($prePolygonId == -1) {
			$sql = "select polygonId from polygons";
			$result = executeSQL($sql);
			while ($rows = mysql_fetch_array($result, MYSQL_ASSOC)) {
				//echo json_encode($rows);
				$polygonId = $rows['polygonId'];
				if (isInThePolygon($latitude, $longitude, $polygonId))
					return $polygonId;
			}
		}
		else {
			$map = new HashTable(50);
			$queue = new SplQueue();
			$queue->enqueue($prePolygonId);
			$map->insert($prePolygonId, 1);
			while (!$queue->isEmpty()) {
				$front = $queue->top();
				//echo $front;
				$queue->dequeue();
				$sql = "select minPointId as polygonId from edges where maxPointId = '$front'";
				$sql .= " union";
				$sql .= " select maxPointId as polygonId from edges where minPointId = '$front'";
				$result = executeSQL($sql);
				while ($rows = mysql_fetch_array($result, MYSQL_ASSOC)) {
					//echo json_encode($rows);
					$polygonId = $rows['polygonId'];
					if ($map->find($polygonId) == 1) {
						
					} else {
						//echo "//".$polygonId."//";
						$queue->enqueue($polygonId);
						$map->insert($prePolygonId, 1);
					}
					if (isInThePolygon($latitude, $longitude, $polygonId))
						return $polygonId;
				}
			}
		}
		return $prePolygonId;
	}
	$prePolygonId = intval($_GET['prePolygonId']);
	$latitude = doubleval($_GET['latitude']);
	$longitude = doubleval($_GET['longitude']);
	echo getPolygonOfThePoint($prePolygonId, $latitude, $longitude);
 
?>