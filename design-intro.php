<?php
  session_start();
  if(!isset($_SESSION["username"])){
    header("location:index.php");
  }

  include "conn.php";
?>

<?php 
include('header.php');
?>
  
  <h3>Part 2. Design Task</h3>
  <p>
    In this part of the study, you will work on two design tasks in Photoshop.<br><br>

    Two video tutorial browsing interfaces will help you learn new skills in the tasks.<br>
    During the tasks, video tutorials are the only resource available for help: <b>Web search or help document is not allowed.</b> <br>
    Your goal is to complete your task in Photoshop and submit your work in <b>20 minutes</b>.<br><br>

    During your session, please think aloud. This will help us better understand your experience with the interfaces.<br>
  </p>

  <br>
  <p>If you are ready to proceed, please click NEXT.</p>

  <form id="myForm" action="vs-process.php" method="POST">
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
    var input = $("<input>").attr("type", "hidden").attr("name", "part").val("<?php echo $_GET['part'];?>");
    $('#myForm').append($(input));
    var input = $("<input>").attr("type", "hidden").attr("name", "cond").val("<?php echo $_GET['cond'];?>");
    $('#myForm').append($(input));
    var input = $("<input>").attr("type", "hidden").attr("name", "step").val("<?php echo $_GET['step'];?>");
    $('#myForm').append($(input));
    var input = $("<input>").attr("type", "hidden").attr("name", "from").val("<?php echo $_SERVER['PHP_SELF'];?>");
    $('#myForm').append($(input));


    if (jwplayer("mediaplayer1") != null){
      jwplayer("mediaplayer1").setup({
        flashplayer: "js/libs/jwplayer/player.swf",
        width: "400",
        height: "250",
        controlbar: "bottom",
        file: "video/<?=$video_filename;?>",
        image: "<?=$json['thumbnail'];?>"
      });
    }

    if (jwplayer("mediaplayer2") != null){
      jwplayer("mediaplayer2").setup({
        flashplayer: "js/libs/jwplayer/player.swf",
        width: "400",
        height: "250",
        controlbar: "bottom",
        file: "video/<?=$video_filename;?>",
        image: "<?=$json['thumbnail'];?>"
      });
    }

    $("#task-selector li").eq(4).addClass("active");
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