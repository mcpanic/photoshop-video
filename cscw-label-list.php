<?php 
include "conn.php";
?>

<html>
<body>
<?php
	$task_list = array("1001", "1002", "1003", "1004", "1005", "2001", "2002", "2003", "2004", "2005");

	// grab only cooking and makeup videos
	$result = $mysqli->query("SELECT * FROM videos WHERE task_id > 1000");
		// $video['id'] = "1";
		// $video['task_id'] = "1001";
	while ($video = $result->fetch_assoc()) {
		$url = "cscw-label.php?vid=" . $video['id'] . "&tid=" . $video['task_id'];
		echo $video['id'] . "\t" . $video['title'] . "\t" . "<a href='$url'>$url</a>";	}
?>
</body>
</html>