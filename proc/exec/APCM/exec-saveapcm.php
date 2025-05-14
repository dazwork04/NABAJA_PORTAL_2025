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

		$oRpc = $vCmp->GetBusinessObject(112);
		$oRpc->DocObjectCode = 19;
		$oRpc->CardCode = $vendor;
		$oRpc->DocDate = $postingdate;
		$oRpc->DocDueDate = $deliverydate;
		$oRpc->TaxDate = $documentdate;
		$oRpc->DiscountPercent = ($discPercent == '')? 0 : $discPercent;
		$oRpc->PaymentGroupCode = $paymentterms;
		$oRpc->Comments = $remarks; //SET REMARKS
		$oRpc->NumAtCard = $numatcard;
		$oRpc->ControlAccount = $txtCtlAcctCode;
		
		if (isset($shipto) && $shipto != '')
        {
            $oRpc->Address2 = $shipto;
        }
		
		if (isset($billto) && $billto != '')
        {
            $oRpc->Address = $billto;
        }
		
		$oRpc->SalesPersonCode  = $salesemployee;
		if (isset($owner) && $owner != '')
        {
            $oRpc->DocumentsOwner = $owner;
        }

		$oRpc->Series = $series;
		//$oRpc->BPL_IDAssignedToInvoice = $bplid;

		//$oRpc->DocTotal = ($tpaymentdue == '')? 0 : $tpaymentdue;
		if($selDocCur != '') 
		{
		if($selDocCur != 'PHP') 
			{
				$oRpc->DocRate = $txtDocRate;
				
				$oRpc->DocCurrency = $selDocCur;
			}
		}
		if($servicetype == 'I'){
			$oRpc->DocType = 0;
		}else{
			$oRpc->DocType = 1;
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
							$oRpc->Lines->BaseEntry = $basentry;
							$oRpc->Lines->BaseLine = $value[11];
							$oRpc->Lines->BaseType = 18;

							$oRpc->Lines->ItemCode = $value[0];
							$oRpc->Lines->Quantity = $value[1]; //change to inventory
							$oRpc->Lines->UnitPrice = 0;
							$oRpc->Lines->DiscountPercent = 0;
							$oRpc->Lines->PriceAfterVAT = 0;
							$oRpc->Lines->WarehouseCode = $value[3];
							$oRpc->Lines->ItemDetails = $value[13];
							$oRpc->Lines->WTLiable = $value[18];

							if($value[14] != '') //Department
							{
								$oRpc->Lines->CostingCode = $value[14];
							}
							
							if($value[15] != '') //Project
							{
								$oRpc->Lines->ProjectCode = $value[15];
							}
							
							if($value[16] != '') //Employee
							{
								$oRpc->Lines->CostingCode2 = $value[16];
							}

							if($value[17] != '') //Equipment
							{
								$oRpc->Lines->CostingCode3 = $value[17];
							}
							
							$oRpc->Lines->Add();
							
							$ctr += 1;
						}
						else
						{
							$oRpc->SpecialLines->LineType = 0;
					        $oRpc->SpecialLines->AfterLineNumber = $ctr;
					        $oRpc->SpecialLines->LineText = $value[0];
					        $oRpc->SpecialLines->Add();
						}
					}
					else
					{
						if($value[10] == 'N')
						{ 
							$oRpc->Lines->ItemCode = $value[0];
							$oRpc->Lines->Quantity = $value[1];
							$oRpc->Lines->UnitPrice = 0;
							$oRpc->Lines->DiscountPercent = 0;
							$oRpc->Lines->PriceAfterVAT = 0;							
							$oRpc->Lines->WarehouseCode = $value[3];
							$oRpc->Lines->VatGroup = $value[4];
							$oRpc->Lines->ItemDetails = $value[13];
							$oRpc->Lines->WTLiable = $value[18];
							
							if($value[14] != '') //Department
							{
								$oRpc->Lines->CostingCode = $value[14];
							}
							
							if($value[15] != '') //Project
							{
								$oRpc->Lines->ProjectCode = $value[15];
							}
							
							if($value[16] != '') //Employee
							{
								$oRpc->Lines->CostingCode2 = $value[16];
							}

							if($value[17] != '') //Equipment
							{
								$oRpc->Lines->CostingCode3 = $value[17];
							}

							$oRpc->Lines->Add();
							
							$ctr += 1;
							
						}
						else
						{
							$oRpc->SpecialLines->LineType = 0;
					        $oRpc->SpecialLines->AfterLineNumber = $ctr;
					        $oRpc->SpecialLines->LineText = $value[0];
					        $oRpc->SpecialLines->Add();
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
						$oRpc->Lines->BaseEntry = $basentry;
						$oRpc->Lines->BaseLine = $value[6];
						$oRpc->Lines->BaseType = 18;

						$oRpc->Lines->ItemDescription = $value[0];
		       			$oRpc->Lines->AccountCode = $value[1];
						$oRpc->Lines->PriceAfterVAT = $value[4];
		       			$oRpc->Lines->VatGroup = $value[3];
						$oRpc->Lines->WTLiable = $value[11];

						//---- OcrCodes ----//
						if($value[7] != '') //Department
						{
							$oRpc->Lines->CostingCode = $value[7];
						}
						
						if($value[8] != '') //Project
						{
							$oRpc->Lines->ProjectCode = $value[8];
						}
						
						if($value[9] != '') //Employee
						{
							$oRpc->Lines->CostingCode2 = $value[9];
						}

						if($value[10] != '') //Equipment
						{
							$oRpc->Lines->CostingCode3 = $value[10];
						}

		       			$oRpc->Lines->Add(); 
						
					}
					else
					{
						$oRpc->Lines->ItemDescription = $value[0];
		       			$oRpc->Lines->AccountCode = $value[1];
		       			$oRpc->Lines->PriceAfterVAT = $value[4];
		       			$oRpc->Lines->VatGroup = $value[3];
						$oRpc->Lines->WTLiable = $value[11];

						//---- OcrCodes ----//
						if($value[7] != '') //Department
						{
							$oRpc->Lines->CostingCode = $value[7];
						}
						
						if($value[8] != '') //Project
						{
							$oRpc->Lines->ProjectCode = $value[8];
						}
						
						if($value[9] != '') //Employee
						{
							$oRpc->Lines->CostingCode2 = $value[9];
						}

						if($value[10] != '') //Equipment
						{
							$oRpc->Lines->CostingCode3 = $value[10];
						}
					
		       			$oRpc->Lines->Add(); 
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
					$oRpc->WithholdingTaxData->WTCode = $value2[0];
					$oRpc->WithholdingTaxData->Add();
				}
			} 
		}

		//Add PO
		$retval = $oRpc->Add();
		
		if ($retval != 0) 
		{
			$errmsg .= $vCmp->GetLastErrorDescription;
			$err += 1;
		}
		else
		{
			$vCmp->GetNewObjectCode($docentry);
			$oRpc1 = $vCmp->GetBusinessObject(112);
			$oRpc1->GetByKey($docentry);
			
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
						
						$oRpc1->Lines->SetCurrentLine($a);
						$oRpc1->Lines->ItemCode = $value1[0];
						
						if($value1[5] == 0)
						{
							$oRpc1->Lines->PriceAfterVAT = $value1[6];
						}
						else
						{
							$oRpc1->Lines->UnitPrice = $value1[2];
							$oRpc1->Lines->DiscountPercent = $value1[5];
						}
						
						$a++;
					}
				}
			}
			
			$oRpc1->Update();
			$retval = $oRpc1->SaveDraftToDocument();
			$vCmp->GetNewObjectCode($docentry);
			if ($retval != 0) 
			{
				$errmsg .= $vCmp->GetLastErrorDescription;
				$err += 1;
			}
			else
			{
				$oRpc1->Remove();
				
			}
		}
	}

if($err == 0){
	//odbc_commit($MSSQL_CONN);
	echo 'true*Successfully Added A/P Credit Memo ' . $docentry;
}else{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>