<?php
//print_r($_POST);

$video_id = mysql_real_escape_string($_POST["video_id"]);
$user_id = mysql_real_escape_string($_POST["user_id"]);
$tm = mysql_real_escape_string($_POST["tm"]);
$type = mysql_real_escape_string($_POST["type"]);
$tool = mysql_real_escape_string($_POST["tool"]);
$comment = mysql_real_escape_string($_POST["comment"]);
$filepath = mysql_real_escape_string($_POST["filepath"]);

$thumbnail = $filepath;

$mysqli = new mysqli(localhost, "root", "root", "video_learning");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

/*
$url = "localhost:8888/labeler/make-thumbnail.php";
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
}
*/

$url = "labeler.php?vid=$video_id&uid=$user_id&tm=$tm";

if (!$mysqli->query("INSERT INTO labels(video_id,user_id,tm,type,tool,comment,thumbnail) " . 
  "VALUES('$video_id', '$user_id', '$tm', '$type', '$tool', '$comment', '$thumbnail')"))
  echo "query error";
if ($mysqli->affected_rows != 1)
  echo "query error";

$json = array(
  "thumbnail"=>$thumbnail,
  "tm"=>$tm,
  "type"=>$type,
  "tool"=>$tool,
  "comment"=>$comment,
  "url"=>$url
);
echo json_encode($json);

/*
function checkRemoteFile($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(curl_exec($ch)!==FALSE)
    {
        return true;
    }
    else
    {
        return false;
    }
}
 */
?>
