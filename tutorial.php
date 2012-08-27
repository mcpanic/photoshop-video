<?php

  session_start();
  if(!isset($_SESSION["username"])){
    header("location:index.php");
  }

  include('header.php');
?>

  <div class="row">
    <h2 class="span12">Learn from video tutorials</h2>
  </div>
  <div class="row">
    <h3 class="span12">1. Our Interface</h3>
    <img class="span8 img-polaroid img-rounded" src="img/interface1.png">
  </div>
  <div class="row">
    <h3 class="span12">2. Video Search Interface</h3>
    <img class="span8 img-polaroid img-rounded" src="img/interface2.png">
  </div>
  <div class="row">
    <h3 class="span12">3. Web Search Interface</h3>
    <img class="span8 img-polaroid img-rounded" src="img/interface3.png">
  </div>  
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
      //var task_id = <?php echo $task_id; ?>;
      //var is_admin = <?php echo isset($_SESSION["is_admin"]); ?>;
      $("#task-selector li").eq(0).addClass("active");
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
