<?php

include_once('../../../config/config.php');

	$docentry = '';
	$err = 0;
	$errmsg = '';

	$txtPrcCode = $_POST['txtPrcCode'];
	$txtPrcName = $_POST['txtPrcName'];
	$ChkActive = $_POST['ChkActive'];
	
	$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."];
																	SELECT Count(PrjCode) AS OPRJCount 
																	FROM OPRJ
																	WHERE PrjCode = '$txtPrcCode' ");

	while (odbc_fetch_row($qry)) 
	{
		$OPRJCount = odbc_result($qry, 'OPRJCount');
	}
	
	if($OPRJCount == 1)
	{
		$errmsg .= 'This entry already exists in the following tables';
		$err += 1;
	}
	else
	{
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
			
			$project  = $projectService->GetDataInterface(0);

			$project->Code = $txtPrcCode;
			$project->Name = $txtPrcName;
			$project->Active  = $ChkActive == 'Y' ? 1 : 0;
			
			$projectService->AddProject($project);
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