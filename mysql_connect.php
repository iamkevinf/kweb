<?php
function connect ()
{
	$servername = "localhost";
	$username = "root";
	$password = "123.com";
	$databasename = "db1th";

	$db = mysql_connect($servername, $username, $password) or die("�������ݿ�ʧ�ܣ�");
	mysql_select_db($databasename) or die ("�������ӵ�$databasename".mysql_error());
}
connect();
?>
