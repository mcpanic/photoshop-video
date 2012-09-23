<?php
  include "conn.php";
  
  $result1 = $mysqli->query("SELECT * FROM videos");

  $videos_array = array();
  $labels_array = array();
  $meta_array = array();


  while ($video = $result1->fetch_assoc()) {
    $video_filename = $video['filename'];
    //echo $video_filename . "<br>";
//    $string = file_get_contents("video/$video_filename.info.json");
//    $json = json_decode($string, true);

    $result3 = $mysqli->query("SELECT * FROM labels WHERE video_id = '" . $video['id'] . "'");    
    //$label_array = array();
    // $html = "";
    echo "mkdir -- '" . $video_filename . "'<br>";
    $count = 1;
    $param = "";
    while ($label = $result3->fetch_assoc()) {
      $label_array[] = $label;  
      echo "ffmpeg -y -ss " . ($label['tm']-1) . " -t 2 -i '../" . $video_filename . "' -target ntsc-vcd -qscale 1 './" . $video_filename . "/" . $count . ".mpg'<br>";
      $param .= "'./" . $video_filename . "/" . $count . ".mpg' ";
      $count += 1;
    }

    echo "cat -- " . $param . "> '" . $video_filename . ".mpg'<br>";
    echo "ffmpeg -y -i '" . $video_filename . ".mpg' -qscale 1 -- '" . $video_filename . ".flv'<br>"; 
    echo "rm -rf -- '" . $video_filename . "'<br>";
    echo "rm -rf -- '" . $video_filename . ".mpg'<br>";
    echo "<br>";
/*
ffmpeg -i input1.avi -qscale:v 1 intermediate1.mpg
ffmpeg -i input2.avi -qscale:v 1 intermediate2.mpg
cat intermediate1.mpg intermediate2.mpg > intermediate_all.mpg
ffmpeg -i intermediate_all.mpg -qscale:v 2 output.avi
*/
//gmdate("H:i:s", $label['tm'])
    //$labels_array[] = $label_array;
    //$videos_array[] = $video;
    //$meta_array[] = $json;
  } 

?>