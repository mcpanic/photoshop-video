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

	$result = $mysqli->query("SELECT * FROM tasks WHERE id='$task_id'");
	$task = $result->fetch_assoc();
  

?>

<?php 
include('header.php');
?>

  <h3>Good work!</h3>
  <p>
    Now that you are familiar with the interface, let's begin the task.
  </p>

  <h3>Task <?php echo ($task_id-3); ?>: <?php echo $task['name'];?></h3>
  <p>
    <?php echo $task['description'];?>
    <br><br>
    <img src="img/examples/<?php echo $task['ex_before_img'];?>" class="intro-img">
    <img src="img/examples/<?php echo $task['ex_after_img'];?>" class="intro-img">
  </p>
  <br><br>
  <h3>What to do</h3>
  <p>
  	Your task is to apply <?php echo $task['name'];?> to the image below. <br>
  	You can use the video tutorial browsing interface during your work. <br>
    You have <b>20 minutes</b> to complete the task.<br><br>
    
    Make sure your Photoshop is open with the right image.<br><br>
  	<img src="img/tasks/<?php echo $task['start_img'];?>" class="intro-task-img">
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