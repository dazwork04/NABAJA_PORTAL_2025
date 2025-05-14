<?php

	include_once('../../../config/config.php');

	$err = 0;
	$errmsg = '';
	
	$json = $_POST['json'];
	$json2 = $_POST['json'];
	
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
		
		if (json_decode($json) != null) 
		{

			$json = json_decode($json, true);
		   
			foreach ($json as $key => $value) 
			{
				$oItm = $vCmp->GetBusinessObject(4);
				$oItm->GetByKey($value[0]);
				
				$oItmPln = $oItm->PriceList;
				$oItmPln->SetCurrentLine(0);
				$oItmPln->Currency = 'PHP';
				$oItmPln->Price = $value[1];
				
				$retval = $oItm->Update();
				
				if ($retval != 0) 
				{
					$errmsg .= $vCmp->GetLastErrorDescription;
					$err += 1;
				}
			}
		}
	}
	
if($err == 0)
{
	echo 'true*Successfully Update Item!';
}
else
{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>