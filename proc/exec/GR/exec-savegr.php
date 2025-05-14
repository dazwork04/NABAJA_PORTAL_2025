<?php

include_once('../../../config/config.php');

//Variables
//Header
$docentry = '';
$docno = '';
$err = 0;
$errmsg = '';

$basentry = $_POST['basentry'];
$ref2 = $_POST['ref2'];
$postingdate = $_POST['postingdate'];
$documentdate = $_POST['documentdate'];
$pricelist = $_POST['pricelist'];
$remarks = $_POST['remarks'];
$journalremarks = $_POST['journalremarks'];

$series = $_POST['series'];
$bplid = $_POST['bplid'];

$servicetype = $_POST['servicetype'];

//End Header
//Details
$json = $_POST['json'];
$json2 = $_POST['json'];
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
    $oGr = $vCmp->GetBusinessObject(59);
    $oGr->PaymentGroupCode = $pricelist;
    $oGr->DocDate = $postingdate;
    $oGr->TaxDate = $documentdate;
    $oGr->Reference2 = $ref2;
    $oGr->Comments = $remarks; //SET REMARKS
    $oGr->JournalMemo = $journalremarks;
	
    //$oGr->Series = $series;
	
	if ($servicetype == 'I') {
        $oGr->DocType = 0;
    } else {
        $oGr->DocType = 1;
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
				
				$oGr->Lines->ItemCode = $value[0];
                $oGr->Lines->Quantity = $value[1];
                $oGr->Lines->UnitPrice = $value[2];
				$oGr->Lines->WarehouseCode = $value[3];
				$oGr->Lines->AccountCode = $value[13];

                $oGr->Lines->Add();
            } //End Check Service Type
        }
    } //End if
    //End Insert Details

    $retval = $oGr->Add();
    $vCmp->GetNewObjectCode($docentry);

    if ($retval != 0) {
        $errmsg .= $vCmp->GetLastErrorDescription;
        $err += 1;
    } else 
	{
		/* if($_SESSION['mssqldb'] == 'HIRAM_LIVE')
		{
			$_SESSION['mssqldb'] = '357TRADERS_LIVE';
		}
		else
		{
			$_SESSION['mssqldb'] = 'HIRAM_LIVE';
		}

		if (json_decode($json2) != null) 
		{
			$json2 = json_decode($json2, true);
			foreach ($json2 as $key => $value1) 
			{
				$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; UPDATE IGE1 SET LineStatus = 'C' WHERE DocEntry = $basentry AND ItemCode = '$value1[0]' AND LineNum = $value1[11] ");
			}
		} */
    }

} // End if DI API



if ($err == 0) {
    //odbc_commit($MSSQL_CONN);
    echo 'true*Successfully Added GR # ' . $docentry;
} else {
    echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);
?>