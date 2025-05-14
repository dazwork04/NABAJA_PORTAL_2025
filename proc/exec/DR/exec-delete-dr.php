<?php
include_once('../../../config/config.php');

$docentry = '';
$err = 0;
$errmsg = '';

$txtDocEntry = $_POST['txtDocEntry'];
$linenum = $_POST['linenum'];


if($txtDocEntry == '')
{
	$errmsg .= 'You cannot delete this item. Please try again!';
	$err += 1;
}

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
			$oSo = $vCmp->GetBusinessObject(15);
			$oSo->GetByKey($txtDocEntry);
		
			$oSo->Lines->SetCurrentLine($linenum);
			$oSo->Lines->Delete();

			$retval = $oSo->Update();
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

?>