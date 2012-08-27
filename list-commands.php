<?php
  session_start();
  if(!isset($_SESSION["username"])){
    header("location:index.php");
  }

//$term = $_GET["term"];
$scope = $_GET["scope"];
$json_file = "tools.json";
$headers_array = array("#", "value", "label", "kind", "desc", "order", "icon");
if (strcmp($scope, "menu") == 0) {
	$json_file = "menus.json";
	$headers_array = array("#", "value", "label", "kind", "desc", "level", "icon");
} else 
	$scope = "tool";

$items = json_decode(file_get_contents($json_file), true);
$results_array = array();

foreach ($items as $item) {			
	$results_array[] = $item;
}

//echo json_encode($results);


  include "conn.php";
  include "header.php";
?>

<h1><?php echo $scope; ?> list  <small>total: <?php echo count($results_array); ?></small></h1>
<table class="table table-striped table-hover">
	<thead><tr></tr></thead>
	<tbody></tbody>
</table>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>

<script src="js/libs/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="js/libs/jquery-ui-1.8.22.custom.min.js"></script> 
<script type="text/javascript" src="js/libs/jcarousel/jquery.jcarousel.min.js"></script>

<script src="js/libs/jcview/jquery.vt.jcview.js"></script>
<script src="js/libs/log4javascript.js"></script>

<script type="text/javascript">    

/*  MENU
	{
		"value": "photoshop-preferences-guides_grid_slices",
		"label": "Photoshop > Preferences > Guides, Grid, & Slices",
		"kind": "Photoshop",
		"desc": "",
		"level": "3",
		"icon": "white.png"
	}, 
	TOOL
	{
		"value": "single-column-marquee",
		"label": "Single Column Marquee",
		"kind": "Selection",
		"desc": "",
		"order": "2",
		"icon": "2_3.png"
	},	
*/
    $(document).ready(function() {
    	var results_array = <?php echo json_encode($results_array); ?>;
		var headers_array = <?php echo json_encode($headers_array); ?>;

		var html = "";
		$.each(headers_array, function(index, item) {
			html = html + "<th>" + item + "</th>";
		});
		$("table thead tr").append(html);
    	
    	$.each(results_array, function(index, item) {    		
    		$tr = $("<tr/>");
    		$tr.append("<td>" + parseInt(index+1) + "</td>")
    		//console.log(item);    		
    		$.each(item, function(i, v) {
    			//console.log(v);
    			$tr.append($("<td/>").html(v));
    		});
    		$("table tbody").append($tr);
    	});
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
