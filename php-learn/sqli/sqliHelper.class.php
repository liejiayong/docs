<?php
	class sqliHelper{
		//将来这里写死的数据信息，是需要配置要一个文件
		private static $host = "localhost";
		private static $user = "root";
		private static $password = "";
		private static $db = "test" ;
		
		private $mysqli;
		
	public function __construct(){
		//完成初始化
		$this->mysqli = new MySQLi(self::$host,self::$user,self::$password,self::$db);
		
		if($this->mysqli->connect_error){
			die("连接失败".$this->mysqli->connect_error);
		}
		
		//保证以utf-8方式操作数据库
		$this->mysqli->query("set names utf8");
		
		
		
	}
	
	
	public function execute_dql($sql){
		$res = $this->mysqli->query($sql) or die("false".$this->mysqli->error);
		
		return $res;
	}
	
	public function execute_dml($sql){
		
		$res = $this->mysqli->query($sql) or die("false".$this->mysqli->error);
		
		if(!$res){
			
			return 0;
		}else {
			
			if($this->mysqli->affected_rows>0){
				return 1;//success
				
			}else{
				return 2;//无影响
			}
		}
	}
		
		
		
		
		
	}


?>