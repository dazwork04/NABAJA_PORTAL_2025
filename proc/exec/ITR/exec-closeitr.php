<?php

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

	$BaseRef = '';
	
	$No = '';

	if ($lRetCode != 0) 
	{
		$errmsg .= $vCmp->GetLastErrorDescription;
		$err += 1;
	}
	else
	{

		$iTr = $vCmp->GetBusinessObject(1250000001);
		$iTr->GetByKey($docentry);
		
		$retval = $iTr->Close();
		
		if ($retval != 0) 
		{
			$errmsg .= $vCmp->GetLastErrorDescription;
			$err += 1;
		}
		else
		{
		
		}
		
	}



if($err == 0)
{
	echo 'true*Successfully Closed ITR # ' . $docentry;
}
else
{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>