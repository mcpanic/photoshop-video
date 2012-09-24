<?php
//print_r($_POST);

include "conn.php";

$video_id = $mysqli->escape_string($_POST["video_id"]);
$user_id = $mysqli->escape_string($_POST["user_id"]);
$tm = $mysqli->escape_string($_POST["tm"]);
$type = $mysqli->escape_string($_POST["type"]);
$tool = $mysqli->escape_string($_POST["tool"]);
$comment = $mysqli->escape_string($_POST["comment"]);
$thumbnail = $mysqli->escape_string($_POST["filepath"]);


//$url = "label.php?vid=$video_id&uid=$user_id&tm=$tm";
$url = "label.php?vid=$video_id&tm=$tm";

$success = true;
if (!$mysqli->query("INSERT INTO labels(video_id,user_id,tm,type,tool,comment,thumbnail) " . 
  "VALUES('$video_id', '$user_id', '$tm', '$type', '$tool', '$comment', '$thumbnail')"))
  $success = false;
if ($mysqli->affected_rows != 1)
  $success = false;

$json = array(
  "id"=>$mysqli->insert_id,
  "thumbnail"=>$thumbnail,
  "tm"=>$tm,
  "type"=>$type,
  "tool"=>$tool,
  "comment"=>$comment,
  "url"=>$url,
  "success"=>$success
);
echo json_encode($json);
?>
