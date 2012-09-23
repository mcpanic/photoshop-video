<?php
//print_r($_POST);
/* example format
    [logger] => [default]
    [timestamp] => 1345848273272
    [level] => INFO
    [url] => http://localhost:8888/labeler/index.php
    [message] => [object Object]
    [layout] => HttpPostDataLayout

    message format: JSON
    - video_id
    - user_id
    - interface_id
    - task_id
    - action (hover, click, ...)
    - object type: label, video, user, ...
    - object id: (object type)_id to refer to the corresponding database
*/



$logger = mysql_real_escape_string($_POST["logger"]);
$timestamp = mysql_real_escape_string($_POST["timestamp"]);
$level = mysql_real_escape_string($_POST["level"]);
$url = mysql_real_escape_string($_POST["url"]);
$message = mysql_real_escape_string($_POST["message"]);
/*
$message = json_decode($_POST["message"], true);

foreach($message as $key => $value) {
  $keys[] = $key;
  $values[] = "'" . mysql_real_escape_string($value) . "'";
  //(is_numeric($value)) ? "`$key` = $value" : "`$key` = '" . mysql_real_escape_string($value) . "'"; 
}
$keys = implode(", ", $keys);
$values = implode(", ", $values);
*/
include "conn.php";

//if (!$mysqli->query("INSERT INTO logs(logger, timestamp, level, url, $keys) " . 
//  "VALUES('$loger', '$timestamp', '$level', '$url', )"))
//  echo "query error";
//if ($mysqli->affected_rows != 1)
//  echo "query error";


if (!$mysqli->query("INSERT INTO logs(logger, time_logged, level, url, message) " . 
  "VALUES('$logger', '$timestamp', '$level', '$url', '$message')"))
  $success = false;
if ($mysqli->affected_rows != 1)
  $success = false;

//echo "INSERT INTO logs(logger, time_logged, level, url, message) " . 
//  "VALUES('$logger', '$timestamp', '$level', '$url', '$message')";
?>
