<?php

include_once('../../../config/config.php');

	//Header
	$docentry = '';
	$docno = '';
	$err = 0;
	$errmsg = '';
	
	$basentry = $_POST['basentry'];
	$basentry1 = $_POST['basentry1'];
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
	$bplid = $_POST['bplid'];
	
	$selDocCur = $_POST['selDocCur'];
	$selCurSource = $_POST['selCurSource'];
	$txtDocRate = $_POST['txtDocRate'];
	
	$shipto = $_POST['shipto'];
	$sel_shipto = $_POST['sel_shipto'];
	$billto = $_POST['billto'];
	$sel_billto = $_POST['sel_billto'];
	
	$salesemployee = $_POST['salesemployee'];
	$owner = $_POST['owner'];

	$servicetype = $_POST['servicetype'];

	// UDFs
	$U_ContrctPrice = $_POST['U_ContrctPrice'];
	$U_Downpaymnt = $_POST['U_Downpaymnt'];
	$U_MiscFee = $_POST['U_MiscFee'];
	$U_ResrvationFee = $_POST['U_ResrvationFee'];
	$U_Realty = $_POST['U_Realty'];
	$U_SalesCoordinator = $_POST['U_SalesCoordinator'];
	
	$json = $_POST['json'];
	$json2 = $_POST['json'];
	
	$ctrbom = 0;
	$appbom = 0;
	
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

		//$oArdp = $vCmp->GetBusinessObject(13);
		$oArdp = $vCmp->GetBusinessObject(112);
		$oArdp->DocObjectCode = 203;
    $oArdp->DownPaymentType = 1; 

		$oArdp->CardCode = $vendor;
		$oArdp->DocDate = $postingdate;
		$oArdp->DocDueDate = $deliverydate;
		$oArdp->TaxDate = $documentdate;
		$oArdp->DiscountPercent = ($discPercent == '')? 0 : $discPercent;
		$oArdp->PaymentGroupCode = $paymentterms;
		$oArdp->Comments = $remarks; //SET REMARKS

		// UDFs
		$oArdp->UserFields->Fields["U_ContrctPrice"]->Value = $U_ContrctPrice;
		$oArdp->UserFields->Fields["U_Downpaymnt"]->Value = $U_Downpaymnt;
		$oArdp->UserFields->Fields["U_MiscFee"]->Value = $U_MiscFee;
		$oArdp->UserFields->Fields["U_ResrvationFee"]->Value = $U_ResrvationFee; 
    
		$oArdp->UserFields->Fields["U_Realty"]->Value = $U_Realty;
		$oArdp->UserFields->Fields["U_SalesCoordinator"]->Value = $U_SalesCoordinator;
		
		if (isset($numatcard) && $numatcard != '')
        {
			$oArdp->NumAtCard = $numatcard;
		}
		
		if (isset($shipto) && $shipto != '')
        {
            $oArdp->Address2 = $shipto;
			$oArdp->ShipToCode = $sel_shipto;
        } 
		
		if (isset($billto) && $billto != '')
        {
            $oArdp->Address = $billto;
            $oArdp->PayToCode = $sel_billto;
        } 
		
		$oArdp->SalesPersonCode = $salesemployee;
		if (isset($owner) && $owner != '')
        {
            $oArdp->DocumentsOwner = $owner;
        }
		
		if($selDocCur != '') 
		{
			if($selDocCur != 'PHP') 
				{
					$oArdp->DocRate = $txtDocRate;
					
					$oArdp->DocCurrency = $selDocCur;
				}
		}
		
		if (isset($series) && $series != '')
        {
		$oArdp->Series = $series;
		}
		
		if($servicetype == 'I')
		{
			$oArdp->DocType = 0;
		}
		else
		{
			$oArdp->DocType = 1;
		}
		
		
		//Insert Details
		if (json_decode($json) != null){

			//DECODE JSON
			$json = json_decode($json, true);
			$ctr = -1;
			foreach ($json as $key => $value) {
				
				//Check Service Type
				if($servicetype == 'I')
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
					//$value[12] - isFather
					//$value[13] - serialno
					//$value[14] - baseprice
					//$value[15] - barcode

					//Catch Blank Numerics
					$value[1] = $value[1] == '' ? 0 : $value[1];
					$value[2] = $value[2] == '' ? 0 : $value[2];
					$value[5] = $value[5] == '' ? 0 : $value[5];
					$value[6] = $value[6] == '' ? 0 : $value[6];
					$value[7] = $value[7] == '' ? 0 : $value[7];
					$value[8] = $value[8] == '' ? 0 : $value[8];
					$value[9] = $value[9] == '' ? 0 : $value[9];
					$value[15] = $value[15] == '' ? 0 : $value[15];
					//End Catch Blank Numerics
					
					// if ($basentry != '' && $value[11] != '')
					// {
					// 	$oArdp->Lines->BaseEntry = $basentry;
					// 	$oArdp->Lines->BaseLine = $value[11];
					// 	$oArdp->Lines->BaseType = 15;
					// }
					
					if ($basentry1 != '' && $value[11] != '')
					{
						$oArdp->Lines->BaseEntry = $basentry1;
						$oArdp->Lines->BaseLine = $value[11];
						$oArdp->Lines->BaseType = 17;
					}
					
					$oArdp->Lines->ItemCode = $value[0]; 
					$oArdp->Lines->Quantity = $value[1]; 
					$oArdp->Lines->UnitPrice = 0;
					$oArdp->Lines->DiscountPercent = 0;
					$oArdp->Lines->PriceAfterVAT = 0;
					$oArdp->Lines->WarehouseCode = $value[3];
					$oArdp->Lines->VatGroup = $value[4];

					if($value[16] != '') //Department
					{
						$oArdp->Lines->CostingCode = $value[16];
					}
					
					if($value[17] != '') //Project
					{
						$oArdp->Lines->ProjectCode = $value[17];
					}
					
					if($value[18] != '') //Employee
					{
						$oArdp->Lines->CostingCode2 = $value[18];
					}

					if($value[19] != '') //Equipment
					{
						$oArdp->Lines->CostingCode3 = $value[19];
					}

					$oArdp->Lines->Add();
					
				}
				else
				{
					//$value[0] - Remarks
					//$value[1] - Account Code
					//$value[2] - Price
					//$value[3] - taxcode
					//$value[4] - grossprice
					//$value[5] - taxamount
					//$value[6] - LineNo
					
					// if ($basentry != '' && $value[6] != '')
					// {
					// 	$oArdp->Lines->BaseEntry = $basentry;
					// 	$oArdp->Lines->BaseLine = $value[6];
					// 	$oArdp->Lines->BaseType = 15;
					// }
					
					if ($basentry1 != '' && $value[6] != '')
					{
						$oArdp->Lines->BaseEntry = $basentry1;
						$oArdp->Lines->BaseLine = $value[6];
						$oArdp->Lines->BaseType = 17;
					}

					$value[2] = $value[2] == '' ? 0 : $value[2];
					$value[4] = $value[4] == '' ? 0 : $value[4];
					$value[5] = $value[5] == '' ? 0 : $value[5];
					
					$oArdp->Lines->ItemDescription = $value[0];
					$oArdp->Lines->AccountCode = $value[1];
					$oArdp->Lines->UnitPrice = 0;
					$oArdp->Lines->DiscountPercent = 0;
					$oArdp->Lines->PriceAfterVAT = 0;
					$oArdp->Lines->VatGroup = $value[3];
								
					if($value[7] != '') //Department
					{
						$oArdp->Lines->CostingCode = $value[7];
					}
					
					if($value[8] != '') //Project
					{
						$oArdp->Lines->ProjectCode = $value[8];
					}
					
					if($value[9] != '') //Employee
					{
						$oArdp->Lines->CostingCode2 = $value[9];
					}

					if($value[10] != '') //Equipment
					{
						$oArdp->Lines->CostingCode3 = $value[10];
					}
					$oArdp->Lines->Add();
				}
			}
		}	

		$retval = $oArdp->Add();
		
		if ($retval != 0) 
		{
			$errmsg .= $vCmp->GetLastErrorDescription;
			$err += 1;
		}
		else
		{
			$vCmp->GetNewObjectCode($docentry);
			$oInv1 = $vCmp->GetBusinessObject(112);
			$oInv1->GetByKey($docentry);
			
			if($servicetype == 'I')
			{
				if (json_decode($json2) != null)
				{
					$json2 = json_decode($json2, true);
			
					$a = 0;
					foreach ($json2 as $key => $value1) 
					{
						$value1[2] = $value1[2] == '' ? 0 : $value1[2];
						$value1[5] = $value1[5] == '' ? 0 : $value1[5];
						$value1[6] = $value1[6] == '' ? 0 : $value1[6];
						
						$oInv1->Lines->SetCurrentLine($a);
						$oInv1->Lines->ItemCode = $value1[0];
						
						if($value1[5] == 0)
						{
							$oInv1->Lines->PriceAfterVAT = $value1[6];
						}
						else
						{
							$oInv1->Lines->UnitPrice = $value1[2];
							$oInv1->Lines->DiscountPercent = $value1[5];
						}
						
						$a++;
					}
				}
			}
			else
			{
				if (json_decode($json2) != null)
				{
					$json2 = json_decode($json2, true);
				
					$a = 0;
					foreach ($json2 as $key => $value1) 
					{
						$value1[4] = $value1[4] == '' ? 0 : $value1[4];
						
						$oInv1->Lines->SetCurrentLine($a);
						$oInv1->Lines->ItemDescription = $value1[0];
						
						$oInv1->Lines->PriceAfterVAT = $value1[4]; 
						
						$a++;
					}
				}
			}
			
			$oInv1->Update();
			$retval = $oInv1->SaveDraftToDocument();
			if ($retval != 0) 
			{
				$errmsg .= $vCmp->GetLastErrorDescription;
				$err += 1;
			}
			else
			{
				$oInv1->Remove();
			}
		}
	}

if($err == 0)
{
	echo 'true*Successfully Added A/R Down Payment'; 
}
else
{
	echo 'false*' . $errmsg;
}

odbc_close($MSSQL_CONN);


?>