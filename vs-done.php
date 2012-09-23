<?php
  session_start();
  if(!isset($_SESSION["username"])){
    header("location:index.php");
  }

  if (!isset($_GET["cond"]))
    $_GET["cond"] = 3;

  if ($_GET["cond"] == "1" || $_GET["cond"] == "2")
    $message = "<h3>" . $_GET["cond"] . " of 3 conditions complete.</h3><p><p>If you are ready to proceed, please click NEXT.</p>";
  else
    $message = "<h3>" . $_GET["cond"] . " of 3 conditions complete. This concludes study Part 1.</h3><h3>Please notify the researcher before continuing.</h3>";

  include "conn.php";
?>

<?php 
include('header.php');
?>
  <?php echo $message; ?>

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