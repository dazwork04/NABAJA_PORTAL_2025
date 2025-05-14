<?php
ini_set('max_execution_time', 10);
// include_once('../../../config/configmd.php');
include_once('../../../config/config.php');

	$userid = $_POST['userid'];
	$err = 0;
	$errmsg = '';
	
//Disable Auto Commit
odbc_autocommit($MSSQL_CONN,FALSE);
//End Disable Auto Commit

//Insert
// $qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."]; DELETE FROM [@OUSR] WHERE UserId = '$userid'");
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['SESS_COMMONDB']."]; DELETE FROM [@OUSR] WHERE UserId = '$userid'");
															
//End Insert


//Check Error
if(!$qry){
	$err += 1;
	$errmsg .= 'Error Deleting User (Error Code: '.odbc_error().') - '.odbc_errormsg();
}
//End Check Error



if($err == 0){
	odbc_commit($MSSQL_CONN);
	echo 'true*Successfully Delete UserId # ' . $userid;
}else{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>