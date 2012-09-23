<?php
  include "conn.php";
  include "study-conditions.php";

	$result = $mysqli->query("SELECT * FROM responses");
	
  $temp_array = array();
  $vs_ba_array = array();
  $vs_before_array = array();
  $vs_after_array = array();
  $vs_postq_array = array();
  $vs_time_array = array();

  $ds_time_array = array();
  $ds_preq_array = array();
  $ds_pq_array = array();

  
  while($responses = $result->fetch_assoc()){
    $cur_session = unserialize($responses["session"]);
    $cur_session["id"] = $responses["id"];
    $cur_session["added_at"] = $responses["added_at"];
    $cur_response = unserialize($responses["response"]);   

    $temp_array[] = $cur_session["username"] . " " . $cur_response["step"];
    if ($cur_session["username"][0] != "P")
      continue;

    if ($cur_response["part"] == 1){
      if ($cur_response["step"] == "intro-t"){
        $vs_time_array[] = array_merge($cur_session, $cur_response);
      } else if ($cur_response["step"] == "list"){
        $vs_before_array[] = array_merge($cur_session, $cur_response);
        $vs_time_array[] = array_merge($cur_session, $cur_response);
      } else if ($cur_response["step"] == "watch1" || $cur_response["step"] == "watch2" || $cur_response["step"] == "watch3") {
        $vs_after_array[] = array_merge($cur_session, $cur_response);                        
      } else if ($cur_response["step"] == "pq") {
        $vs_pq_array[]  = array_merge($cur_session, $cur_response);
      }
    } else if ($cur_response["part"] == 2){
      // for time measurement
      if ($cur_response["step"] == "intro-task" || $cur_response["step"] == "list" || $cur_response["step"] == "preq"){
        $ds_time_array[] = array_merge($cur_session, $cur_response); 
      } 

      if ($cur_response["step"] == "preq"){
        $ds_preq_array[] = array_merge($cur_session, $cur_response); 
      } else if ($cur_response["step"] == "postq"){
        $ds_pq_array[] = array_merge($cur_session, $cur_response); 
      } 

    }
  }
  $mysqli->close();

  $mysqli = new mysqli("localhost", "root", "root", "video_learning_copy");
  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  }

  $result = $mysqli->query("SELECT * FROM responses");

  while($responses = $result->fetch_assoc()){
    $cur_session = unserialize($responses["session"]);
    $cur_session["id"] = $responses["id"];
    $cur_session["added_at"] = $responses["added_at"];
    $cur_response = unserialize($responses["response"]);   

    $temp_array[] = $cur_session["username"] . " " . $cur_response["step"];
    if ($cur_session["username"][0] != "P")
      continue;

    if ($cur_response["part"] == 1){
      if ($cur_response["step"] == "intro-t"){
        $vs_time_array[] = array_merge($cur_session, $cur_response);
      } else if ($cur_response["step"] == "list"){
        $vs_before_array[] = array_merge($cur_session, $cur_response);
        $vs_time_array[] = array_merge($cur_session, $cur_response);
      } else if ($cur_response["step"] == "watch1" || $cur_response["step"] == "watch2" || $cur_response["step"] == "watch3") {
        $vs_after_array[] = array_merge($cur_session, $cur_response);                        
      } else if ($cur_response["step"] == "pq") {
        $vs_pq_array[]  = array_merge($cur_session, $cur_response);
      }
    } else if ($cur_response["part"] == 2){
      // for time measurement
      if ($cur_response["step"] == "intro-task" || $cur_response["step"] == "list" || $cur_response["step"] == "preq"){
        $ds_time_array[] = array_merge($cur_session, $cur_response); 
      } 

      if ($cur_response["step"] == "preq"){
        $ds_preq_array[] = array_merge($cur_session, $cur_response); 
      } else if ($cur_response["step"] == "postq"){
        $ds_pq_array[] = array_merge($cur_session, $cur_response); 
      } 

    }
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

<div id="vs_time"><h3>VS Time</h3></div>
<div id="vs_diff"><h3>VS Diff</h3></div>
<div id="vs_before"><h3>VS Before</h3></div>
<div id="vs_after"><h3>VS After</h3></div>
<div id="vs_pq"><h3>VS PQ</h3></div>
<div id="vs_pq_comments"><h3>VS PQ Comments</h3></div>

<div id="ds_time"><h3>DS Time</h3></div>
<div id="ds_preq"><h3>DS PreQ</h3></div>
<div id="ds_pq"><h3>DS PQ</h3></div>
<div id="ds_pq_comments"><h3>DS PQ Comments</h3></div>
</div><!--container-->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>
<script src="js/libs/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="js/libs/jquery-ui-1.8.22.custom.min.js"></script> 
<script type="text/javascript" src="js/libs/underscore-min.js"></script>
<script type="text/javascript" src="js/libs/backbone-min.js"></script>
<script type="text/javascript" src="js/libs/mustache/0.5.0-dev/mustache.js"></script>

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

  $(document).ready(function() {
    var user_condition = <?php echo json_encode($user_condition); ?>;
    var vs_before_array = <?php echo json_encode($vs_before_array); ?>;
    var vs_after_array = <?php echo json_encode($vs_after_array); ?>;    
    var vs_pq_array = <?php echo json_encode($vs_pq_array); ?>;
    var vs_time_array = <?php echo json_encode($vs_time_array); ?>;
    var ds_time_array = <?php echo json_encode($ds_time_array); ?>;
    var ds_preq_array = <?php echo json_encode($ds_preq_array); ?>;
    var ds_pq_array = <?php echo json_encode($ds_pq_array); ?>;
    var temp_array = <?php echo json_encode($temp_array); ?>;
/*
    $.each(temp_array, function(key, value){
      console.log(value);
    });
*/
    $.each(vs_before_array, function(key, value){
      var tid = user_condition[value.username]["cond" + value.cond]["tid"];
      var iid = user_condition[value.username]["cond" + value.cond]["iid"];
      var answers = "";
      $.each(value, function(k, v){        
        if (k.substr(0, 5) == "entry")          
          answers = answers + v + "  "; 
      });
      $("#vs_before").append("<div>" + value.id + " " + value.username + " " + getTDisplay(tid) + " " + getIDisplay_vs(iid) + answers + "</div>");      
      
    });

    var vs_duration_array = new Object;
    $.each(vs_time_array, function(key, value){

      var tid = user_condition[value.username]["cond" + value.cond]["tid"];
      var iid = user_condition[value.username]["cond" + value.cond]["iid"];
      console.log("HI", value.username, tid, iid, value.cond, value.step);

      if (vs_duration_array[value.username] === undefined)
          vs_duration_array[value.username] = new Object; 
      if (vs_duration_array[value.username][value.cond] === undefined)
          vs_duration_array[value.username][value.cond] = new Object; 

      vs_duration_array[value.username][value.cond]["tid"] = tid;
      vs_duration_array[value.username][value.cond]["iid"] = iid;  

      if (value.step == "intro-t")
        vs_duration_array[value.username][value.cond]["start"] = new Date(value.added_at);
      else if (value.step == "list")
        vs_duration_array[value.username][value.cond]["end"] = new Date(value.added_at);

      //$("#vs_time").append("<div>" + value.id + " " + value.username + " " + getTDisplay(tid) + " " + getIDisplay_vs(iid) + answers + "</div>");      
    });

    $.each(vs_duration_array, function(key,value){
      $.each(value, function(k, v){
        var diff=new Date();
        //var i=0;
        //for (i=1; i<=3; i++){
          diff.setTime(v["end"] - v["start"]);
          $("#vs_time").append("<div>" + key + " " + k + " " + getTDisplay(v.tid) + " " + getIDisplay_vs(v.iid) + " " + parseInt(diff.getMinutes()*60 + diff.getSeconds()) + "</div>");
        //}
        console.log(key, value);
      });
    });


    $.each(vs_pq_array, function(key, value){

      var tid = user_condition[value.username]["cond" + value.cond]["tid"];
      var iid = user_condition[value.username]["cond" + value.cond]["iid"];
      var answers = "";
      $.each(value, function(k, v){        
        if (k.substr(0, 5) == "entry")          
          answers = answers + v + "  "; 
      });
      $("#vs_pq").append("<div>" + value.username + " " + getTDisplay(tid) + " " + getIDisplay_vs(iid) + " " + answers + "</div>");      
      //$("#vs_pq_comments").append("<div class='comment'><div class='info'>" + value.username + " " + getTDisplay(tid) + " " + getIDisplay_vs(iid) + "</div>" + value.comment + "</div>");      
      $("#vs_pq_comments").append("<div class='comment'><div class='info'>" + value.username + " " + getTDisplay(tid) + " " + getIDisplay_vs(iid) + "</div>" + value.comment + "</div>");      
    });
/*
a:44:{s:15:"entry_0_0_group";s:1:"2";s:15:"entry_0_1_group";s:1:"4";s:15:"entry_0_2_group";s:1:"6";s:15:"entry_1_0_group";s:1:"1";
s:15:"entry_1_1_group";s:1:"5";s:15:"entry_1_2_group";s:1:"5";s:15:"entry_2_0_group";s:1:"1";s:15:"entry_2_1_group";s:1:"7";
s:15:"entry_2_2_group";s:1:"7";s:15:"entry_3_0_group";s:1:"4";s:15:"entry_3_1_group";s:1:"7";s:15:"entry_3_2_group";s:1:"6";
s:15:"entry_4_0_group";s:1:"5";s:15:"entry_4_1_group";s:1:"4";s:15:"entry_4_2_group";s:1:"6";s:15:"entry_5_0_group";s:1:"6";
s:15:"entry_5_1_group";s:1:"7";s:15:"entry_5_2_group";s:1:"5";s:15:"entry_6_0_group";s:1:"1";s:15:"entry_6_1_group";s:1:"2";
s:15:"entry_6_2_group";s:1:"2";s:15:"entry_7_0_group";s:1:"6";s:15:"entry_7_1_group";s:1:"6";s:15:"entry_7_2_group";s:1:"6";
s:15:"entry_8_0_group";s:1:"4";s:15:"entry_8_1_group";s:1:"4";s:15:"entry_8_2_group";s:1:"2";s:15:"entry_9_0_group";s:1:"6";
s:15:"entry_9_1_group";s:1:"6";s:15:"entry_9_2_group";s:1:"4";
s:4:"part";s:1:"1";s:4:"cond";s:1:"1";s:4:"step";s:4:"list";
s:4:"from";s:20:"/labeler/vs-list.php";
s:4:"vid0";s:1:"1";s:4:"vid1";s:1:"2";s:4:"vid2";s:1:"3";s:4:"vid3";s:1:"4";s:4:"vid4";s:1:"5";s:4:"vid5";s:1:"6";s:4:"vid6";s:1:"7";s:4:"vid7";s:1:"8";s:4:"vid8";s:1:"9";s:4:"vid9";s:2:"10";}
*/
  // For answer difference detection
    $.each(vs_after_array, function(key, value){
      var tid = user_condition[value.username]["cond" + value.cond]["tid"];
      var iid = user_condition[value.username]["cond" + value.cond]["iid"];
      var before_answers = [];
      var after_answers = [];
      $.each(value, function(k, v){        
        if (k.substr(0, 5) == "entry")          
          after_answers.push(v); 
      });
      var vid = 0;
      if (value.step == "watch1")
        vid = value.vid1;
      else if (value.step == "watch2")
        vid = value.vid2;
      else if (value.step == "watch3")
        vid = value.vid3; 

      $.each(vs_before_array, function(k, v){
        if (value.username != v.username || value.cond != v.cond)
          return;
        var i;
        for(i=0;i<10;i++){
          if (v["vid"+i] == vid) {
            //console.log("HIT", vid);
            before_answers.push(v["entry_"+i+"_0_group"]);
            before_answers.push(v["entry_"+i+"_1_group"]);
            before_answers.push(v["entry_"+i+"_2_group"]);
            console.log(before_answers.toString(), after_answers.toString());
            $("#vs_diff").append("<div>" + value.username + " " + getTDisplay(tid) + " " + getIDisplay_vs(iid) + " " + value.cond + " " + before_answers[0] + " " + after_answers[0] + " " + before_answers[1] + " " + after_answers[1] + " " + before_answers[2] + " " + after_answers[2] + "</div>");
        }
        }
      });     
    });

    $.each(vs_after_array, function(key, value){
      var tid = user_condition[value.username]["cond" + value.cond]["tid"];
      var iid = user_condition[value.username]["cond" + value.cond]["iid"];
      var answers = "";
      $.each(value, function(k, v){        
        if (k.substr(0, 5) == "entry")          
          answers = answers + v + "  "; 
      });
      var vid = 0;
      if (value.step == "watch1")
        vid = value.vid1;
      else if (value.step == "watch2")
        vid = value.vid2;
      else if (value.step == "watch3")
        vid = value.vid3;

      $("#vs_after").append("<div>" + value.id + " " + value.username + " [" + vid + "] " + getTDisplay(tid) + " " + getIDisplay_vs(iid) + " " + answers + " " + value.comment + "</div>");
      
    });


    var duration_array = new Object;
    $.each(ds_time_array, function(key, value){
      var tid = user_condition[value.username]["cond" + value.cond]["tid"];
      var iid = user_condition[value.username]["cond" + value.cond]["iid"];
      
      if (duration_array[value.username] === undefined)
          duration_array[value.username] = new Object;        

      duration_array[value.username]["tid"] = tid;
      duration_array[value.username]["iid"] = iid;    
      if ((value.username == "P03" || value.username == "P05") && value.step == "intro-task" && value.cond == "A"){
        duration_array[value.username]["astart"] = new Date(value.added_at);
      }
      if (value.cond == "A" && value.step == "preq") {
        duration_array[value.username]["astart"] = new Date(value.added_at);
      } else if (value.cond == "A" && value.step == "list")
        duration_array[value.username]["aend"] = new Date(value.added_at);
      else if (value.cond == "B" && value.step == "intro-task")
        duration_array[value.username]["bstart"] = new Date(value.added_at);
      else if (value.cond == "B" && value.step == "list")
        duration_array[value.username]["bend"] = new Date(value.added_at);

      $("#ds_time").append("<div>" + value.id + " " + value.username + " " + value.cond + " " + getTDisplay(tid) + " " + getIDisplay_ds(iid) + " " + value.step + " " + value.added_at + "</div>"); 
      
    });
    

    $.each(duration_array, function(key,value){
      var diff=new Date();
      var length = 0;
      console.log(value);
      if (value.astart !== undefined){
        diff.setTime(value.aend - value.astart);
        //console.log(diff.getMinutes()*60 + diff.getSeconds());
        $("#ds_time").append("<div>" + key + " A " + " " + getTDisplay(value.tid) + " " + getIDisplay_ds(value.iid) + " " + parseInt(diff.getMinutes()*60 + diff.getSeconds()) + "</div>");
      }
      if (value.bstart !== undefined){
        diff.setTime(value.bend - value.bstart);
        //console.log(key, "B", diff.getHours(), diff.getMinutes(), diff.getSeconds());
        $("#ds_time").append("<div>" + key + " B " + " " + getTDisplay(value.tid) + " " + getIDisplay_ds(value.iid) + " " + parseInt(diff.getMinutes()*60 + diff.getSeconds()) + "</div>");
      //console.log(value.aend - value.astart);
      }
    });
    //console.log(duration_array);

    $.each(ds_preq_array, function(key, value){
      var tid = user_condition[value.username]["cond" + value.cond]["tid"];
      var iid = user_condition[value.username]["cond" + value.cond]["iid"];
      var answers = "";
      $.each(value, function(k, v){        
        if (k.substr(0, 5) == "entry")          
          answers = answers + v + "  "; 
      });

      $("#ds_preq").append("<div>" + value.id + " " + value.username + " " + answers + "</div>"); 
      
    });

    $.each(ds_pq_array, function(key, value){
      var tid = user_condition[value.username]["cond" + value.cond]["tid"];
      var iid = user_condition[value.username]["cond" + value.cond]["iid"];
      var answers = "";
      $.each(value, function(k, v){        
        if (k.substr(0, 5) == "entry")          
          answers = answers + v + "  "; 
      });
      $("#ds_pq").append("<div>" + value.id + " " + value.username + " " + value.cond + " " + getTDisplay(tid) + " " + getIDisplay_ds(iid) + " " +  answers + "</div>"); 
      $("#ds_pq_comments").append("<div class='comment'><div class='info'>" + value.username + " " + getTDisplay(tid) + " " + getIDisplay_ds(iid) + "</div>" + value.comment + "</div>");
      
    });        
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