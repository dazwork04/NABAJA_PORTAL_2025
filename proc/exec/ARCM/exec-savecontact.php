<?php

include_once('../../../config/config.php');

	$docentry = '';
	$docno = '';
	$err = 0;
	$errmsg = '';
	$sample = '';
	$affected = 0;
	
	$contactperson = $_POST['contactperson'];
	$vendor = $_POST['vendor'];
	
	$qryContact = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "]; SELECT Name FROM OCPR WHERE CardCode = '$vendor' ");
	$affected = odbc_num_rows($qryContact);
	odbc_free_result($qryContact);
	
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

	if ($lRetCode != 0) {
		$errmsg .= $vCmp->GetLastErrorDescription;
		$err += 1;
	}else{

		$oBPo = $vCmp->GetBusinessObject(2);
		$oBPo->GetByKey($vendor);
		
		if ($affected == 0)
		{
			$oBPo->ContactEmployees->Name = $contactperson;
			$oBPo->ContactEmployees->Add();
		}
		else{
			
			$oBPo->ContactEmployees->Add();
			$oBPo->ContactEmployees->Name = $contactperson;
			
		}
				
		$retval = $oBPo->Update();
		
		$html = '';
		$htmldetails = '';
		if ($retval != 0) {
			$errmsg .= $vCmp->GetLastErrorDescription;
			$err += 1;
		}else{
			
		}
	}

if($err == 0){
	echo 'true*Successfully Added Contact';
}else{
	echo 'false*' . $errmsg;
}

odbc_close($MSSQL_CONN);

?>