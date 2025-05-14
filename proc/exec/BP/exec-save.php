<?php

	include_once('../../../config/config.php');

	$err = 0;
	$errmsg = '';
	
	$selCategory = $_POST['selCategory'];
	$txtBPCode = $_POST['txtBPCode'];
	$txtBPName = $_POST['txtBPName'];
	$selGroup = $_POST['selGroup'];
	$selStatus = $_POST['selStatus'];
	
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
		$errmsg .= $vCmp->GetLastErrorDescription;
		$err += 1;
	}
	else
	{

		$oBp = $vCmp->GetBusinessObject(2);
		
		/* if($selCategory == 0)
		{
			$oBp->Series = 72;
		}
		else
		{
			$oBp->Series = 74;
		} */
		
		$oBp->CardCode = $txtBPCode;
		$oBp->CardName = $txtBPName;
		$oBp->CardType = $selCategory;
		$oBp->GroupCode = $selGroup;
		$oBp->Frozen = $selStatus;
		
		$retval = $oBp->Add();
		
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
	//odbc_commit($MSSQL_CONN);
	echo 'true*Successfully Added Business Partner!';
	
}
else
{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>