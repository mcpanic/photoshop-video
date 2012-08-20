<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$img_r = imagecreatefromjpeg($_POST['filepath']);
	//$dst_r = imagecreatetruecolor(100,100);
	$dst_r = imagecreatetruecolor( $_POST['w'], $_POST['h'] );

	//imagecopy($dst_r,$img_r,0,0,0,0,100,100);
	imagecopy($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$_POST['w'],$_POST['h']);

	//header('Content-type: image/png');
	$new_filename = $_POST['filepath'].'_crop.png';
	imagejpeg($dst_r, $new_filename, 100);
	imagedestroy($dst_r);
	echo $new_filename;
}
?>