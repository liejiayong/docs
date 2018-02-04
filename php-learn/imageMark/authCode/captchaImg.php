<?php
	session_start();
	
	$table = array(
		'v1' => 'red',
		'v2' => 'blue'
	);
	
	$index = rand(0,2);
	$value = $table['v'.$index];
	$_SESSION['authCode'] = $value;
	
	echo "dirname(__FILE__)";
	exit();
	$filename = dirname(__FILE__).'\v'.$index.'jpg';
	$contents = file_get_contents($filename);
	
	header("content-type:image/jpg");
	echo $contents;
	
?>