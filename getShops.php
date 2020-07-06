<?php
	include (dirname(__FILE__).'/private_version/connect.php');
	include (dirname(__FILE__).'/functions.php');
	$searchContent = "%";
	$searchContent .= $_GET['searchContent'];
	$searchContent .= "%";
	$sql = "select latitude, longitude, name, SUM(remain) as remain";
	$sql .= " from shops inner join commoditiesInShops";
	$sql .= " on shops.shopId = commoditiesInShops.shopId";
	$sql .= " where commodityId in";
	$sql .= "(select commodityId from commodities where catagory like '$searchContent' or name like '$searchContent')";
	$sql .= " group by shops.shopId";
	$arr = array();
	$result = executeSQL($sql);
	while ($rows = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$arr[] = $rows;
	}
	echo json_encode($arr);
?>