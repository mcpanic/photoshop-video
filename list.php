<?php
  session_start();
  if(!isset($_SESSION["username"])){
    header("location:index.php");
  }

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
  else
    $result1 = $mysqli->query("SELECT * FROM videos WHERE task_id='$task_id'");
 
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

include('header.php');
?>

<?php if ($interface_id == 1) { ?>
  <div class="row">
    <h1 class="span12" id="title"></h1>
    <div class="span4">
      <div id="stats"></div>
      <div>
        <h4 id="applied-filters-header">Showing all videos</h4>
        <div id="applied-filters">
        </div>
      </div> 
    </div>
    <div id="filters" class="span8 filters"><h3>Top tools</h3></div>
  </div>

<?php } elseif ($interface_id == 2) { ?>

  <div class="row">
    <h1 class="span12" id="title"></h1>
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

<script src="js/libs/jcview/jquery.vt.jcview.js"></script>
<script src="js/libs/log4javascript.js"></script>

<script type="text/javascript">    
    $(document).ready(function() {
      // Nav bar activation
      var task_id = <?php echo $task_id; ?>;
      var interface_id = <?php echo $interface_id; ?>;
      var is_admin = <?php echo $_SESSION["is_admin"]; ?>;
      //console.log(is_admin, !is_admin, (is_admin == true), (is_admin == false));      
      var log = log4javascript.getDefaultLogger();
      var ajaxAppender = new log4javascript.AjaxAppender("ajax-add-log.php");
      log.addAppender(ajaxAppender);

      var num_videos = <?php echo $result1->num_rows; ?>;
      var labels_array = <?php echo json_encode($labels_array); ?>;
      var videos_array = <?php echo json_encode($videos_array); ?>;
      var tools_array = <?php echo json_encode($tools_array); ?>;
      var meta_array = <?php echo json_encode($meta_array); ?>;
      //var user_id = <?php echo $user_id; ?>;
      var num_labels = 0;
      var filters = [];

      // Setting the page title
      if (task_id > 0)
        $("#task-selector li").eq(task_id).addClass("active");
      if (task_id == 1){
        $("#title").html("Motion Blur");
      } else if (task_id == 2) {
        $("#title").html("Retro Effect");
      } else if (task_id == 3) {
        $("#title").html("TBD");
      } else {
        $("#title").html("Showing all videos");
      }

      // Add video entries for each interface
      $.each(videos_array, function(index, value){
        var params = "?vid=" + this.id + "&iid=" + interface_id;
        if (interface_id == 1) {
          num_labels = num_labels + labels_array[index].length;
          var html2 = "";
          if (is_admin)
            html2 = "<p class='video-info-meta pull-right'><a href='label.php" + params + "'>(Label)</a></p>";

          $title = $("<h3/>").addClass("span8").append("<a href='browse.php" + params + "'>" + this.title + "</a>");
          $desc = $("<div/>").addClass("span8").append(meta_array[index].description);
          $detail = $("<div/>").addClass("span3").append("<p class='pull-right video-info-meta'>" + getTimeDisplay(this.duration) + " | " + labels_array[index].length + " labels</p>" + html2);
          $info = $("<div/>").addClass("video-info row").append($title).append($detail).append($desc);
          $view = $("<div/>").addClass("row").append("<div class='video-view span12'></div>");
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
          console.log(meta_array[index]);
          var thumbnail = meta_array[index].thumbnail;

          $thumb = $("<div/>").addClass("span2").append("<a href='" + thumbnail + "'><img src='" + thumbnail + "'></a></div>");
          $title = $("<h3/>").append("<a href='browse.php" + params + "'>" + this.title + "</a>");
          $desc = $("<div/>").append(meta_array[index].description);
          $detail = $("<div/>").addClass("video-meta").append("<p>" + getTimeDisplay(this.duration) + " | by " + meta_array[index].uploader + " | " + meta_array[index].upload_date + "</p>");
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
      if (interface_id == 1)
        $("#stats").html("Total: " + num_videos + " videos and " + num_labels + " labels");
      else if (interface_id == 2)
        $("#stats").html("Total: " + num_videos + " videos");
      else if (interface_id == 3)
        $("#stats").html("Total: " + num_videos + " results");      

/* From here, VT Browser ONLY */
      
      // Display top tools (most frequently used tool)
      $("#filters").append($("<ul/>"));
      $.each(tools_array, function(index, value){
        var bar_width = parseInt(this.freq) * 10;
        $("#filters ul").append("<li><span class='span3'><a href='#' class='tool-filter' data-tool-id='" + this.tool + "'>" 
          + this.tool + "</a> (" + this.freq + ")</span>" 
          + "<div class='progress span2' style='margin-bottom:10px; height:10px;'><div class='bar' style='width:" + bar_width + "px;'></div></div></li>");
        
      });

      // Dynamically update the results based on the applied filters
      function update_list(filters){
        var hide_array = [];
        var num_labels = 0;
        // get video_id that must be hidden
        $.each(videos_array, function(index, video){
          var is_shown = false;
          if (filters.length == 0){
              num_labels = num_labels + labels_array[index].length;   
              return;  
          }
          $.each(labels_array[index], function(i, v){
            // skip image and only look at others
            // also, do not count duplicates
            if (!is_shown && this.type != "image" && filters.indexOf(this.tool) != -1) {
              num_labels = num_labels + labels_array[index].length;
              is_shown = true;
            }
          });
          if (filters.length > 0 && !is_shown)
            hide_array.push(video.id);
        });
        console.log("hide_array", hide_array);
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
        console.log("add filter", tool);
        console.log("filters", filters);
        // for each video div, hide the ones that do not have this tool in it
        update_list(filters);
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
        console.log("remove filter", tool);
        console.log("filters", filters);
        // for each video div, hide the ones that do not have this tool in it
        update_list(filters);
      });

      // for each result entry, display a slideshow summary
      $(".video-view").each(function(index){
        //console.log(videos_array[index].duration);
        $(this).jcview({
          labels: labels_array[index], 
          duration: videos_array[index].duration
        });
      });

/*
      function mycarousel_initCallback(carousel) {
        $('.jcarousel-control a').click(function() {
          carousel.scroll($.jcarousel.intval($('.jcarousel-control a').index($(this))));
          return false;
        });
      }
*/
      $(".mycarousel").jcarousel({
        scroll: 1,
        visible: 9//,
        //initCallback: mycarousel_initCallback
      });
    });
</script>
<script src="js/script.js"></script>
<!--
<script>
  var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
  g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
  s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
-->
</body>
</html>
