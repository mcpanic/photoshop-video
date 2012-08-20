<?php
$term = $_GET["term"];
$scope = $_GET["scope"];
$json_file = "tools.json";
if (strcmp($scope, "menu") == 0) 
	$json_file = "menus.json";

$items = json_decode(file_get_contents($json_file), true);
$results = array();

foreach ($items as $item) {			
	if (strpos(strtolower($item["label"]), strtolower($term)) !== false) {
		$results[] = $item;
	}
}

echo json_encode($results);

?>