<?php

ob_start();

include "conn.php";

// Define $myusername and $mypassword
$myusername=$_POST["myusername"];
//$mypassword=$_POST["mypassword"];

// To protect MySQL injection
$myusername = stripslashes($myusername);
//$mypassword = stripslashes($mypassword);

$myusername = $mysqli->escape_string($myusername);
//$myusername = mysql_real_escape_string($myusername);
//$mypassword = mysql_real_escape_string($mypassword);

$result = $mysqli->query("SELECT * FROM users WHERE username='$myusername'");
if ($result->num_rows == 1){
	$user = $result->fetch_assoc();
	//print_r($user);
	session_start();
	$_SESSION["username"] = $myusername;
	$_SESSION["is_admin"] = $user["is_admin"];
	$_SESSION["uid"] = $user["id"];
	if ($_POST["version"] == "v1")
		header("location:intro.php");
	else
		header("location:v2-list.php?tid=1&iid=1");
} else
	echo "Username does not exist!";

ob_end_flush();
?>