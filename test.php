
<?php

    $err = 0;
	$errmsg = '';

    $vCmp=new COM("SAPbobsCOM.company") or die ("No connection");
	
	// $vCmp->DbServerType = 15;
	// $vCmp->server = 'NLCSERVER1';
	// $vCmp->UseTrusted = false;
	// $vCmp->DBusername = 'sa';
	// $vCmp->DBpassword = 'nlcs@p2022';
	// $vCmp->CompanyDB = 'NCL_UAT01';
	// $vCmp->username = 'manager';
	// $vCmp->password = '1234';
	// $vCmp->LicenseServer = 'NLCSERVER1:30000';
    
	// $vCmp->SLDServer  = 'NLCSERVER1:40000';

  $vCmp->DbServerType = 15;
	$vCmp->server = 'SUPESPEED-DEV8';
	$vCmp->UseTrusted = false;
	$vCmp->DBusername = 'sa';
	$vCmp->DBpassword = 'devs@p2021';
	$vCmp->CompanyDB = 'NCL_UAT_PORTAL';
	$vCmp->username = 'manager';
	$vCmp->password = '1234';
	$vCmp->LicenseServer = 'SUPERSPEED-DEV8:30000';
  $vCmp->SLDServer  = 'SUPERSPEED-DEV8:40000';

	$lRetCode = $vCmp->Connect;
	$errid = 0;
	$serr = '';

	$BaseRef = '';

	if ($lRetCode != 0) 
	{
		$errmsg .= $vCmp->GetLastErrorDescription;
		$err += 1;

        echo $errmsg;
	}else{
        echo 'success';
    }
    ?>