<?php
  include "conn.php";
  include "study-conditions.php";

  $result = $mysqli->query("SELECT * FROM videos");

  $labels_array = array();
  $videos_array = array();
  
  while($video = $result->fetch_assoc()){
    //$video_filename = $video['filename'];
    //$string = file_get_contents("video/$video_filename.info.json");
    //$json = json_decode($string, true);

    $result3 = $mysqli->query("SELECT * FROM labels WHERE video_id = '" . $video['id'] . "'");    
    $label_array = array();
    while ($label = $result3->fetch_assoc()) {
      $label_array[] = $label;  
    }
    $labels_array[] = $label_array;
    $videos_array[] = $video;
  }

  $mysqli->close();

?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>Video Tutorial Browser</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <meta name="viewport" content="width=device-width">

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
  <link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.22.custom.css" type="text/css" />
  <link rel="stylesheet" href="js/libs/slickgrid/2.0.1/slick.grid.css" type="text/css" />

  <!-- Recline CSS components -->

  <link rel="stylesheet" href="css/recline/grid.css">
  <link rel="stylesheet" href="css/recline/slickgrid.css">
  <link rel="stylesheet" href="css/recline/graph.css">
  <link rel="stylesheet" href="css/recline/transform.css">
  <link rel="stylesheet" href="css/recline/map.css">
  <link rel="stylesheet" href="css/recline/multiview.css">
  <!-- /Recline CSS components -->

  <link rel="stylesheet" href="css/style.css">
  <style type="text/css">
.recline-slickgrid {
  height: 550px;
}

.info{
  font-weight: bold;
}
.comment{}
  </style>

  <script src="js/libs/modernizr-2.5.3-respond-1.1.0.min.js"></script>
</head>
<body>
<div class="container">

<!--<div id="mygrid" style="height: 700px"></div>-->

<div id="stats"><h3>Videos</h3></div>
<!--<div id="stats"><h3>Usage Stats</h3></div>
<div id="log"><h3>Usage Log</h3></div>
-->
</div><!--container-->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>
<script src="js/libs/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="js/libs/jquery-ui-1.8.22.custom.min.js"></script> 
<script type="text/javascript" src="js/libs/underscore-min.js"></script>
<script type="text/javascript" src="js/libs/backbone-min.js"></script>
<script type="text/javascript" src="js/libs/mustache/0.5.0-dev/mustache.js"></script>
<script type="text/javascript" src="js/libs/date.js"></script>
<script type="text/javascript" src="js/libs/slickgrid/2.0.1/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/libs/slickgrid/2.0.1/jquery.event.drag-2.0.min.js"></script>
<script type="text/javascript" src="js/libs/slickgrid/2.0.1/slick.grid.min.js"></script>
<script type="text/javascript" src="js/libs/recline.js"></script>
<script src="js/script.js"></script>

<script type="text/javascript">
function getIDisplay_vs(id){
  if (id == 1) return "SB";
  if (id == 2) return "FF";
  if (id == 3) return "SC";
}

function getIDisplay_ds(id){
  if (id == 1) return "JH";
  if (id == 2) return "YT";
}

function getTDisplay(id){
  if (id == 1) return "MBlur";
  if (id == 2) return "BGRem";
  if (id == 3) return "LomoE";
  if (id == 4) return "Retro";
  if (id == 5) return "Sktch";
}

// Detecting JSON parse errors
(function(){
    var parse = JSON.parse;
    JSON = {
        stringify: JSON.stringify,

        validate: function(str){        
            try{
                parse(str);
                return true;
            }catch(err){
                return err;
            }
        },
        parse: function(str){
            try{
                return parse(str);
            }catch(err){
                return undefined;
            }
        }
    }    
})();


  $(document).ready(function() {      
      var labels_array = <?php echo json_encode($labels_array); ?>;
      var videos_array = <?php echo json_encode($videos_array); ?>;
      //var tools_array = <?php echo json_encode($tools_array); ?>;
      //var meta_array = <?php echo json_encode($meta_array); ?>;
      //var user_id = <?php echo $user_id; ?>;
      var num_labels = 0;
      var num_images = 0;
      var num_tools = 0;

      $("#stats").append("<div>id tid dur num_l b a num_i num_t</div>");
      // Add video entries for each interface
      $.each(videos_array, function(index, value){
        var video = new Object;
        video = $.extend(video, value);
        video.num_labels = labels_array[index].length;
        num_labels = num_labels + labels_array[index].length;
        var local_num_images = 0;
        var local_num_tools = 0;
        $.each(labels_array[index], function(i, v){
          if (v.type == "image"){
            local_num_images = local_num_images + 1;
            if (v.comment == "#initial")
              video.before_img = v.tm;
            if (v.comment == "#final")
              video.after_img = v.tm;
          }
        });
        local_num_tools = video.num_labels - local_num_images;
        num_images = num_images + local_num_images;
        num_tools = num_tools + local_num_tools;
        video.num_images = local_num_images;
        video.num_tools = local_num_tools;

        video.before_at = video.before_img / video.duration; //(video.before_img / video.duration).toFixed(2);
        video.after_at = (video.duration - video.after_img) / video.duration; // (video.after_img / video.duration).toFixed(2);

        console.log(video);
        var html = "";
        $.each(video, function(i, v){
          if (i == "title" || i == "url" || i == "slug" || i == "added_at" || i == "filename")
            return;
          html = html + " " + v;
        });
        $("#stats").append("<div>" + html + "</div>")
      });
/*
    var user_stats = new Object;
    var formatted_logs = [];
    $.each(logs, function(index, log){
      //var tid = user_condition[value.username]["cond" + value.cond]["tid"];
      //var iid = user_condition[value.username]["cond" + value.cond]["iid"];
      var date = new Date(parseInt(log.time_logged));
      var t = Date.parse(date.customFormat( "#MM#/#DD#/#YYYY# #hh#:#mm#:#ss#" )); //date.getDate() + "/" + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
      var message = JSON.parse(log.message);
      if (message === undefined) return;
      if (message.uid[0] !== "P") return;
      if (parseInt(message.uid.substr(1,2)) > 12) return;
      if (duration_array[message.uid] !== undefined){
        if (date < duration_array[message.uid]["astart"] || date > duration_array[message.uid]["bend"])
          return;
        if (date > duration_array[message.uid]["aend"] && date < duration_array[message.uid]["bstart"])
          return;
      }
      if (user_stats[message.uid] === undefined)
        user_stats[message.uid] = new Array();        
      log.time = t;
      user_stats[message.uid].push(log); 

      message.action = message.action.replace(/\s+/g, '');
      message.target = message.target.replace(/\s+/g, '');
      if (typeof(message.obj) == "string")
        message.obj = message.obj.replace(/\s+/g, '');
      formatted_item = message;
      formatted_item.id = log.id;
      formatted_item.time_logged = t.toString('dd/HH:mm:ss');
      formatted_item.url = log.url;
      formatted_logs.push(formatted_item);   
      //console.log("FI", formatted_item);
      $("#log").append("<div>" + log.id + " " + message.uid + " " + getTDisplay(message.tid) + " " + getIDisplay_ds(message.iid) + " " + log.time.toString('dd HH:mm:ss') + " " + message.action + " " + message.target + " " + message.obj + " " + log.url + "</div>");      
      
    });
*/
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