<?php

include "conn.php";

$cid_list = array(56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80);
$mid_list = array(81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105);
$pid_list = array(1,2,3,4,5,22,23,24,25,26,32,33,34,35,36,11,12,13,14,15,42,43,44,45,46);

$cid_list = array(56 => "c01_v01",57 => "c01_v02",58 => "c01_v03",59 => "c01_v04",60 => "c01_v05",
				  61 => "c02_v01",62 => "c02_v02",63 => "c02_v03",64 => "c02_v04",65 => "c02_v05",
				  66 => "c03_v01",67 => "c03_v02",68 => "c03_v03",69 => "c03_v04",70 => "c03_v05",
				  71 => "c04_v01",72 => "c04_v02",73 => "c04_v03",74 => "c04_v04",75 => "c04_v05",
				  76 => "c05_v01",77 => "c05_v02",78 => "c05_v03",79 => "c05_v04",80 => "c05_v05");
$mid_list = array(81 => "m01_v01",82 => "m01_v02",83 => "m01_v03",84 => "m01_v04",85 => "m01_v05",
				  86 => "m02_v01",87 => "m02_v02",88 => "m02_v03",89 => "m02_v04",90 => "m02_v05",
				  91 => "m03_v01",92 => "m03_v02",93 => "m03_v03",94 => "m03_v04",95 => "m03_v05",
				  96 => "m04_v01",97 => "m04_v02",98 => "m04_v03",99 => "m04_v04",100 => "m04_v05",
				  101 => "m05_v01",102 => "m05_v02",103 => "m05_v03",104 => "m05_v04",105 => "m05_v05");
$pid_list = array(1  => "p01_v01",2  => "p01_v02",3  => "p01_v03",4  => "p01_v04",5  => "p01_v05",
				  22 => "p02_v01",23 => "p02_v02",24 => "p02_v03",25 => "p02_v04",26 => "p02_v05",
				  32 => "p03_v01",33 => "p03_v02",34 => "p03_v03",35 => "p03_v04",36 => "p03_v05",
				  11 => "p04_v01",12 => "p04_v02",13 => "p04_v03",14 => "p04_v04",15 => "p04_v05",
				  42 => "p05_v01",43 => "p05_v02",44 => "p05_v03",45 => "p05_v04",46 => "p05_v05");

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
	if (!array_key_exists(intval($video['id']), $cid_list) && !array_key_exists(intval($video['id']), $mid_list) && !array_key_exists(intval($video['id']), $pid_list))
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
	// foreach ($labels_array as $key => $label_array){
	// 	$inst_string = "";
	// 	$ba_string = "";
	// 	foreach ($label_array as $label){
	// 		if ($label['type'] == "image")
	// 			$ba_string .= $label['tm'] . "\t";
	// 		else
	// 			$inst_string .= $label['tm'] . "@" . $label['tool'] . "\t";
	// 	}
	// 	$inst_string .= "\n";
	// 	$ba_string .= "\n";

	// 	if (array_key_exists(intval($key), $cid_list))
	// 		$vid = $cid_list[$key];
	// 	elseif (array_key_exists(intval($key), $mid_list))
	// 		$vid = $mid_list[$key];
	// 	elseif (array_key_exists(intval($key), $pid_list))
	// 		$vid = $pid_list[$key];

	// 	echo $key . "\t\ts1_" . $vid . "\t\t" . $inst_string . "<br/>";
	// 	//var_dump($labels_array[$key]);
	// 	//echo $key . "\n";
	// 	//var_dump($label_array);
	// }
	$results = array();
	foreach ($labels_array as $key => $label_array){
		$result = array();
		// $inst_string = "";
		// $ba_string = "";
		foreach ($label_array as $label){
			$instruction = array();
			if ($label['type'] == "image"){
				$ba_string .= $label['tm'] . "\t";
			} else{
				$inst_string .= $label['tm'] . "@" . $label['tool'] . "\t";
				$instruction['label_id'] = $label['id'];
				$instruction['time'] = $label['tm'];
				$instruction['desc'] = $label['tool'];
				$result[$label['id']] = $instruction; 
			}
		}
		// $inst_string .= "\n";
		// $ba_string .= "\n";
		

		if (array_key_exists(intval($key), $cid_list))
			$vid = $cid_list[$key];
		elseif (array_key_exists(intval($key), $mid_list))
			$vid = $mid_list[$key];
		elseif (array_key_exists(intval($key), $pid_list))
			$vid = $pid_list[$key];
		$results["s1_".$vid] = $result;
		//echo $key . "\t\ts1_" . $vid . "\t\t" . $inst_string . "<br/>";
		//var_dump($labels_array[$key]);
		//echo $key . "\n";
		//var_dump($label_array);
	}
	echo json_encode($results);
	//file_put_contents("s1_" . $domain . ".data.json", json_encode($result), FILE_APPEND);
	?>
</body>
</html>