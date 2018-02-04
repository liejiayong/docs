<?php

$mysqli = new MYSQLi("localhost" ,"root","","test");

if(!$mysqli){
	
	die("链接失败".$mysqli->connect_error);
}

//add
$sql_add = "insert into user (name,age)values('小红',33),('i',35)";

//delete
$sql_delete = "delete from user where id = 2";


$res = $mysqli->query($sql_delete);

if(!$res){
	die("操场失败".$mysqli->error);
}else{
	if($mysqli->affected_rows>0){
		
	}else{
		echo "没有函数受印象";
	}
}

$mysqli->close();


?>