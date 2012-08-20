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
?>

<?php 
include('header.php');
?>


	<h1><a href="index.php">Label a Photoshop video tutorial</a></h1>

  <h2><?=$json['title'];?></h2>
  <!--<div id="description"><?=$json['description'];?></div>-->

	<!-- START OF THE PLAYER EMBEDDING TO COPY-PASTE -->
	<div id="mediaplayer">JW Player goes here</div>


	<div id="mainContentInner">
	<div id="controller">
    <a href="javascript:jump(-10)">-10s</a>&nbsp;&nbsp;
    <a href="javascript:jump(-5)">-5s</a>&nbsp;&nbsp;
    <a href="javascript:jump(-1)">-1s</a>&nbsp;&nbsp;
    <a href="javascript:jump(1)">+1s</a>&nbsp;&nbsp;
    <a href="javascript:jump(5)">+5s</a>&nbsp;&nbsp;
    <a href="javascript:jump(10)">+10s</a>&nbsp;&nbsp;
  </div>
  <!--Elapsed time: <span id="elapsedText">0</span>-->
  <button type="button" name="capture" class="btn btn-primary" id="capture-button">CAPTURE</button>
  <div id="capture-form">
    <form id="form1" name="form1" action="add-label.php">
      <ul id="capture-list">
      <li class="capture-header">
        <span class="capture-image">Image</span>
        <span class="capture-time">Time</span>
        <span class="capture-tool">What's going on?<br>
          <label for="select-image"><input type="radio" name="selection" id="select-image" value="Image" />Image</label>	
          <label for="select-tool"><input type="radio" name="selection" id="select-tool" value="Tool" />Tool</label>
          <label for="select-menu"><input type="radio" name="selection" id="select-menu" value="Menu" />Menu</label>
          <label for="select-other"><input type="radio" name="selection" id="select-other" value="Other" />Other</label>
<!--          <a href="#" id="select-tool">Tool</a>&nbsp;or&nbsp;
          <a href="#" id="select-menu">Menu</a></span>-->
        </span>
        <span class="capture-comment">Parameters / Comment</span>
      </li>
      <li class="capture-item">
        <span class="capture-image"><a id="jcrop-link" href="#"><img src="img/icons/white.png"/></a></span>
        <span class="capture-time"><input type="text" id="tm" name="tm" style="width:50px"></span>
        <span class="capture-tool">
          <img id="tool-icon" src="img/icons/white.png"/>
          <input type="text" id="tool" name="tool" style="width:200px">
        </span>
        <span class="capture-comment"><input type="text" id="comment" name="comment" style="width:200px"></span>
        <span class="capture-option">
          <input type="hidden" id="tool-id"/>
          <input type="hidden" id="type"/>
          <input type="submit" class="btn btn-success" value="Save">
        </span>
      </li>
      </ul>
    </form>
  </div>
  <div id="labels">
    <ul id="label-list">

      <li class="label-header">
        <span class="list-image">Image</span>
        <span class="list-time">Time</span>
        <span class="list-tool">What's going on?</span>
        <span class="list-comment">Parameters / Comment</span>
        <span class="list-option">Options</span>
      </li>

<?php
  $result = $mysqli->query("SELECT * FROM labels WHERE video_id='$video_id' AND user_id='$user_id' ORDER BY tm ASC");
 
  while ($row = $result->fetch_assoc()) {
    $url = "labeler.php?vid=" . $video_id . "&uid=" . $user_id . "&tm=" . $row['tm'];
    echo "<li class='label-item'><span class='list-image'><a href='". $row['thumbnail'] 
      . "'><img src='" . $row['thumbnail'] . "?rand=" . rand() . "'></a>"
      . "</span><span class='list-time'>" . $row['tm']
      . "</span><span class='list-tool'><b>" . $row['type'] . "</b><br>" . $row['tool']
      . "</span><span class='list-comment'>" . $row['comment']
      . "</span><span class='list-option'><a href='javascript:jwplayer().seek(\"" . $row['tm'] . "\");'>open</a>&nbsp;&nbsp;"
      . "<a href='#'>remove</a>"
      . "</span></li>";
  }
  $result->free();
  $mysqli->close();
?>
    </ul>
  </div>

</div><!--container-->


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>
<script src="js/libs/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="js/libs/jquery-ui-1.8.22.custom.min.js"></script> 
<script type="text/javascript" src="js/libs/jcrop/jquery.Jcrop.min.js"></script> 
<script type="text/javascript" src="js/libs/jwplayer/jwplayer.js"></script>
<script src="js/script.js"></script>

  <script type="text/javascript">

  var imageLoadTimer;
