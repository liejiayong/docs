<?php

//mysqli操作mysql数据库（面向对象风格）
//1、创建mysql对象
$mysqli = new MySQLi("localhost","root","","test");

if($mysqli->connect_error){
	die("链接失败");
}
//2、操作数据库（发送sql）

$sql = "select * from user";
$res = $mysqli->query($sql);

//var_dump($res);
//3、处理结果$row=mysql_fetch_row();
while($row = $res->fetch_row()){
	foreach($row as $key=>$val ){
		echo "---$val";
	}
	echo "<br/>";
	}

//4、关闭ziyuan
//释放资源
$res->free();
//关闭链接
$mysqli->close();

?>