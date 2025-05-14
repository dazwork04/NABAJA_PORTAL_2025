<?php

include_once('../../../config/config.php');

$docentry = '';
$docno = '';
$err = 0;
$errmsg = '';

$basentry = $_POST['basentry'];
$bpcode = $_POST['bpcode'];
$bpname = $_POST['bpname'];
$contactperson = $_POST['contactperson'];
$postingdate = $_POST['postingdate'];
$shipto = $_POST['shipto'];
$duedate = $_POST['duedate'];
$documentdate = $_POST['documentdate'];
$fromwarehouse = $_POST['fromwarehouse'];
$towarehouse = $_POST['towarehouse'];
$pricelist = $_POST['pricelist'];
$salesemployee = $_POST['salesemployee'];
$journalremarks = $_POST['journalremarks'];
$txtDocRef = $_POST['txtDocRef'];
$remarks = $_POST['remarks'];

$series = $_POST['series'];
$bplid = $_POST['bplid'];

$json = $_POST['json'];

if ($err == 0) {
	
    $vCmp = new COM("SAPbobsCOM.company") or die("No connection");
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
    } else {

        $oIt = $vCmp->GetBusinessObject(67);

        $oIt->CardCode = $bpcode;
        $oIt->CardName = $bpname;
      
        $oIt->Address = $shipto;
        $oIt->DueDate = $postingdate;
        $oIt->DocDate = $postingdate;
		$oIt->TaxDate = $documentdate;
		
        if(isset($fromwarehouse) &&  !empty($fromwarehouse))
        {
            $oIt->FromWarehouse = $fromwarehouse;
        }
        if(isset($towarehouse) &&  !empty($towarehouse))
        {
            $oIt->ToWarehouse = $towarehouse;            
        }
        $oIt->Pricelist = $pricelist;
        $oIt->SalesPersonCode = $salesemployee;

        $oIt->JournalMemo = $journalremarks;
        $oIt->Comments = $remarks;

        $oIt->Series = $series;

        if (json_decode($json) != null) {

            //DECODE JSON
            $json = json_decode($json, true);
            $ctr = -1;
            foreach ($json as $key => $value) {
                $value[3] = $value[3] == '' ? 0 : $value[3];

                $oIt->Lines->ItemCode = $value[0];
                $oIt->Lines->FromWarehouseCode = $value[1];
                $oIt->Lines->WarehouseCode = $value[2];
                $oIt->Lines->Quantity = $value[3]; //change to inventory

                if ($basentry != '' && isset($value[4]) && $value[4] != '') {
                    $oIt->Lines->BaseEntry = $basentry;
                    $oIt->Lines->BaseLine = $value[4];
                    $oIt->Lines->BaseType = 5;
                }
				
			    $oIt->Lines->Add();

                $ctr += 1;
            }
        } //End if

        $retval = $oIt->Add();
        $vCmp->GetNewObjectCode($docentry);

        if ($retval != 0) {
			$errmsg .= $vCmp->GetLastErrorDescription;
            $err += 1;
        } else {

            
        }

	} // End if DI API
}

if ($err == 0) {
    odbc_commit($MSSQL_CONN);
    echo 'true*Successfully Added IT # ' . $docentry;
} else {
    echo 'false*' . $errmsg;
}

odbc_close($MSSQL_CONN);
?>