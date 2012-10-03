<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>ToolScape</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <meta name="viewport" content="width=device-width">

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
  <link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.22.custom.css" type="text/css" />
  <link rel="stylesheet" href="css/jcrop/jquery.Jcrop.css" type="text/css" />  
  <link rel="stylesheet" type="text/css" href="css/jcarousel/skins/tango/skin.css" />
  <link rel="stylesheet" href="css/style.css">

  <script src="js/libs/modernizr-2.5.3-respond-1.1.0.min.js"></script>
</head>
<body>
<div class="container">
<div class="row">
	<h1 class="offset3">ToolScape</h1>
	<div class="offset2">
		<form class="form-horizontal" name="form1" method="post" action="check-login.php">
		<div class="control-group">	
			<label class="control-label" for="myusername">Username</label>
			<div class="controls">
				<input name="myusername" type="text" id="myusername">
			</div>
			
			<div class="controls">
				<input type="radio" name="version" value="v2" checked>  V2 prototype<br>
				<input type="radio" name="version" value="v1">  V1 Video Tutorials Study
			</div>
		</div>
		<div class="control-group">
			<div class="controls">							
				<button type="submit" class="btn">Sign in</button>
			</div>
		</div>
		</form>
	</div>
</div>
</div>
</body>
</html>