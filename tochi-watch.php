<?php
  $video_id = $_GET["vid"];
  //$user_id = $_GET["uid"];

  if (isset($_GET["tid"]))
    $task_id = $_GET["tid"];
  else
    $task_id = 0;

  if (isset($_GET["tm"]))
    $tm = $_GET["tm"];
  else
    $tm = -1;

  if (isset($_GET["iid"]))
    $interface_id = $_GET["iid"];
  else
    $interface_id = 1;

  include "conn.php";
  
  $result = $mysqli->query("SELECT * FROM videos WHERE id='$video_id'");
  if ($result->num_rows != 1)
    echo "query error";
  $video = $result->fetch_assoc();
  $duration = $video['duration'];
  $video_filename = $video['filename'];

  $string = file_get_contents("video/$video_filename.info.json");
  $json = json_decode($string, true);

  //$result = $mysqli->query("SELECT * FROM labels WHERE video_id='$video_id' AND user_id='$user_id' ORDER BY tm ASC");
  $result = $mysqli->query("SELECT * FROM labels WHERE video_id='$video_id' ORDER BY tm ASC");
  $labels = array();
  while ($row = $result->fetch_assoc()) {
    $labels[] = $row;
  }
  $result->free();
  $mysqli->close();
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

  <h3><a href="javascript:history.go(-1)">&lt;&lt; Back to list</a></h3>

  <h2><?=$video['title'];?></h2>
  <!--<div id="description"><?=$json['description'];?></div>-->

<?php if ($interface_id == 1) { ?>
  <div id="mediaplayer" class="row">loading video...</div>

    <div class="row">
      <div class="span12">
        <div id="timeline">
          <div id="control-left">
            <a class="btn btn-small" href="#" id="play-button"><i class="icon-play"></i></a>
            <span id="elapsed" class="time-display">0:00</span>
          </div>
          <div id="control-center">
            <!--<div id="playhead"></div>-->
            <!--<div id="seekhead"></div>-->
            <div id="timeline-bottom"></div>
          </div>
          <div id="control-right">            
            <span id="duration" class="time-display">0:00</span>
          </div>
        </div>
      </div>
    </div>


<?php } elseif ($interface_id == 2) { ?>

  <div id="mediaplayer" class="row">loading video...</div>

<?php } elseif ($interface_id == 3) { ?>

  <ul id="html_tutorial"> 
  </ul>
  <!--<div id="mediaplayer" class="row" style="display:none"></div>-->
<?php } elseif ($interface_id == 4) { ?>
  <div id="mediaplayer" class="row">loading video...</div>

  <div id="tabs" class="row">
    <ul>
      <li><a href="#tabs-1">Before and After</a></li>
      <li><a href="#tabs-4">Slide Show</a></li>
      <li><a href="#tabs-2">Works in Progress</a></li>
      <li><a href="#tabs-3">Step by Step</a></li>
    </ul>
    <div id="tabs-1"></div>
    <div id="tabs-4"></div>
    <div id="tabs-2"></div>
    <div id="tabs-3"></div>

  </div>
<?php } ?>

</div><!--container-->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>
<script src="js/libs/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="js/libs/jquery-ui-1.8.22.custom.min.js"></script> 
<script type="text/javascript" src="js/libs/jwplayer/jwplayer.js"></script>
<script type="text/javascript" src="js/libs/jcarousel/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="js/libs/imagesloaded/jquery.imagesloaded.min.js"></script>
<!-- <script src="js/libs/log4javascript.js"></script> -->


