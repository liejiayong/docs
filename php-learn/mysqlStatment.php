<?php

//预编译,执行dml语句
//1、创建对象
$mysql = new MySQLi("localhost","root",""."test");

//2、创建预编译对象
$sql = "insert into user (name,age),values(?,?)";
$sqli_stmt = $myslq->prepare($sql);

//绑定参数
$name = "小倩";
$age = 44;

//参数绑定->给？赋值

$mysqli_stmt->bind_param("si",$name,$age);

//执行
$b = $mysqli_stmt->execute();

//继续添加
$name = "小倩2";
$age = 444;
$mysqli_stmt->bind_param("si",$name,$age);

//执行
$b = $mysqli_stmt->execute();

if(!$b){
	die("false".$mysqli_stmt->error);
}else{
	echo "ok";

}

//释放
$mysql ->close();


?>


