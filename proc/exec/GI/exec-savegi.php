<?php

include_once('../../../config/config.php');

//Variables
//Header
$docentry = '';
$docno = '';
$err = 0;
$errmsg = '';

$basentry = $_POST['basentry'];
$issuetype = $_POST['issuetype'];
$ref2 = $_POST['ref2'];
$postingdate = $_POST['postingdate'];
$documentdate = $_POST['documentdate'];
$pricelist = $_POST['pricelist'];
$remarks = $_POST['remarks'];
$journalremarks = $_POST['journalremarks'];
$rgoods = $_POST['rgoods'];
$soref = $_POST['soref'];
$series = $_POST['series'];
$bplid = $_POST['bplid'];

$servicetype = $_POST['servicetype'];

//End Header
//Details
$json = $_POST['json'];
//End Details
//End Variables


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

if ($lRetCode != 0) {
    $errmsg .= $vCmp->GetLastErrorDescription;
    $err += 1;
} else {




    //DI API HERE
    //=============================================================================================
    //Header
    $oGi = $vCmp->GetBusinessObject(60);
    $oGi->PaymentGroupCode = $pricelist;
    $oGi->DocDate = $documentdate;
    $oGi->TaxDate = $postingdate;
    $oGi->Reference2 = $ref2;
    $oGi->Comments = $remarks; //SET REMARKS
    $oGi->JournalMemo = $journalremarks;
	
    $oGi->Series = $series;
	
	if ($servicetype == 'I') {
        $oGi->DocType = 0;
    } else {
        $oGi->DocType = 1;
    }

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
                //End Catch Blank Numerics
                //If with base document
				
				/* if($value[12] == '') 
				{
					$cars = '';
				}
				else
				{
					$cars = explode(",", $value[12]);
				}
				
				if($cars != '') 
				{
					$arrlength = count($cars);
					for($x=0;$x<$arrlength;$x++)
						{
							$oGi->Lines->SerialNumbers->SystemSerialNumber = $cars[$x];
							$oGi->Lines->SerialNumbers->ManufacturerSerialNumber = "";
							$oGi->Lines->SerialNumbers->InternalSerialNumber = "";
							$oGi->Lines->SerialNumbers->SetCurrentLine($x);
							$oGi->Lines->SerialNumbers->Add();
							
						}
				} */

                $oGi->Lines->ItemCode = $value[0];
                $oGi->Lines->Quantity = $value[1];
                $oGi->Lines->UnitPrice = $value[2];
				$oGi->Lines->WarehouseCode = $value[3];
				$oGi->Lines->AccountCode = $value[13];

                $oGi->Lines->Add();
            } //End Check Service Type
        }
    } //End if
    //End Insert Details
    //Add PO
    $retval = $oGi->Add();
    $vCmp->GetNewObjectCode($docentry);

    if ($retval != 0) {
        $errmsg .= $vCmp->GetLastErrorDescription;
        $err += 1;
    } else {

    }
    
} // End if DI API



if ($err == 0) {
    //odbc_commit($MSSQL_CONN);
    echo 'true*Successfully Added GI #' . $docentry;
} else {
    echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);
?>