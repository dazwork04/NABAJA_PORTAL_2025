<?php
include_once('../../../config/config.php');

	$docentry = $_POST['docentry'];
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
	$remarks = $_POST['remarks'];
	$discPercent = $_POST['discPercent'];
	$tpaymentdue = $_POST['tpaymentdue'];
	$series = $_POST['series'];
	
	$shipto = $_POST['shipto'];
	$billto = $_POST['billto'];
	
	$selDocCur = $_POST['selDocCur'];
	$selCurSource = $_POST['selCurSource'];
	$txtDocRate = $_POST['txtDocRate'];

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
	$json1 = $_POST['json'];

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

		$oSo = $vCmp->GetBusinessObject(17);
		$oSo->GetByKey($docentry);
		
		$oSo->DocDate = $postingdate;
		$oSo->DocDueDate = $deliverydate;
		$oSo->TaxDate = $documentdate;
		$oSo->DiscountPercent = ($discPercent == '')? 0 : $discPercent;
		$oSo->PaymentGroupCode = $paymentterms;
		$oSo->Comments = $remarks; //SET REMARKS
		$oSo->NumAtCard = $numatcard;

		// UDFs
		$oSo->UserFields->Fields["U_ContrctPrice"]->Value = $U_ContrctPrice;
		$oSo->UserFields->Fields["U_Downpaymnt"]->Value = $U_Downpaymnt;
		$oSo->UserFields->Fields["U_MiscFee"]->Value = $U_MiscFee;
		$oSo->UserFields->Fields["U_ResrvationFee"]->Value = $U_ResrvationFee;
    
		$oSo->UserFields->Fields["U_Realty"]->Value = $U_Realty;
		$oSo->UserFields->Fields["U_SalesCoordinator"]->Value = $U_SalesCoordinator;
		
		if ($contactperson != '')
        {
            $oSo->ContactPersonCode = $contactperson;
        }
		
		if (isset($shipto) && $shipto != '')
        {
            $oSo->ShipToCode = $shipto;
        }
		
		if (isset($billto) && $billto != '')
        {
            $oSo->PayToCode = $billto;
        }
		
		$oSo->SalesPersonCode = $salesemployee;
		
		if (isset($owner) && $owner != '')
        {
            $oSo->DocumentsOwner = $owner;
        }
		
		if($selDocCur != '') 
		{
			if($selDocCur != 'PHP') 
			{
				$oSo->DocRate = $txtDocRate;
				$oSo->DocCurrency = $selDocCur;
			}
		}
			
		if (json_decode($json) != null)
		{
			$json = json_decode($json, true);
			$ctr = -1;
			$a = 0;
			$b = 0;
			foreach ($json as $key => $value) 
			{
				
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
					
					$value[1] = $value[1] == '' ? 0 : $value[1];
					$value[2] = $value[2] == '' ? 0 : $value[2];
					$value[5] = $value[5] == '' ? 0 : $value[5];
					$value[6] = $value[6] == '' ? 0 : $value[6];
					$value[7] = $value[7] == '' ? 0 : $value[7];
					$value[8] = $value[8] == '' ? 0 : $value[8];
					$value[9] = $value[9] == '' ? 0 : $value[9];
					//End Catch Blank Numerics

				
						if($value[11] != '')
						{
							$oSo->Lines->SetCurrentLine($a);
						}
						else
						{
							$oSo->Lines->Add();
						}
						
						$oSo->Lines->ItemCode = $value[0];
						$oSo->Lines->Quantity = $value[1];
						
						$oSo->Lines->PriceAfterVAT = 0;
						$oSo->Lines->UnitPrice = 0;
						$oSo->Lines->DiscountPercent = 0;
							
						$oSo->Lines->WarehouseCode = $value[3];
						$oSo->Lines->VatGroup = $value[4];
						$oSo->Lines->DiscountPercent = $value[5];

						if($value[13] != '') //Department
						{
							$oSo->Lines->CostingCode = $value[13];
						}
						
						if($value[14] != '') //Project
						{
							$oSo->Lines->ProjectCode = $value[14];
						}
						
						if($value[15] != '') //Employee
						{
							$oSo->Lines->CostingCode3 = $value[15];
						}

						if($value[16] != '') //Equipment
						{
							$oSo->Lines->CostingCode4 = $value[16];
						}
						
						$a++;
				}
				else
				{
					
					if($value[6] != '')
					{
						$oSo->Lines->SetCurrentLine($b);
					}
					else
					{
						$oSo->Lines->Add();
					}
					
					//$value[0] - Remarks
					//$value[1] - Account Code
					//$value[2] - Price
					//$value[3] - taxcode
					//$value[4] - grossprice
					//$value[5] - taxamount
					//$value[6] - LineNo

					$value[2] = $value[2] == '' ? 0 : $value[2];
					$value[4] = $value[4] == '' ? 0 : $value[4];
					$value[5] = $value[5] == '' ? 0 : $value[5];
				
					$oSo->Lines->ItemDescription = $value[0];
	       			$oSo->Lines->AccountCode = $value[1];
	       			$oSo->Lines->UnitPrice = 0;
					$oSo->Lines->DiscountPercent = 0;
					$oSo->Lines->PriceAfterVAT = 0;
	       			$oSo->Lines->VatGroup = $value[3];

					if($value[7] != '') //Department
					{
						$oSo->Lines->CostingCode = $value[7];
					}
					
					if($value[8] != '') //Project
					{
						$oSo->Lines->ProjectCode = $value[8];
					}
					
					if($value[9] != '') //Employee
					{
						$oSo->Lines->CostingCode3 = $value[9];
					}

					if($value[10] != '') //Equipment
					{
						$oSo->Lines->CostingCode4 = $value[10];
					}
					
					$b++;
	       		}
			}
		}
		
		$retval = $oSo->Update();
		
		if ($retval != 0) 
		{
			$errmsg .= $vCmp->GetLastErrorDescription;
			$err += 1;
		}
		else
		{
			$vCmp->GetNewObjectCode($docentry);
			
			$oRdr = $vCmp->GetBusinessObject(17);
			$oRdr->GetByKey($docentry);
			
			if ($servicetype == 'I')
			{
				if (json_decode($json1) != null)
				{
					$json1 = json_decode($json1, true);
				
					$a = 0;
					foreach ($json1 as $key => $value1) 
					{
						$value1[2] = $value1[2] == '' ? 0 : $value1[2];
						$value1[5] = $value1[5] == '' ? 0 : $value1[5];
						$value1[6] = $value1[6] == '' ? 0 : $value1[6];
						
						$oRdr->Lines->SetCurrentLine($a);
						$oRdr->Lines->ItemCode = $value1[0];
						
						if($value1[5] == 0)
						{
							$oRdr->Lines->PriceAfterVAT = $value1[6];
						}
						else
						{
							$oRdr->Lines->UnitPrice = $value1[2];
							$oRdr->Lines->DiscountPercent = $value1[5];
						}
						
						$a++;
					}
				}
			}
			else
			{
				if (json_decode($json1) != null)
				{
					$json1 = json_decode($json1, true);
				
					$a = 0;
					foreach ($json1 as $key => $value1) 
					{
						$value1[4] = $value1[4] == '' ? 0 : $value1[4];
						
						$oRdr->Lines->SetCurrentLine($a);
						$oRdr->Lines->ItemDescription = $value1[0];
						
						$oRdr->Lines->PriceAfterVAT = $value1[4]; 
						
						$a++;
					}
				}
			}
			
			$retval = $oRdr->Update();
			
			if ($retval != 0) 
			{
				$errmsg .= $vCmp->GetLastErrorDescription;
				$err += 1;
			}
		}
	}



if($err == 0)
{
	echo 'true*Operation completed successfully.'; 
}
else
{
	echo 'false*' . $errmsg;
}

odbc_close($MSSQL_CONN);


?>