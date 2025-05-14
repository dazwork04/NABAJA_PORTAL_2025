<?php
session_start();

//Sanitize the POST values
$sqluser = 'sa';
$sqlpass = 'devs@p2021';
$server = 'SUPERSPEED-DEV8';
$db = $_POST['dbList']; 
$db1 = 'NLC-COMMON';

$username = $_POST['username'];
$password = $_POST['password'];

$dbver = $_POST['dbver'];
$port = $_POST['port'];

//  Crystal Report
$serverIP = '192.168.0.145';
$crPort = '44332';
$crPath = '/SAPCrystalReport/Layout/';

$empid = '';
$name = '';

//**************************************
//MSSQL
$MSSQL_USER = $sqluser;
$MSSQl_PASSWORD = $sqlpass;
$MSSQL_SERVER = $server;
//$MSSQL_DB = mysql_real_escape_string('SATURN_BRANCH_FINAL');
$MSSQL_CONN = odbc_connect("Driver={SQL Server Native Client 11.0};Server=$MSSQL_SERVER;", $MSSQL_USER, $MSSQl_PASSWORD) or 
die('Could not open database!');


//MSSQL
//**************************************
$err = 0;
$errmsg = '';

$qry = odbc_exec($MSSQL_CONN, "USE [".$db1."]; SELECT * FROM [@OUSR] WHERE UserCode='$username' AND UserPass='$password' AND Status='Active'");

odbc_fetch_row($qry);

//Check Username and Password
if (odbc_num_rows($qry) <= 0) {
	$err += 1;
	$errmsg .= 'Invalid Username or Password! or Deactivated User.';
}
odbc_free_result($qry);
//End Check Username and Password


if($err == 0){
	//Check Username and Password
	$qry = odbc_exec($MSSQL_CONN, "USE [".$db1."]; SELECT * FROM [@OUSR] WHERE UserCode='$username' AND UserPass='$password' AND Status='Active'");
	odbc_fetch_row($qry);
	//End Check Username and Password
	$_SESSION['SESS_USERID'] = odbc_result($qry, 'UserId');
	$_SESSION['SESS_USERCODE'] = odbc_result($qry, 'UserCode');
	$_SESSION['SESS_NAME'] = odbc_result($qry, 'Name');
	$_SESSION['SESS_USER_TYPE'] = odbc_result($qry, 'UserType');
	$_SESSION['SESS_USER_ACCS'] = odbc_result($qry, 'forms');
	$_SESSION['SESS_SAPUSER'] = odbc_result($qry, 'sapuser');
	$_SESSION['SESS_SAPPASS'] = odbc_result($qry, 'sappass');
	$_SESSION['SESS_POS'] = odbc_result($qry, 'position');
	$_SESSION['SESS_MAN'] = odbc_result($qry, 'manufacturer');
	$_SESSION['SESS_EMP'] = odbc_result($qry, 'empid');
	$_SESSION['SESS_MULTIMAN'] = odbc_result($qry, 'multimanu');
	$_SESSION['SESS_EMAIL'] = odbc_result($qry, 'toemail');
	$_SESSION['SESS_PER_EMAIL'] = odbc_result($qry, 'per_email');
	$_SESSION['SESS_SHOWDETAILS'] = odbc_result($qry, 'ShowDetails');
	$_SESSION['mssqluser'] = $sqluser;
	$_SESSION['mssqlpass'] = $sqlpass;
	$_SESSION['mssqlserver'] = $server;
	$_SESSION['mssqldb'] = $db;
	$_SESSION['SESS_COMMONDB'] = $db1;
	$_SESSION['LICENSE_SERVER'] = $server;

  // Crystal Report
  $_SESSION['SERVER_IP'] = $serverIP;
  $_SESSION['CR_PORT'] = $crPort;
  $_SESSION['CR_PATH'] = $crPath;

	$empid = odbc_result($qry, 'empid');
	$name = odbc_result($qry, 'Name');

	$_SESSION['dbver'] = $dbver;
	$_SESSION['port'] = $port;
	odbc_free_result($qry);
		if(!$qry)
	{
		$errmsg .= 'Error Approve (Error Code: '.odbc_error().') - '.odbc_errormsg();
		echo 'false*'.$errmsg;
	}
	else
	{
		$No = $_SERVER['REMOTE_ADDR'];;
		$qry = odbc_exec($MSSQL_CONN, "INSERT INTO [".$db1."].[dbo].[@LOGS] 
								(CreateId
								  ,CreateName
								  ,Module
								  ,Action)
									VALUES
								('$empid','$name','$No','LOGIN')");
		odbc_free_result($qry);
			
		echo 'true*Successfull! Redirecting..';
	}
	echo 'true*Successfull! Redirecting..';
}
else
{
	echo 'false*'.$errmsg;
}


odbc_close($MSSQL_CONN);
?>