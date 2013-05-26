<?php

include "conn.php";

$cid_list = array();
$mid_list = array();
$pid_list = array();


$result1 = $mysqli->query("SELECT * FROM videos WHERE task_id='$task_id'");
$result2 = $mysqli->query("SELECT * FROM tasks WHERE id='$task_id'");
$task = $result2->fetch_assoc();


$videos_array = array();
$labels_array = array();
$meta_array = array();

while ($video = $result1->fetch_assoc()) {
	// $video_filename = $video['filename'];
	// $string = file_get_contents("video/$video_filename.info.json");
	// $json = json_decode($string, true);

	$result3 = $mysqli->query("SELECT * FROM labels WHERE video_id = '" . $video['id'] . "'");    
	$label_array = array();
	// $html = "";
	while ($label = $result3->fetch_assoc()) {
	  $label_array[] = $label;  
	}

	$labels_array[] = $label_array;
	$videos_array[] = $video;
	// $meta_array[] = $json;
} 

$result4 = $mysqli->query("SELECT count(distinct videos.id) AS freq, tool FROM labels, videos WHERE labels.video_id=videos.id AND tool<>'' GROUP BY tool ORDER BY freq DESC LIMIT 7");
$tools_array = array();
while ($tool = $result4->fetch_assoc()) {
	$tools_array[] = $tool;
}
?>

<html>
<body>
	<?php
	foreach ($labels_array as $label_array){
		var_dump($label_array);
	}
	?>
</body>
</html>