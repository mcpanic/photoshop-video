<?php
  session_start();
  if(!isset($_SESSION["username"])){
    header("location:index.php");
  }

  $video_id = $_GET["vid"];
  $task_id = $_GET["tid"];
  $user_id = $_SESSION["uid"];
  $tm = $_GET["tm"];

  include "conn.php";
  
  $result = $mysqli->query("SELECT * FROM videos WHERE id='$video_id'");
  if ($result->num_rows != 1)
    echo "query error";
  $video = $result->fetch_assoc();
  $video_filename = $video['filename'];

  $json_filename = "cscw/" . $task_id . "/" . $video_filename . ".info.json";
  //$string = file_get_contents("video/$video_filename.info.json");  
  $string = file_get_contents($json_filename);  
  // $json_file = fopen($json_filename);
  // if (!$json_file){
  //   echo "unable to read remote file " . $json_filename;
  //   exit;
  // }
  // $string = "";
  // while (!feof ($json_file)) {
  //     $string .= fgets ($json_file, 1024);
  // }
  // fclose($json_file);  

  $json = json_decode($string, true);

  // user_id needed here because you might want to only see your own labels, not other people's.
  $result = $mysqli->query("SELECT * FROM labels WHERE video_id='$video_id' AND user_id='$user_id' ORDER BY tm ASC");
  $labels_array = array();
  while ($row = $result->fetch_assoc()) {
    $labels_array[] = $row;   
  }
  $result->free();
  $mysqli->close();

  include('v2-header.php');
