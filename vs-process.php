<?php 
session_start();
include "conn.php";
//echo var_dump($_POST);
//echo var_dump($_SESSION);
//echo var_dump($_SERVER);

$session_data = mysql_real_escape_string(serialize($_SESSION));
$post_data = mysql_real_escape_string(serialize($_POST));

$success = true;
if (!$mysqli->query("INSERT INTO responses(session, response) VALUES('$session_data', '$post_data')"))
  $success = false;
if ($mysqli->affected_rows != 1)
  $success = false;

/*
$query=SELECT * FROM shirtColors;
$doQuery=mysql_query($query);
$numrows=mysql_num_rows($doQuery);
if($numrows>0)
{
 while($colors=mysql_fetch_array($doQuery))
  {
  $colors=unserialize($colors['colors']);
	
  foreach($colors as $shirt)
   {
	  print $shirt.', ';
   }
  }
}
else
{
 print 'No colors in database.';
}
*/

include "study-conditions.php";

// from the rating data, return the three highest rated videos
function getCandidateVideos(){
	$ratings = array();
	$vid = array();
	for ($i=0; $i<10; $i++) {
		$ratings[] = $_POST["entry_" . $i . "_0_group"];
		$vids[] = $_POST["vid" . $i];		
	}
	//echo print_r($ratings);
	asort($ratings);
	//echo print_r(array_slice($ratings, -3, 3, true));
	$result = array();
	foreach(array_slice($ratings, -3, 3, true) as $key => $value)
		$result[] = $vids[$key];
	//echo print_r($result);
	return $result;
}

// part
// --cond
// ----step

$nextUrl = "";
$params = "";
$otherParams = "";

if ($_POST["part"] == 0) {
// part 0. Intro
	$nextPart = 1;
	$nextUrl = "vs-intro.php";
	$nextStep = "intro";
	$nextCond = 1;
} else if ($_POST["part"] == 1) {	
// part 1. Video Summarization Study
	if ($_POST["step"] == "intro"){
		$nextPart = 1;
		$nextUrl = "vs-intro-i.php";
		$nextStep = "intro-i";
		$nextCond = $_POST["cond"];
		$otherParams .= "&tid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["tid"];
		$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["iid"];
	} else if ($_POST["step"] == "intro-i"){
		$nextPart = 1;
		$nextUrl = "vs-intro-t.php";
		$nextStep = "intro-t";
		$nextCond = $_POST["cond"];
		$otherParams .= "&tid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["tid"];
		$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["iid"];		
	} else if ($_POST["step"] == "intro-t"){
		$nextPart = 1;
		$nextUrl = "vs-list.php";
		$nextStep = "list";
		$nextCond = $_POST["cond"];
		$otherParams .= "&tid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["tid"];
		$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["iid"];		
	} else if ($_POST["step"] == "list"){
		$nextPart = 1;
		$nextUrl = "vs-watch.php";
		$nextStep = "watch1";
		$nextCond = $_POST["cond"];
		$toWatch = getCandidateVideos();
		$otherParams .= "&vid1=" . $toWatch[0] . "&vid2=" . $toWatch[1] . "&vid3=" . $toWatch[2];
		$otherParams .= "&tid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["tid"];
		$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["iid"];		
	} else if ($_POST["step"] == "watch1") {
		$nextPart = 1;
		$nextUrl = "vs-watch.php";
		$nextStep = "watch2";
		$nextCond = $_POST["cond"];		
		$otherParams .= "&vid1=" . $_POST["vid1"] . "&vid2=" . $_POST["vid2"] . "&vid3=" . $_POST["vid3"];	
		$otherParams .= "&tid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["tid"];
		$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["iid"];			
	} else if ($_POST["step"] == "watch2") {
		$nextPart = 1;
		$nextUrl = "vs-watch.php";
		$nextStep = "watch3";
		$nextCond = $_POST["cond"];
		$otherParams .= "&vid1=" . $_POST["vid1"] . "&vid2=" . $_POST["vid2"] . "&vid3=" . $_POST["vid3"];
		$otherParams .= "&tid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["tid"];
		$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["iid"];		
	} else if ($_POST["step"] == "watch3")	{
		$nextPart = 1;
		$nextUrl = "vs-post-questionnaire.php";
		$nextStep = "pq";
		$nextCond = $_POST["cond"];
		$otherParams .= "&tid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["tid"];
		$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["iid"];		
	} else if ($_POST["step"] == "pq")	{
		$nextPart = 1;
		$nextUrl = "vs-done.php";
		$nextStep = "done";
		$nextCond = $_POST["cond"];
		$otherParams .= "&tid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["tid"];
		$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["iid"];		
	} else if ($_POST["step"] == "done") {
		if ($_POST["cond"] == 3) {
			$nextPart = 2;
			$nextUrl = "design-intro.php";
			$nextStep = "intro";
			$nextCond = "A";
		} else {
			$nextPart = 1;
			$nextUrl = "vs-intro-i.php";
			$nextStep = "intro-i";
			$nextCond = $_POST["cond"] + 1;
			$otherParams .= "&tid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["tid"];
			$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $nextCond]["iid"];			
		}
	}
