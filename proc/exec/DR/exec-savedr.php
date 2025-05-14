<?php

include_once('../../../config/config.php');

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
	$remarks = $_POST['remarks'];
	$discPercent = $_POST['discPercent'];
	$tpaymentdue = $_POST['tpaymentdue'];
	$series = $_POST['series'];
	$bplid = $_POST['bplid'];
	
	$selDocCur = $_POST['selDocCur'];
	$selCurSource = $_POST['selCurSource'];
	$txtDocRate = $_POST['txtDocRate'];
	
	$salesemployee = $_POST['salesemployee'];
	$owner = $_POST['owner'];
	
	$servicetype = $_POST['servicetype'];
	
	$shipto = $_POST['shipto'];
	$billto = $_POST['billto'];

	$json = $_POST['json'];
	$json2 = $_POST['json'];
	
	$ctrbom = 0;
	
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
		
		if($ctrbom == 0)
		{
			//If No Bom, Document is set to Delivery
			//$oDr = $vCmp->GetBusinessObject(15);
			$oDr = $vCmp->GetBusinessObject(112);
			$oDr->DocObjectCode = 15;
		}
		else
		{
			//If with Bom Document is set to Draft
			$oDr = $vCmp->GetBusinessObject(112);
			$oDr->DocObjectCode = 15;
		}

		$oDr->CardCode = $vendor;
		$oDr->DocDate = $postingdate;
		$oDr->DocDueDate = $deliverydate;
		$oDr->TaxDate = $documentdate;
		$oDr->DiscountPercent = ($discPercent == '')? 0 : $discPercent;
		$oDr->PaymentGroupCode = $paymentterms;
		$oDr->Comments = $remarks;
        $oDr->NumAtCard = $numatcard;
		$oDr->SalesPersonCode  = $salesemployee;
	
		if (isset($owner) && $owner != '')
        {
            $oDr->DocumentsOwner = $owner;
        }
		
		if($selDocCur != '') 
		{
		if($selDocCur != 'PHP') 
			{
				$oDr->DocRate = $txtDocRate;
				
				$oDr->DocCurrency = $selDocCur;
			}
		}

		$oDr->Series = $series;

		if($servicetype == 'I')
		{
			$oDr->DocType = 0;
		}
		else
		{
			$oDr->DocType = 1;
		}
		
		if (isset($shipto) && $shipto != '')
        {
            $oDr->ShipToCode = $shipto;
        }
		
		if (isset($billto) && $billto != '')
        {
            $oDr->PayToCode = $billto;
        }
		
		if (json_decode($json) != null)
		{
			//DECODE JSON
			$json = json_decode($json, true);
			$ctr = -1;
			foreach ($json as $key => $value) 
			{
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

					//Catch Blank Numerics
					$value[1] = $value[1] == '' ? 0 : $value[1];
					$value[2] = $value[2] == '' ? 0 : $value[2];
					$value[5] = $value[5] == '' ? 0 : $value[5];
					$value[6] = $value[6] == '' ? 0 : $value[6];
					$value[7] = $value[7] == '' ? 0 : $value[7];
					$value[8] = $value[8] == '' ? 0 : $value[8];
					$value[9] = $value[9] == '' ? 0 : $value[9];
					//End Catch Blank Numerics

					if($basentry != '' && $value[11] != '')
					{
						$ctr += 1;
						$oDr->Lines->BaseEntry = $basentry;
						$oDr->Lines->BaseLine = $value[11];
						$oDr->Lines->BaseType = 17;
					}
					
					$oDr->Lines->ItemCode = $value[0];
					$oDr->Lines->Quantity = $value[1];
					$oDr->Lines->UnitPrice = 0;
					$oDr->Lines->DiscountPercent = 0;
					$oDr->Lines->PriceAfterVAT = 0;
					$oDr->Lines->VatGroup = $value[4];
					$oDr->Lines->WarehouseCode = $value[3];

					if($value[14] != '') //Department
					{
						$oDr->Lines->CostingCode = $value[14];
					}
					
					if($value[15] != '') //Project
					{
						$oDr->Lines->ProjectCode = $value[15];
					}
					
					if($value[16] != '') //Employee
					{
						$oDr->Lines->CostingCode3 = $value[16];
					}

					if($value[17] != '') //Equipment
					{
						$oDr->Lines->CostingCode4 = $value[17];
					}

					$oDr->Lines->Add();
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

					$value[2] = $value[2] == '' ? 0 : $value[2];
					$value[4] = $value[4] == '' ? 0 : $value[4];
					$value[5] = $value[5] == '' ? 0 : $value[5];

					if ($basentry != '' && $value[6] != '')
					{
						$oDr->Lines->BaseEntry = $basentry;
						$oDr->Lines->BaseLine = $value[6];
						$oDr->Lines->BaseType = 17;
					}
					
					$oDr->Lines->ItemDescription = $value[0];
					$oDr->Lines->AccountCode = $value[1];
					$oDr->Lines->PriceAfterVAT = $value[4];
					$oDr->Lines->VatGroup = $value[3];

					if($value[7] != '') //Department
					{
						$oDr->Lines->CostingCode = $value[7];
					}
					
					if($value[8] != '') //Project
					{
						$oDr->Lines->ProjectCode = $value[8];
					}
					
					if($value[9] != '') //Employee
					{
						$oDr->Lines->CostingCode3 = $value[9];
					}

					if($value[10] != '') //Equipment
					{
						$oDr->Lines->CostingCode4 = $value[10];
					}

					$oDr->Lines->Add(); 
				}
			}
		}

		$retval = $oDr->Add();
		
		if ($retval != 0) 
		{
			$errmsg .= $vCmp->GetLastErrorDescription;
			$err += 1;
		}
		else
		{
			$vCmp->GetNewObjectCode($docentry);
			$oDln1 = $vCmp->GetBusinessObject(112);
			$oDln1->GetByKey($docentry);
			
			if ($servicetype == 'I')
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
						
						$oDln1->Lines->SetCurrentLine($a);
						$oDln1->Lines->ItemCode = $value1[0];
						
						if($value1[5] == 0)
						{
							$oDln1->Lines->PriceAfterVAT = $value1[6];
						}
						else
						{
							$oDln1->Lines->UnitPrice = $value1[2];
							$oDln1->Lines->DiscountPercent = $value1[5];
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
						
						$oDln1->Lines->SetCurrentLine($a);
						$oDln1->Lines->ItemDescription = $value1[0];
						
						$oDln1->Lines->PriceAfterVAT = $value1[4]; 
						
						$a++;
					}
				}
			}
			
			$oDln1->Update();
			
			$retval = $oDln1->SaveDraftToDocument();
			
			if ($retval != 0) 
			{
				$errmsg .= $vCmp->GetLastErrorDescription;
				$err += 1;
			}
			else
			{
				$oDln1->Remove();
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