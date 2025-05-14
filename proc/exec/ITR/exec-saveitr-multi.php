<?php

ini_set('max_execution_time', 60000);
include_once('../../../config/config.php');

//Variables
//Header
$docentry = '';
$docno = '';
$err = 0;
$errmsg = '';

$basentry = $_POST['basentry'];
$requestertype = $_POST['requestertype'];
$requester = $_POST['requester'];
$requestername = $_POST['requestername'];
$branch = $_POST['branch'];
$department = $_POST['department'];
$postingdate = $_POST['postingdate'];
$documentdate = $_POST['documentdate'];
$validuntildate = $_POST['validuntildate'];
$requireddate = $_POST['requireddate'];
$requestingbusinessunit = $_POST['requestingbusinessunit'];
$remarks = $_POST['remarks'];
$discPercent = $_POST['discPercent'];
$tpaymentdue = $_POST['tpaymentdue'];

$series = $_POST['series'];
$bplid = $_POST['bplid'];

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
$qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['MDdb'] . "]; SELECT T0.DocNum,T0.DocStatus FROM [@OPRQ] T0
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
        $oItr = $vCmp->GetBusinessObject(1470000113);
//                echo 'after get bo';
        if ($requestertype == "1") { //user
            $oItr->ReqType = 12;
        }if ($requestertype == "2") { //employee
            $oItr->ReqType = 171;
        }

        $oItr->Requester = $requester;
        $oItr->RequesterName = $requestername;
