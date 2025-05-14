<?php

	include_once('../../../config/config.php');

	$err = 0;
	$errmsg = '';
	
	
	$txtItemCode = $_POST['txtItemCode'];
	$txtItemName = $_POST['txtItemName'];
	$selGroup = $_POST['selGroup'];
	$selStatus = $_POST['selStatus'];
	
	$vCmp=new COM("SAPbobsCOM.company") or die ("No connection");
	$vCmp->DbServerType = 15;
	$vCmp->server = $MSSQL_SERVER;
	$vCmp->UseTrusted = false;
	$vCmp->DBusername = $MSSQL_USER;
	$vCmp->DBpassword = $MSSQl_PASSWORD;
	$vCmp->CompanyDB = $CompanyDb;
	$vCmp->username = $_SESSION['SESS_SAPUSER'];
	$vCmp->password = $_SESSION['SESS_SAPPASS'];
	$vCmp->LicenseServer = $MSSQL_SERVER .':30000';
	
	
	$lRetCode = $vCmp->Connect;
	$errid = 0;
	$serr = '';

	$BaseRef = '';

	if ($lRetCode != 0) 
	{
		$errmsg .= $vCmp->GetLastErrorDescription;
		$err += 1;
	}
	else
	{

		$oItm = $vCmp->GetBusinessObject(4);
		$oItm->ItemCode = $txtItemCode;
		$oItm->ItemName = $txtItemName;
		$oItm->ItemsGroupCode = $selGroup;
		$oItm->Frozen = $selStatus;
		
		$retval = $oItm->Add();
		
		if ($retval != 0) 
		{
			$errmsg .= $vCmp->GetLastErrorDescription;
			$err += 1;
		}
		else
		{
						
		}
	} // End if DI API



if($err == 0)
{
	//odbc_commit($MSSQL_CONN);
	echo 'true*Successfully Added Item!';
	
}
else
{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>