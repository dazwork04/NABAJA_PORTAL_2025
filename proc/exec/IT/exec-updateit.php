<?php

include_once('../../../config/config.php');

//Variables
//Header
$docentry = $_POST['docentry'];
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
//$pickandpackremarks = $_POST['pickandpackremarks'];
$journalremarks = $_POST['journalremarks'];
$remarks = $_POST['remarks'];

$series = $_POST['series'];
$bplid = $_POST['bplid'];

//End Header
//Details
$json = $_POST['json'];
//End Details
//End Variables
//Turn off autocommit
odbc_autocommit($MSSQL_CONN, false);
//End turn off autocommit
//Check for close document
$qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "]; SELECT T0.DocNum,T0.DocStatus FROM [OWTQ] T0
																WHERE T0.DocEntry = '$basentry'");
while (odbc_fetch_row($qry)) {
    if (odbc_result($qry, 'DocStatus') == 'C') {
        $errmsg .= odbc_result($qry, 'DocNum') . ' is already closed!';
        $err += 1;
    }
}
odbc_free_result($qry);
//End Check for close document

if ($err == 0) {
//    echo 'false*hello';
    $vCmp = new COM("SAPbobsCOM.company") or die("No connection");
    $vCmp->DbServerType = $_SESSION['dbver'];
    $vCmp->server = $_SESSION['mssqlserver'];
    $vCmp->UseTrusted = false;
    $vCmp->DBusername = $_SESSION['mssqluser'];
    $vCmp->DBpassword = $_SESSION['mssqlpass'];
    $vCmp->CompanyDB = $_SESSION['mssqldb'];
    $vCmp->username = $_SESSION['SESS_SAPUSER'];
    $vCmp->password = $_SESSION['SESS_SAPPASS'];
    //$vCmp->LicenseServer = "".$LICENSE_SERVER.":30000";
    $vCmp->LicenseServer = "" . $LICENSE_SERVER . ":30000";
	$vCmp->SLDServer = $MSSQL_SERVER .':40000';

    $lRetCode = $vCmp->Connect;
    $errid = 0;
    $serr = '';

    $BaseRef = '';

    if ($lRetCode != 0) {
        $vCmp->GetLastError($err, $errmsg);
        $err += 1;
    } else {

        //DI API HERE
        //=============================================================================================
        //Header
        $oIt = $vCmp->GetBusinessObject(67);
        $oIt->GetByKey($docentry);

//        $oIt->CardCode = $bpcode;
//        $oIt->CardName = $bpname;
//        $oIt->ContactPerson = $contactperson;
        $oIt->TaxDate = $documentdate;
//        $oIt->Address = $shipto;
//        $oIt->DueDate = $duedate;
//        $oIt->DocDate = $postingdate;
//        $oIt->FromWarehouse = $fromwarehouse;
//        $oIt->ToWarehouse = $towarehouse;
//        $oIt->Pricelist = $pricelist;
//        $oIt->SalesPersonCode = $salesemployee;
//        $oIt->PickRmrk = $pickandpackremarks;
        $oIt->JournalMemo = $journalremarks;
        $oIt->Comments = $remarks;

//        $oIt->Series = $series;

        //End Header
        //Insert Details
        if (json_decode($json) != null) {

            //DECODE JSON
            $json = json_decode($json, true);
            $ctr = -1;
            foreach ($json as $key => $value) {
                $value[3] = $value[3] == '' ? 0 : $value[3];
                if ($value[6] != '') {
                    $oIt->Lines->SetCurrentLine($value[6]);
                }
                //Insert None Free Text
//                $oIt->Lines->ItemCode = $value[0];
                //$oIt->Lines->UnitPrice = $value[2];
//                $oIt->Lines->FromWarehouseCode = $value[1];
//                $oIt->Lines->WarehouseCode = $value[2];
//                $oIt->Lines->Quantity = $value[3]; //change to inventory
//                if (isset($value[4])) {
//                    $oIt->Lines->CostingCode = $value[4];
//                }
//                if (isset($value[5])) {
//                    $oIt->Lines->CostingCode5 = $value[5];
//                }
//						
//						echo "false*zzzz" ;// print_r($oIt, true);
                $oIt->Lines->Add();

                $ctr += 1;
            }
        } //End if
        //End Insert Details
        //Add PO
        $retval = $oIt->Update();
        $vCmp->GetNewObjectCode($docentry);

        if ($retval != 0) {
            $vCmp->GetLastError($err, $errmsg);
            $err += 1;
        } else {
       }
        //End Add PO
        //END DI API HERE
        //=============================================================================================
    } // End if DI API
}


if ($err == 0) {
    odbc_commit($MSSQL_CONN);
    echo 'true*Successfully Updated IT ' . $docno;
} else {
    echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);
?>