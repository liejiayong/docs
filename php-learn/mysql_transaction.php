<?php

$mysqli = new MySQLi("localhost","root","","test");

if(!$mysqli){
	
	die($mysqli->connect_error);
}

$mysqli->autocommit(false);

$sql1 = "update balance set balance=balance-2 where id=2";
$sql2 = "update balance set balance=balance+2 where id=1"; 

$b1 = $mysqli->query($sql1);
$b2 = $mysqli->query($sql2);

if(!$b1||!$b2){
	echo "false,rollback".$mysqli->error;
	$mysqli->rollback();
}else{
	$mysqli->commit();
}

$mysqli->close();

?>