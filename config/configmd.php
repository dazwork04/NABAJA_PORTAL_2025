<?php
@session_start();
//$_SESSION['mssqldb']
//**************************************
//MSSQL
$MSSQL_USER = $_SESSION['MDUser'];
$MSSQl_PASSWORD = $_SESSION['MDPass'];
$MSSQL_SERVER = $_SESSION['MDServer'];
//$MSSQL_DB = mysql_real_escape_string('SATURN_BRANCH_FINAL');
$MSSQL_CONN = odbc_connect("Driver={SQL Server Native Client 11.0};Server=$MSSQL_SERVER;", $MSSQL_USER, $MSSQl_PASSWORD) or 
die('Could not open database!');

//MSSQL
//**************************************

?>