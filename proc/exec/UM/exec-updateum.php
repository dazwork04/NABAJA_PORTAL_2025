<?php

include_once('../../../config/config.php');
include_once('exec-config.php');

//Variables
	//Header
	
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
	$userid = $_POST['userid'];
	$toemail = $_POST['toemail'];
	$peremail = $_POST['peremail'];
	$selDatabase = $_POST['selDatabase'];
	$selShowDetails = $_POST['selShowDetails'];
	
	if(isset($_POST['myCheckboxes'])){
		$AccessLvl = implode(';', $_POST['myCheckboxes']);
	}else{
		$AccessLvl = '';
	}
	
	if(isset($_POST['myMan'])){
		$Man = implode(',', $_POST['myMan']);
	}else{
		$Man = '';
	}

	//Details
	//$json = $_POST['json'];
	//End Details
//End Variables

//Disable Auto Commit
odbc_autocommit($MSSQL_CONN,FALSE);
//End Disable Auto Commit

if($password == "")
{
	$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['SESS_COMMONDB']."]; UPDATE [@OUSR] SET Name = '$name',
															Department = '$department',
															sapuser = '$sapuser',
															sappass = '$sappass',
															Status = '$status',
															forms = '$AccessLvl',
															position = '$position',
															manufacturer = '$manufacturer',
															multimanu = '$selDatabase',
															toemail = '$toemail',
															ShowDetails = '$selShowDetails',
															per_email = '$peremail'
															WHERE UserId = '$userid'");
}
else
{
	$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['SESS_COMMONDB']."]; UPDATE [@OUSR] SET Name = '$name',
															Department = '$department',
															sapuser = '$sapuser',
															sappass = '$sappass',
															UserPass = '$password',
															Status = '$status',
															forms = '$AccessLvl',
															position = '$position',
															manufacturer = '$manufacturer',
															multimanu = '$selDatabase',
															toemail = '$toemail',
															ShowDetails = '$selShowDetails',
															per_email = '$peremail'
															WHERE UserId = '$userid'");
}

//Check Error
if(!$qry){
	$err += 1;
	$errmsg .= 'Error Updating Header (Error Code: '.odbc_error().') - '.odbc_errormsg();
}
//End Check Error


if($err == 0){
	odbc_commit($MSSQL_CONN);
	echo 'true*Successfully Updated User ' . $usercode;
}else{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>