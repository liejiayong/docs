<?php
	$src = 'image/bg.png';
	
	$info = getimagesize($src);
	
	$type = image_type_to_extension($info[2],false);
	
	$fun = "imagecreatefrom{$type}";
	
	$image = $fun($src);
	
	//
	$image_thumb = imagecreatetruecolor(300, 200);
	
	imagecopyresampled($image_thumb, $image, 0, 0, 0, 0, 300,200,$info[0],$info[1]);
	
	//
	imagedestroy($image);
	
	//output
	header("Content-type:".$info['mime']);
	
	$fun = "image{$type}";
	
	$fun($image_thumb);
	
	$fun($image_thumb,'thumb_iamge.'.$type);
	imagedestroy($image_thumb);

?>