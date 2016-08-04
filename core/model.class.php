<?php
class Model
{
	//数据库连接句柄
	protected $dbLink = NULL;

	public function __construct()
	{
	  global $C;
	  $this->dbLink = mysql_connect($C['DB_HOST'], $C['DB_USER'], $C['DB_PWD']) or exit(mysql_error());
	  mysql_select_db($C['DB_NAME'], $this->dbLink) or exit(mysql_error());
	  mysql_query("SET NAMES {$C['DB_CHAR']}");
	}

	public function Query($sql)
	{
	  $res = mysql_query($sql) or die(mysql_error());
	  return $res;
	}

	public function Execute($sql)
	{
	  if(mysql_query($sql))
		return TRUE;
	  return FALSE;
	}

	public function Fetch($res, $type = 'array')
	{
	  $func = $type == 'array' ? 'mysql_fetch_array' : 'mysql_fetch_object';
	  $row  = $func($res);
	  return $row;
	}
}
?>
