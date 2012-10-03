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
          <a class="brand" href="v2-list.php?iid=1">ToolScape</a>
          <div class="nav-collapse">
            <ul class="nav" id="task-selector">   
              <li><a href="v2-list.php?iid=1">All Tasks</a></li>           
              <li><a href="v2-list.php?tid=1&iid=1">MBlur</a></li>
              <li><a href="v2-list.php?tid=2&iid=1">BGRem</a></li>
              <li><a href="v2-list.php?tid=3&iid=1">Lomo</a></li>
              <li><a href="v2-list.php?tid=4&iid=1">Retro</a></li>
              <li><a href="v2-list.php?tid=5&iid=1">Sktch</a></li>
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
