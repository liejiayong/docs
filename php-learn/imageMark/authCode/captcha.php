<?php
	session_start();
	$bg_Width = 100;
	$bg_Height = 30; 
	$bg = imagecreatetruecolor($bg_Width,$bg_Height);
	$color = imagecolorallocate($bg, 255, 255, 255);
	imagefill($bg, 0,0,$color);

	/**
	 * 输出验证码
	 */
/*	for ($i=0;$i<4;$i++){
		$font_color = imagecolorallocate($bg, rand(1,120), rand(0,120), rand(0,120));
		$font_size = 6;
		$font_content = rand(0,9);
		$font_x = ($i*100/4)+rand(5,10);
		$font_y = rand(8,16);
		
		imagestring($bg,$font_size,$font_x,$font_y,$font_content,$font_color);
	}
	
	*/
	$session_content = '';
	for($i=0;$i<4;$i++){
		$content = "abcdefghigklmnopqrstuvwxyz0123456789";	
		$font_content = substr($content, rand(0,strlen($content)),1);
		$session_content .= $font_content;
		$font_color = imagecolorallocate($bg, rand(1,120), rand(1,120), rand(1,120));
		$font_x = ($i*100/4)+rand(5,10);
		$font_y = rand(5,15);
		$font_size = 6;
		imagestring($bg, $font_size, $font_x, $font_y, $font_content, $font_color);
		
	}
	
	$_SESSION['authCode'] = $session_content;
	/**
	 * 输出背景颜色和横线
	 */
	
	for($i=0;$i<200;$i++){
		$font_color = imagecolorallocate($bg, rand(80,200), rand(80,200), rand(80,200));
		
		imagesetpixel($bg, rand(1,99), rand(1,29), $font_color);
	}
	for($i=0;$i<3;$i++){
	$line_color = imagecolorallocate($bg, rand(80,200), rand(80,200), rand(80,200));
	imageline($bg, rand(0,50), rand(0,15), rand(50,99), rand(15,29), $line_color);
	}
	
	header("content-type:image/png");
	imagepng($bg);
	
	imagedestroy($bg);
?>