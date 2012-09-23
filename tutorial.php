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
  
  <h3>Practice</h3>
  <p>
    This example will help you become familiar with the interface for browsing video tutorials.<br><br>

    Let's imagine you have this photo of your friend smiling. <br>
    You like it, except for the teeth color. You want to whiten them.<br>
    Now let's find some video tutorials on teeth whitening in Photoshop.
  </p>

  <div class="row">
  <img class="span8 img-polaroid img-rounded intro-task-img" src="img/tasks/teeth-whitening.jpg"><br>
  </div>
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

      var cond = "<?php echo $_GET["cond"]; ?>";
      if (cond == "A")
        $("#task-selector li").eq(5).addClass("active");
      else
        $("#task-selector li").eq(6).addClass("active");  
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
