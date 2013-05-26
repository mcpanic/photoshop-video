<?php

include "conn.php";

$cid_list = array(56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80);
$mid_list = array(81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105);
$pid_list = array(1,2,3,4,5,22,23,24,25,26,32,33,34,35,36,11,12,13,14,15,42,43,44,45,46);


$result1 = $mysqli->query("SELECT * FROM videos");
//$result2 = $mysqli->query("SELECT * FROM tasks WHERE id='$task_id'");
//$task = $result2->fetch_assoc();


$videos_array = array();
$labels_array = array();
$meta_array = array();

while ($video = $result1->fetch_assoc()) {
	// $video_filename = $video['filename'];
	// $string = file_get_contents("video/$video_filename.info.json");
	// $json = json_decode($string, true);
	if (!in_array(intval($video['id']), $cid_list) && !in_array(intval($video['id']), $mid_list) && !in_array(intval($video['id']), $pid_list))
		continue;
	$result3 = $mysqli->query("SELECT * FROM labels WHERE video_id = '" . $video['id'] . "' ORDER BY tm ASC");    
	$label_array = array();
	// $html = "";
	while ($label = $result3->fetch_assoc()) {
	  $label_array[] = $label;  
	}
	$labels_array[$video['id']] = array();
	$labels_array[$video['id']] = $label_array;
	$videos_array[] = $video;
	// $meta_array[] = $json;
} 

?>

<html>
<body>
	<?php
	foreach ($labels_array as $key => $label_array){
		$inst_string = "";
		$ba_string = "";
		foreach ($label_array as $label){
			if ($label['type'] == "image")
				$ba_string .= $label['tm'] . "\t";
			else
				$inst_string .= $label['tm'] . "@" . $label['tool'] . "\t";
		}
		$inst_string .= "\n";
		$ba_string .= "\n";
		echo $key . "\t\t" . $inst_string . "<br/>";
		//var_dump($labels_array[$key]);
		//echo $key . "\n";
		//var_dump($label_array);
	}
	?>
</body>
</html>