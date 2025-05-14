<?php

include_once('../../../config/config.php');

	$docentry = '';
	$err = 0;
	$errmsg = '';

	$txtPrcCode = $_POST['txtPrcCode'];
	$txtPrcName = $_POST['txtPrcName'];
	$ChkActive = $_POST['ChkActive'];

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
		$oCpmSrv = $vCmp->GetCompanyService();
				
		$projectService  = $oCpmSrv->GetBusinessService(63);
		
		$project  = $projectService->GetDataInterface(2);
		
		$project->Code = $txtPrcCode;
		
		$projectService->DeleteProject($project);
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