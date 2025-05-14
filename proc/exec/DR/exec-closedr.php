<?php
include_once('../../../config/config.php');

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

	if ($lRetCode != 0) 
	{
		$vCmp->GetLastError($err, $errmsg);
		$err += 1;
	}
	else
	{

		$oSo = $vCmp->GetBusinessObject(15);
		$oSo->GetByKey($docentry);
		
		$retval = $oSo->Close();
		
		if ($retval != 0) 
		{
			$vCmp->GetLastError($err, $errmsg);
			$err += 1;
		}
		else
		{

		}
	}

if($err == 0)
{
	echo 'true*Successfully Closed DR # ' . $docentry;
}
else
{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>