<?php
	//include (dirname(__FILE__).'/private_version/connect.php');
	include (dirname(__FILE__).'/inPolygon.php');
	class Polygon {
		public $polygonId;
		public $risk;
		public $pointList;
		public function __construct($polygonId, $risk, $pointList) {
			$this->polygonId = $polygonId;
			$this->risk = $risk;
			$this->pointList = $pointList;
		}
	}
	$prePolygonId = intval($_GET['prePolygonId']);
	$latitude = doubleval($_GET['latitude']);
	$longitude = doubleval($_GET['longitude']);
	$isAdmin = intval($_GET['isAdmin']);
	$ret = Array();
	$polygonId = getPolygonOfThePoint($prePolygonId, $latitude, $longitude);
	if ($polygonId <= 0) $polygonId = 1;
	$ret['getPolygonId'] = $polygonId;
	$map = new HashTable(1000);
	$queue = new SplQueue();
	$queue->push($polygonId);
	$sql = "select risk from polygons where polygonId = '$polygonId'";
	$result = executeSQL($sql);
	$result = mysql_fetch_array($result, MYSQL_ASSOC);
	$map->insert($polygonId, $result['risk']);
	$ret['arr'] = Array();
	while (!$queue->isEmpty()) {
		$front = $queue->top();
		$queue->pop();
		//echo $queue->count()."?</br>";
		if ($map->find($front) > 0 || $isAdmin == 1) {
			//echo $front."</br>";
			$sql = "select latitude, longitude from points where polygonId = '$front'";
			$pointList = Array();
			$result = executeSQL($sql);
			while ($rows = mysql_fetch_array($result,MYSQL_ASSOC)){
				$pointList[] = $rows;
			}
			$ret['arr'][] = new Polygon($front, $map->find($front), $pointList);
		}
		$sql = "select minPointId as polygonId from edges where maxPointId = '$front'";
		$sql .= " union";
		$sql .= " select maxPointId as polygonId from edges where minPointId = '$front'";
		$result = executeSQL($sql);
		while ($rows = mysql_fetch_array($result, MYSQL_ASSOC)) {
			//echo json_encode($rows);
			$polygonId = $rows['polygonId'];
			$tmp = $map->find($polygonId);
			if (isset($tmp)) {
				
			} else if ($map->size() <= 1000){
				//echo "//".$polygonId."//</br>";
				$queue->push($polygonId);
				$sql = "select risk from polygons where polygonId = '$polygonId'";
				$tmpResult = executeSQL($sql);
				$tmpResult = mysql_fetch_array($tmpResult, MYSQL_ASSOC);
				$map->insert($polygonId, $tmpResult['risk']);
			}
		}
	}
	echo json_encode($ret);
?>