//                $oItr->Branch = $branch;
//                $oItr->Department = $department;
//        $oItr->Email = 'test@email.com';
        $oItr->TaxDate = $postingdate;
        $oItr->DocDueDate = $validuntildate;
        $oItr->DocDate = $documentdate;
        $oItr->RequriedDate = $requireddate;
        $oItr->UserFields->Fields["U_REQBSUNIT"]->Value = $requestingbusinessunit;

        $oItr->DiscountPercent = ($discPercent == '') ? 0 : $discPercent;
        $oItr->Comments = $remarks; //SET REMARKS

        $oItr->Series = $series;
        //$oItr->BPL_IDAssignedToInvoice = $bplid;
        //$oItr->DocTotal = ($tpaymentdue == '')? 0 : $tpaymentdue;

        if ($servicetype == 'I') {
            $oItr->DocType = 0;
        } else {
            $oItr->DocType = 1;
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
                        //Insert None Free Text
                        $oItr->Lines->ItemCode = $value[0];
                        //$oItr->Lines->UnitPrice = $value[2];
                        $oItr->Lines->PriceAfterVAT = $value[6];
                        $oItr->Lines->Quantity = $value[1]; //change to inventory

                        $oItr->Lines->WarehouseCode = $value[3];
                        $oItr->Lines->VatGroup = $value[4];
                        $oItr->Lines->DiscountPercent = $value[5];

                        //New fields 20170513
                        $oItr->Lines->BarCode = $value[12];
                        $oItr->Lines->UserFields->Fields["U_Weight1"]->Value = $value[13];
                        $oItr->Lines->UserFields->Fields["U_PricePerKG"]->Value = $value[14];
                        if (isset($value[4])) {
//                            $oItr->Lines->CostingCode = $value[4];
                        }
                        if (isset($value[5])) {
//                            $oItr->Lines->CostingCode5 = $value[5];
                        }
//                                                $oItr->Lines->UserFields->Fields["U_Weight2"]->Value=$value[14];
//                                                $oItr->Lines->UserFields->Fields["U_Weight3"]->Value=$value[15];
//                                                $oItr->Lines->UserFields->Fields["U_Weight4"]->Value=$value[16];
//                                                $oItr->Lines->UserFields->Fields["U_Weight5"]->Value=$value[17];
//                                                $oItr->Lines->UserFields->Fields["U_Weight6"]->Value=$value[18];
//                                                $oItr->Lines->UserFields->Fields["U_Weight7"]->Value=$value[19];

                        $oItr->Lines->Add();
                        //End Insert None Free Text
                        //Update PostedQty
                        $qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['MDdb'] . "]; UPDATE [@PRQ1] SET PostedQty = PostedQty + " . $value[1] . " WHERE DocEntry = '{$value[17]}' AND LineNum = '" . $value[11] . "' AND LineStatus='O' AND ItemCode = '{$value[0]}' ");
                        //ENd Update Posted Qty
                    } else {
                        //$value[0] - LineText
                        //Insert Item Type Details Free Text

                        $oItr->SpecialLines->LineType = 0;
                        $oItr->SpecialLines->AfterLineNumber = $ctr;
                        $oItr->SpecialLines->LineText = $value[0];
                        $oItr->SpecialLines->Add();

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


                    $oItr->Lines->ItemDescription = $value[0];
                    $oItr->Lines->AccountCode = $value[1];
                    //$oItr->Lines->UnitPrice = $value[2];
                    $oItr->Lines->PriceAfterVAT = $value[4];
                    $oItr->Lines->VatGroup = $value[3];
                    $oItr->Lines->Add();
                    //End Insert Service Type Details
                    //Update PostedQty
                    $qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['MDdb'] . "]; UPDATE [@PRQ1] SET PostedQty = PostedQty + " . $value[2] . " WHERE DocEntry = '{$value[7]}' AND LineNum = '" . $value[6] . "' AND LineStatus='O'");
                    //ENd Update Posted Qty
                } //End Check Service Type
            }
        } //End if
        //End Insert Details
        //Add PO
        $retval = $oItr->Add();
        $vCmp->GetNewObjectCode($docentry);

        if ($retval != 0) {
            $errmsg .= $vCmp->GetLastErrorDescription;
            $err += 1;
        } else {

            if ($servicetype == 'I') {
                //Close Document if all lines are Closed
                $qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['MDdb'] . "]; UPDATE [@PRQ1] 
					SET LineStatus = 'C' WHERE DocEntry IN($basentry) AND PostedQty = Quantity");


                $qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['MDdb'] . "];  SELECT DISTINCT T0.DocEntry,T0.LineStatus,
																			 (
																				SELECT COUNT(*) FROM [@PRQ1] 
																				WHERE LineStatus = 'O' AND DocEntry = T0.DocEntry
																			 ) AS Res
																			 
																			  FROM [@PRQ1] T0 WHERE T0.DocEntry IN($basentry)");
                $dentry = '';
                while (odbc_fetch_row($qry)) {
                    $result = odbc_result($qry, 'Res');

                    if ($result == 0) {
                        $dentry .= odbc_result($qry, 'DocEntry') . ',';
                    }// End if
                }

                odbc_free_result($qry);
                $dentry = substr($dentry, 0, strlen($dentry) - 1);

                $dentry = ($dentry == '') ? 0 : $dentry;

                $qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['MDdb'] . "]; UPDATE [@OPRQ] SET DocStatus = 'C' WHERE DocEntry IN($dentry)");


                //ENd Close Document if all lines are Closed
            } else {

                //Close Document if all lines are Closed
                $qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['MDdb'] . "]; UPDATE [@PRQ1] 
					SET LineStatus = 'C' WHERE DocEntry IN('$basentry') AND PostedQty >= Price");

                $qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['MDdb'] . "];  SELECT DISTINCT T0.DocEntry,T0.LineStatus,
																			 (
																				SELECT COUNT(*) FROM [@PRQ1] 
																				WHERE LineStatus = 'O' AND DocEntry = T0.DocEntry
																			 ) AS Res
																			 
																			  FROM [@PRQ1] T0 WHERE AND T0.DocEntry IN($basentry)");


                $dentry = '';
                while (odbc_fetch_row($qry)) {
                    $result = odbc_result($qry, 'Res');

                    if ($result == 0) {
                        $dentry .= odbc_result($qry, 'DocEntry') . ',';
                    }// End if
                }

                odbc_free_result($qry);
                $dentry = substr($dentry, 0, strlen($dentry) - 1);
                $qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['MDdb'] . "]; UPDATE [@OPRQ] SET DocStatus = 'C' WHERE DocEntry IN($dentry)");
                //ENd Close Document if all lines are Closed
            }

            //Get DocNo
            $getDN = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "]; SELECT DocNum FROM OPRQ WHERE DocEntry = '$docentry'");
            odbc_fetch_row($getDN);
            $docno = odbc_result($getDN, 1);
            odbc_free_result($getDN);
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