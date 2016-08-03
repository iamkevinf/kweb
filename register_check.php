<?php
require_once 'mysql_connect.php';
$name=$_POST['name'];
$password=$_POST['password'];
$pwd_again=$_POST['pwd_again'];
$code=$_POST['check'];
$tablename = "user";

if($name==""|| $password=="")
{
	echo"用户名或者密码不能为空";
}
else 
{
    if($password!=$pwd_again)
    {
    	echo"两次输入的密码不一致,请重新输入！";
    	echo"<a href='register.php'>重新输入</a>";
    	
    }
    else if(false && $code!=$_SESSION['check'])
    {
    	echo"验证码错误！";
    	echo"<a href='register.php'>重新输入</a>";
    }
    else
	{
		$sql = "SELECT username FROM $tablename WHERE username='$name' LIMIT 1";
		$result = mysql_query($sql);
		if(mysql_num_rows($result))
		{
			echo "用户已存在";
    		echo"<a href='register.php'>返回</a>";
		}
		else
		{
			$sql="INSERT INTO $tablename (username, password) VALUES('$name', '$password')";
			$result=mysql_query($sql);
			if(!$result)
			{
				echo"注册不成功！";
				echo"<a href='register.php'>返回</a>";
			}
			else 
			{
				echo"注册成功!";
			}
		}
    }
}
?>
