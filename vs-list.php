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

include('header.php');
?>

  <div class="row">
    <h1 class="span12" id="title"></h1>
    <div id="stats" class="span4"></div>
  </div>

  <form id="myForm" action="vs-process.php" method="POST">

<ol id="results" class="list-striped list-hover"></ol>


    <div class="alert alert-block alert-error fade in">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <h4 class="alert-heading">Please answer all the questions.</h4>
    </div>
  <br>
  <button id="nextButton" class="btn btn-large btn-primary" type="submit">
    NEXT
  </button>
  </form>


</div><!--container-->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>

<script src="js/libs/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="js/libs/jquery-ui-1.8.22.custom.min.js"></script> 
<script type="text/javascript" src="js/libs/jwplayer/jwplayer.js"></script>
<script type="text/javascript" src="js/libs/jcarousel/jquery.jcarousel.min.js"></script>

<script src="js/libs/jcview/jquery.vt.jcview.js"></script>
<script src="js/libs/log4javascript.js"></script>
<script type="text/javascript" src="js/libs/lightbox/jquery.lightbox-0.5.js"></script>

<script src="js/script.js"></script>

<script type="text/javascript">    

  $(document).ready(function() {
    $(".alert").hide();

    var input = $("<input>").attr("type", "hidden").attr("name", "part").val("<?php echo $_GET['part'];?>");
    $('#myForm').append($(input));
    var input = $("<input>").attr("type", "hidden").attr("name", "cond").val("<?php echo $_GET['cond'];?>");
    $('#myForm').append($(input));
    var input = $("<input>").attr("type", "hidden").attr("name", "step").val("<?php echo $_GET['step'];?>");
    $('#myForm').append($(input));
    var input = $("<input>").attr("type", "hidden").attr("name", "from").val("<?php echo $_SERVER['PHP_SELF'];?>");
    $('#myForm').append($(input));

    $("#myForm").submit(function(){
      $(".alert").hide();
      var radio_groups = {};
      $(":radio").each(function(){
          radio_groups[this.name] = true;
      });
      for(group in radio_groups){
        if_checked = !!$(":radio[name='"+group+"']:checked").length;
        if (!if_checked) {
          $(".alert").show();
          //console.log(group+(if_checked?' has checked radios':' does not have checked radios'));
          return false;
        }
      }
    });


/* Code for injecting random values to all radio buttons*/
/*
var radio_groups = {}
$(":radio").each(function(){
    radio_groups[this.name] = true;
});
for(group in radio_groups){
  var val = Math.floor(Math.random() * 7);
  $(":radio[name='"+group+"']:nth("+val+")").attr("checked", true);
};
*/

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
      var num_labels = 0;
      var filters = [];

      // Setting the page title
      var cond = <?php echo $_GET["cond"]; ?>;
      if (cond > 0)
        $("#task-selector li").eq(cond).addClass("active");

      if (task_id == 0){
        $("#title").html("Showing all videos");
      } else {        
        $("#title").html("<?php echo $task['name'];?>");
      }

      // Add video entries for each interface
      $.each(videos_array, function(index, value){
        var input = $("<input>").attr("type", "hidden").attr("name", "vid" + index).val(this.id);
        $('#myForm').append($(input));

        var params = "?vid=" + this.id + "&iid=" + interface_id;
        if (interface_id == 1) {
          num_labels = num_labels + labels_array[index].length;
          var html2 = "";
          if (is_admin)
            html2 = "<p class='video-info-meta pull-right'><a href='label.php" + params + "'>(Label)</a></p>";

          //$title = $("<h3/>").addClass("span8").append("<a href='browse.php" + params + "'>" + this.title + "</a>");
          //$title = $("<h3/>").addClass("span8").append(this.title);
          //$desc = $("<div/>").addClass("span8").append(meta_array[index].description);
          //$detail = $("<div/>").addClass("span3").append("<p class='pull-right video-info-meta'>" + getTimeDisplay(this.duration) + " | " + labels_array[index].length + " labels</p>" + html2);
          //$info = $("<div/>").addClass("video-info row").append($title).append($detail).append($desc);
          $info = $("<div/>").addClass("video-info row");//.append($title);
          $view = $("<div/>").addClass("row").append("<div class='video-view span12'></div>");
          $likert = $("<div/>").addClass("row").append("<div class='video-likert span12'>" + likert.getVSVideoHTML(index) + "</div>");
          $item = $("<div/>").addClass("video-item").append($info).append($view).append($likert);
          $("<li/>").data("video_id", this.id).append($item).appendTo("#results");
        } else if (interface_id == 2) {

          num_labels = num_labels + labels_array[index].length;
          var html2 = "";
          if (is_admin)
            html2 = "<p class='video-info-meta pull-right'><a href='label.php" + params + "'>(Label)</a></p>";

          //$title = $("<h3/>").addClass("span8").append("<a href='browse.php" + params + "'>" + this.title + "</a>");
          //$title = $("<h3/>").addClass("span8").append(this.title);
          //$desc = $("<div/>").addClass("span8").append(meta_array[index].description);
          //$detail = $("<div/>").addClass("span3").append("<p class='pull-right video-info-meta'>" + getTimeDisplay(this.duration) + " | " + labels_array[index].length + " labels</p>" + html2);
          //$info = $("<div/>").addClass("video-info row").append($title).append($detail).append($desc);
          $info = $("<div/>").addClass("video-info row");//.append($title);
          $view = $("<div/>").addClass("row").append("<div class='video-view span12'><div id='mediaplayer" + index + "'></div></div>");
          $likert = $("<div/>").addClass("row").append("<div class='video-likert span12'>" + likert.getVSVideoHTML(index) + "</div>");
          $item = $("<div/>").addClass("video-item").append($info).append($view).append($likert);
          $("<li/>").data("video_id", this.id).append($item).appendTo("#results");

        } else if (interface_id == 3) {
          num_labels = num_labels + labels_array[index].length;
          var html2 = "";
          if (is_admin)
            html2 = "<p class='video-info-meta pull-right'><a href='label.php" + params + "'>(Label)</a></p>";

          //$title = $("<h3/>").addClass("span8").append("<a href='browse.php" + params + "'>" + this.title + "</a>");
          //$title = $("<h3/>").addClass("span8").append(this.title);
          //$desc = $("<div/>").addClass("span8").append(meta_array[index].description);
          //$detail = $("<div/>").addClass("span3").append("<p class='pull-right video-info-meta'>" + getTimeDisplay(this.duration) + " | " + labels_array[index].length + " labels</p>" + html2);
          //$info = $("<div/>").addClass("video-info row").append($title).append($detail).append($desc);
          $info = $("<div/>").addClass("video-info row");//.append($title);
          $view = $("<div/>").addClass("row").append("<div class='video-view span12'><div id='mediaplayer" + index + "'></div></div>");
          $likert = $("<div/>").addClass("row").append("<div class='video-likert span12'>" + likert.getVSVideoHTML(index) + "</div>");
          $item = $("<div/>").addClass("video-item").append($info).append($view).append($likert);
          $("<li/>").data("video_id", this.id).append($item).appendTo("#results");
   
        }
      });

      // Display statistics
      $("#stats").html("Total: " + num_videos + " videos");

/* From here, VT Browser ONLY */
      
      // for each result entry, display a slideshow summary
      $(".video-view").each(function(index){
        //console.log(videos_array[index].duration);
        if (interface_id == 1){
          $(this).jcview({
            labels: labels_array[index], 
            duration: videos_array[index].duration
          });
        } else if (interface_id == 2){
          var filename = "video/ff/" + videos_array[index].filename + ".flv";
          if (jwplayer("mediaplayer" + index) != null){
            jwplayer("mediaplayer" + index).setup({
              flashplayer: "js/libs/jwplayer/player.swf",
              width: "400",
              height: "250",
              controlbar: "bottom",
              file: filename,
              image: meta_array[index].thumbnail
            });
          }
        } else if (interface_id == 3){
          var filename = "video/sc/" + videos_array[index].filename + ".flv";
          if (jwplayer("mediaplayer" + index) != null){
            jwplayer("mediaplayer" + index).setup({
              flashplayer: "js/libs/jwplayer/player.swf",
              width: "400",
              height: "250",
              controlbar: "bottom",
              file: filename,
              image: meta_array[index].thumbnail
            });
          }
        }
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
        scroll: 2,
        visible: 11//,
        //initCallback: mycarousel_initCallback
      });


      // lightbox init
      $("a.lightbox").lightBox();
  });
</script>

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
