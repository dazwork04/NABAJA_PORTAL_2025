<?php
@session_start();

if(!isset($_SESSION['mssqlserver'])) 
{
	// if($_SERVER['SERVER_NAME'] == 'NLCServer1')
	// {
	// 	header('Location:http://NLCServer1:8088/nabaja_portal/');
	// 	exit;
	// } 
	// elseif($_SERVER['SERVER_NAME'] == 'localhost')
	// {
	// 	header('Location:http://localhost:8088/nabaja_portal/');
	// 	exit;
	// }
	// else
	// {
	// 	header('Location:http://192.168.1.186:8088/nabaja_portal/');
	// 	exit;
	// }
  // if($_SERVER['SERVER_NAME'] == 'SUPERSPEED-DEV8')
	// {
	// 	header('Location:http://SUPERSPEED-DEV8:8085/NABAJA_PORTAL_2025/');
	// 	exit;
	// } 
}
$MSSQL_USER = $_SESSION['mssqluser'];
$MSSQl_PASSWORD = $_SESSION['mssqlpass'];
$MSSQL_SERVER = $_SESSION['mssqlserver'];

$MSSQL_CONN = odbc_connect("Driver={SQL Server Native Client 11.0};Server=$MSSQL_SERVER;", $MSSQL_USER, $MSSQl_PASSWORD) or 
die('Could not open database!');

//MSSQL
//**************************************

?>