<?php

include_once('../../../config/config.php');
include_once('../../../sbo-common/Common.php');

//Variables
//Header
$docentry = '';
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
$requestingbusinessunit = $_POST['requestingbusinessunit'];
$remarks = $_POST['remarks'];
$discPercent = $_POST['discPercent'];
$series = $_POST['series'];
$bplid = $_POST['bplid'];
$tpaymentdue = $_POST['tpaymentdue'];

$servicetype = $_POST['servicetype'];
//End Header
//Details
$json = $_POST['json'];
//End Details
//End Variables
//Turn off autocommit
odbc_autocommit($MSSQL_CONN, false);
//End turn off autocommit
//Check for close document
$qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "]; SELECT T0.DocNum,T0.DocStatus FROM [OPRQ] T0
																WHERE T0.DocEntry IN($basentry)");
while (odbc_fetch_row($qry)) {
    if (odbc_result($qry, 'DocStatus') == 'C') {
        $errmsg .= odbc_result($qry, 'DocNum') . ' is already closed!';
        $err += 1;
        break;
    }
}
odbc_free_result($qry);
//End Check for close document

if ($err == 0) {

    $vCmp = new COM("SAPbobsCOM.company") or die("No connection");
    $vCmp->DbServerType = $_SESSION['dbver'];
    $vCmp->server = $_SESSION['mssqlserver'];
    $vCmp->UseTrusted = false;
    $vCmp->DBusername = $_SESSION['mssqluser'];
    $vCmp->DBpassword = $_SESSION['mssqlpass'];
    $vCmp->CompanyDB = $_SESSION['mssqldb'];
    $vCmp->username = $_SESSION['SESS_SAPUSER'];
    $vCmp->password = $_SESSION['SESS_SAPPASS'];
    $vCmp->LicenseServer = "" . $LICENSE_SERVER . ":30000";
	$vCmp->SLDServer = $MSSQL_SERVER .':40000';

    $lRetCode = $vCmp->Connect;
    $errid = 0;
    $serr = '';

    $BaseRef = '';

    if ($lRetCode != 0) {
        $errmsg .= $vCmp->GetLastErrorDescription;
        $err += 1;
    } else {




        //DI API HERE
        //=============================================================================================
        //Header
        $oPo = $vCmp->GetBusinessObject(13);
        $oPo->CardCode = $vendor;
        $oPo->DocDate = $documentdate;
        $oPo->DocDueDate = $deliverydate;
        $oPo->TaxDate = $postingdate;
        $oPo->DiscountPercent = ($discPercent == '') ? 0 : $discPercent;
        $oPo->PaymentGroupCode = $paymentterms;

        $oPo->Comments = $remarks; //SET REMARKS
        $oPo->NumAtCard = $numatcard;


        $oPo->Series = $series;
        //$oPo->BPL_IDAssignedToInvoice = $bplid;
        //$oPo->DocTotal = ($tpaymentdue == '')? 0 : $tpaymentdue;

        if ($servicetype == 'I') {
            $oPo->DocType = 0;
        } else {
            $oPo->DocType = 1;
        }

        //End Header
        //Insert Details
        if (json_decode($json) != null) {

            //DECODE JSON
            $json = json_decode($json, true);
            $ctr = 0;
            foreach ($json as $key => $value) {






                //Check Service Type
                if ($servicetype == 'I') {
                    //$value[0] - Item Code
                    //$value[1] - Quantity
                    //$value[2] - Price
                    //$value[3] - warehouse
                    //$value[4] - taxcode
                    //$value[5] - discount
                    //$value[6] - grossprice
                    //$value[7] - taxamount
                    //$value[8] - linetotal
                    //$value[9] - grosstotal
                    //$value[10] - Free Text Y or N
                    //$value[11] - LineNo
                    //$value[12] - BarCode
                    //$value[14] - DocEntry
                    /*
                      //$value[13] = Weight (Live)
                      //$value[14] = Weight (Received)
                      //$value[15] = Weight (Carcass)
                      //$value[16] = Weight (Entrails)
                      //$value[17] = Weight (Head)
                      //$value[18] = Weight (HL Carcass)
                      //$value[19] = Weight (Delivery)
                     */
                    //Catch Blank Numerics
                    $value[1] = $value[1] == '' ? 0 : $value[1];
                    $value[2] = $value[2] == '' ? 0 : $value[2];
                    $value[5] = $value[5] == '' ? 0 : $value[5];
                    $value[6] = $value[6] == '' ? 0 : $value[6];
                    $value[7] = $value[7] == '' ? 0 : $value[7];
                    $value[8] = $value[8] == '' ? 0 : $value[8];
                    $value[9] = $value[9] == '' ? 0 : $value[9];
                    $value[13] = $value[13] == '' ? 0 : $value[13];
                    $value[14] = $value[14] == '' ? 0 : $value[14];
//                                        $value[15] = $value[15] == '' ? 0 : $value[15];
//                                        $value[16] = $value[16] == '' ? 0 : $value[16];
//                                        $value[17] = $value[17] == '' ? 0 : $value[17];
//                                        $value[18] = $value[18] == '' ? 0 : $value[18];
//                                        $value[19] = $value[19] == '' ? 0 : $value[19];
                    //End Catch Blank Numerics

                    if ($value[10] == 'N') { // Check if Free Text
                        //Insert None Free Text
                        $oPo->Lines->ItemCode = $value[0];
                        //$oPo->Lines->UnitPrice = $value[2];
                        $oPo->Lines->PriceAfterVAT = $value[6];
                        $oPo->Lines->Quantity = $value[1]; //change to inventory

                        $oPo->Lines->WarehouseCode = $value[3];
                        $oPo->Lines->VatGroup = $value[4];
                        $oPo->Lines->DiscountPercent = $value[5];

                        //New fields 20170513
                        $oPo->Lines->BarCode = $value[12];
                     

                        if (isset($value[15])) {
                            $oPo->Lines->CostingCode = $value[15];
                        }
                        if (isset($value[16])) {
                            $oPo->Lines->CostingCode5 = $value[16];
                        }
//                                                $oPo->Lines->UserFields->Fields["U_Weight2"]->Value=$value[14];
//                                                $oPo->Lines->UserFields->Fields["U_Weight3"]->Value=$value[15];
//                                                $oPo->Lines->UserFields->Fields["U_Weight4"]->Value=$value[16];
//                                                $oPo->Lines->UserFields->Fields["U_Weight5"]->Value=$value[17];
//                                                $oPo->Lines->UserFields->Fields["U_Weight6"]->Value=$value[18];
//                                                $oPo->Lines->UserFields->Fields["U_Weight7"]->Value=$value[19];

                        $oPo->Lines->Add();
                        //End Insert None Free Text
                        //Update PostedQty
                        //ENd Update Posted Qty
                    } else {
                        //$value[0] - LineText
                        //Insert Item Type Details Free Text

                        $oPo->SpecialLines->LineType = 0;
                        $oPo->SpecialLines->AfterLineNumber = $ctr;
                        $oPo->SpecialLines->LineText = $value[0];
                        $oPo->SpecialLines->Add();

                        //End Insert Item Type Details Free Text




                        $ctr += 1;
                    }//End Check Free Text
                } else {
                    //$value[0] - Remarks
                    //$value[1] - Account Code
                    //$value[2] - Price
                    //$value[3] - taxcode
                    //$value[4] - grossprice
                    //$value[5] - taxamount
                    //$value[6] - LineNo
                    //$value[7] - DocEntry
                    //Catch Blank Numerics
                    $value[2] = $value[2] == '' ? 0 : $value[2];
                    $value[4] = $value[4] == '' ? 0 : $value[4];
                    $value[5] = $value[5] == '' ? 0 : $value[5];
                    //End Catch Blank Numerics
                    //Insert Service Type Details


                    $oPo->Lines->ItemDescription = $value[0];
                    $oPo->Lines->AccountCode = $value[1];
                    //$oPo->Lines->UnitPrice = $value[2];
                    $oPo->Lines->PriceAfterVAT = $value[4];
                    $oPo->Lines->VatGroup = $value[3];
                    $oPo->Lines->Add();
                    //End Insert Service Type Details
                    //Update PostedQty
                    //ENd Update Posted Qty
                } //End Check Service Type
            }
        } //End if
        //End Insert Details
        //Add PO
        $retval = $oPo->Add();
        $vCmp->GetNewObjectCode($docentry);

        if ($retval != 0) {
            $errmsg .= $vCmp->GetLastErrorDescription;
            $err += 1;
        } else {

         
            //End Get DocNo
        }
        //End Add PO
        //END DI API HERE
        //=============================================================================================
    } // End if DI API
}


if ($err == 0) {
    odbc_commit($MSSQL_CONN);
    echo 'true*Successfully Added PO ' . $docno;
} else {
    echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);
?>