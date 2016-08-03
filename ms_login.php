<?php
function collect_data($tablename)
{
	require_once ("mysql_connect.php");
	$sql = "select * from $tablename";
	$result = mysql_query($sql);
	$colum= mysql_fetch_array($result);
	return $colum;
}
?>