// 	cond 1, 2, 3: determined per user from the table
//		step 0: vs-list.php
//		step 1, 2, 3: determined per user from their ratings in step 0.
//		step 4: vs-post-questionnaire.php

// part 2. End-to-End Design Study
} else if ($_POST["part"] == 2) {
	if ($_POST["step"] == "intro"){
		$nextPart = 2;
		$nextUrl = "tutorial.php";
		$nextStep = "tutorial";
		$nextCond = $_POST["cond"];
	} else if ($_POST["step"] == "tutorial"){
		$nextPart = 2;
		$nextUrl = "list.php";
		$nextStep = "tutorial-task";
		$nextCond = $_POST["cond"];
		$otherParams .= "&tid=" . $tutorial_tid;
		$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $_POST["cond"]]["iid"];
	} else if ($_POST["step"] == "tutorial-task"){
		$nextPart = 2;
		$nextUrl = "intro-task.php";
		$nextStep = "intro-task";
		$nextCond = $_POST["cond"];	
		$otherParams .= "&tid=" . $user_condition[$_SESSION["username"]]["cond" . $_POST["cond"]]["tid"];
		$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $_POST["cond"]]["iid"];	
	} else if ($_POST["step"] == "intro-task"){
		$nextPart = 2;
// Original Code: asking twice. uncomment the two lines.
		//$nextUrl = "preq.php";
		//$nextStep = "preq";
		if ($_POST["cond"] == "A"){
			$nextUrl = "preq.php";
			$nextStep = "preq";			
		} else {
			$nextUrl = "list.php";
			$nextStep = "list";						
		}	
		$nextCond = $_POST["cond"];	
		$otherParams .= "&tid=" . $user_condition[$_SESSION["username"]]["cond" . $_POST["cond"]]["tid"];
		$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $_POST["cond"]]["iid"];										
	} else if ($_POST["step"] == "preq"){
		$nextPart = 2;
		$nextUrl = "list.php";
		$nextStep = "list";
		$nextCond = $_POST["cond"];	
		$otherParams .= "&tid=" . $user_condition[$_SESSION["username"]]["cond" . $_POST["cond"]]["tid"];
		$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $_POST["cond"]]["iid"];		
	} else if ($_POST["step"] == "list"){
		$nextPart = 2;
		$nextUrl = "postq.php";
		$nextStep = "postq";
		$nextCond = $_POST["cond"];	
		$otherParams .= "&tid=" . $user_condition[$_SESSION["username"]]["cond" . $_POST["cond"]]["tid"];
		$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $_POST["cond"]]["iid"];	
	} else if ($_POST["step"] == "postq"){
		$nextPart = 2;
		$nextUrl = "done.php";
		$nextStep = "done";
		$nextCond = $_POST["cond"];	
		$otherParams .= "&tid=" . $user_condition[$_SESSION["username"]]["cond" . $_POST["cond"]]["tid"];
		$otherParams .= "&iid=" . $user_condition[$_SESSION["username"]]["cond" . $_POST["cond"]]["iid"];	
	} else if ($_POST["step"] == "done"){
		if ($_POST["cond"] == "A") {
			$nextPart = 2;
			$nextUrl = "tutorial.php";
			$nextStep = "tutorial";
			$nextCond = "B";
		} else {
			$nextPart = 3;
			$nextUrl = "finish.php";
			$nextStep = "finish";
			$nextCond = "";
		}
	}

} else {
// Store everything anyway to make sure we don't miss anything.

}
$params = "?part=" . $nextPart . "&step=" . $nextStep . "&cond=" . $nextCond; 
$nextUrl .= $params . $otherParams;
header("location:" . $nextUrl);
?>