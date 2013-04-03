<?php
$dbhost = 'localhost';
$dbname = "";
$dbusername = "";
$dbuserpassword = "";

//$dbusername = trim($usr);
//$dbuserpassword = $pass;
/************************DBUtils.php******************************
Desc:This file used for connecting mysql database
Author:Rajes.gs
Date:9-04-2002
******************************************************************/
//error_reporting(0);
//session_start();
$MYSQL_ERRNO = '';
$MYSQL_ERROR = '';
function dbconnect() {
	global $dbhost, $dbusername , $dbuserpassword, $dbname;
	global $MYSQL_ERRNO, $MYSQL_ERROR;
	
	$dbHandle = mysql_connect($dbhost, $dbusername, $dbuserpassword);
	if (!$dbHandle) {
		$MYSQL_ERRNO = 0;
		$MYSQL_ERROR = "Connection to the host $dbhost failed. Try again";
		die (sql_error());
		
	} else if (empty($dbname) && !mysql_select_db($dbname)) {
		$MYSQL_ERRNO = mysql_errno();
		$MYSQL_ERROR = mysql_error();
		return 0;
	} else {
		if (!mysql_select_db($dbname)) die(sql_error());
	}
}
function sql_error() {
	global $MYSQL_ERRNO, $MYSQL_ERROR;
	if (empty($MYSQL_ERROR)) {
		$MYSQL_ERRNO = mysql_errno();
		$MYSQL_ERROR = mysql_error();
	}
	return "$MYSQL_ERRNO: $MYSQL_ERROR";
}
?>