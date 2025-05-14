<?php

include_once('../../../config/config.php');

	$docentry = '';
	$err = 0;
	$errmsg = '';
	$oPCParams = 0;

	$txtPrcCode = $_POST['txtPrcCode'];
	$txtPrcName = $_POST['txtPrcName'];
	$selDimension = $_POST['selDimension'];
	$txtEffectiveDate = $_POST['txtEffectiveDate'];
	$ChkActive = $_POST['ChkActive'];
	
	$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."];
																	SELECT Count(PrcCode) AS OPRCCount 
																	FROM OPRC 
																	WHERE PrcCode = '$txtPrcCode' ");

	while (odbc_fetch_row($qry)) 
	{
		$OPRCCount = odbc_result($qry, 'OPRCCount');
	}
	
	if($OPRCCount == 1)
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
					
			$oPCService  = $oCpmSrv->GetBusinessService(61);
			
			$oPCParams  = $oPCService->GetDataInterface(2);
			
			$oPC   = $oPCService->GetDataInterface(0);
			$oPC->CenterCode = $txtPrcCode;
			$oPC->CenterName = $txtPrcName;
			$oPC->Effectivefrom = $txtEffectiveDate;
			$oPC->InWhichDimension  = $selDimension;
			$oPC->Active  = $ChkActive == 'Y' ? 1 : 0;
			
			$oPCParams  = $oPCService->AddProfitCenter($oPC);
			
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