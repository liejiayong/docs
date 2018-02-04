
<?php

//mysqli操作mysql数据库（面向guocheng风格）
//1、创建mysql对象
$mysqli = mysqli_connect("localhost","root","","test");

if(!$mysqli){//mysqli_connect_error($mysqli)){
	die("链接失败");
}
//2、操作数据库（发送sql语句ddl\dml\dql）

$sql = "select * from user";
$res=mysqli_query($mysqli,$sql);


//var_dump($res);
//3、处理结果$row=mysql_fetch_row();
while($row=mysqli_fetch_row($res)){
	foreach($row as $key=>$val ){
		echo "---$val";
	}
	echo "<br/>";
	}

//4、关闭ziyuan
//释放资源
mysqli_free_result($res);
//关闭链接
mysqli_close($mysqli);

?>



