<?php
//请编写一个函数，可以接受一个表名，然后把表的头和记录信息显示在网页

function show_tab_info($table_name){
	
	$conn = mysql_connect("localhost","root","root");
	
	if(!$conn){
		die(mysql_error();
	}
	
	mysql_select_db("test",$conn);
	mysql_query("set names utf8");
	$sql = "select * from $table_name";
	
	$res = mysql_query($sql,$conn);
	
	//我要知道共有多少行，和多少列
	$field_info = mysql_fetch_field($res);
	
	var_dump($res);
	
	
	
}

	show_tab_info(user1);
?>