/*
    function urlExists(url) {
      var http = new XMLHttpRequest();
      http.open('HEAD', url, false);
      http.send();
        console.log(http.status);
      return http.status!=404;
    }

    function checkUrl(url, id) {
      if (urlExists(url)) {
        clearTimeout(imageLoadTimer);
        console.log("file created");
        $("#" + id + " .list-image img").src = url; 
      } else
        console.log("still waiting");
    }
 */
    function jump(time) {
      var elapsed = jwplayer().getPosition();
      jwplayer().seek(elapsed+time);
    }


/*
    function setText(id, messageText) {
      document.getElementById(id).innerHTML = messageText;
    }
 */

/*
//bind the select event to mousedown
$("#tool").autocomplete('widget').bind('mousedown.choose_option',function() {
   //immediately closes autocomplete when option is selected
   $("#tool").autocomplete('close');
 
   //perform desired action
   alert(search_option.id);
});
 */    
    
    $(document).ready(function() {

      $("#jcrop-link").click(function(event){
        event.preventDefault();
        //console.log($(this).find("img").height());
        var width = "720px"; // 180 -> 720
        //var height = $(this).find("img").height() * 4 + "px"; // 180 -> 720 is 4 times
        var jcrop_api;
        var $dialog = $("<div><div class='jc-dialog'><img width='" + width //+ "' height='" + height 
          + "' src='" + $(this).find("img").attr("src") + "?rand=" + Math.random() + "' />"   
          + "</div></div>");
        $dialog.find('img').Jcrop({},function(){
          jcrop_api = this;
          console.log(jcrop_api.getBounds());
          $dialog.dialog({
            modal: true,
            title: "Select a region of interest",
            close: function(){ $dialog.remove(); },
            position: ["left", "top"],
            width: "auto",//jcrop_api.getBounds()[0]+34,
            height: "auto",
            buttons: {"Crop": function(){
        var select_region = jcrop_api.tellSelect();
        //console.log(select_region);
        //console.log($("#jcrop-link img").attr("src"));
        if (select_region.x == 0 || select_region.y == 0){
          alert("no region selected.");
          return false;
        }
        $.ajax({
              url: "jcrop.php",        
              type: "POST",
              data: {
                x: select_region.x,
                y: select_region.y,
                w: select_region.w,
                h: select_region.h,
                filepath: $("#jcrop-link img").attr("src")              
              }}).done(function(data){
                $("#jcrop-link img").attr("src", data + "?rand=" + Math.random());                              

              }).fail(function(){
                $("#jcrop-link").html("capture failed."); 

              })
            ;
                $(this).dialog("close");
            }},
            resizable: true
          });
        });
        $dialog.append('<p>Select a region you want to keep.</p>');
        return false;
      });

      $("#select-image").attr("checked", "true");
      $("#tool").attr("disabled", "disabled");
      $("#type").val("image");

      $("#select-image").click(function(event){
        $("#tool").val("");
        $("#tool").attr("disabled", "disabled");
        $( "#tool-icon" ).attr( "src", "img/icons/white.png" );
        $("#type").val("image");
      });
      $("#select-tool, #select-menu, #select-other").click(function(event){
        $("#tool").val("");
        $("#tool").removeAttr("disabled");
        $( "#tool-icon" ).attr( "src", "img/icons/white.png" );
      });

    $( "#tool" ).autocomplete({
      minLength: 2,
      source: function (request, response) {
        $.getJSON("autocomplete.php", {
          term: request.term,
          scope: "all"
        }, response);
      },
      focus: function( event, ui ) {
        $( "#tool" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        console.log(ui.item);
        $( "#tool" ).val( ui.item.label );
        $( "#tool-id" ).val( ui.item.value );
        $( "#tool-icon" ).attr( "src", "img/icons/" + ui.item.icon );
        return false;
      }
    })    
    .data( "autocomplete" )._renderItem = function( ul, item ) {
      return $( "<li></li>" )
        .data( "item.autocomplete", item )
            .append( "<a><div class='autocomplete-item'><img src='img/icons/" + item.icon + "'>" + item.label + "</div></a>" )
        .appendTo( ul );
    };
<?php 
  if (isset($tm)){
    echo "jwplayer().play(true);\n";
    echo "jwplayer().seek(" . $tm . ");";
  } 
?> 

      $("#select-tool").click(function(event) {
        $("#type").val("tool");
        $("#tool").autocomplete({
          disabled: false,
      source: function (request, response) {
        $.getJSON("autocomplete.php", {
          term: request.term,
          scope: "tool"
        }, response);
      },
        })
    // .data( "autocomplete" )._renderItem = function( ul, item ) {
    //  return $( "<li></li>" )
    //    .data( "item.autocomplete", item )
  //          .append( "<a><div class='autocomplete-item'><img src='img/icons/" + item.icon + "'>" + item.label + "</div></a>" )
    //    .appendTo( ul );
    // };
      });

      $("#select-menu").click(function(event) {
        $("#type").val("menu");
        $("#tool").autocomplete({
          disabled: false,
      source: function (request, response) {
        $.getJSON("autocomplete.php", {
          term: request.term,
          scope: "menu"
        }, response);
      },
        })
    // .data( "autocomplete" )._renderItem = function( ul, item ) {      
    //  return $( "<li></li>" )
    //    .data( "item.autocomplete", item )
   //         .append( "<a><div class='autocomplete-item'><img src='img/icons/" + item.icon + "'>" + item.label + "</div></a>" )
    //    .appendTo( ul );
    // };
      });

      $("#select-other").click(function(event) {
        $("#type").val("other");
        $("#tool").autocomplete({ disabled: true });
      });
        
        $("#capture-form").hide();
        $("#capture-button").click(function() {
          if ($("#capture-button").text() == "RESUME") {
            jwplayer().play(true);
            $("#capture-button").text("CAPTURE");
            $("#capture-form").hide('slow');
          } else {
            jwplayer().play(false);
            $("#capture-button").text("RESUME");
            $("#form1 #tm").val(Math.floor(jwplayer().getPosition()));
            $("#form1 #tool").val("");
            $("#select-image").attr("checked", "true");
        $("#tool").attr("disabled", "disabled");
          $("#form1 #type").val("image");
      $("#form1 #tool-icon" ).attr( "src", "img/icons/white.png");
      $("#form1 #tool-id" ).val("");
            $("#form1 #comment").val("");
            $("#capture-form").show('slow');

            $.ajax({
              url: "ajax-make-thumbnail.php",        
              type: "POST",
              data: {
                tm: $("#form1 #tm").val(),
                filepath: "video/<?=$video_filename;?>"               
              }}).done(function(data){
                $("#jcrop-link img").attr("src", data);                             
              }).fail(function(){
                $("#jcrop-link").html("capture failed."); 
              }).always(function(){
              })
            ;
          }
        });

        /* attach a submit handler to the form */
        $("#form1").submit(function(event) {
          /* stop form from submitting normally */
          event.preventDefault(); 
           
          //$('#tag').focus();
          $.post($("#form1").attr("action"), { 
            video_id: "<?=$video_id;?>",
            user_id: "<?=$user_id;?>",
            tm: $("#form1 #tm").val(),
            type: $("#form1 #type").val(),
            tool: $("#form1 #tool").val(),
            comment: $("#form1 #comment").val(),
            filepath: $("#jcrop-link img").attr("src")  //"video/<?=$video_filename;?>"
            },
            function( data ) {
              var obj = JSON.parse(data);
              var id = "label-at-" + obj.tm
              var html = "<li class='label-item' id=" + id 
                + "><span class='list-image'><a href='" + obj.thumbnail
                + "'><img src='" + obj.thumbnail + "?rand=" + Math.random() + "'></a>"
                + "</span><span class='list-time'>" + obj.tm 
                + "</span><span class='list-tool'><b>" + obj.type + "</b><br>" + obj.tool
                + "</span><span class='list-comment'>" + obj.comment
                + "</span><span class='list-option'><a href='javascript:jwplayer().seek(\"" + obj.tm + "\");'>open"
                + "</a>&nbsp;&nbsp;<a href='#'>remove</a></span></li>";
              $('#label-list .label-header').after(html);
              $('#' + id).effect("highlight", {}, 5000);
              //imageLoadTimer = setInterval(checkUrl(obj.thumbnail, id), 100);
            }
          );
        });
    });

    </script>

  <script type="text/javascript">
    jwplayer("mediaplayer").setup({
      flashplayer: "js/libs/jwplayer/player.swf",
      width: "640",
      height: "363",
      controlbar: "bottom",
      file: "video/<?=$video_filename;?>",
      image: "<?=$json['thumbnail'];?>"

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
