<?php
//print_r($_POST);

$id = intval($_POST["id"]);

include "conn.php";

if (!$mysqli->query("DELETE FROM labels WHERE id='$id'")) 
  echo "query error";
if ($mysqli->affected_rows != 1)
  echo "query error";

$json = array(
  "id"=>$id,
  "success"=> true
);
echo json_encode($json);

?>
