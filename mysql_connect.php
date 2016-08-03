<?php
function connect ()
{
	$servername = "localhost";
	$username = "root";
	$password = "123.com";
	$databasename = "db1th";

	$db = mysql_connect($servername, $username, $password) or die("连接数据库失败！");
	mysql_select_db($databasename) or die ("不能连接到$databasename".mysql_error());
}
connect();
?>
