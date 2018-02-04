<?php

require "sqliHelper.class.php";

header("Content-type:text/html;charset=utf-8");

//chuangjian duixiang 
$sqliHelper = new sqliHelper();
$sql = "insert into user (name,age) values ('小胡',34)";

$res = $sqliHelper->execute_dml($sql);

if($res == 0){
	echo "false";

}else if($res == 1){
	echo "this is ".mysqli_result::fetch_assoc;

}else if($res == 2){
	echo "没有收到影响";
}



?>