<?php
require_once 'mysql_connect.php';
$name=$_POST['name'];
$password=$_POST['password'];
$pwd_again=$_POST['pwd_again'];
$code=$_POST['check'];
$tablename = "user";

if($name==""|| $password=="")
{
	echo"�û����������벻��Ϊ��";
}
else 
{
    if($password!=$pwd_again)
    {
    	echo"������������벻һ��,���������룡";
    	echo"<a href='register.php'>��������</a>";
    	
    }
    else if(false && $code!=$_SESSION['check'])
    {
    	echo"��֤�����";
    	echo"<a href='register.php'>��������</a>";
    }
    else
	{
		$sql = "SELECT username FROM $tablename WHERE username='$name' LIMIT 1";
		$result = mysql_query($sql);
		if(mysql_num_rows($result))
		{
			echo "�û��Ѵ���";
    		echo"<a href='register.php'>����</a>";
		}
		else
		{
			$sql="INSERT INTO $tablename (username, password) VALUES('$name', '$password')";
			$result=mysql_query($sql);
			if(!$result)
			{
				echo"ע�᲻�ɹ���";
				echo"<a href='register.php'>����</a>";
			}
			else 
			{
				echo"ע��ɹ�!";
			}
		}
    }
}
?>
