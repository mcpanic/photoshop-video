<?php
  include "conn.php";
  
  // Hardcode for the example
  $result1 = $mysqli->query("SELECT * FROM videos WHERE id='52'");
  $video = $result1->fetch_assoc();
  $video_filename = $video['filename'];
  $string = file_get_contents("video/$video_filename.info.json");
  $json = json_decode($string, true);

  $result3 = $mysqli->query("SELECT * FROM labels WHERE video_id = '" . $video['id'] . "'");    
  $label_array = array();
  // $html = "";
  while ($label = $result3->fetch_assoc()) {
    $label_array[] = $label;  
  }
  	$filename = "video/" . $video_filename;
    $ff_filename = "video/ff/" . $video_filename . ".flv";
    $sc_filename = "video/sc/" . $video_filename . ".flv";
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>TOCHI Supplement Materials</title>
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

  <h2>Supplementary Materials for TOCHI-2013-0064</h2>
  <h4>Summarization and Interaction Techniques for Enhancing the Learning Experience of How-to Videos</h4>
  <div>
  	This web-based supplementary materials provide examples of the three video summarization methods used in Study 1, and the two interface conditions used in Study 2.
  </div>
  <div>
  	Browser support: Tested with Chrome on Mac and Windows. Videos might not play due to codec issues under some browsers.
  </div>

  <h3>Part 1. The summarization methods used in Study 1.</h3>
	<div id="original-demo">
	    This is the original tutorial video shown to participants as an example.<br><br>
	    <div id="mediaplayer" class="row">video loading...</div>
	</div>
	<div id="sb-demo">
	    <br><br><b>Method 1. Storyboard</b> is a static summary of important moments in a tutorial. <br>
	    Here is an example. You can scroll left and right to browse steps in a video tutorial. <br><br>
	    <div class="span12 video-view" id="storyboard"></div>
	    <!--<img src="img/storyboard.png" class="img-polaroid">-->
	    <div class="row"></div>
	</div>
	<div id="ff-demo">
	    <br><br><b>Method 2. Fast Forward</b> is an abbreviated version of the actual clip, at 12x speed. <br>
	    Play the video for an example.<br><br>
	    <div id="ff-mediaplayer" class="row">video loading...</div>
	</div>
	<div id="sc-demo">
	    <br><br><b>Method 3. Scene Clip</b> is compilation of short snippets of important moments in a tutorial. <br>
	    Play the video for an example.<br><br>
	    <div id="sc-mediaplayer" class="row">video loading...</div><br><br>
	</div>
  <h3>Part 2. The interface conditions used in Study 2.</h3>
  Click the links to open the interfaces used in Study 2.
  <h4>Interface Condition 1. Baseline</h4>
  <div id="yt-browse"><a href="tochi-list.php?part=2&step=tutorial-task&cond=A&tid=6&iid=2">Browsing Page</a></div>
  <div id="yt-watch"><a href="tochi-watch.php?vid=52&iid=2">Watching Page</a></div>

  <h4>Interface Condition 2. ToolScape</h4>
  <div id="ts-browse"><a href="tochi-list.php?part=2&step=tutorial-task&cond=A&tid=6&iid=1">Browsing Page</a></div>
  <div id="ts-watch"><a href="tochi-watch.php?vid=52&iid=1">Watching Page</a></div>

</div><!--container-->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>
<script src="js/libs/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="js/libs/jquery-ui-1.8.22.custom.min.js"></script> 
<script type="text/javascript" src="js/libs/jwplayer/jwplayer.js"></script>
<script type="text/javascript" src="js/libs/jcarousel/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="js/libs/imagesloaded/jquery.imagesloaded.min.js"></script>
<script src="js/libs/jcview/jquery.vt.jcview.js"></script>
<script type="text/javascript" src="js/libs/lightbox/jquery.lightbox-0.5.js"></script>

<script src="js/script.js"></script>

<script type="text/javascript">

  $(document).ready(function() {

    var label_array = <?php echo json_encode($label_array); ?>;
    var video = <?php echo json_encode($video); ?>;

    var filename = "<?=$filename;?>";
    if (jwplayer("mediaplayer") != null){
      jwplayer("mediaplayer").setup({
        flashplayer: "js/libs/jwplayer/player.swf",
        width: "400",
        height: "250",
        controlbar: "bottom",        
        file: filename,
        image: "<?=$json['thumbnail'];?>"
      });
    }

      $("#storyboard").jcview({
        labels: label_array, 
        duration: video.duration
      });         
      $(".mycarousel").jcarousel({
        scroll: 1,
        visible: 5//,
        //initCallback: mycarousel_initCallback
      });
      // lightbox init
      $("a.lightbox").lightBox();

    var ff_filename = "<?=$ff_filename;?>";
    if (jwplayer("ff-mediaplayer") != null){
      jwplayer("ff-mediaplayer").setup({
        flashplayer: "js/libs/jwplayer/player.swf",
        width: "400",
        height: "250",
        controlbar: "bottom",        
        file: ff_filename,
        image: "<?=$json['thumbnail'];?>"
      });
    }

    var sc_filename = "<?=$sc_filename;?>";
    if (jwplayer("sc-mediaplayer") != null){
      jwplayer("sc-mediaplayer").setup({
        flashplayer: "js/libs/jwplayer/player.swf",
        width: "400",
        height: "250",
        controlbar: "bottom",        
        file: sc_filename,
        image: "<?=$json['thumbnail'];?>"
      });
    }


  });

</script>

</body>
</html>