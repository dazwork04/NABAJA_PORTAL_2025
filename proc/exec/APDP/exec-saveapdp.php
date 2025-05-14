<?php

include_once('../../../config/config.php');

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
	$remarks = $_POST['remarks'];
	$discPercent = $_POST['discPercent'];
	$tpaymentdue = $_POST['tpaymentdue'];
	$series = $_POST['series'];
	$bplid = $_POST['bplid'];
	$salesemployee = $_POST['salesemployee'];
	$owner = $_POST['owner'];
	$txtDocRef = $_POST['txtDocRef'];
	
	$selDocCur = $_POST['selDocCur'];
	$selCurSource = $_POST['selCurSource'];
	$txtDocRate = $_POST['txtDocRate'];
	
	$shipto = $_POST['shipto'];
	$billto = $_POST['billto'];

	$servicetype = $_POST['servicetype'];
	
	$json = $_POST['json'];
	$json2 = $_POST['json'];
	$json3 = $_POST['json1'];

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
	}else{

		$aPDP = $vCmp->GetBusinessObject(112);
		$aPDP->DownPaymentType = 1; 
		$aPDP->DocObjectCode = 204;
		$aPDP->CardCode = $vendor;
		$aPDP->DocDate = $postingdate;
		$aPDP->DocDueDate = $deliverydate;
		$aPDP->TaxDate = $documentdate;
		$aPDP->DiscountPercent = ($discPercent == '')? 0 : $discPercent;
		$aPDP->PaymentGroupCode = $paymentterms;
		$aPDP->Comments = $remarks; //SET REMARKS
		$aPDP->NumAtCard = $numatcard;
		
		if (isset($shipto) && $shipto != '')
        {
            $aPDP->Address2 = $shipto;
        }
		
		if (isset($billto) && $billto != '')
        {
            $aPDP->Address = $billto;
        }
		
		$aPDP->SalesPersonCode  = $salesemployee;
		if (isset($owner) && $owner != '')
        {
            $aPDP->DocumentsOwner = $owner;
        }

		$aPDP->Series = $series;
		//$oPch->BPL_IDAssignedToInvoice = $bplid;

		//$oPch->DocTotal = ($tpaymentdue == '')? 0 : $tpaymentdue;
		if($selDocCur != '') 
		{
		if($selDocCur != 'PHP') 
			{
				$aPDP->DocRate = $txtDocRate;
				
				$aPDP->DocCurrency = $selDocCur;
			}
		}
		if($servicetype == 'I'){
			$aPDP->DocType = 0;
		}else{
			$aPDP->DocType = 1;
		}
		
		//End Header



		//Insert Details
		if (json_decode($json) != null){

			//DECODE JSON
			$json = json_decode($json, true);
			$ctr = -1;
			foreach ($json as $key => $value) 
			{
				//Check Service Type
				if($servicetype == 'I'){
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
					if ($basentry != '' && $value[11] != '')
					{
						if($value[10] == 'N')
						{
							$aPDP->Lines->BaseEntry = $basentry;
							$aPDP->Lines->BaseLine = $value[11];
							$aPDP->Lines->BaseType = 22;

							$aPDP->Lines->ItemCode = $value[0];
							$aPDP->Lines->Quantity = $value[1]; //change to inventory
							$aPDP->Lines->UnitPrice = 0;
							$aPDP->Lines->DiscountPercent = 0;
							$aPDP->Lines->PriceAfterVAT = 0;
							$aPDP->Lines->WarehouseCode = $value[3];
							$aPDP->Lines->ItemDetails = $value[13];
							$aPDP->Lines->WTLiable = $value[18];

							if($value[14] != '') //Department
							{
								$aPDP->Lines->CostingCode = $value[14];
							}
							
							if($value[15] != '') //Project
							{
								$aPDP->Lines->ProjectCode = $value[15];
							}
							
							if($value[16] != '') //Employee
							{
								$aPDP->Lines->CostingCode2 = $value[16];
							}

							if($value[17] != '') //Equipment
							{
								$aPDP->Lines->CostingCode3 = $value[17];
							}
							
							$aPDP->Lines->Add();
							
							$ctr += 1;
						}
						else
						{
							$aPDP->SpecialLines->LineType = 0;
					        $aPDP->SpecialLines->AfterLineNumber = $ctr;
					        $aPDP->SpecialLines->LineText = $value[0];
					        $aPDP->SpecialLines->Add();
						}
					}
					else
					{
						if($value[10] == 'N')
						{ 
							$aPDP->Lines->ItemCode = $value[0];
							$aPDP->Lines->Quantity = $value[1];
							$aPDP->Lines->UnitPrice = 0;
							$aPDP->Lines->DiscountPercent = 0;
							$aPDP->Lines->PriceAfterVAT = 0;							
							$aPDP->Lines->WarehouseCode = $value[3];
							$aPDP->Lines->VatGroup = $value[4];
							$aPDP->Lines->ItemDetails = $value[13];
							$aPDP->Lines->WTLiable = $value[18];
							
							if($value[14] != '') //Department
							{
								$aPDP->Lines->CostingCode = $value[14];
							}
							
							if($value[15] != '') //Project
							{
								$aPDP->Lines->ProjectCode = $value[15];
							}
							
							if($value[16] != '') //Employee
							{
								$aPDP->Lines->CostingCode2 = $value[16];
							}

							if($value[17] != '') //Equipment
							{
								$aPDP->Lines->CostingCode3 = $value[17];
							}

							$aPDP->Lines->Add();
							
							$ctr += 1;
							
						}
						else
						{
							$aPDP->SpecialLines->LineType = 0;
					        $aPDP->SpecialLines->AfterLineNumber = $ctr;
					        $aPDP->SpecialLines->LineText = $value[0];
					        $aPDP->SpecialLines->Add();
						}
					}
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
						$aPDP->Lines->BaseEntry = $basentry;
						$aPDP->Lines->BaseLine = $value[6];
						$aPDP->Lines->BaseType = 22;

						$aPDP->Lines->ItemDescription = $value[0];
		       			$aPDP->Lines->AccountCode = $value[1];
						$aPDP->Lines->PriceAfterVAT = $value[4];
		       			$aPDP->Lines->VatGroup = $value[3];
						$aPDP->Lines->WTLiable = $value[11];

						//---- OcrCodes ----//
						if($value[7] != '') //Department
						{
							$aPDP->Lines->CostingCode = $value[7];
						}
						
						if($value[8] != '') //Project
						{
							$aPDP->Lines->ProjectCode = $value[8];
						}
						
						if($value[9] != '') //Employee
						{
							$aPDP->Lines->CostingCode2 = $value[9];
						}

						if($value[10] != '') //Equipment
						{
							$aPDP->Lines->CostingCode3 = $value[10];
						}

		       			$aPDP->Lines->Add(); 
						
					}
					else
					{
						$aPDP->Lines->ItemDescription = $value[0];
		       			$aPDP->Lines->AccountCode = $value[1];
		       			$aPDP->Lines->PriceAfterVAT = $value[4];
		       			$aPDP->Lines->VatGroup = $value[3];
						$aPDP->Lines->WTLiable = $value[11];

						//---- OcrCodes ----//
						if($value[7] != '') //Department
						{
							$aPDP->Lines->CostingCode = $value[7];
						}
						
						if($value[8] != '') //Project
						{
							$aPDP->Lines->ProjectCode = $value[8];
						}
						
						if($value[9] != '') //Employee
						{
							$aPDP->Lines->CostingCode2 = $value[9];
						}

						if($value[10] != '') //Equipment
						{
							$aPDP->Lines->CostingCode3 = $value[10];
						}
					
		       			$aPDP->Lines->Add(); 
						//End Insert Service Type Details
					}
				} //End Check Service Type
			}

		} //End if
		//End Insert Details
		
		if(count(json_decode($json3,1)) !=0) 
		{
			if(json_decode($json3) != null) 
			{
				$json3 = json_decode($json3, true);
				$linenum = 0;
				foreach ($json3 as $key => $value2) 
				{
					$aPDP->WithholdingTaxData->WTCode = $value2[0];
					$aPDP->WithholdingTaxData->Add();
				}
			} 
		}

		//Add APDP
		$retval = $aPDP->Add();
		
		if ($retval != 0) 
		{
			$errmsg .= $vCmp->GetLastErrorDescription;
			$err += 1;
		}
		else
		{
			$vCmp->GetNewObjectCode($docentry);
			$aPDP1 = $vCmp->GetBusinessObject(112);
			$aPDP1->GetByKey($docentry);
			
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
						
						$aPDP1->Lines->SetCurrentLine($a);
						$aPDP1->Lines->ItemCode = $value1[0];
						
						if($value1[5] == 0)
						{
							$aPDP1->Lines->PriceAfterVAT = $value1[6];
						}
						else
						{
							$aPDP1->Lines->UnitPrice = $value1[2];
							$aPDP1->Lines->DiscountPercent = $value1[5];
						}
						
						$a++;
					}
				}
			}
			
			$aPDP1->Update();
			$retval = $aPDP1->SaveDraftToDocument();
			$vCmp->GetNewObjectCode($docentry);
			if ($retval != 0) 
			{
				$errmsg .= $vCmp->GetLastErrorDescription;
				$err += 1;
			}
			else
			{
				$aPDP1->Remove();
				
			}
		}
	}

if($err == 0){
	//odbc_commit($MSSQL_CONN);
	echo 'true*Successfully Added A/P Down Payment ' . $docentry;
}else{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>