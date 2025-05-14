<?php

include_once('../../../config/config.php');
include_once('../../../sbo-common/Common.php');

//Variables
//Header
$docentry = $_POST['docentry'];
$docno = '';
$err = 0;
$errmsg = '';

$basentry = $_POST['basentry'];
$customer = $_POST['customer'];
$contactperson = $_POST['contactperson'];
$numatcard = $_POST['numatcard'];
$paymentterms = $_POST['paymentterms'];
$postingdate = $_POST['postingdate'];
$duedate = $_POST['duedate'];
$documentdate = $_POST['documentdate'];
$requestingbusinessunit = $_POST['requestingbusinessunit'];
$remarks = $_POST['remarks'];
$discPercent = $_POST['discPercent'];
$tpaymentdue = $_POST['tpaymentdue'];
$series = $_POST['series'];
$salesemployee = $_POST['salesemployee'];
$owner = $_POST['owner'];
//$bplid = $_POST['bplid'];

$servicetype = $_POST['servicetype'];
//End Header
//Details
$json = $_POST['json'];
//End Details
//End Variables

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
        $oSi = $vCmp->GetBusinessObject(13);
        $oSi->GetByKey($docentry);
        //$oSi->CardCode = $customer;
        $oSi->DocDate = $documentdate;
        $oSi->DocDueDate = $duedate;
       
        $oSi->TaxDate = $postingdate;
        $oSi->DiscountPercent = ($discPercent == '') ? 0 : $discPercent;
//        $oSi->PaymentGroupCode = $paymentterms;
        $oSi->Comments = $remarks; //SET REMARKS
        $oSi->NumAtCard = $numatcard;
//        $oSi->Series = $series;
        $oSi->SalesPersonCode = $salesemployee;
        $oSi->DocumentsOwner = $owner;
        //$oSi->BPL_IDAssignedToInvoice = $bplid;
        //$oSi->DocTotal = ($tpaymentdue == '')? 0 : $tpaymentdue;

        /*
          if($servicetype == 'I'){
          $oSi->DocType = 0;
          }else{
          $oSi->DocType = 1;
          }
         */
        //End Header
        //Insert Details
        if (json_decode($json) != null) {

            //DECODE JSON
            $json = json_decode($json, true);
            $ctr = -1;
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
                    //Catch Blank Numerics
                    $value[1] = $value[1] == '' ? 0 : $value[1];
                    $value[2] = $value[2] == '' ? 0 : $value[2];
                    $value[5] = $value[5] == '' ? 0 : $value[5];
                    $value[6] = $value[6] == '' ? 0 : $value[6];
                    $value[7] = $value[7] == '' ? 0 : $value[7];
                    $value[8] = $value[8] == '' ? 0 : $value[8];
                    $value[9] = $value[9] == '' ? 0 : $value[9];
//                    $value[13] = $value[13] == '' ? 0 : $value[13];
//                    $value[14] = $value[14] == '' ? 0 : $value[14];
//                                        $value[15] = $value[15] == '' ? 0 : $value[15];
//                                        $value[16] = $value[16] == '' ? 0 : $value[16];
//                                        $value[17] = $value[17] == '' ? 0 : $value[17];
//                                        $value[18] = $value[18] == '' ? 0 : $value[18];
//                                        $value[19] = $value[19] == '' ? 0 : $value[19];
                    //End Catch Blank Numerics

                    if ($value[10] == 'N') { // Check if Free Text
                        //catch blank only if item type
                        $value[13] = $value[13] == '' ? 0 : $value[13];
                        $value[14] = $value[14] == '' ? 0 : $value[14];
                        if ($value[11] != '') {
                            $oSi->Lines->SetCurrentLine($value[11]);
                            $ctr = $value[11];
                        }

                        //Insert None Free Text
                        $oSi->Lines->ItemCode = $value[0];
                        //$oSi->Lines->UnitPrice = $value[2];
//                        $oSi->Lines->DiscountPercent = $value[5];
                        $oSi->Lines->PriceAfterVAT = $value[6];
                        $oSi->Lines->Quantity = $value[1]; //change to inventory
                        $oSi->Lines->WarehouseCode = $value[3];
                        $oSi->Lines->VatGroup = $value[4];

                        //New fields 20170503
                        $oSi->Lines->BarCode = $value[12];
                    

//                                                echo 'false*'.print_r($oSi, false);
//                        if (isset($value[15])) {
//                            $oSi->Lines->CostingCode = $value[15];
//                        }
//                        if (isset($value[16])) {
//                            $oSi->Lines->CostingCode5 = $value[16];
//                        }
//                                                $soPo->Lines->UserFields->Fields["U_Weight2"]->Value=$value[14];
//                                                $oSi->Lines->UserFields->Fields["U_Weight3"]->Value=$value[15];
//                                                $oSi->Lines->UserFields->Fields["U_Weight4"]->Value=$value[16];
//                                                $oSi->Lines->UserFields->Fields["U_Weight5"]->Value=$value[17];
//                                                $oSi->Lines->UserFields->Fields["U_Weight6"]->Value=$value[18];
//                                                $oSi->Lines->UserFields->Fields["U_Weight7"]->Value=$value[19];
                        $oSi->Lines->Add();
                        //End Insert None Free Text
                    } else {
                        //Set Current Line
                        if ($value[11] != '') {
                            $oSi->SpecialLines->SetCurrentLine($value[11]);
                        }
                        //End Set Current Line
                        //$value[0] - LineText
                        //Insert Item Type Details Free Text

                        $oSi->SpecialLines->LineType = 0;
                        $oSi->SpecialLines->AfterLineNumber = $ctr;
                        $oSi->SpecialLines->LineText = $value[0];
                        $oSi->SpecialLines->Add();

                        //End Insert Item Type Details Free Text
                    }//End Check Free Text
                } else {

                    if ($value[6] != '') {
                        $oDr->Lines->SetCurrentLine($value[6]);
                    }

                    //$value[0] - Remarks
                    //$value[1] - Account Code
                    //$value[2] - Price
                    //$value[3] - taxcode
                    //$value[4] - grossprice
                    //$value[5] - taxamount
                    //$value[6] - LineNo
                    //Catch Blank Numerics
                    $value[2] = $value[2] == '' ? 0 : $value[2];
                    $value[4] = $value[4] == '' ? 0 : $value[4];
                    $value[5] = $value[5] == '' ? 0 : $value[5];
                    //End Catch Blank Numerics
                    //Insert Service Type Details


                    $oSi->Lines->ItemDescription = $value[0];
                    $oSi->Lines->AccountCode = $value[1];
                    //$oSi->Lines->UnitPrice = $value[2];
                    $oSi->Lines->PriceAfterVAT = $value[4];
                    $oSi->Lines->VatGroup = $value[3];
                    $oSi->Lines->Add();
                    //End Insert Service Type Details
                } //End Check Service Type
            }
        } //End if
        //End Insert Details
        //Add PO
        $retval = $oSi->Update();
        //$vCmp->GetNewObjectCode($docentry);

        if ($retval != 0) {
            $errmsg .= $vCmp->GetLastErrorDescription;
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
    echo 'true*Successfully Updated SI ' . $docno;
} else {
    echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);
?>