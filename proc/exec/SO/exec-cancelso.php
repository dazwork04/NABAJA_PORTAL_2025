<?php
//ini_set('max_execution_time', 60);
include_once('../../../config/config.php');

	$name = $_SESSION['SESS_NAME'];
	$empid = $_SESSION['SESS_EMP'];
	
	$docentry = $_POST['docentry'];
	
	$err = 0;
	$errmsg = '';
	
	$vCmp=new COM("SAPbobsCOM.company") or die ("No connection");
	
	$vCmp->DbServerType = 15;
	$vCmp->server = $MSSQL_SERVER;
	$vCmp->UseTrusted = false;
	$vCmp->DBusername = $MSSQL_USER;
	$vCmp->DBpassword = $MSSQl_PASSWORD;
	$vCmp->CompanyDB = $_SESSION['mssqldb'];
	$vCmp->username = $_SESSION['SESS_SAPUSER'];
	$vCmp->password = $_SESSION['SESS_SAPPASS'];
	$vCmp->LicenseServer = $MSSQL_SERVER .':30000';
	$vCmp->SLDServer = $MSSQL_SERVER .':40000';

	$lRetCode = $vCmp->Connect;
	$errid = 0;
	$serr = '';
	
	$No = '';

	$BaseRef = '';

	if ($lRetCode != 0) 
	{
		$errmsg .= $vCmp->GetLastErrorDescription;
		$err += 1;
	}
	else
	{

		//Header
		$oSo = $vCmp->GetBusinessObject(17);
		$oSo->GetByKey($docentry);
		
		//Cancel SO
		$retval = $oSo->Cancel();
		
		if ($retval != 0) 
		{
			$errmsg .= $vCmp->GetLastErrorDescription;
			$err += 1;
		}
		else
		{
			
		}
		//End Add SO

	}



if($err == 0){
	echo 'true*Successfully Canceled SO ';
}else{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>