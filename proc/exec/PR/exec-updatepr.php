<?php
//ini_set('max_execution_time', 6000);
include_once('../../../config/config.php');

//Variables
//Header
$docentry = $_POST['docentry'];
$docno = '';
$err = 0;
$errmsg = '';
$curr = '';

$basentry = $_POST['basentry'];
$requestertype = $_POST['requestertype'];
$requester = $_POST['requester'];
$requestername = $_POST['requestername'];

$postingdate = $_POST['postingdate'];
$documentdate = $_POST['documentdate'];
$validuntildate = $_POST['validuntildate'];
$requireddate = $_POST['requireddate'];

$remarks = $_POST['remarks'];
$discPercent = $_POST['discPercent'];
$tpaymentdue = $_POST['tpaymentdue'];

$owner = $_POST['owner'];
$txtDocRef = $_POST['txtDocRef'];

$series = $_POST['series'];
$bplid = $_POST['bplid'];

//$bplid = $_POST['bplid'];

$servicetype = $_POST['servicetype'];
//End Header
//Details
$json = $_POST['json'];
//End Details

if ($err == 0) {

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

        $oPrq = $vCmp->GetBusinessObject(1470000113);
        $oPrq->GetByKey($docentry);
        
		if ($requestertype == "1") 
		{
            $oPrq->ReqType = 12;
        }
		if ($requestertype == "2") 
		{
            $oPrq->ReqType = 171;
        }

        $oPrq->Requester = $requester;
        $oPrq->RequesterName = $requestername;
        $oPrq->DocDate = $postingdate;
        $oPrq->DocDueDate = $validuntildate;
        $oPrq->TaxDate = $documentdate;
        $oPrq->RequriedDate = $requireddate;

        $oPrq->DiscountPercent = ($discPercent == '') ? 0 : $discPercent;
        $oPrq->Comments = $remarks; //SET REMARKS
		
		if (isset($owner) && $owner != '')
        {
            $oPrq->DocumentsOwner = $owner;
        }
		
		//$oPrq->UserFields->Fields["U_PRref"]->Value = $txtDocRef;
		
        //Insert Details
        if (json_decode($json) != null) 
		{

            //DECODE JSON
            $json = json_decode($json, true);
            $ctr = -1;
			$a = 0;
			$b = 0;
            foreach ($json as $key => $value) 
			{

                //Check Service Type
                if ($servicetype == 'I') 
				{
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
					//$curr = $value[12];

                    if ($value[10] == 'N') 
					{ // Check if Free Text
                        //catch blank only if item type
                        if ($value[11] != '') 
						{
                           $oPrq->Lines->SetCurrentLine($a);
                        }
						else
						{
							$oPrq->Lines->Add();
						}

                        //Insert None Free Text
                        $oPrq->Lines->ItemCode = $value[0];
                        
                        if($value[3] != '')
						{
							$oPrq->Lines->WarehouseCode = $value[3];
						}
						
						if($value[2] == 0)
						{
							$oPrq->Lines->UnitPrice = 0;
						}
						else
						{
							$oPrq->Lines->DiscountPercent = $value[5];
							$oPrq->Lines->UnitPrice = $value[2];
						}
                       
                        $oPrq->Lines->Quantity = $value[1];
                        $oPrq->Lines->VatGroup = $value[4];
						$oPrq->Lines->ItemDetails = $value[13];
						//$oPrq->Lines->Currency = $value[12];
                        
                       
                        //End Insert None Free Text
                    }
					else 
					{
                        //Set Current Line
                        if ($value[11] != '') 
						{
                            $oPrq->SpecialLines->SetCurrentLine($value[11]);
						}
						
                        $oPrq->SpecialLines->LineType = 0;
                        $oPrq->SpecialLines->AfterLineNumber = $ctr;
                        $oPrq->SpecialLines->LineText = $value[0];
                        $oPrq->SpecialLines->Add();

                        //End Insert Item Type Details Free Text
                    }//End Check Free Text
                } 
				else 
				{

                    if ($value[6] != '') 
					{
                        $oPrq->Lines->SetCurrentLine($a);
                    }
					else
					{
						$oPrq->Lines->Add();
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
                   
                    $oPrq->Lines->ItemDescription = $value[0];
                    $oPrq->Lines->AccountCode = $value[1];
					$oPrq->Lines->RequiredDate = $requireddate;
                    //$oPrq->Lines->UnitPrice = $value[2];
                    $oPrq->Lines->PriceAfterVAT = $value[4];
                    $oPrq->Lines->VatGroup = $value[3];
                    
                    //End Insert Service Type Details
                } //End Check Service Type
				
				$a++;
            }
        } //End if
        //End Insert Details
        //Add PO
        $retval = $oPrq->Update();
        //$vCmp->GetNewObjectCode($docentry);

        if ($retval != 0) 
		{
			$errmsg .= $vCmp->GetLastErrorDescription;
            $err += 1;
        }
		else 
		{
			
			
        }
        //End Add PO
        //END DI API HERE
        //=============================================================================================
    } // End if DI API
}


if ($err == 0) {
    odbc_commit($MSSQL_CONN);
    echo 'true*Successfully Updated PR # ' . $docentry;
} else {
	
    echo 'false*' . $errmsg;

}


odbc_close($MSSQL_CONN);
?>