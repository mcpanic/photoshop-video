<?php

include "conn.php";

$rate = 2;

$supported_ext = array('flv', 'mp4');
//$ffmpeg_path = "/opt/local/bin/ffmpeg";
$path = './video/';
$ffpath = "ff/";
$scpath = "sc/";
$temp_dir = "ffmpeg_temp";

if ($handle = opendir($path)) {
    echo "Entries:<br>";

    while (false !== ($file = readdir($handle))) {

    	if (!is_dir($path.$file)){
            $splitted = explode('.', $file);
            $ext = strtolower($splitted[count($splitted)-1]);

            if (in_array($ext, $supported_ext)) {
            	//$files[] = $file;
            	echo "$file: ";
            	
				ob_start();
				passthru("mkdir ffmpeg_temp");
				$command = FFMPEG_PATH . " -i " . $path.$file . " -r " . $rate . " -f image2 " . $temp_dir . "/%05d.png";				
				passthru($command);
				$command2 = FFMPEG_PATH . " -i " . $path.$temp_dir . "/%05d.png -sameq " . $path.$ffpath.$file;
				passthru($command2);
				passthru("rm -rf " . $path . $temp_dir);				
				ob_end_clean();
				//echo $command2;
				echo "done<br>";
				
/*				
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
*/  
            }
      }

    }

    closedir($handle);
}

$mysqli->close();

?>