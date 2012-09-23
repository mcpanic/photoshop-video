<?php
$path_parts = pathinfo($_SERVER["SCRIPT_NAME"]);
//echo $path_parts['dirname'], "\n";

echo $_SERVER['HTTP_HOST'] . $path_parts['dirname'] . "/make-thumbnail.php";

//echo var_dump($_SERVER);


//$url = "localhost:8888/labeler/make-thumbnail.php";
echo $_SERVER['HTTP_HOST'] . " " . $_SERVER['SERVER_NAME']. " " . $_SERVER['PATH_INFO'];
?>