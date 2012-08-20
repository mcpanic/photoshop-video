<?php
  $video_id = $_GET["vid"];
  $user_id = $_GET["uid"];
  $tm = $_GET["tm"];

  $mysqli = new mysqli(localhost, "root", "root", "video_learning");
  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  }
  
  $result = $mysqli->query("SELECT * FROM videos WHERE id='$video_id'");
  if ($result->num_rows != 1)
    echo "query error";
  $row = $result->fetch_assoc();
  $video_filename = $row['filename'];

  $string = file_get_contents("video/$video_filename.info.json");
  $json = json_decode($string, true);

  $result = $mysqli->query("SELECT * FROM labels WHERE video_id='$video_id' AND user_id='$user_id' ORDER BY tm ASC");
  $labels = array();
  while ($row = $result->fetch_assoc()) {
    $labels[] = $row;
    /*
    $url = "browser.php?vid=" . $video_id . "&uid=" . $user_id . "&tm=" . $row['tm'];

    if (strcmp($row['comment'], "#initial") == 0)
      $tabs1_html .= "<div class='browse-initial'>Initial Image&nbsp;<a class='seek' id='time" . $row['tm']. "'href='#'>play</a><br><a href='". $row['thumbnail'] 
                  . "'><img src='" . $row['thumbnail'] . "?rand=" . rand() . "'></a></div>";

    if (strcmp($row['comment'], "#final") == 0)
      $tabs1_html .= "<div class='browse-final'>Final Image&nbsp;<a class='seek' id='time" . $row['tm']. "'href='#'>play</a><br><a href='". $row['thumbnail'] 
                  . "'><img src='" . $row['thumbnail'] . "?rand=" . rand() . "'></a></div>";

    if (strcmp($row['type'], "image") == 0) {
      $tabs2_html .= "<div class='browse-wip'>Time: " . $row['tm'] . "&nbsp;<a class='seek' id='time" . $row['tm']. "'href='#'>play</a><br><a href='". $row['thumbnail'] 
                  . "'><img src='" . $row['thumbnail'] . "?rand=" . rand() . "'></a></div>";
      $tabs3_html .= "<div class='browse-steps-image'>Time: " . $row['tm'] . "&nbsp;<a class='seek' id='time" . $row['tm']. "'href='#'>play</a><br><a href='". $row['thumbnail'] 
                  . "'><img src='" . $row['thumbnail'] . "?rand=" . rand() . "'></a></div>";
    } else {
      $tabs3_html .= "<div class='browse-steps-tool'>Time: " . $row['tm'] . "&nbsp;<a class='seek' id='time" . $row['tm']. "'href='#'>play</a><br><b>" 
      . $row['tool'] . "</b></div>";
    }
    */
  }
  $result->free();
  $mysqli->close();
?>

<?php 
include('header.php');
?>

  <h2><?=$json['title'];?></h2>
  <!--<div id="description"><?=$json['description'];?></div>-->

	<!-- START OF THE PLAYER EMBEDDING TO COPY-PASTE -->
	<div id="mediaplayer" class="row">JW Player goes here</div>

    <div class="row">
      <div class="span12">
        <div id="timeline">
          <div id="control-left">
            <a class="btn btn-small" href="#" id="play-button"><i class="icon-play"></i></a>
            <span id="elapsed" class="time-display">0:00</span>
          </div>
          <div id="control-center">
            <!--<div id="playhead"></div>-->
            <!--<div id="seekhead"></div>-->
            <div id="timeline-bottom"></div>
          </div>
          <div id="control-right">            
            <span id="duration" class="time-display">0:00</span>
          </div>
        </div>
      </div>
    </div>
<!--
	<div id="controller">
    <a href="javascript:jump(-10)">-10s</a>&nbsp;&nbsp;
    <a href="javascript:jump(-5)">-5s</a>&nbsp;&nbsp;
    <a href="javascript:jump(-1)">-1s</a>&nbsp;&nbsp;
    <a href="javascript:jump(1)">+1s</a>&nbsp;&nbsp;
    <a href="javascript:jump(5)">+5s</a>&nbsp;&nbsp;
    <a href="javascript:jump(10)">+10s</a>&nbsp;&nbsp;
  </div>
