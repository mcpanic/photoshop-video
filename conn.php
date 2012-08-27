<?php
	$mysqli = new mysqli(localhost, "root", "root", "video_learning");
	if ($mysqli->connect_errno) {
	    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
?>