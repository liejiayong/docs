<?php

$mysqli = new MySQLi("localhost","root","","test");

$sql = "select * from user";
$sql .= "select * from test";

if($res=$sqli->muti_query($sqls)){
	do{
		$result = $mysqli->store_result();
		
		while($row = $result->fetch_row()){
			foreach($row as $key => $val){
				echo "--$val";
		
			}
			echo "<br/>";
		}
		$result->free();
		if($mysqli->more_results()){
			break;
		}
		echo "<br/>88888新的结果集8888"；
		
	}while($mysqli ->next_result());
}

$mysqli->close();
?>