<?php

include "conn.php";

$supported_ext = array('flv', 'mp4');
$path = './video/';
if ($handle = opendir($path)) {
    echo "Entries:<br>";

    while (false !== ($file = readdir($handle))) {
    	if (!is_dir($path.$file)){
            $splitted = explode('.', $file);
            $ext = strtolower($splitted[count($splitted)-1]);

            if ($ext == "json") {
            	echo $file . "<br>";
            	$string = file_get_contents($path.$file);
  				$json = json_decode($string, true);

  				$title = $mysqli->escape_string($json['title']);
  				$id = $mysqli->escape_string($json['id']);
  				$ext = $json['ext'];
				  if (!$mysqli->query("UPDATE videos SET title='$title', filename='$id.$ext' WHERE slug='$id'"))
				    echo "query error";  				
          }
            /*
            if (in_array($ext, $supported_ext)) {
            	//$files[] = $file;
            	echo "$file: ";
				ob_start();
				passthru("/opt/local/bin/ffmpeg -i \"". $path.$file . "\" 2>&1");
				$duration = ob_get_contents();
				ob_end_clean();

				preg_match('/Duration: (.*?),/', $duration, $matches);
				$duration = $matches[1];
				echo "$duration  ";
				$duration_array = split(':', $duration);
				$duration = $duration_array[0] * 3600 + $duration_array[1] * 60 + $duration_array[2];
				$duration = floor($duration);
				echo "$duration<br>";

				if (!$mysqli->query("UPDATE videos SET duration='$duration' WHERE filename='$file'"))
				  echo "query error";
				//if ($mysqli->affected_rows != 1)
				//  echo "query error";
            }
            */
      }

    }

    closedir($handle);
}

$mysqli->close();

?>