<script src="js/script.js"></script>

  <script type="text/javascript">

    function jump(time) {
      var elapsed = jwplayer().getPosition();
      jwplayer().seek(elapsed+time);
    }

    function mycarousel_initCallback(carousel) {
      $('.jcarousel-control a').click(function() {
        carousel.scroll($.jcarousel.intval($('.jcarousel-control a').index($(this))));
        //console.log($('.jcarousel-control a').index($(this)))
        return false;
      });
    }

    $(document).ready(function() {
      var tm = <?php echo $tm; ?>;
      var duration = <?php echo $duration; ?>;
      //console.log(duration);
      var video_id = <?php echo $video_id; ?>;
      var task_id = <?php echo $task_id; ?>;
      var interface_id = <?php echo $interface_id; ?>;
      // var log = log4javascript.getLogger();
      // var ajaxAppender = new log4javascript.AjaxAppender("ajax-add-log.php");
      // log.addAppender(ajaxAppender);
      // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "open", "page"));


      $("#duration").html(getTimeDisplay(duration));

      $( "#tabs" ).tabs();
      
      $("#play-button").click(function(){
        if ($("#play-button i").hasClass("icon-play")){
          jwplayer().play();
          // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "click", "play", "#play-button"));
        } else {
          jwplayer().pause();     
          // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "click", "pause", "#play-button"));
        }
        return false;
      });

      // Play button next to thumbnails
      $(".seek").click(function() {
        // get your id here
        var id = parseInt(this.id.substring(4));
        if (id < 5) id = 5; // seek does not accept negative integers
        jwplayer().seek(id - 5);
        // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "click", "seek", id));
        return false;
      });

      // handling popover shown on the timeline viz
      $("body").on("mouseenter", ".vt-popover", function(){
        var index = parseInt($(this).css("z-index"));
        $(this).css("z-index", index+1);
        $(this).find(".vt-popover-inner").addClass("popover-highlight");
      });

      // handling popover shown on the timeline viz
      $("body").on("mouseleave", ".vt-popover", function(){
        var index = parseInt($(this).css("z-index"));
        $(this).css("z-index", index-1);
        $(this).find(".vt-popover-inner").removeClass("popover-highlight");
      });

      // tiny markers on the timeline slider
      $("#control-center").on("click", ".marker", function(){
        var id = parseInt(this.id.substring(6));
        if (id < 5) id = 5;
        jwplayer().seek(id - 5); 
        // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "click", "control-center marker", id));
        return false;       
      });

      $( "#timeline-bottom" ).slider({
        range: "min",
        min: 0,
        max: parseInt(duration),
        step: 0.1,
        animate: true,
        slide: function(event, ui){
          jwplayer().seek(ui.value);
          // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "click", "slider", ui.value));
        }
      });

      var labels = <?php echo json_encode($labels); ?>;
      var tabs1_html = tabs2_html = tabs3_html = "";
      var tabs4_html = "<ul id='mycarousel' class='jcarousel-skin-tango'>";
      var control_html = "<div class='jcarousel-control'>";
      
      var text_html = "";

      $.each(labels, function(index, label){
        // 1. Adding Time Markers
        var id = "marker" + label.tm;
        var tm_formatted = getTimeDisplay(parseInt(label.tm));
        //var offset = parseInt(label.tm) * control_width / duration;                

        var offset = parseInt(label.tm * 100) / duration;
        var html = "<span class='marker' id='" + id + "' style='left:" + offset + "%;'></span>";        

        $("#control-center").append(html);

        // 1.1 Adding idle markers
        if (label.type == "image" && label.comment == "#initial") {
          html = "<span class='idle-marker idle-marker-left' style='width:" + offset + "%;'></span>";        
          $("#control-center").append(html);
        } else if (label.type == "image" && label.comment == "#final") {
          html = "<span class='idle-marker idle-marker-right' style='width:" + (100-offset) + "%;left:" + offset + "%;'></span>";        
          $("#control-center").append(html);
        }


        // 2. Adding popovers
        var popover_position = "";
        var popover_content = "";
        if (label.type == "image") {
          // adding idle marker          
          $("#" + id).addClass("marker-image");            
          popover_position = "bottom";
          popover_content = "<p><a id='a" + id + "' class='popover-click-image' href='#'><img class='timeline-popover' src='" + label.thumbnail + "'></a></p>";
        } else {
          $("#" + id).addClass("marker-tool");
          popover_position = "top";
          popover_content = "<p><a id='a" + id + "' class='popover-click-tool' href='#'>" + formatTool(label.tool, "toolname-block") + "</a></p>";
        }
        html = "<div class='vt-popover " + popover_position + "' data-offset='" + offset + "' style='left: " + offset + "%; display: block; z-index: 1010;'>"
              + "<div class='arrow'></div>"
              + "<div class='vt-popover-inner'>"
                + "<h3 class='vt-popover-title'>" + tm_formatted  + " " + label.comment + " </h3>"
                + "<div class='vt-popover-content'>" + popover_content + "</div>"
              + "</div>"
            + "</div>"; 
        $("#" + id).after(html);

        // 3. Adding Marker Sticks that connect markers with popovers
        id = "marker-stick" + label.tm;
        var stick_class = "marker-stick-";
        if (label.type == "image") 
          stick_class = stick_class + "image";
        else
          stick_class = stick_class + "tool";
        
        html = "<span class='marker-stick " + stick_class + "' id='" + id + "' style='left:" + offset + "%;'></span>";
        $("#control-center").append(html);

        // 4. Adding tabbed views and HTML Tutorial
        var url = "browse.php?vid=" + <?=$video_id?> + "&tm=" + label.tm;
        var thumb_html = "<a href='" + label.thumbnail + "'><img src='" + label.thumbnail + "?rand=" + Math.random() + "'></a>";
        var play_html = "<a class='seek btn' id='time" + label.tm + "'href='#'><i class='icon-play'></i></a>";

        if (label.comment == "#initial")
          tabs1_html = tabs1_html + "<div class='browse-initial'>Initial Image&nbsp;" + play_html + "<br>" + thumb_html + "</div>";

        if (label.comment == "#final")
          tabs1_html = tabs1_html + "<div class='browse-final'>Final Image&nbsp;" + play_html + "<br>" + thumb_html + "</div>";

        if (label.type == "image") {
          tabs2_html = tabs2_html + "<div class='browse-wip'>(" + tm_formatted + ") &nbsp;" + play_html + "<br>" + thumb_html + "</div>";
          tabs3_html = tabs3_html + "<div class='browse-steps-image'>(" + tm_formatted + ") &nbsp;" + play_html + "<br>" + thumb_html + "</div>";
          tabs4_html = tabs4_html + "<li>" + thumb_html + "</li>";
          control_html = control_html + "<a href='#' class='marker-image'>" + tm_formatted + "</a> ";

          //text_html = text_html + "<li class='span8'><h3>Step " + parseInt(index+1) + "</h3>" + thumb_html + "</li>";
          text_html = text_html + "<li class='span8'>" + thumb_html + "<br><br><br></li>";
        } else {
          tabs3_html = tabs3_html + "<div class='browse-steps-tool'>(" + tm_formatted + ") &nbsp;" + play_html + "<br><b>" + label.tool + "</b></div>";
          tabs4_html = tabs4_html + "<li><b>" + label.tool + "</b><br>" + thumb_html + "</li>";
          control_html = control_html + "<a href='#' class='marker-tool'>" + tm_formatted + "</a> ";

          //text_html = text_html + "<li class='span8'><h3>Step " + parseInt(index+1) + "</h3><div><b>" + label.tool + "</b></div><br>" + thumb_html + "</li>";
        }
        
      });
      tabs4_html = control_html + "</div>" + tabs4_html + "</ul>";
      $("#tabs-1").html(tabs1_html);
      $("#tabs-2").html(tabs2_html);
      $("#tabs-3").html(tabs3_html);
      $("#tabs-4").html(tabs4_html);

      $("#html_tutorial").html(text_html);

      $(".popover-click-image, .popover-click-tool").click(function(){
        var id = parseInt(this.id.substring(7));
        if (id < 5) id = 5;
        jwplayer().seek(id - 5); 
        //seek(id - 5); 
        // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "click", "popover-click-image popover-click-tool", id));
        return false;       
      });

      $("#mycarousel").jcarousel({
        scroll: 1,
        visible: 3,
        initCallback: mycarousel_initCallback,
        itemFirstInCallback: function(carousel, obj, index, action){
          $('.jcarousel-item').eq(index).css("border", "5px solid #FF6600"); 
          $('.jcarousel-control a').eq(index).css("border", "5px solid #FF6600");      
        },
        itemFirstOutCallback: function(carousel, obj, index, action){
          $('.jcarousel-item').eq(index).css("border", "none"); 
          $('.jcarousel-control a').eq(index).css("border", "none");      
        }
      });


      function updatePosition(){
        $(".vt-popover").each(function(index, item){
          $(this).css("left", $(this).data("offset") + "%");
          $(this).css("left", $(this).position().left - $(this).width()/2 + 1);
        });
      }

      // Popover positioning upon resizing is tricky because it has both % and pixel positioning assignment
      $(window).resize(function(event){
        updatePosition();
      });

      // Getting the correct position for each popover should be done after all images are loaded.
      // Otherwise prev.width() might return wrong values.
      // That's why this imagesLoaded plugin is necessary.      
      $(".timeline-popover").imagesLoaded(function( $images, $proper, $broken ){
        // TODO: error checking using $broken...
        //console.log( $images.length + ' images total have been loaded in ' + this );
        //console.log( $proper.length + ' properly loaded images' );
        //console.log( $broken.length + ' broken images' );

        updatePosition();

         $(".popover-click-image").each(function(index, item){
          var cur = $(this).parent().parent().parent().parent();             
          var cur_stick = $(".marker-stick-image").eq(index);

          if (index == 0){
            cur.addClass("popover-image-top");
            cur_stick.addClass("marker-stick-image-top");
          } else {
            var prev_index = index - 1;
            var prev = $(".popover-click-image:eq(" + prev_index + ")").parent().parent().parent().parent();
            //console.log(index, prev.offset().left, prev.width(), cur.offset().left);
            //console.log("image", prev.offset().left, prev.width(), cur.offset().left);
            if (cur.position().left - prev.position().left >= prev.width()){
              cur.addClass("popover-image-top");
              cur_stick.addClass("marker-stick-image-top");
            } else {
              if (prev.hasClass("popover-image-top")){
                cur.addClass("popover-image-bottom");
                cur_stick.addClass("marker-stick-image-bottom");
                //cur.css("margin-top", (5+prev.height()) + "px");
              }
              else {
                cur.addClass("popover-image-top");
                cur_stick.addClass("marker-stick-image-top");
              }
            }
          }

          // Now offset management: Hopefully DEPRECATED
          /*
          var offset = $(cur).children().eq(0).offset().left - cur_stick.offset().left;
          console.log("offset", offset);
          if (offset > 10 || offset < -10) {
            console.log("OFFSET BUG");
            //$(cur).css("left", $(cur).offset().left - offset - 5);
          }        
          */      
        });

        $(".popover-click-tool").each(function(index, item){
          var cur = $(this).parent().parent().parent().parent();
          var cur_stick = $(".marker-stick-tool").eq(index);
          
          if (index == 0){
            cur.addClass("popover-tool-bottom");
            cur_stick.addClass("marker-stick-tool-bottom");
            return;
          } else {
            var prev_index = index - 1;
            var prev = $(".popover-click-tool:eq(" + prev_index + ")").parent().parent().parent().parent();
            
            // getting prev.width() is fine because all the content is text, so it's already loaded.
            if (cur.position().left - prev.position().left >= prev.width()){
              cur.addClass("popover-tool-bottom");
              cur_stick.addClass("marker-stick-tool-bottom");
            } else {
              if (prev.hasClass("popover-tool-bottom")) {
                cur.addClass("popover-tool-top");
                cur_stick.addClass("marker-stick-tool-top");
              } else {
                cur.addClass("popover-tool-bottom");
                cur_stick.addClass("marker-stick-tool-bottom");
              }
            }
          }
        });
      });
      // if a specific moment was designated in the url, jump to that time
      if (tm >= 0) {
        jwplayer().play(true);
        jwplayer().seek(tm);
        // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "open", "tm in url", tm));
      }

      var cond = "<?php echo $_GET["cond"]; ?>";
      if (cond == "A")
        $("#task-selector li").eq(5).addClass("active");
      else
        $("#task-selector li").eq(6).addClass("active");


/*
var player = null; 
function playerReady(thePlayer) { 
  player = window.document[thePlayer.id]; 
  player.addModelListener('STATE', 'stateEventListener');
}


// Potentially encapsulate in your player utility class
oneTimeStateEvents = [];


function addOneTimeStateEvent(playerId, targetState, f) {
    var e = {
        playerId: playerId          // ID of player
        , targetState: targetState  // State to trigger on
        , f: f                      // Function to execute
        };
    oneTimeStateEvents.push(e);
}


function stateEventListener(info) {
  console.log(info);
    for (var i = 0; i < oneTimeStateEvents.length; ) {
        var e = oneTimeStateEvents[i];
        // id doesn't appear to be properly set in incoming event objects?
        //if (e.playerId == info.id && e.targetState == info.newstate ) {
        if (e.targetState == info.newstate ) {
            oneTimeStateEvents.splice(i,1);  // remove event
            e.f();
            continue;
        }
        i++;
    }
}


// Potentially encapsulate in your player class




function seek(time) {
    var state = player.getConfig()['state'];
    console.log(state);
    if (state == 'IDLE') {
        // Create a one time event to seek once playback starts
        addOneTimeStateEvent("mediaplayer",'PLAYING',
            function() {
                player.sendEvent('SEEK',time);
        });
        player.sendEvent('PLAY', true);
    }
    else {
        if (state == 'STOPPED') {
            player.sendEvent('PLAY', true);
        }
        player.sendEvent('SEEK', time);
    }
}
*/
      jwplayer("mediaplayer").setup({
        flashplayer: "js/libs/jwplayer/player.swf",
        width: "900",
        height: "500",
        controlbar: "bottom",
        file: "video/<?=$video_filename;?>",
        image: "<?=$json['thumbnail'];?>",
        autostart: true,
        plugins: {"timeslidertooltipplugin-3": {
          "preview":{
            'enabled':true,
            'path':'video/thumbs/',
            'prefix':"<?=pathinfo($video_filename, PATHINFO_FILENAME);?>",
            'frequency': 3
            }
        }},
        events: {
          onTime: function(event){
            $("#elapsed").html(getTimeDisplay(parseInt(event.position)));
            $("#timeline-bottom").slider('value', event.position);
          },
          onPlay: function(event){
            $("#play-button i").removeClass("icon-play").addClass("icon-pause");
            // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "click", "mediaplayer played", jwplayer().getPosition()));
          },
          onPause: function(event){
            $("#play-button i").removeClass("icon-pause").addClass("icon-play");
            // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "click", "mediaplayer paused", jwplayer().getPosition()));
          },
          onFullscreen: function(event){
            // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "click", "mediaplayer fullscreen", event.fullscreen));
          },        
          onError: function(event){
            // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "error", "mediaplayer error", event.message));
          },              
          onMute: function(event){
            // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "click", "mediaplayer mute", ""));
          },            
          onComplete: function(event){
            // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "complete", "mediaplayer complete", ""));
          },            
          onVolume: function(event){
            // log.info(formatBrowseLog(video_id, interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "click", "mediaplayer volume", event.volume));
          },            
        }

      });

  }); // ready
  </script>
  <!-- END OF THE PLAYER EMBEDDING -->

</body>
</html>
