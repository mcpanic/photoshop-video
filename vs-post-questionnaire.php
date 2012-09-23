<?php
  session_start();
  if(!isset($_SESSION["username"])){
    header("location:index.php");
  }

  include "conn.php";

  if (isset($_GET["tid"]))
    $task_id = $_GET["tid"];
  else
    $task_id = 0;

  if (isset($_GET["iid"]))
    $interface_id = $_GET["iid"];
  else
    $interface_id = 1;
  
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


  if ($interface_id == 1) {
    $interface = "Storyboard";
    $filename = "";
  } else if ($interface_id == 2) {
    $interface = "Fast Forward";
    $filename = "video/ff/" . $video_filename . ".flv";
  } else if ($interface_id == 3) {
    $interface = "Scene Clip";
    $filename = "video/sc/" . $video_filename . ".flv";
  }  
?>

<?php 
include('header.php');
?>
  <h3>Please answer the following questions about the <b><?php echo $interface; ?></b> video summarization.</h3>

    <?php 
      if ($interface_id == 1) {
    ?>
    <b>Storyboard</b> is a static summary of important moments in a tutorial. <br>
    Here is an example. You can scroll left and right to browse steps in a video tutorial. <br><br>
    <div class="span12 video-view" id="storyboard"></div>
    <!--<img src="img/storyboard.png" class="img-polaroid">-->
    <div class="row"></div>
    <?php 
      } else if ($interface_id == 2) {
    ?>
    <b>Fast Forward</b> is an abbreviated version of the actual clip, at around 10x-20x speed. <br>
    Play the video for an example.<br><br>
    <div id="mediaplayer" class="row">video loading...</div><br><br>

    <?php 
      } else if ($interface_id == 3) {
    ?>
    <b>Scene Clip</b> is compilation of short snippets of important moments in a tutorial. <br>
    Play the video for an example.<br><br>
    <div id="mediaplayer" class="row">video loading...</div><br><br>

    <?php 
      } 
    ?>

  <form id="myForm" action="vs-process.php" method="POST">
  <div id="likert" class="row"></div>

  <div class="row">
    <div class="span12">
      <p>Based on your experience, what are the strengths and weaknesses of this method?</p>
      <textarea name="comment" rows="5" cols="60" class="comments">
      </textarea> 
    </div>
  </div>

  <br>
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
<script type="text/javascript" src="js/libs/imagesloaded/jquery.imagesloaded.min.js"></script>
<script src="js/libs/jcview/jquery.vt.jcview.js"></script>
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

    var label_array = <?php echo json_encode($label_array); ?>;
    var video = <?php echo json_encode($video); ?>;
    var interface_id = <?php echo $interface_id; ?>;
    if (interface_id == 1){
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
    }

    var filename = "<?=$filename;?>";
    if (jwplayer("mediaplayer") != null){
      jwplayer("mediaplayer").setup({
        flashplayer: "js/libs/jwplayer/player.swf",
        width: "400",
        height: "250",
        controlbar: "bottom",        
        file: filename, //filename.substr(0, filename.lastIndexOf(".")) + ".flv",
        image: "<?=$json['thumbnail'];?>"
      });
    }

    $("#myForm").submit(function(){
      $(".alert").hide();
      var radio_groups = {}
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

    $("#likert").append(likert.getVSPostHTML(0));
    var cond = <?php echo $_GET["cond"]; ?>;
    if (cond > 0)
      $("#task-selector li").eq(cond).addClass("active");

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