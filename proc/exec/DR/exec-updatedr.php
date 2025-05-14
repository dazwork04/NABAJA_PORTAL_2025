<?php
include_once('../../../config/config.php');

	$docentry = $_POST['docentry'];
	$docno = '';
	$err = 0;
	$errmsg = '';
	
	$basentry = $_POST['basentry'];
	$vendor = $_POST['vendor'];
	$contactperson = $_POST['contactperson'];
	$numatcard = $_POST['numatcard'];
	$paymentterms = $_POST['paymentterms'];
	$postingdate = $_POST['postingdate'];
	$deliverydate = $_POST['deliverydate'];
	$documentdate = $_POST['documentdate'];
	$remarks = $_POST['remarks'];
	$discPercent = $_POST['discPercent'];
	$tpaymentdue = $_POST['tpaymentdue'];
	$series = $_POST['series'];
	
	$selDocCur = $_POST['selDocCur'];
	$selCurSource = $_POST['selCurSource'];
	$txtDocRate = $_POST['txtDocRate'];
	
	$salesemployee = $_POST['salesemployee'];
	$owner = $_POST['owner'];

	$servicetype = $_POST['servicetype'];
	
	$json = $_POST['json'];
	
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
		$oDr = $vCmp->GetBusinessObject(15);
		$oDr->GetByKey($docentry);
		
		$oDr->DocDate = $postingdate;
		$oDr->DocDueDate = $deliverydate;
		$oDr->TaxDate = $documentdate;
		$oDr->DiscountPercent = ($discPercent == '')? 0 : $discPercent;
		$oDr->PaymentGroupCode = $paymentterms;
		$oDr->Comments = $remarks;
		$oDr->NumAtCard = $numatcard;
		$oDr->SalesPersonCode = $salesemployee;
	
		if (isset($owner) && $owner != '')
        {
            $oDr->DocumentsOwner = $owner;
        }

		$retval = $oDr->Update();
		$vCmp->GetNewObjectCode($docentry);

		if ($retval != 0) 
		{
			$errmsg .= $vCmp->GetLastErrorDescription;
			$err += 1;
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