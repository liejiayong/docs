<?php
	
	class sqlTool{
		private $conn;
		private $host = "localhost";
		private $root = "root";
		private $password="root";
		private $db = "test";
		
		function sqlTool(){
			
			$this->conn = mysql_connect($host,$root,$password
		);
		
		if(!$conn){
			die("链接数据库失败".mysql_error());
		}
		mysql_select_db($this->db,$this->conn);	
		mysql_query("set name utf8");
			
		} 
		
		//select
		function execute_dql($sql){
			
		}
		
		function execute_dml($sql){
			
			$b = mysql_query($sql,$this->conn);
			
			if(!$b){
				return 0;//fail
				
			}else{
				if(mysql_affected_rows($this->conn) > 0){
					return 1;//success
				}else{
					
					return 2；//表示没有行数影响
				}
				
				
			}
			
		}
		
		
	}



?>