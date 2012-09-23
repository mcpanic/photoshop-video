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
  <h3>Welcome to the Video Tutorials Study!</h3>
  <p>
    In this study, you will work with various interfaces designed to help you learn Photoshop skills using video tutorials. <br>
    This research project aims to enhance the searching, browsing, and watching experience of video tutorials. <br>
    The experiments are designed to evaluate the usability of the interfaces, not you or your Photoshop skills, so don't worry :) <br>
  </p>
  <p>
    The study is composed of two parts. <br>
    In the first part, you will use <b>three video summarization methods</b> designed to help you find relevant tutorials more efficiently. <br>
    In the second part, you will learn how to do <b>two design tasks in Photoshop</b> with video tutorials, using two browsing interfaces. <br>
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
    var input = $("<input>").attr("type", "hidden").attr("name", "part").val("0");
    $('#myForm').append($(input));
    var input = $("<input>").attr("type", "hidden").attr("name", "cond").val("");
    $('#myForm').append($(input));
    var input = $("<input>").attr("type", "hidden").attr("name", "step").val("");
    $('#myForm').append($(input));
    var input = $("<input>").attr("type", "hidden").attr("name", "from").val("<?php echo $_SERVER['PHP_SELF'];?>");
    $('#myForm').append($(input));
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