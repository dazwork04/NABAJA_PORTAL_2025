<?php
//ini_set('max_execution_time', 10);
include_once('../../../config/config.php');
include_once('exec-config.php');

//Variables
	//Header
	$docentry = '';
	$docno = '';
	$err = 0;
	$errmsg = '';
	
	$usercode = $_POST['usercode'];
	$name = $_POST['name'];
	$password = $_POST['password'];
	$department = $_POST['department'];
	// $sapuser = $_POST['sapuser'];
	// $sappass = $_POST['sappass'];
  $sapuser = $configSapUser;
	$sappass = $configSapPass;
	$status = $_POST['status'];
	$position = $_POST['position'];
	$manufacturer = $_POST['manufacturer'];
	$empid = $_POST['empid'];
	$toemail = $_POST['toemail'];
	$peremail = $_POST['peremail'];
	$selDatabase = $_POST['selDatabase'];
	$selShowDetails = $_POST['selShowDetails'];
	
	//$myCheckboxes = $_POST['myCheckboxes'];
	if(isset($_POST['myCheckboxes']))
	{
		$AccessLvl = implode(';', $_POST['myCheckboxes']);
	}
	else
	{
		$AccessLvl = '';
	}
	
	if(isset($_POST['myMan']))
	{
		$Man = implode(',', $_POST['myMan']);
	}
	else
	{
		$Man = '';
	}
	//Details
	//$json = $_POST['json'];
	//End Details
//End Variables

//Disable Auto Commit
odbc_autocommit($MSSQL_CONN,FALSE);
//End Disable Auto Commit

//Check if already existed
$sqlqry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['SESS_COMMONDB']."]; SELECT COUNT(*) AS Res 
																FROM [@OUSR] 
																WHERE empid = '$empid'");
odbc_fetch_row($sqlqry);
$isExist = odbc_result($sqlqry, "Res");
odbc_free_result($sqlqry);
//End Check if already existed

if(!$isExist){
	//Insert
	$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['SESS_COMMONDB']."]; INSERT INTO [@OUSR](UserCode,Name,UserPass,Department,sapuser,sappass,Status,forms, position, manufacturer, empid, multimanu, toemail, per_email, ShowDetails)
																VALUES('$usercode', '$name', '$password', '$department', '$sapuser', '$sappass', '$status', '$AccessLvl', '$position', '$manufacturer', '$empid', '$selDatabase', '$toemail', '$peremail', '$selShowDetails')");
	//End Insert


	//Check Error
	if(!$qry){
		$err += 1;
		$errmsg .= 'Error Inserting Header (Error Code: '.odbc_error().') - '.odbc_errormsg();
	}
	//End Check Error

}else{
	$err += 1;
	$errmsg .= 'User already exist!';
}//End If isExist



	



if($err == 0){
	odbc_commit($MSSQL_CONN);
	echo 'true*Successfully Added User ' . $usercode;
}else{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>