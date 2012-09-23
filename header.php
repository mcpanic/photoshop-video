<?php
include "study-conditions.php";

$part1_1 = "vs-intro-i.php?part=1&step=intro-i&cond=1" . 
  "&tid=" . $user_condition[$_SESSION["username"]]["cond1"]["tid"] .
  "&iid=" . $user_condition[$_SESSION["username"]]["cond1"]["iid"];
$part1_2 = "vs-intro-i.php?part=1&step=intro-i&cond=2" . 
  "&tid=" . $user_condition[$_SESSION["username"]]["cond2"]["tid"] .
  "&iid=" . $user_condition[$_SESSION["username"]]["cond2"]["iid"];  
$part1_3 = "vs-intro-i.php?part=1&step=intro-i&cond=3" . 
  "&tid=" . $user_condition[$_SESSION["username"]]["cond3"]["tid"] .
  "&iid=" . $user_condition[$_SESSION["username"]]["cond3"]["iid"];  

$part2_1 = "tutorial.php?part=2&step=tutorial&cond=A";
$part2_2 = "tutorial.php?part=2&step=tutorial&cond=B";  

//$part1_2 = 
//$part1_3 =
//$part2_1 =
//$part2_2 =

?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>Video Tutorial Browser</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <meta name="viewport" content="width=device-width">

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
  <link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.22.custom.css" type="text/css" />
  <link rel="stylesheet" href="css/jcrop/jquery.Jcrop.css" type="text/css" />  
  <link rel="stylesheet" type="text/css" href="css/jcarousel/skins/tango/skin.css" />
  <link rel="stylesheet" type="text/css" href="css/lightbox/jquery.lightbox-0.5.css" media="screen" />
  <link rel="stylesheet" href="css/style.css">

  <script src="js/libs/modernizr-2.5.3-respond-1.1.0.min.js"></script>
</head>
<body>
<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
<div class="container">

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="intro.php">Tutorial Video Browser</a>
          <div class="nav-collapse">
            <ul class="nav" id="task-selector">
              <!--
              <li><a href="tutorial.php">Tutorial</a></li>
              <li><a href="list.php?tid=1">Task 1</a></li>
              <li><a href="list.php?tid=2">Task 2</a></li>
              <li><a href="list.php?tid=3">Task 3</a></li>       
              -->
              <li><a href="vs-intro.php?part=1&step=intro&cond=1">Part 1 Intro</a></li>
              <li><a href="<?php echo $part1_1;?>">Part 1-1</a></li>
              <li><a href="<?php echo $part1_2;?>">Part 1-2</a></li>
              <li><a href="<?php echo $part1_3;?>">Part 1-3</a></li>
              <li><a href="design-intro.php?part=2&step=intro&cond=A">Part 2 Intro</a></li>
              <li><a href="<?php echo $part2_1;?>">Part 2-1</a></li>
              <li><a href="<?php echo $part2_2;?>">Part 2-2</a></li>       
            </ul>
            <ul class="nav pull-right">
              <li class="divider-vertical"></li>
              <li class="dropdown" id="profile">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#profile"><i class="icon-user"></i><?php echo $_SESSION['username']; ?><b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <?php if ($_SESSION["is_admin"]) { ?>
                  <li><a href="list.php?tid=0">All videos</a></li>
                  <li><a href="list-commands.php?scope=menu">Menu list</a></li>
                  <li><a href="list-commands.php?scope=tool">Tool list</a></li>
                  <li><a href="manage.php">Study Management</a></li>
                  <?php } ?>
                  <li><a href="logout.php">Logout</a></li>
                </ul>
              </li>
            </ul>            
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
