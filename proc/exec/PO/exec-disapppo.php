<?php

	include_once('../../../config/config.php');

	//Variables
	$docentry = $_POST['docentry'];
	$err = 0;
	$errmsg = '';
	
	//Update
	$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; UPDATE [OPOR] SET U_PoStatus = 'Disapproved'
																	WHERE DocEntry = '$docentry'");
	//End Update


	//Check Error
	if(!$qry){
		$err += 1;
		$errmsg .= 'Error Disapproved (Error Code: '.odbc_error().') - '.odbc_errormsg();
	}
	//End Check Error

if($err == 0){
	echo 'true*Successfully Disapproved PO # ' . $docentry;
}else{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>