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
  <h3>Please answer the following questions about your experience in this design task.</h3>
  <form id="myForm" action="vs-process.php" method="POST">
  <h3>Overall experience</h3>
  <div id="likert1" class="row"></div>
  <h3>How confident are you...</h3>
  <div id="likert2" class="row"></div>
  <h3>Video Browsing Interface</h3>
  <div id="likert3" class="row"></div>
  <h4>Did the following features help you in completing the task?</h4>
  <p><b>Refer to the printed interface screenshot on your table to see what each feature is.<b></p>
  <div id="likert4" class="row"></div>

  <div class="row">
    <div class="span12">
      <p>Based on your experience, what are the strengths and weaknesses of this interface?</p>
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
    var iid = <?php echo $_GET["iid"];?>;

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

    $("#likert1").append(likert.getDSPostOverallHTML(0));
    $("#likert2").append(likert.getDSConfidenceHTML(1));
    $("#likert3").append(likert.getDSPostInterfaceHTML(2));
    if (iid == 1)
      $("#likert4").append(likert.getDSPostSpecificInterfaceHTML(3, false));
    else if (iid == 2)
      $("#likert4").append(likert.getDSPostSpecificInterfaceHTML(3, true));
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