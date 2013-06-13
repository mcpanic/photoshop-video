<?php

  if (isset($_GET["tid"]))
    $task_id = $_GET["tid"];
  else
    $task_id = 0;

  if (isset($_GET["iid"]))
    $interface_id = $_GET["iid"];
  else
    $interface_id = 1;

  include "conn.php";
  
  if ($task_id == 0)
    $result1 = $mysqli->query("SELECT * FROM videos");
  else {
    $result1 = $mysqli->query("SELECT * FROM videos WHERE task_id='$task_id'");
    $result2 = $mysqli->query("SELECT * FROM tasks WHERE id='$task_id'");
    $task = $result2->fetch_assoc();
  }

  $videos_array = array();
  $labels_array = array();
  $meta_array = array();

  while ($video = $result1->fetch_assoc()) {
    $video_filename = $video['filename'];
    $string = file_get_contents("video/$video_filename.info.json");
    $json = json_decode($string, true);

    $result3 = $mysqli->query("SELECT * FROM labels WHERE video_id = '" . $video['id'] . "'");    
    $label_array = array();
    // $html = "";
    while ($label = $result3->fetch_assoc()) {
      $label_array[] = $label;  
    }

    $labels_array[] = $label_array;
    $videos_array[] = $video;
    $meta_array[] = $json;
  } 

  if ($task_id == 0)
    $result4 = $mysqli->query("SELECT count(distinct videos.id) AS freq, tool FROM labels, videos WHERE labels.video_id=videos.id AND tool<>'' GROUP BY tool ORDER BY freq DESC LIMIT 7");
  else
    $result4 = $mysqli->query("SELECT count(distinct videos.id) AS freq, tool FROM labels, videos WHERE labels.video_id=videos.id AND tool<>'' AND videos.task_id='$task_id' GROUP BY tool ORDER BY freq DESC LIMIT 7");
//    $result4 = $mysqli->query("SELECT count(*) AS freq, tool FROM labels, videos WHERE labels.video_id=videos.id AND tool<>'' AND videos.task_id='$task_id' GROUP BY tool ORDER BY freq DESC LIMIT 7");

  $tools_array = array();
  while ($tool = $result4->fetch_assoc()) {
    $tools_array[] = $tool;
  }


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

<?php if ($interface_id == 1) { ?>
  <div class="row">
    <h1 class="span9" id="title"></h1>
    <div class="span3">
<!--       <form id="myForm" action="vs-process.php" method="POST">
        Done with the task?
        <button id="nextButton" class="btn btn-large btn-primary" type="submit">
          NEXT
        </button>
      </form> -->
    </div>
    <div class="span4">
      <div id="stats"></div>
      <div>
        <h4 id="applied-filters-header">Showing all videos</h4>
        <div id="applied-filters">
        </div>
      </div> 
    </div>
    <div id="filters" class="span6 filters"><h3>Top tools</h3></div>
    <div id="views" class="span2">
      <h3>Views</h3>
      <input type="radio" name="view-option" value="all" checked>&nbsp;&nbsp;All<br>
      <input type="radio" name="view-option" value="simple">&nbsp;&nbsp;Simple
    </div>
  </div>

<?php } elseif ($interface_id == 2) { ?>

  <div class="row">
    <h1 class="span9" id="title"></h1>
      <div class="span3">
<!--     <form id="myForm" action="vs-process.php" method="POST">
      Done with the task?
      <button id="nextButton" class="btn btn-large btn-primary" type="submit">
        NEXT
      </button>
    </form> -->
  </div>

    <div id="stats" class="span4"></div>
  </div>

<?php } elseif ($interface_id == 3) { ?>

  <div class="row">
    <h1 class="span12" id="title"></h1>
    <div id="stats" class="span4"></div>
  </div>

<?php } ?>

<ol id="results" class="list-striped list-hover"></ol>

</div><!--container-->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>

<script src="js/libs/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="js/libs/jquery-ui-1.8.22.custom.min.js"></script> 
<script type="text/javascript" src="js/libs/jcarousel/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="js/libs/lightbox/jquery.lightbox-0.5.js"></script>
<script src="js/libs/jcview/jquery.vt.jcview.js"></script>
<!-- <script src="js/libs/log4javascript.js"></script> -->

