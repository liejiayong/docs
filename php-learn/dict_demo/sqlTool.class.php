<?php

class sqlTool{
	private $conn;
	private $host = localhost;
	private $user ="root";
	private $password = "root";
	
	function sqlTool(){
		$this->conn = mysql_connect($this->host,$this->user,$this->password);
		
		if(!$this->conn){
			
			die("数据库连接失败".mysql_error());
		}
		
		mysql_select_db($this->db,$this->conn);
		mysql_query("set name utf8");
		
	}
	
	public function execute_dql($sql){
		$res = mysql_query($sql) or die(mysql_error());
	}
	
	public function execute_dml($sql){
		$b = mysql_query($sql,$this->conn);
		
		if(!$b){
			return 0 ;
		}else{
			if(mysql_affected_rows($this->conn)>0){
				return 1;
				}else{
					return 2;
				}
		}
		
		mysql_close($sql);
	}
	
	
}



?>