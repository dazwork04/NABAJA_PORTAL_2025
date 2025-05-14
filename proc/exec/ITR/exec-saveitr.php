<?php
include_once('../../../config/config.php');

$docentry = '';
$docno = '';
$err = 0;
$errmsg = '';

$basentry = $_POST['basentry'];
$bpcode = $_POST['bpcode'];
$bpname = $_POST['bpname'];
$postingdate = $_POST['postingdate'];
$duedate = $_POST['duedate'];
$documentdate = $_POST['documentdate'];
$fromwarehouse = $_POST['fromwarehouse'];
$towarehouse = $_POST['towarehouse'];
$salesemployee = $_POST['salesemployee'];
$pickandpackremarks = $_POST['pickandpackremarks'];
$journalremarks = $_POST['journalremarks'];
$remarks = $_POST['remarks'];

$series = $_POST['series'];
$bplid = $_POST['bplid'];


$json = $_POST['json'];
$json2 = $_POST['json'];

if ($err == 0) 
{

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

        $oItr = $vCmp->GetBusinessObject(1250000001);
        $oItr->CardCode = $bpcode;
        $oItr->TaxDate = $postingdate;
        $oItr->DueDate = $duedate;
        $oItr->DocDate = $documentdate;

        if(isset($fromwarehouse) && $fromwarehouse!='' && $fromwarehouse!='null')
        {
            $oItr->FromWarehouse = $fromwarehouse;
        }
		
        if(isset($towarehouse) && $towarehouse!='' && $towarehouse!='null')
        {
            $oItr->ToWarehouse = $towarehouse;            
        }
        
        $oItr->SalesPersonCode = $salesemployee;
        $oItr->JournalMemo = $journalremarks;
        $oItr->Comments = $remarks;

        $oItr->Series = $series;

        if (json_decode($json) != null)
		{

            $json = json_decode($json, true);
            $ctr = -1;
            foreach ($json as $key => $value) 
			{
                $value[1] = $value[1] == '' ? 0 : $value[1];
				
				$oItr->Lines->ItemCode = $value[0];
                $oItr->Lines->FromWarehouseCode = $value[3];
                $oItr->Lines->WarehouseCode = $value[4];
                $oItr->Lines->Quantity = $value[1];
                $oItr->Lines->Add();

                $ctr += 1;
            }
        }

        $retval = $oItr->Add();
        $vCmp->GetNewObjectCode($docentry);

        if ($retval != 0) 
		{
            $errmsg .= $vCmp->GetLastErrorDescription;
            $err += 1;
        }
		else 
		{
			
            
        }
    } // End if DI API
}

if ($err == 0) {
    
    echo 'true*Successfully Added ITR # ' . $docentry;
} else {
    echo 'false*' . $errmsg;
}
odbc_commit($MSSQL_CONN);
odbc_close($MSSQL_CONN);
?>