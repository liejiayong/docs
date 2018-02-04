<?php

	function err3($errno,$errmes){
	$err_info = "cuowuhao:".$errno."---".$errmes;
	echo $errmes;
	date_default_timezone_set("PRC");
	//输出现在时间
	error_log("shijianshi". date("Y-m-d G-i-s")."\r\n",3,"d:/myerr.txt");

}

	set_error_handler("err3",E_USER_WARNING);
	
	$age=700;
	if($age>300){
		trigger_error("shuruduoyu da 11",E_USER_WARNING);
		
	}


?>