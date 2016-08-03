<?php
$servername = "localhost";
$username = "root";
$password = "123.com";
$databasename = "db1th";
$tablename = "user";

// 创建连接
$conn = mysql_connect($servername, $username, $password);
// 检测连接
if (!$conn) {
    die("连接失败: <br>" . mysqli_connect_error());
}

// 创建数据库
$sql = "CREATE DATABASE ".$databasename;
if (mysql_query($sql)) {
    echo "数据库创建成功<br>";
} else {
    echo "Error creating database: <br>" . mysqli_error($conn);
}


// 使用 sql 创建数据表
$sql = "CREATE TABLE $tablename (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
username VARCHAR(30) NOT NULL UNIQUE,
password VARCHAR(30) NOT NULL,
reg_date TIMESTAMP
)";

mysql_select_db($databasename) or die(mysql_error());
if (mysql_query($sql)) {
    echo "数据表 $tablename 创建成功";
} else {
    echo "创建数据表错误: " . mysql_error();
}
?>