-->  
  
  <div id="tabs" class="row">
    <ul>
      <li><a href="#tabs-1">Before and After</a></li>
      <li><a href="#tabs-4">Slide Show</a></li>
      <li><a href="#tabs-2">Works in Progress</a></li>
      <li><a href="#tabs-3">Step by Step</a></li>
    </ul>
    <div id="tabs-1"></div>
    <div id="tabs-4"></div>
    <div id="tabs-2"></div>
    <div id="tabs-3"></div>

  </div>

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

    function jump(time) {
      var elapsed = jwplayer().getPosition();
      jwplayer().seek(elapsed+time);
    }
    
    $(document).ready(function() {
      $( "#tabs" ).tabs();
      $("#play-button").click(function(){
        if ($("#play-button i").hasClass("icon-play")){
          jwplayer().play();
        } else {
          jwplayer().pause();     
        }
      });
      $(".seek").click(function() {
        // get your id here
        var id = parseInt(this.id.substring(4));
        if (id < 5) id = 5; // seek does not accept negative integers
        jwplayer().seek(id - 5);
        return false;
      });

      $("body").on("mouseenter", ".popover", function(){
        var index = parseInt($(this).css("z-index"));
        $(this).css("z-index", index+1);
        $(this).find(".popover-inner").css("background-color", "#FF6600");
      });

      $("body").on("mouseleave", ".popover", function(){
        var index = parseInt($(this).css("z-index"));
        $(this).css("z-index", index-1);
        $(this).find(".popover-inner").css("background-color", "#000000");
      });

      $("#control-center").on("click", ".marker", function(){
        var id = parseInt(this.id.substring(6));
        if (id < 5) id = 5;
        jwplayer().seek(id - 5); 
        return false;       
      });


<?php 
  if (isset($tm)){
    echo "jwplayer().play(true);\n";
    echo "jwplayer().seek(" . $tm . ");";
  } 
?> 
    });


    function mycarousel_initCallback(carousel) {
      $('.jcarousel-control a').click(function() {
        carousel.scroll($.jcarousel.intval($('.jcarousel-control a').index($(this))));
        //console.log($('.jcarousel-control a').index($(this)))
        return false;
      });
    }

    var initialized = false;
    function init(duration, control_width) {
      $( "#timeline-bottom" ).slider({
        range: "min",
        min: 0,
        max: parseInt(duration),
        step: 0.1,
        animate: true,
        slide: function(event, ui){
          jwplayer().seek(ui.value);
        }
      });

      var labels = <?php echo json_encode($labels); ?>;
      var tabs1_html = tabs2_html = tabs3_html = "";
      var tabs4_html = "<ul id='mycarousel' class='jcarousel-skin-tango'>";
      var control_html = "<div class='jcarousel-control'>";
      
      // First pass to load all the images in place in a fake div
      /*$.each(labels, function(index, label){
        var id = "marker" + label.tm;
        var html = "<div style='display:none' id='popover-wrapper-'" + label.tm + "><a id='a" + id + "' class='popover-click-image' href='#'><img class='timeline-popover' src='" + label.thumbnail + "'></a></div>"
        $("body").append(html);
      });*/

      $.each(labels, function(index, label){
        // Adding Time Markers
        var id = "marker" + label.tm;
        var tm_formatted = getTimeDisplay(parseInt(label.tm));
        //var offset = parseInt(label.tm) * control_width / duration;
        var offset = parseInt(label.tm * 100) / duration;
        var html = "<span class='marker' id='" + id + "' style='left:" + offset + "%;'></span>";        
        $("#control-center").append(html);

        if (label.type == "image") {
          $("#" + id)
            .addClass("marker-image")
            .attr("title", tm_formatted + " " + label.comment)
            .attr("data-content", "<a id='a" + id + "' class='popover-click-image' href='#'><img class='timeline-popover' src='" + label.thumbnail + "'></a>")
            .popover({placement: 'bottom', trigger: 'manual'});         
        } else {
          $("#" + id)
            .addClass("marker-tool")
            .attr("title", tm_formatted)
            .attr("data-content", "<a id='a" + id + "' class='popover-click-tool' href='#'>" + label.tool + "</a>")
            .popover({placement: 'top', trigger: 'manual'});
        }
        $("#" + id).popover('show');

        // Adding Marker Sticks
        id = "marker-stick" + label.tm;
        var stick_class = "marker-stick-";
        if (label.type == "image") 
          stick_class = stick_class + "image";
        else
          stick_class = stick_class + "tool";
        
        html = "<span class='marker-stick " + stick_class + "' id='" + id + "' style='left:" + offset + "%;'></span>";
        $("#control-center").append(html);

        // Adding tabbed views
        var url = "browser.php?vid=" + <?=$video_id?> + "&uid=" + <?=$user_id?> + "&tm=" + label.tm;

        var thumb_html = "<a href='" + label.thumbnail + "'><img src='" + label.thumbnail + "?rand=" + Math.random() + "'></a>";
        var play_html = "<a class='seek btn' id='time" + label.tm + "'href='#'><i class='icon-play'></i></a>";

        if (label.comment == "#initial")
          tabs1_html = tabs1_html + "<div class='browse-initial'>Initial Image&nbsp;" + play_html + "<br>" + thumb_html + "</div>";

        if (label.comment == "#final")
          tabs1_html = tabs1_html + "<div class='browse-final'>Final Image&nbsp;" + play_html + "<br>" + thumb_html + "</div>";

        if (label.type == "image") {
          tabs2_html = tabs2_html + "<div class='browse-wip'>(" + tm_formatted + ") &nbsp;" + play_html + "<br>" + thumb_html + "</div>";
          tabs3_html = tabs3_html + "<div class='browse-steps-image'>(" + tm_formatted + ") &nbsp;" + play_html + "<br>" + thumb_html + "</div>";
          tabs4_html = tabs4_html + "<li>" + thumb_html + "</li>";
          control_html = control_html + "<a href='#' class='marker-image'>" + tm_formatted + "</a> ";
        } else {
          tabs3_html = tabs3_html + "<div class='browse-steps-tool'>(" + tm_formatted + ") &nbsp;" + play_html + "<br><b>" + label.tool + "</b></div>";
          tabs4_html = tabs4_html + "<li><b>" + label.tool + "</b><br>" + thumb_html + "</li>";
          control_html = control_html + "<a href='#' class='marker-tool'>" + tm_formatted + "</a> ";
        }
        
      });
      tabs4_html = control_html + "</div>" + tabs4_html + "</ul>";
      $("#tabs-1").html(tabs1_html);
      $("#tabs-2").html(tabs2_html);
      $("#tabs-3").html(tabs3_html);
      $("#tabs-4").html(tabs4_html);

      $(".popover-click-image, .popover-click-tool").click(function(){
        var id = parseInt(this.id.substring(7));
        if (id < 5) id = 5;
        jwplayer().seek(id - 5); 
        return false;       
      });

      $("#mycarousel").jcarousel({
        scroll: 1,
        visible: 3,
        initCallback: mycarousel_initCallback,
        itemFirstInCallback: function(carousel, obj, index, action){
          $('.jcarousel-item').eq(index).css("border", "5px solid #FF6600"); 
          $('.jcarousel-control a').eq(index).css("border", "5px solid #FF6600");      
        },
        itemFirstOutCallback: function(carousel, obj, index, action){
          $('.jcarousel-item').eq(index).css("border", "none"); 
          $('.jcarousel-control a').eq(index).css("border", "none");      
        }
      });

      // getting the correct position for each popover should be done after all images are loaded.
      // Otherwise prev.width() might return wrong values.
      // That's why this imagesLoaded plugin is necessary.      
      $(".timeline-popover").imagesLoaded(function( $images, $proper, $broken ){
        // TODO: error checking using $broken...
        console.log( $images.length + ' images total have been loaded in ' + this );
        console.log( $proper.length + ' properly loaded images' );
        console.log( $broken.length + ' broken images' );

/*
        var $container = this,
          x = 1;

        $images.each( function() {
          var $this = $(this).css({ left: x });
          x += $this.width() + 1;
        });

        $container.width(x);
*/

           $(".popover-click-image").each(function(index, item){
              var cur = $(this).parent().parent().parent().parent();
              var cur_stick = $(".marker-stick-image").eq(index);
              if (index == 0){
                cur.addClass("popover-image-top");
                cur_stick.addClass("marker-stick-image-top");
              } else {
                var prev_index = index - 1;
                var prev = $(".popover-click-image:eq(" + prev_index + ")").parent().parent().parent().parent();
                //console.log(index, prev.offset().left, prev.width(), cur.offset().left);
                

                //if (cur.offset().left - prev.offset().left >= prev.width()){
                console.log("image", prev.offset().left, prev.width(), cur.offset().left);
                if (cur.offset().left - prev.offset().left >= prev.width()){
                  cur.addClass("popover-image-top");
                  cur_stick.addClass("marker-stick-image-top");
                } else {
                  if (prev.hasClass("popover-image-top")){
                    cur.addClass("popover-image-bottom");
                    cur_stick.addClass("marker-stick-image-bottom");
                    //cur.css("margin-top", (5+prev.height()) + "px");
                  }
                  else {
                    cur.addClass("popover-image-top");
                    cur_stick.addClass("marker-stick-image-top");
                  }
                }
              }

              // Now offset management
              var offset = $(cur).children().eq(0).offset().left - cur_stick.offset().left;
              console.log("offset", offset);
              if (offset > 5 || offset < -5) {
                console.log("OFFSET BUG");
                $(cur).css("left", $(cur).offset().left - offset - 2.5);
              }
              
            });

            $(".popover-click-tool").each(function(index, item){
              var cur = $(this).parent().parent().parent().parent();
              var cur_stick = $(".marker-stick-tool").eq(index);
              if (index == 0){
                cur.addClass("popover-tool-bottom");
                cur_stick.addClass("marker-stick-tool-bottom");
                return;
              } else {
                var prev_index = index - 1;
                var prev = $(".popover-click-tool:eq(" + prev_index + ")").parent().parent().parent().parent();
                //console.log(index, prev.offset().left, cur.offset().left);
                
                // getting prev.width() is fine because all the content is text, so it's already loaded.
                //console.log("tool", prev.width());
                if (cur.offset().left - prev.offset().left >= prev.width()){
                  cur.addClass("popover-tool-bottom");
                  cur_stick.addClass("marker-stick-tool-bottom");
                } else {
                  if (prev.hasClass("popover-tool-bottom")) {
                    cur.addClass("popover-tool-top");
                    cur_stick.addClass("marker-stick-tool-top");
                  } else {
                    cur.addClass("popover-tool-bottom");
                    cur_stick.addClass("marker-stick-tool-bottom");
                  }
                }
              }
            });

      });

 

      //  $(".popover-click-image:odd").parent().parent().parent().parent().css("margin-top", "100px");
      //  $(".popover-click-tool:odd").parent().parent().parent().parent().css("margin-top", "-50px");



    }

    jwplayer("mediaplayer").setup({
      flashplayer: "js/libs/jwplayer/player.swf",
      width: "900",
      height: "500",
      controlbar: "bottom",
      file: "video/<?=$video_filename;?>",
      image: "<?=$json['thumbnail'];?>",
      autostart: true,
      events: {
        onTime: function(event){
          var control_width = 800;
          if (!initialized) {
            $("#duration").html(getTimeDisplay(parseInt(event.duration)));
            init(event.duration, control_width);
            initialized = true;
          }
          $("#elapsed").html(getTimeDisplay(parseInt(event.position)));
          /*$("#playhead").width(event.position * control_width / event.duration);*/
          //$("#timeline-bottom").val(parseInt(event.position));
          //$("#timeline-bottom").slider('refresh');
          $("#timeline-bottom").slider('value', event.position);
        },
        onPlay: function(event){
          $("#play-button i").removeClass("icon-play").addClass("icon-pause");
        },
        onPause: function(event){
          $("#play-button i").removeClass("icon-pause").addClass("icon-play");
        },
      }

    });
  </script>
  <!-- END OF THE PLAYER EMBEDDING -->

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
