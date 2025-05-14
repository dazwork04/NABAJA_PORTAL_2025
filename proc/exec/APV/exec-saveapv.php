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
	$txtCtlAcctCode = $_POST['txtCtlAcctCode'];
	$txtCtlAcctName = $_POST['txtCtlAcctName'];
	
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

		$oPch = $vCmp->GetBusinessObject(112);
		$oPch->DocObjectCode = 18;
		$oPch->CardCode = $vendor;
		$oPch->DocDate = $postingdate;
		$oPch->DocDueDate = $deliverydate;
		$oPch->TaxDate = $documentdate;
		$oPch->DiscountPercent = ($discPercent == '')? 0 : $discPercent;
		$oPch->PaymentGroupCode = $paymentterms;
		$oPch->Comments = $remarks; //SET REMARKS
		$oPch->NumAtCard = $numatcard;
		$oPch->ControlAccount = $txtCtlAcctCode;
		
		if (isset($shipto) && $shipto != '')
        {
            $oPch->Address2 = $shipto;
        }
		
		if (isset($billto) && $billto != '')
        {
            $oPch->Address = $billto;
        }
		
		$oPch->SalesPersonCode  = $salesemployee;
		if (isset($owner) && $owner != '')
        {
            $oPch->DocumentsOwner = $owner;
        }

		$oPch->Series = $series;
		//$oPch->BPL_IDAssignedToInvoice = $bplid;

		//$oPch->DocTotal = ($tpaymentdue == '')? 0 : $tpaymentdue;
		if($selDocCur != '') 
		{
		if($selDocCur != 'PHP') 
			{
				$oPch->DocRate = $txtDocRate;
				
				$oPch->DocCurrency = $selDocCur;
			}
		}
		if($servicetype == 'I'){
			$oPch->DocType = 0;
		}else{
			$oPch->DocType = 1;
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
							$oPch->Lines->BaseEntry = $basentry;
							$oPch->Lines->BaseLine = $value[11];
							$oPch->Lines->BaseType = 20;

							$oPch->Lines->ItemCode = $value[0];
							$oPch->Lines->Quantity = $value[1]; //change to inventory
							$oPch->Lines->UnitPrice = 0;
							$oPch->Lines->DiscountPercent = 0;
							$oPch->Lines->PriceAfterVAT = 0;
							$oPch->Lines->WarehouseCode = $value[3];
							$oPch->Lines->ItemDetails = $value[13];
							$oPch->Lines->WTLiable = $value[18];

							if($value[14] != '') //Department
							{
								$oPch->Lines->CostingCode = $value[14];
							}
							
							if($value[15] != '') //Project
							{
								$oPch->Lines->ProjectCode = $value[15];
							}
							
							if($value[16] != '') //Employee
							{
								$oPch->Lines->CostingCode2 = $value[16];
							}

							if($value[17] != '') //Equipment
							{
								$oPch->Lines->CostingCode3 = $value[17];
							}
							
							$oPch->Lines->Add();
							
							$ctr += 1;
						}
						else
						{
							$oPch->SpecialLines->LineType = 0;
					        $oPch->SpecialLines->AfterLineNumber = $ctr;
					        $oPch->SpecialLines->LineText = $value[0];
					        $oPch->SpecialLines->Add();
						}
					}
					else
					{
						if($value[10] == 'N')
						{ 
							$oPch->Lines->ItemCode = $value[0];
							$oPch->Lines->Quantity = $value[1];
							$oPch->Lines->UnitPrice = 0;
							$oPch->Lines->DiscountPercent = 0;
							$oPch->Lines->PriceAfterVAT = 0;							
							$oPch->Lines->WarehouseCode = $value[3];
							$oPch->Lines->VatGroup = $value[4];
							$oPch->Lines->ItemDetails = $value[13];
							$oPch->Lines->WTLiable = $value[18];
							
							if($value[14] != '') //Department
							{
								$oPch->Lines->CostingCode = $value[14];
							}
							
							if($value[15] != '') //Project
							{
								$oPch->Lines->ProjectCode = $value[15];
							}
							
							if($value[16] != '') //Employee
							{
								$oPch->Lines->CostingCode2 = $value[16];
							}

							if($value[17] != '') //Equipment
							{
								$oPch->Lines->CostingCode3 = $value[17];
							}

							$oPch->Lines->Add();
							
							$ctr += 1;
							
						}
						else
						{
							$oPch->SpecialLines->LineType = 0;
					        $oPch->SpecialLines->AfterLineNumber = $ctr;
					        $oPch->SpecialLines->LineText = $value[0];
					        $oPch->SpecialLines->Add();
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
						$oPch->Lines->BaseEntry = $basentry;
						$oPch->Lines->BaseLine = $value[6];
						$oPch->Lines->BaseType = 20;

						$oPch->Lines->ItemDescription = $value[0];
		       			$oPch->Lines->AccountCode = $value[1];
						$oPch->Lines->PriceAfterVAT = $value[4];
		       			$oPch->Lines->VatGroup = $value[3];
						$oPch->Lines->WTLiable = $value[11];

						//---- OcrCodes ----//
						if($value[7] != '') //Department
						{
							$oPch->Lines->CostingCode = $value[7];
						}
						
						if($value[8] != '') //Project
						{
							$oPch->Lines->ProjectCode = $value[8];
						}
						
						if($value[9] != '') //Employee
						{
							$oPch->Lines->CostingCode2 = $value[9];
						}

						if($value[10] != '') //Equipment
						{
							$oPch->Lines->CostingCode3 = $value[10];
						}

		       			$oPch->Lines->Add(); 
						
					}
					else
					{
						$oPch->Lines->ItemDescription = $value[0];
		       			$oPch->Lines->AccountCode = $value[1];
		       			$oPch->Lines->PriceAfterVAT = $value[4];
		       			$oPch->Lines->VatGroup = $value[3];
						$oPch->Lines->WTLiable = $value[11];

						//---- OcrCodes ----//
						if($value[7] != '') //Department
						{
							$oPch->Lines->CostingCode = $value[7];
						}
						
						if($value[8] != '') //Project
						{
							$oPch->Lines->ProjectCode = $value[8];
						}
						
						if($value[9] != '') //Employee
						{
							$oPch->Lines->CostingCode2 = $value[9];
						}

						if($value[10] != '') //Equipment
						{
							$oPch->Lines->CostingCode3 = $value[10];
						}
					
		       			$oPch->Lines->Add(); 
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
					$oPch->WithholdingTaxData->WTCode = $value2[0];
					$oPch->WithholdingTaxData->Add();
				}
			} 
		}

		//Add PO
		$retval = $oPch->Add();
		
		if ($retval != 0) 
		{
			$errmsg .= $vCmp->GetLastErrorDescription;
			$err += 1;
		}
		else
		{
			$vCmp->GetNewObjectCode($docentry);
			$oPch1 = $vCmp->GetBusinessObject(112);
			$oPch1->GetByKey($docentry);
			
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
						
						$oPch1->Lines->SetCurrentLine($a);
						$oPch1->Lines->ItemCode = $value1[0];
						
						if($value1[5] == 0)
						{
							$oPch1->Lines->PriceAfterVAT = $value1[6];
						}
						else
						{
							$oPch1->Lines->UnitPrice = $value1[2];
							$oPch1->Lines->DiscountPercent = $value1[5];
						}
						
						$a++;
					}
				}
			}
			
			$oPch1->Update();
			$retval = $oPch1->SaveDraftToDocument();
			$vCmp->GetNewObjectCode($docentry);
			if ($retval != 0) 
			{
				$errmsg .= $vCmp->GetLastErrorDescription;
				$err += 1;
			}
			else
			{
				$oPch1->Remove();
				
			}
		}
	}

if($err == 0){
	//odbc_commit($MSSQL_CONN);
	echo 'true*Successfully Added A/P Invoice ' . $docentry;
}else{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>