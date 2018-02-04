<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<script language="javascript">
<!--
	function setType(val){
		
		if(val=='arithment'){
		
			table1.style.display="block";
			table2.style.display="none";
		}else if(val=='cyc'){
	
			table1.style.display="none";
			table2.style.display="block";
		}
		
	}
//-->
</script>
</head>
<body>

<form action="function.php" method="post">
<input type="radio" name="sel" onclick="setType('arithment')"/>四则运算
<input type="radio" name="sel" onclick="setType('cyc')"/>图形
<table id="table1" style="display:block">

<tr><td>first number</td>
<td><input value="" type="text" name="op1"/></td></tr>

<tr><td>Second number</td>
<td><input value="" type="text" name="op2"/></td></tr>

<tr><td>四则运算</td>
<td>
<select name="op">
<option value="+">+</option>
<option value="-">-</option>
<option value="*">*</option>
<option value="/">/</option>
</select>
</td></tr>

<tr><td colspan="2"><input type="submit" value="sumbit"/>
<input type="hidden" name="arithment" />
</td></tr>
</table>
<table id="table2" style="display:none" >

<tr><td>width：</td>
<td><input value="" type="text" name="ope1"/></td></tr>

<tr><td>height</td>
<td><input value="" type="text" name="ope2"/></td></tr>

<tr><td>图形化</td><td>
<select name="ope">
<option value="one">one</option>
<option value="two">two</option>
<option value="three">three</option>
<option value="four">four</option>
</select>
</td></tr>

<tr><td colspan="2"><input type="submit" value="sumbit"/>
<input type="hidden" name="cyc"/>
</td></tr>
</table>
</form>
</body>
</html>