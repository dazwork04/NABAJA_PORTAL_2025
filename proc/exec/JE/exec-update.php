<?php

include_once('../../../config/config.php');

	$docentry = '';
	$err = 0;
	$errmsg = '';

	$txtDocEntry = $_POST['txtDocEntry'];
	$txtRefNo = $_POST['txtRefNo'];
	$txtRemarks = $_POST['txtRemarks'];
	$txtRefDate = $_POST['txtRefDate'];
	$txtDueDate = $_POST['txtDueDate'];
	$txtTaxDate = $_POST['txtTaxDate'];

	$json = $_POST['json'];

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

	if ($lRetCode != 0) 
	{
		$errmsg .= $vCmp->GetLastErrorDescription;
		$err += 1;
	}
	else
	{
		$oJdt = $vCmp->GetBusinessObject(30);
		$oJdt->GetByKey($txtDocEntry);
		
		$oJdt->Reference = $txtRefNo;
		$oJdt->Memo = $txtRemarks;
		//$oJdt->ReferenceDate = $txtRefDate;
		$oJdt->DueDate = $txtDueDate;
		//$oJdt->TaxDate = $txtTaxDate;
		
		$retval = $oJdt->Update();
		
		if ($retval != 0) 
		{
			$errmsg .= $vCmp->GetLastErrorDescription;
			$err += 1;
		}
		else
		{
			$vCmp->GetNewObjectCode($docentry);
		}
	}

if($err == 0)
{
	echo 'true*Operation completed successfully.';
}
else
{
	echo 'false*' . $errmsg;
}

odbc_close($MSSQL_CONN);


?>