<?php

include_once('../../../config/config.php');

	$err = 0;
	$errmsg='';
	
	$empid = $_SESSION['SESS_EMP'];

	$cpassword = $_POST['cpassword'];
	
	//$MSSQL_DB2 = 'HIRAM-COMMON';

	$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['SESS_COMMONDB']."]; UPDATE [@OUSR] SET
																UserPass = '$cpassword'
																WHERE empid = '$empid'");

	//Check Error
	if(!$qry){
		$err += 1;
		$errmsg .= 'Error Updating Header (Error Code: '.odbc_error().') - '.odbc_errormsg();
	}
	//End Check Error


if($err == 0){
	
	echo 'true*Successfully Updated User';
}else{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>