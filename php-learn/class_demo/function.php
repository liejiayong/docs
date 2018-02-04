<?php
	require_once 'person.class.php';
	
	$p1 = new person;
	$op1 = $_REQUEST['op1'];
	$op2 = $_REQUEST['op2'];
	$op = $_REQUEST['op'];
	
	$p1->work($op1,$op2,$op);

?>