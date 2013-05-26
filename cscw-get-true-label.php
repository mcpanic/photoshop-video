<?php

//include "conn.php";

// NOTE: The following 'define' settings are machine and server-specific.
// Make sure to configure them correctly before starting ToolScape.

define("FFMPEG_PATH", "/opt/local/bin/ffmpeg");	// path of ffmpeg

define("DB_HOST", "50.116.6.114");	// MySQL host name
define("DB_USERNAME", "toolscape");	// MySQL username
define("DB_PASSWD", "toolscape");	// MySQL password
define("DB_NAME", "video_learning");	// MySQL database name. vt.sql uses the default video_learning name. So be careful.

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWD, DB_NAME);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}


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