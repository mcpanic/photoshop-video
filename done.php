<?php
  session_start();
  if(!isset($_SESSION["username"])){
    header("location:index.php");
  }

  if (!isset($_GET["cond"]))
    $_GET["cond"] = "B";

  if ($_GET["cond"] == "A")
    $message = "<h3>1 of 2 tasks complete.</h3><h3>Now you will do another design task with a different browsing interface.</h3>";
  else
    $message = "<h3>2 of 2 tasks complete. This concludes study Part 2.</h3><h3>Please notify the researcher before continuing.</h3>";

  include "conn.php";
?>

<?php 
include('header.php');
?>
  <?php echo $message; ?>

  <form id="myForm" action="vs-process.php" method="POST">

<?php if ($_GET["cond"] == "B") {   ?>
  <div class="row">
    <div class="offset1">
      <p>Please leave any comments about the study.</p>
      <textarea name="comment" rows="5" cols="60" class="comments">
      </textarea> 
    </div>
  </div>
<?php } ?>  

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