<script type="text/javascript">    
    $(document).ready(function() {
      // Nav bar activation
      var task_id = <?php echo $task_id; ?>;
      var interface_id = <?php echo $interface_id; ?>;
      var is_admin = false;
      //console.log(is_admin, !is_admin, (is_admin == true), (is_admin == false));      
      // var log = log4javascript.getLogger();
      // var ajaxAppender = new log4javascript.AjaxAppender("ajax-add-log.php");
      // log.addAppender(ajaxAppender);
      // log.info(formatListLog(interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "open", "page"));

      var num_videos = <?php echo $result1->num_rows; ?>;
      var labels_array = <?php echo json_encode($labels_array); ?>;
      var videos_array = <?php echo json_encode($videos_array); ?>;
      var tools_array = <?php echo json_encode($tools_array); ?>;
      var meta_array = <?php echo json_encode($meta_array); ?>;
      //var user_id = <?php echo $user_id; ?>;
      var num_labels = 0;
      var num_images = 0;
      var num_tools = 0;

      var filters = [];

      $('input[name=view-option]').first().trigger("click")
      
      // var input = $("<input>").attr("type", "hidden").attr("name", "part").val("<?php echo $_GET['part'];?>");
      // $('#myForm').append($(input));
      // var input = $("<input>").attr("type", "hidden").attr("name", "cond").val("<?php echo $_GET['cond'];?>");
      // $('#myForm').append($(input));
      // var input = $("<input>").attr("type", "hidden").attr("name", "step").val("<?php echo $_GET['step'];?>");
      // $('#myForm').append($(input));
      // var input = $("<input>").attr("type", "hidden").attr("name", "from").val("<?php echo $_SERVER['PHP_SELF'];?>");
      // $('#myForm').append($(input));
      // var input = $("<input>").attr("type", "hidden").attr("name", "tid").val(task_id);
      // $('#myForm').append($(input));
      // var input = $("<input>").attr("type", "hidden").attr("name", "iid").val(interface_id);
      // $('#myForm').append($(input));
      
      // Setting the page title
      if (task_id == 0){
        $("#title").html("Showing all videos");
      } else {        
        $("#title").html("<?php echo $task['name'];?>");
      }


      $('input[type=radio]').live('change', function() { 
        // log.info(formatListLog(interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "click", "views", ""));
        $(".all-view").toggle();
        $(".simple-view").toggle();
      });
      
      // Add video entries for each interface
      $.each(videos_array, function(index, value){
        var params = "?vid=" + this.id + "&iid=" + interface_id;


        if (interface_id == 1) {
          num_labels = num_labels + labels_array[index].length;
          var local_num_images = 0;
          var local_num_tools = 0;
          var before_img = "";
          var after_img = "";
          $.each(labels_array[index], function(i, v){
            if (v.type == "image"){
              local_num_images = local_num_images + 1;
              if (v.comment == "#initial")
                before_img = v.thumbnail;
              if (v.comment == "#final")
                after_img = v.thumbnail;
            }
          });
          local_num_tools = labels_array[index].length - local_num_images;
          num_images = num_images + local_num_images;
          num_tools = num_tools + local_num_tools;
          var html2 = "";
          if (is_admin)
            html2 = " | <a href='label.php" + params + "'>(Label)</a>";
            //html2 = "<p class='video-info-meta pull-right'><a href='label.php" + params + "'>(Label)</a></p>";

          $title = $("<h3/>").addClass("span12").append("<a href='tochi-watch.php" + params + "'>" + this.title + "</a>");
          $desc = $("<div/>").addClass("span12").append(meta_array[index].description);
          //if (is_admin)
          //  $detail = $("<div/>").addClass("span3").append("<p class='pull-right video-info-meta'>" + labels_array[index].length + " labels (" + local_num_images + "+" + local_num_tools + ")</p>" + html2);
          //else
          //  $detail = $("<div/>").addClass("span3").append("<p class='pull-right video-info-meta'>" + local_num_tools + " steps</p>" + html2);
          //$info = $("<div/>").addClass("video-info row").append($title).append($detail).append($desc);

          if (is_admin)
            $detail = $("<div/>").addClass("span12 video-meta").append("<p>" + labels_array[index].length + " labels (" + local_num_images + "+" + local_num_tools + ") | " + getTimeDisplay(this.duration) + " | by " + meta_array[index].uploader + " | " + formatDate(meta_array[index].upload_date) + html2 + "</p>");
          else
            $detail = $("<div/>").addClass("span12 video-meta").append("<p>" + local_num_tools + " steps | " + getTimeDisplay(this.duration) + " | by " + meta_array[index].uploader + " | " + formatDate(meta_array[index].upload_date) + html2 + "</p>");
          //$info = $("<div/>").addClass("video-info row span9").append($title).append($desc).append($detail);
          $info = $("<div/>").addClass("video-info row").append($title).append($desc).append($detail);

          var simpleHTML = "<div class='simple-view span12'><ul class='thumbnails'>" + 
            "<li class='span3'>" + 
            "<a href='" + before_img + "' class='thumbnail lightbox-simple'><img src='" + before_img + "'></a></li>" + 
            "<li class='span3'>" + 
            "<a href='" + after_img + "' class='thumbnail lightbox-simple'><img src='" + after_img + "'></a></li>" +            
            "</ul></div>";
          $view = $("<div/>").addClass("row view-change").append("<div class='all-view video-view span12'></div>").append(simpleHTML);
          $item = $("<div/>").addClass("video-item").append($info).append($view);
          $("<li/>").data("video_id", this.id).append($item).appendTo("#results");
          /* Generated <li> node:
            <li>
            <div class="video-item">
              <div class="video-info row">
                  <h3 class="span8"><a href='<?=$url;?>'><?=$video['title'];?></a></h3>
                  <span class="span4 pull-right"><?=$html2;?> <?=$video['duration'];?> / <?=$result3->num_rows;?> labels</span>
              </div>
              <div class="row">
                <div class="video-view span12"></div>
              </div>
            </div>
            </li>
          */
        } else if (interface_id == 2) {
          // console.log(meta_array[index]);
          var thumbnail = meta_array[index].thumbnail;

          $thumb = $("<div/>").addClass("span2").append("<a href='" + thumbnail + "'><img src='" + thumbnail + "'></a></div>");
          $title = $("<h3/>").append("<a href='tochi-watch.php" + params + "'>" + this.title + "</a>");
          $desc = $("<div/>").append(meta_array[index].description);
          $detail = $("<div/>").addClass("video-meta").append("<p>" + getTimeDisplay(this.duration) + " | by " + meta_array[index].uploader + " | " + formatDate(meta_array[index].upload_date) + "</p>");
          $info = $("<div/>").addClass("video-info-youtube span9").append($title).append($desc).append($detail);
          $item = $("<div/>").addClass("video-item row").append($thumb).append($info);
          $("<li/>").append($item).appendTo("#results");
          /* <li>
            <div class="video-item row">
              <div class="span2">
                <a href="http://i1.ytimg.com/vi/t8sTxPBtt8A/default.jpg"><img src="http://i1.ytimg.com/vi/t8sTxPBtt8A/default.jpg"></a>
              </div>
              <div class="video-info-youtube span9">
                <h3><a href="browse.phpundefined">Create Movement - Motion Blur Photoshop Tutorial</a></h3>
                <div>Image Link http://www.gamefront.com/files/20041298/skateboarder2.jpg Ignore Tags TeqTutorials,photoshop motion blur psd,after,affects,sony,vegas,pro,modern,w...</div>
                <div><p class="pull-right">2:47</p></div>
              </div>
            </div>
            </li>
          */   
        } else if (interface_id == 3) {
          $title = $("<h3/>").append("<a href='browse.php" + params + "'>" + this.title + "</a>");
          $desc = $("<div/>").append(meta_array[index].description);
          $info = $("<div/>").addClass("video-info-google span8").append($title).append($desc);
          $item = $("<div/>").addClass("video-item row").append($info);
          $("<li/>").append($item).appendTo("#results");    
   
        }
      });

      // Display statistics
      if (interface_id == 1){
        if (is_admin)
          $("#stats").html("Total: " + num_videos + " videos | " + num_labels + " labels (" + num_images + " images +" + num_tools + " steps)");
        else
          $("#stats").html("Total: " + num_videos + " videos | average " + Math.round(num_tools/num_videos) + " steps");
      } else if (interface_id == 2)
        $("#stats").html("Total: " + num_videos + " videos");
      else if (interface_id == 3)
        $("#stats").html("Total: " + num_videos + " results");      

/* From here, VT Browser ONLY */
      
      // Display top tools (most frequently used tool)
      $("#filters").append($("<ul/>"));
      $.each(tools_array, function(index, value){
        var bar_width = parseInt(this.freq) * 10;
        $("#filters ul").append("<li class='row'>" 
          + "<div class='progress span2' style='margin-top:5px; margin-bottom: 5px; height:12px;'><div class='bar' style='float: right; width:" + bar_width + "px;'></div></div>" 
          + "<span class='span4'><a href='#' class='tool-filter' data-tool-id='" + this.tool + "'>" 
          + formatTool(this.tool, "toolname-emphasize") + "</a> (" + this.freq + ")</span>" 
          + "</li>");
        
      });

      // Dynamically update the results based on the applied filters
      function update_list(filters){
        var hide_array = [];
        var num_labels = 0;
        // get video_id that must be hidden
        $.each(videos_array, function(index, video){
          var is_shown = true;
          if (filters.length == 0){
              num_labels = num_labels + labels_array[index].length;   
              return;  
          }
          $.each(filters, function(i,filter){
            var does_exist = false;
            //console.log("filter", filter)
            $.each(labels_array[index], function(ii, label){
              //console.log("label", label);
              if (label.type != "image" && label.tool == filter)
                does_exist = true;
            });
            if (!does_exist)
              is_shown = false;
          });
          /*
          $.each(labels_array[index], function(i, v){
            // skip image and only look at others
            // also, do not count duplicates
            // if any mismatch, this should be hidden (multiple filters are connected via AND not OR)
            if (!is_shown && this.type != "image" && filters.indexOf(this.tool) == -1) {
              is_shown = false;
            }
          });
          */
          if (!is_shown) {
            hide_array.push(video.id);
          } else {
            num_labels = num_labels + labels_array[index].length;
          }
        });
        //console.log("hide_array", hide_array);
        // for each li, hide the ones that are not in the list
        $("#results li").each(function(index, value){
          if (hide_array.indexOf($(this).data('video_id')) != -1)
            $(this).hide();
          else 
            $(this).show();
        });

        // update list alternating gradient, only on visible ones
        $(".list-striped > li").css("background-color", "#ffffff");
        $(".list-striped > li:visible").each(function (i){
          if (i%2 == 0)
            $(this).css("background-color", "#eeeeee");
        });

        if (interface_id == 1)
          $("#stats").html("Total: " + parseInt(videos_array.length - hide_array.length) + " videos and " + num_labels + " labels");
      }

      // when clicking on a tool, add a filter
      $(".tool-filter").click(function(event, ui){
        var tool = $(this).data('tool-id');
        filters.push(tool);
        $("#applied-filters-header").html("Showing all videos with:");
        $("#applied-filters").append("<div class='applied-filter' data-tool-id='" + tool+ "'>" + tool + "<i class='icon-remove'></i></div>");
        //console.log("add filter", tool);
        //console.log("filters", filters);
        // for each video div, hide the ones that do not have this tool in it
        update_list(filters);
        // log.info(formatListLog(interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "click", "filter", tool));
      });
      
      // when click on an already-added filter, remove a filter
      $("body").on("click", ".applied-filter", function(){
        var tool = $(this).data('tool-id');
        var index = filters.indexOf(tool);
        
        if (index != -1) 
          filters.splice(index, 1);
          
        if (filters.length == 0)
          $("#applied-filters-header").html("Showing all videos");
        $(this).toggle();
        // console.log("remove filter", tool);
        // console.log("filters", filters);
        // for each video div, hide the ones that do not have this tool in it
        update_list(filters);
        // log.info(formatListLog(interface_id, task_id, "<?php echo $_SESSION['username']; ?>", "click", "applied-filter", tool));
      });

      // for each result entry, display a slideshow summary
      $(".video-view").each(function(index){
        //console.log(videos_array[index].duration);
        $(this).jcview({
          labels: labels_array[index], 
          duration: videos_array[index].duration
        });
      });

      $("a.lightbox").lightBox();
      $("a.lightbox-simple").lightBox();

/*
      function mycarousel_initCallback(carousel) {
        $('.jcarousel-control a').click(function() {
          carousel.scroll($.jcarousel.intval($('.jcarousel-control a').index($(this))));
          return false;
        });
      }
*/
      $(".mycarousel").jcarousel({
        scroll: 2,
        visible: 7//,
        //initCallback: mycarousel_initCallback
      });

      var cond = "<?php echo $_GET["cond"]; ?>";
      if (cond == "A")
        $("#task-selector li").eq(5).addClass("active");
      else
        $("#task-selector li").eq(6).addClass("active");



    });
</script>
<script src="js/script.js"></script>

</body>
</html>
