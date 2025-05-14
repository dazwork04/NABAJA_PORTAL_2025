<?php
include_once('../../../config/config.php');

$docentry = '';
$err = 0;
$errmsg = '';

$txtDocEntry = $_POST['txtDocEntry'];

$linenum = $_POST['linenum'];

if ($err == 0) 
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
	
	$errid = 0;
    
	$lRetCode = $vCmp->Connect;
		
	if ($lRetCode != 0) 
	{
		$errmsg .= $vCmp->GetLastErrorDescription;
		$err += 1;
	}
	else
	{
			$oPor = $vCmp->GetBusinessObject(22);
			$oPor->GetByKey($txtDocEntry);
		
			$oPor->Lines->SetCurrentLine($linenum);
			$oPor->Lines->Delete();

			$retval = $oPor->Update();
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
}

if ($err == 0) 
{
    echo 'true*Successfully deleted item\s.';
}
else
{
  echo 'false*' . $errmsg;
}

odbc_close($MSSQL_CONN);
?>