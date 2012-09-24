<?php
//print_r($_POST);

include "conn.php";

$id = intval($_POST["id"]);
$video_id = $mysqli->escape_string($_POST["video_id"]);
$user_id = $mysqli->escape_string($_POST["user_id"]);
$tm = $mysqli->escape_string($_POST["tm"]);
$type = $mysqli->escape_string($_POST["type"]);
$tool = $mysqli->escape_string($_POST["tool"]);
$comment = $mysqli->escape_string($_POST["comment"]);
$thumbnail = $mysqli->escape_string($_POST["filepath"]);

$url = "label.php?vid=$video_id&tm=$tm";

$success = true;
if (!$mysqli->query("UPDATE labels SET video_id='$video_id', user_id='$user_id', tm='$tm', type='$type', tool='$tool'," . 
  "comment='$comment', thumbnail='$thumbnail' WHERE id=$id")) 
  $success = false;
if ($mysqli->affected_rows != 1)
  $success = false;

$json = array(
  "id"=>$id,
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
