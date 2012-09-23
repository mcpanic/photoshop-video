<?php
  include "conn.php";
  include "study-conditions.php";
  
	$result = $mysqli->query("SELECT * FROM responses");
	
  $vs_ba_array = array();
  $vs_before_array = array();
  $vs_after_array = array();
  $vs_postq_array = array();
  while($responses = $result->fetch_assoc()){
    //echo $responses["session"];
    
    $cur_session = unserialize($responses["session"]);
    $cur_response = unserialize($responses["response"]);   

    if ($cur_session["username"][0] != "P")
      continue;

    if ($cur_response["part"] == 1){
      if ($cur_response["step"] == "list"){
        $vs_before_array[] = array_merge($cur_session, $cur_response);
        //echo print_r(array_merge($cur_response, $cur_session));
      } else if ($cur_response["step"] == "watch1" || $cur_response["step"] == "watch2" || $cur_response["step"] == "watch3") {
        $vs_after_array[] = array_merge($cur_session, $cur_response);                        
      } else if ($cur_response["step"] == "pq") {
        $vs_pq_array[]  = array_merge($cur_session, $cur_response);
      }
      //if ($cur_response["cond"])
    } else if ($cur_response["part"] == 2){

    }
  }

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
  </style>

  <script src="js/libs/modernizr-2.5.3-respond-1.1.0.min.js"></script>
</head>
<body>
<div class="container">

<div id="mygrid" style="height: 400px"></div>

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

jQuery(function($) {
    var vs_ba_array = <?php echo json_encode($vs_ba_array); ?>;
    var vs_before_array = <?php echo json_encode($vs_before_array); ?>;
    var vs_after_array = <?php echo json_encode($vs_after_array); ?>;
    var vs_pq_array = <?php echo json_encode($vs_pq_array); ?>;

  window.dataExplorer = null;
  window.explorerDiv = $('#mygrid');

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
      records: vs_before_array,
      // let's be really explicit about fields
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
  dataset.query({q: 'UK', size: 2}).done(function() {
  //createExplorer(dataset, state);
  });


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
    var vs_before_array = <?php echo json_encode($vs_before_array); ?>;
    var vs_after_array = <?php echo json_encode($vs_after_array); ?>;
    var vs_pq_array = <?php echo json_encode($vs_pq_array); ?>;

    $.each(vs_before_array, function(key, value){
      $.each(value, function(k, v){
        console.log(k, v);    
      });
      
    });
    
    var dataset = new recline.Model.Dataset({
      records: vs_before_array
    });

    var $el = $('#mygrid');
    var grid = new recline.View.SlickGrid({
      model: dataset,
      el: $el
    });
    grid.visible = true;
    grid.render();    
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