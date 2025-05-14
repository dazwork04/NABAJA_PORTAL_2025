<?php

	include_once('../../../config/config.php');

	$err = 0;
	$errmsg = '';
	
	$selSeries = $_POST['selSeries'];
	$txtItemCode = $_POST['txtItemCode'];
	$txtItemName = $_POST['txtItemName'];
	$selGroup = $_POST['selGroup'];
	$selStatus = $_POST['selStatus'];
	$txtSellingPrice = $_POST['txtSellingPrice'];
	
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

		$oItm = $vCmp->GetBusinessObject(4);
		
		if($selSeries == 'Manual')
		{
			$oItm->ItemCode = $txtItemCode;
		}
		else
		{
			$oItm->Series = 74;
		}
		
		$oItm->ItemName = $txtItemName;
		$oItm->ItemsGroupCode = $selGroup;
		$oItm->Frozen = $selStatus;
		
		if($txtSellingPrice != 0)
		{
			$oItmPln = $oItm->PriceList;
			$oItmPln->SetCurrentLine(0);
			$oItmPln->Currency = 'PHP';
			$oItmPln->Price = $txtSellingPrice;
		}
		
		$retval = $oItm->Add();
		
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
	echo 'true*Successfully Added Item!';
}
else
{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>