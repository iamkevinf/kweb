<?php
$servername = "localhost";
$username = "root";
$password = "123.com";
$databasename = "db1th";
$tablename = "user";

// ��������
$conn = mysql_connect($servername, $username, $password);
// �������
if (!$conn) {
    die("����ʧ��: <br>" . mysqli_connect_error());
}

// �������ݿ�
$sql = "CREATE DATABASE ".$databasename;
if (mysql_query($sql)) {
    echo "���ݿⴴ���ɹ�<br>";
} else {
    echo "Error creating database: <br>" . mysqli_error($conn);
}


// ʹ�� sql �������ݱ�
$sql = "CREATE TABLE $tablename (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
username VARCHAR(30) NOT NULL UNIQUE,
password VARCHAR(30) NOT NULL,
reg_date TIMESTAMP
)";

mysql_select_db($databasename) or die(mysql_error());
if (mysql_query($sql)) {
    echo "���ݱ� $tablename �����ɹ�";
} else {
    echo "�������ݱ����: " . mysql_error();
}
?>
