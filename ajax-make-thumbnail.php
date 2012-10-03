<?php
include "conn.php";
$tm = $mysqli->escape_string($_POST["tm"]);
$filepath = $mysqli->escape_string($_POST["filepath"]);

$path_parts = pathinfo($_SERVER["SCRIPT_NAME"]);
//echo $path_parts['dirname'], "\n";

$url = $_SERVER['HTTP_HOST'] . $path_parts['dirname'] . "/make-thumbnail.php";

//$url = "localhost:8888/labeler/make-thumbnail.php";
$fields = "filepath=" . $_POST['filepath'] . "&tm=" . $tm;
//$fields = "filepath=$_POST['filepath']" . "&tm=$tm";

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 2); 
curl_setopt($ch, CURLOPT_POSTFIELDS,$fields); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
if (!$result = curl_exec($ch)){
  echo curl_error($ch);
  exit();
}
curl_close($ch); 
$thumbnail = "";
if($result == "error") {
	echo "file not found";
	exit();
} else {
	$thumbnail = $result;
	echo $thumbnail;
}

?>
