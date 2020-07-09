<?php
	include (dirname(__FILE__).'/inPolygon.php');
	$prePolygonId = intval($_GET['prePolygonId']);
	$latitude = doubleval($_GET['latitude']);
	$longitude = doubleval($_GET['longitude']);
	echo getPolygonOfThePoint($prePolygonId, $latitude, $longitude);
 
?>