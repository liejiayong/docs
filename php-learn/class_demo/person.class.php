<?php
	class person{
		
		public $name;
		public $age;
		public $tall;
		public $weight;
	
		
		public function work($op1,$op2,$op){
			switch('$op'){
			case '+':
			$result = $op1 + $op2;
			break;
			case '-':
			$result = $op1 - $op2;
			break;
			case '*':
			$result = $op1 * $op2;
			break;
			case '/':
			$result = $op1 / $op2;
			break;
			return "ฝแน๛=".$result;
			
			}
			
		}
		
		
	}

?>