?>

  <h2><?=$video['title'];?></h2>
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
        </span>
        <span class="capture-comment">Parameters / Comment</span>
      </li>
      <li class="capture-item">
        <span class="capture-image"><a id="jcrop-link" class="jcrop-link" href="#"><img src="img/icons/white.png"/></a></span>
        <span class="capture-time"><input type="text" id="tm" class="time-field" name="tm"></span>
        <span class="capture-tool">
          <label for="select-image"><input type="radio" name="selection" id="select-image" value="Image" />Before/After</label>  
          <label for="select-tool"><input type="radio" name="selection" id="select-tool" value="Tool" />Instruction</label>
          <!--
          <label for="select-menu"><input type="radio" name="selection" id="select-menu" value="Menu" />Menu</label>
          <label for="select-other"><input type="radio" name="selection" id="select-other" value="Other" />Other</label>
          -->
          <div>
            <img id="tool-icon" src="img/icons/white.png"/>
            <input type="text" id="tool" name="tool" class="tool-field">
          </div>
        </span>
        <span class="capture-comment"><input type="text" id="comment" name="comment" class="comment-field"></span>
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
    <ul id="label-list" class="list-striped list-hover">
      <li class="label-header">
        <span class="list-image">Image</span>
        <span class="list-time">Time</span>
        <span class="list-tool">What's going on?</span>
        <span class="list-comment">Parameters / Comment</span>
        <span class="list-option">Options</span>
      </li>
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
<!--<script src="js/script.labeler.js"></script>-->

  <script type="text/javascript">
    function jump(time) {
      var elapsed = jwplayer().getPosition();
      jwplayer().seek(elapsed+time);
    }

    function seek(time) {
      jwplayer().seek(time);
    }
    
    Number.prototype.pad = function(n) {
        return ('0000000000' + this).slice((n || 2) * -1);
    }
    
    function getEditHTML(obj){
        var id = "label-at-" + obj.tm;
//          var selected = obj.type;
        var html = "<li class='label-item' id='" + id 
          + "'><span class='list-image'><a class='jcrop-link' href='#" //+ obj.thumbnail
          + "'><img src='" + getLink(obj.thumbnail) + "?rand=" + Math.random() + "'></a>"
          + "</span><span class='list-time'><input type='text' class='time-field' name='tm' value='" + obj.tm 
          + "'></span><span class='list-tool'><select name='type'>" 
          + "<option value='image'>image</option><option value='tool'>tool</option><option value='menu'>menu</option><option value='other'>other</option>"
          + "<br><input type='text' name='tool' value='" + obj.tool
          + "'></span><span class='list-comment'><input type='text' name='comment' value='" + obj.comment
          + "'></span><span class='list-option'><a href='javascript:seek(\"" + obj.tm + "\");'>open"
          + "</a><br><a href='#' class='edit-save-link'>save</a><br><a href='#' class='edit-cancel-link'>cancel</a>"
          + "<input type='hidden' value='" + obj.id +"' name='hidden-id'></span></li>";        
        return html;        
    }

    function getLink(link){
      var parts = link.split("/"); // img/thumbnails/xxx
      var subparts = parts.split("."); // "00003.b0yoIOX8Bk0_3.png"
      var slug = subparts[1].substr(0, 11);
      var sec = subparts[1].substr(12);
      return "http://juhokim.com/annotation/videos/thumbs/v_" + slug + "_" + sec.pad(3) + ".jpg";
    }

    function getLabelHTML(obj){
        var id = "label-at-" + obj.tm;
        var html = "<li class='label-item' id='" + id 
          + "'><span class='list-image'><a class='jcrop-link' href='" + getLink(obj.thumbnail)
          + "'><img src='" + getLink(obj.thumbnail) + "?rand=" + Math.random() + "'></a>"
          + "</span><span class='list-time'>" + obj.tm 
          + "</span><span class='list-tool'><b>" + obj.type + "</b><br>" + obj.tool
          + "</span><span class='list-comment'>" + obj.comment
          + "</span><span class='list-option'><a href='javascript:seek(\"" + obj.tm + "\");'>open"
          + "</a><br><a href='#' class='edit-label-link'>edit</a><br><a href='#' class='remove-label-link'>remove</a>"
          + "<input type='hidden' value='" + obj.id +"' name='hidden-id'></span></li>";        
        return html;
    }
    
    function getLabelByID(labels_array, id){
      var label = null;
      $.each(labels_array, function(index, obj){
        if (parseInt(obj.id) === parseInt(id))
          label = obj;
      });        
      return label;
    }

    function getIndexByID(labels_array, id){
      var result = -1;
      $.each(labels_array, function(index, obj){
        if (parseInt(obj.id) === parseInt(id))
          result = index;
      });        
      return result;
    }

    function trimUrlParameters(url){
      if (url.indexOf('?') == -1)
        return url;
      return url.slice( 0, url.indexOf('?'));
    }

    $(document).ready(function() {
      var labels_array = <?php echo json_encode($labels_array); ?>;

      // display all labels
      $.each(labels_array, function(index, value){
        $('#label-list').append(getLabelHTML(value));         
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
          filepath: trimUrlParameters($("#jcrop-link img").attr("src"))
          },
          function( data ) {
            var obj = JSON.parse(data);
            var html = getLabelHTML(obj);
            $('#label-list .label-header').after(html);
            $('#label-list li:eq(1)').effect("highlight", {}, 5000);
            labels_array.push(obj);
            console.log(labels_array);
            //imageLoadTimer = setInterval(checkUrl(obj.thumbnail, id), 100);
          }
        );
      });

      // when 'remove' is clicked
      $(document).on("click", ".remove-label-link", function(e){
        var $li = $(this).parent().parent();
        var id = $li.find("input[name='hidden-id']").val();
        var obj = getLabelByID(labels_array, id);
        var obj_index = getIndexByID(labels_array, id);

        $.post("delete-label.php", { 
          id: id
          },
          function( data ) {
            var obj = JSON.parse(data);
            $li.hide('slow', function(){$li.remove(); });
            if (obj.success && obj_index > -1)
              labels_array.splice(obj_index, 1);
            console.log(labels_array);
          }
        );
        return false;  
      });

      // when 'edit' is clicked
      $(document).on("click", ".edit-label-link", function(e){
        var $li = $(this).parent().parent();
        var id = $li.find("input[name='hidden-id']").val();
        var obj = getLabelByID(labels_array, id);
        console.log(obj);
        $li.replaceWith(getEditHTML(obj));
        return false;
      });

      // when 'edit - save' is clicked
      $(document).on("click", ".edit-save-link", function(e){
        var $li = $(this).parent().parent();
        var id = $li.find("input[name='hidden-id']").val();
        var obj = getLabelByID(labels_array, id);
        var obj_index = getIndexByID(labels_array, id);
        //console.log(obj.video_id, obj.user_id);
        $.post("update-label.php", { 
          id: id,
          video_id: "<?=$video_id;?>",
          user_id: "<?=$user_id;?>",          
          //video_id: obj.video_id.toString(),
          //user_id: obj.user_id.toString(),
          tm: $li.find("input[name='tm']").val(),
          type: $li.find("select[name='type']").val(),
          tool: $li.find("input[name='tool']").val(),
          comment: $li.find("input[name='comment']").val(),
          filepath: trimUrlParameters($li.find("img").attr("src"))  //"video/<?=$video_filename;?>"
          },
          function( data ) {
            var obj = JSON.parse(data);
            $li.replaceWith(getLabelHTML(obj));
            $li.effect("highlight", {}, 5000);
            if (obj.success && obj_index > -1){
              labels_array.splice(obj_index, 1);
              labels_array.push(obj);
            }
            console.log(labels_array);
            //imageLoadTimer = setInterval(checkUrl(obj.thumbnail, id), 100);
          }      
        );    
        return false;
      });

      // when 'edit - cancel' is clicked
      $(document).on("click", ".edit-cancel-link", function(e){
        var $li = $(this).parent().parent();
        var id = $li.find("input[name='hidden-id']").val();
        var obj = getLabelByID(labels_array, id);
        $li.replaceWith(getLabelHTML(obj));
        $li.effect("highlight", {}, 5000);
        return false;
      });

      $(document).on("click", ".jcrop-link", function(event){
      //$("#jcrop-link").click(function(event){
        event.preventDefault();
        $link = $(this);
        var width = "720px"; // 180 -> 720
        //var height = $(this).find("img").height() * 4 + "px"; // 180 -> 720 is 4 times
        var jcrop_api;
        var $dialog = $("<div><div class='jc-dialog'><img width='" + width //+ "' height='" + height 
          //+ "' src='" + $(this).find("img").attr("src") + "?rand=" + Math.random() + "' />"   
          + "' src='" + $(this).find("img").attr("src") + "' />"   
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
                filepath: trimUrlParameters($link.find("img").attr("src"))
              }}).done(function(data){
                $link.find("img").attr("src", data + "?rand=" + Math.random());        
                console.log($link.find("img"));
              }).fail(function(){
                $link.html("capture failed."); 
              }); // ajax
              $(this).dialog("close");
            }},
            resizable: true
          });
        });
        $dialog.append('<p>Select a region you want to keep.</p>');
        return false;
      });

      $("#select-image").prop("checked", true);
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
        $("#tool-icon").attr( "src", "img/icons/white.png" );
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
            $("#select-image").prop("checked", true);
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
                filepath: "video/<?= substr($video_filename, 6); ?>"               
              }}).done(function(data){
                $("#jcrop-link img").attr("src", data);                             
              }).fail(function(){
                $("#jcrop-link").html("capture failed."); 
              }).always(function(){
              })
            ;
          }
          return false;
        });
    });
  </script>

  <script type="text/javascript">
    jwplayer("mediaplayer").setup({
      flashplayer: "js/libs/jwplayer/player.swf",
      width: "640",
      height: "363",
      controlbar: "bottom",
      file: "http://people.csail.mit.edu/juhokim/annotation-videos/<?=$task_id;?>/<?=$video_filename;?>", //"video/<?=$video_filename;?>",
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
