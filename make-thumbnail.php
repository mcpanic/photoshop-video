<?php
$filepath = $_POST['filepath'];
$tm = $_POST['tm'];

$parts = pathinfo($filepath);
$filename = $parts['filename'] . "_$tm.png";

$inputfile = $filepath;
$outputfile = "img/thumbnails/" . $filename;
// -y to always overwrite
// -s to add image size
$cmd = "/opt/local/bin/ffmpeg -ss $tm -i $inputfile -f mjpeg -vf \"scale=720:-1\" -y -t 1 -an $outputfile 2>&1";
echo $outputfile;
exec($cmd, $output);
//var_dump($output);
?>
