<?php
  include "conn.php";
  include "study-conditions.php";

  $result = $mysqli->query("SELECT * FROM responses");

  $ds_time_array = array();
  $logs = array();
  
  while($responses = $result->fetch_assoc()){
    $cur_session = unserialize($responses["session"]);
    $cur_session["id"] = $responses["id"];
    $cur_session["added_at"] = $responses["added_at"];
    $cur_response = unserialize($responses["response"]);   

    if ($cur_session["username"][0] != "P")
      continue;

    if ($cur_response["part"] == 2){
      // for time measurement
      if ($cur_response["step"] == "intro-task" || $cur_response["step"] == "list" || $cur_response["step"] == "preq"){
        $ds_time_array[] = array_merge($cur_session, $cur_response); 
      } 
    }
  }

  $result = $mysqli->query("SELECT id, time_logged, url, message FROM logs");
  while($log = $result->fetch_assoc()){
    $logs[] = $log;
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

    if ($cur_session["username"][0] != "P")
      continue;

    if ($cur_response["part"] == 2){
      // for time measurement
      if ($cur_response["step"] == "intro-task" || $cur_response["step"] == "list" || $cur_response["step"] == "preq"){
        $ds_time_array[] = array_merge($cur_session, $cur_response); 
      } 
    }
  }

  $result = $mysqli->query("SELECT id, time_logged, url, message FROM logs");
  while($log = $result->fetch_assoc()){
    $logs[] = $log;
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

<div id="mygrid" style="height: 700px"></div>

<div id="ds_time"><h3>Time Log</h3></div>
<div id="stats"><h3>Usage Stats</h3></div>
<div id="log"><h3>Usage Log</h3></div>

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

// Convenient Date formatting
/*
token:     description:             example:
#YYYY#     4-digit year             1999
#YY#       2-digit year             99
#MMMM#     full month name          February
#MMM#      3-letter month name      Feb
#MM#       2-digit month number     02
#M#        month number             2
#DDDD#     full weekday name        Wednesday
#DDD#      3-letter weekday name    Wed
#DD#       2-digit day number       09
#D#        day number               9
#th#       day ordinal suffix       nd
#hhh#      military/24-based hour   17
#hh#       2-digit hour             05
#h#        hour                     5
#mm#       2-digit minute           07
#m#        minute                   7
#ss#       2-digit second           09
#s#        second                   9
#ampm#     "am" or "pm"             pm
#AMPM#     "AM" or "PM"             PM
*/
Date.prototype.customFormat = function(formatString){
    var YYYY,YY,MMMM,MMM,MM,M,DDDD,DDD,DD,D,hhh,hh,h,mm,m,ss,s,ampm,AMPM,dMod,th;
    var dateObject = this;
    YY = ((YYYY=dateObject.getFullYear())+"").slice(-2);
    MM = (M=dateObject.getMonth()+1)<10?('0'+M):M;
    MMM = (MMMM=["January","February","March","April","May","June","July","August","September","October","November","December"][M-1]).substring(0,3);
    DD = (D=dateObject.getDate())<10?('0'+D):D;
    DDD = (DDDD=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"][dateObject.getDay()]).substring(0,3);
    th=(D>=10&&D<=20)?'th':((dMod=D%10)==1)?'st':(dMod==2)?'nd':(dMod==3)?'rd':'th';
    formatString = formatString.replace("#YYYY#",YYYY).replace("#YY#",YY).replace("#MMMM#",MMMM).replace("#MMM#",MMM).replace("#MM#",MM).replace("#M#",M).replace("#DDDD#",DDDD).replace("#DDD#",DDD).replace("#DD#",DD).replace("#D#",D).replace("#th#",th);

    h=(hhh=dateObject.getHours());
    if (h==0) h=24;
    if (h>12) h-=12;
    hh = h<10?('0'+h):h;
    AMPM=(ampm=hhh<12?'am':'pm').toUpperCase();
    mm=(m=dateObject.getMinutes())<10?('0'+m):m;
    ss=(s=dateObject.getSeconds())<10?('0'+s):s;
    return formatString.replace("#hhh#",hhh).replace("#hh#",hh).replace("#h#",h).replace("#mm#",mm).replace("#m#",m).replace("#ss#",ss).replace("#s#",s).replace("#ampm#",ampm).replace("#AMPM#",AMPM);
}





// make Explorer creation / initialization in a function so we can call it
// again and again
var createExplorer = function(dataset, state) {
  // remove existing data explorer view
  var reload = false;
  if (window.dataExplorer) {
    window.dataExplorer.remove();
    reload = true;
  }
  window.dataExplorer = null;
  var $el = $('<div />');
  $el.appendTo(window.explorerDiv);

  var views = [
    {
      id: 'grid',
      label: 'Grid',
      view: new recline.View.SlickGrid({
        model: dataset
      }),
    },
    {
      id: 'graph',
      label: 'Graph',
      view: new recline.View.Graph({
        model: dataset
      }),
    },
/*    {
      id: 'map',
      label: 'Map',
      view: new recline.View.Map({
        model: dataset
      }),
    },
    {
      id: 'transform',
      label: 'Transform',
      view: new recline.View.Transform({
        model: dataset
      })
    }
*/
  ];

  window.dataExplorer = new recline.View.MultiView({
    model: dataset,
    el: $el,
    state: state,
    views: views
  });
}

  $(document).ready(function() {

  window.dataExplorer = null;
  window.explorerDiv = $('#mygrid');

    var logs = <?php echo json_encode($logs); ?>;
    var ds_time_array = <?php echo json_encode($ds_time_array); ?>;
    var user_condition = <?php echo json_encode($user_condition); ?>;

    var duration_array = new Object;
    $.each(ds_time_array, function(key, value){
      var tid = user_condition[value.username]["cond" + value.cond]["tid"];
      var iid = user_condition[value.username]["cond" + value.cond]["iid"];
      if (parseInt(value.username.substr(1,2)) > 12) return;
      if (duration_array[value.username] === undefined)
          duration_array[value.username] = new Object;        

      duration_array[value.username]["tid"] = tid;
      duration_array[value.username]["iid"] = iid;    
      if ((value.username == "P03" || value.username == "P05") && value.step == "intro-task" && value.cond == "1"){
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
      //console.log(value);
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

    //console.log(formatted_logs.length);
/*
    $.each(user_stats, function(index, value){
      $("#stats").append("<div>" + index + " " + value.length + "</div>");
    });
*/

    $.each(formatted_logs, function(index, value){
      console.log(value);
      $("#stats").append("<div>" + value.id + " " + value.time_logged + " " + value.uid + " " + value.tid + " " + value.iid + " " + value.action + " " + value.target + " " + value.obj + " " + value.url + "</div>");
    });

    // This is some fancy stuff to allow configuring the multiview from
    // parameters in the query string
    //
    // For more on state see the view documentation.
    var state = recline.View.parseQueryString(decodeURIComponent(window.location.search));
    if (state) {
      _.each(state, function(value, key) {
        try {
          value = JSON.parse(value);
        } catch(e) {}
        state[key] = value;
      });
    } else {
      state.url = 'demo';
    }
    var dataset = null;
    if (state.dataset || state.url) {
      dataset = recline.Model.Dataset.restore(state);
    } else {
      var dataset = new recline.Model.Dataset({
        records: formatted_logs       // let's be really explicit about fields
        // Plus take opportunity to set date to be a date field and set some labels
        /*fields: [
          {id: 'id'},
          {id: 'date', type: 'date'},
          {id: 'x'},
          {id: 'y'},
          {id: 'z'},
          {id: 'country', 'label': 'Country'},
          {id: 'title', 'label': 'Title'},
          {id: 'lat'},
          {id: 'lon'}
        ]*/
      });
    }
    //dataset.query({q: 'UK', size: 2}).done(function() {
    createExplorer(dataset, state);
    //});


/*
    var dataset = new recline.Model.Dataset({
      records: formatted_logs   
     }); 
    var $el = $('#mygrid');
    var grid = new recline.View.SlickGrid({
      model: dataset,
      el: $el
    });
    grid.visible = true;
    grid.render();      
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