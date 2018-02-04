<?php
	if(isset($_POST['authCode'])){
	session_start();
	
	if(strtolower($_POST['authCode']) == $_SESSION['authCode']){
		echo "success";
	}else{
		echo "false";
	}
	exit();
	}
?>

<html>

<form action = "form.php" method="post">
	验证码：<img border='1' id="change" src="captchaImg.php?r=<?php echo rand();?>"/>
	<a href="javascript:void(0)" onclick="document.getElementById('change').src='captchaImg.php?r='+Math.random()">换一个？</a>
	
	<p>请输入图片中的内容:<input type="text" name="authCode" value=""/></p>
	
	<p><input type='submit' value="提交" style="padding:6px 20px"/></p>
	
	
</form>



</html>