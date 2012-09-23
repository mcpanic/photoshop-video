<?php
  session_start();
  if(!isset($_SESSION["username"])){
    header("location:index.php");
  }

  if ($_GET["step"] == "watch1")
    $video_id = $_GET["vid1"];
  else if ($_GET["step"] == "watch2")
    $video_id = $_GET["vid2"];
  else if ($_GET["step"] == "watch3")
    $video_id = $_GET["vid3"];
  //$user_id = $_GET["uid"];

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
  //$result = $mysqli->query("SELECT * FROM labels WHERE video_id='$video_id' ORDER BY tm ASC");
  //$labels = array();
  //while ($row = $result->fetch_assoc()) {
  //  $labels[] = $row;
  //}
  $result->free();
  $mysqli->close();
?>

<?php 
include('header.php');
?>
  <h4>watching <?php echo substr($_GET["step"], 5, 1); ?> of 3</h4>
  <h3>Please watch the video. Once you're done, answer the following questions.</h3>
  <h3><a href='<?php echo $video['url']; ?>' target='_blank'><?php echo $video['url']; ?></a></h3>
  
  <div id="mediaplayer" class="row">loading video...</div>
  <br><br>

  <form id="myForm" action="vs-process.php" method="POST">
  <div id="likert" class="row"></div>

  <div class="row">
    <div class="offset1">
      <p><b>How was this video different from the expectation you had by just seeing its summary?</b></p>
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
    var input = $("<input>").attr("type", "hidden").attr("name", "vid1").val("<?php echo $_GET['vid1'];?>");
    $('#myForm').append($(input));
    var input = $("<input>").attr("type", "hidden").attr("name", "vid2").val("<?php echo $_GET['vid2'];?>");
    $('#myForm').append($(input));
    var input = $("<input>").attr("type", "hidden").attr("name", "vid3").val("<?php echo $_GET['vid3'];?>");
    $('#myForm').append($(input));            

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

    $("#likert").append(likert.getVSAfterVideoHTML(0));
    var cond = <?php echo $_GET["cond"]; ?>;
    if (cond > 0)
      $("#task-selector li").eq(cond).addClass("active");

  });

    if (jwplayer("mediaplayer") != null){
      jwplayer("mediaplayer").setup({
        flashplayer: "js/libs/jwplayer/player.swf",
        width: "900",
        height: "500",
        controlbar: "bottom",
        file: "video/<?=$video_filename;?>",
        image: "<?=$json['thumbnail'];?>",
        //autostart: true,
        plugins: {"timeslidertooltipplugin-3": {
          "preview":{
            'enabled':true,
            'path':'video/thumbs/',
            'prefix':"<?=pathinfo($video_filename, PATHINFO_FILENAME);?>",
            'frequency': 3
            }
        }}
      });
    }

</script>
  <!-- END OF THE PLAYER EMBEDDING -->

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