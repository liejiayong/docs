<?php

$mysqli = new MySQLi("localhost","root","","test");

if(!$mysqli){
	
	die($mysqli->connect_error);
}

//创建一个预定义对象
$sql = "select id,name,age from user where id>?";
$mysqli_stmt = $mysqli->prepare($sql);

//绑定参数
$mysqli_stmt->bind_param("i",$id);


//绑定结果集

$mysqli_stmt->bind_result($id,$name,$age);

//执行
$mysqli_stmt->execute();

//取出绑定的值
while($mysqli_stmt->fetch()){
	echo "<br/>--$id--$name--$age";
}
//释放资源
$mysqli_stmt->free_result();
//关闭预编译语句
$mysqli_stmt->close();

//关闭链接
$mysqli->close();
?>