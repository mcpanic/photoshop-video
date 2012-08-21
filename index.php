<?php
  if (isset($_GET["uid"]))
    $user_id = $_GET["uid"];
  else
    $user_id = 1;

  $mysqli = new mysqli(localhost, "root", "root", "video_learning");
  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  }
  
  $result = $mysqli->query("SELECT * FROM videos");

?>

<?php 
include('header.php');
?>

	<h1>Learn from video tutorials</h1>

  <ol id="results">

<?php
  if ($result->num_rows == 1)
    echo "<p>no video available</p>";

  $labels = $mysqli->query("SELECT id FROM labels");  
  echo "<p>Total: $result->num_rows videos and $labels->num_rows labels</p>";
  while ($row = $result->fetch_assoc()) {
    $video_filename = $row['filename'];
    $duration = $row['duration'];
    $string = file_get_contents("video/$video_filename.info.json");
    $json = json_decode($string, true);

    $labels_in = $mysqli->query("SELECT * FROM labels WHERE video_id = '" . $row['id'] . "'");    
    $html = "";
    //$label_array = array();
    while ($label = $labels_in->fetch_assoc()) {
      //$label_array[] = $label;
      if ($label['comment'] == "#initial"){
        $html = $html . "<a class='initial' href='" . $label['thumbnail'] . "'><img src='" . $label['thumbnail'] . "?rand=" . Math.rand() . "'></a>";
      } else if ($label['comment'] == "#final"){
        $html = $html . "<a class='final' href='" . $label['thumbnail'] . "'><img src='" . $label['thumbnail'] . "?rand=" . rand() . "'></a>";
      }    
    }
    if ($html == "")
      $html = "<a class='initial span3' href='" . $json['thumbnail'] . "'><img src='" . $json['thumbnail'] . "?rand=" . Math.rand() . "'></a> <span>no labeled image detected.</span>";
    $html2 = "<a class='btn' href='labeler.php?vid=" . $row['id'] . "&uid=" . $user_id . "'>Label</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
          . "<a class='btn' href='browser.php?vid=" . $row['id'] . "&uid=" . $user_id . "'>Browse</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
          . $labels_in->num_rows . " labels";
?>
  <li class="row">
  <div id="tutorial" class="span12">
    <div id="thumbnail" class="span6">
      <?=$html?>
    </div>
    <div id="content" class="span5">
        <h3><?=$json['title'];?></h3>
        <p><?=$html2;?></p><p><?=$duration;?></p>
	  </div>
  </div>
  </li>


<?php

  } 
?>
</ol>

</div><!--container-->


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>

<script src="js/libs/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript">    
    $(document).ready(function() {
      $("#results .row #tutorial:odd").css("background", "#dddddd");
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
