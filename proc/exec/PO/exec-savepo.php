<?php
//ini_set('max_execution_time', 60);
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
	
	$shipto = $_POST['shipto'];
	$billto = $_POST['billto'];
	
	$selDocCur = $_POST['selDocCur'];
	$selCurSource = $_POST['selCurSource'];
	$txtDocRate = $_POST['txtDocRate'];
	
	$txtDocRef = $_POST['txtDocRef'];
	$txtPRRef = $_POST['txtPRRef'];

	$servicetype = $_POST['servicetype'];
		
	$salesemployee = $_POST['salesemployee'];
	$owner = $_POST['owner'];
	//Details
	$json = $_POST['json'];
	$json2 = $_POST['json'];
	
	//End Variables

//Turn off autocommit
odbc_autocommit($MSSQL_CONN,false);
//End turn off autocommit

if($err == 0){

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
	}else{

		//DI API HERE
		//=============================================================================================

		//Header
		$oPo = $vCmp->GetBusinessObject(22);
		$oPo->CardCode = $vendor;
		$oPo->DocDate = $postingdate;
		$oPo->DocDueDate = $deliverydate;
		$oPo->TaxDate = $documentdate;
		$oPo->DiscountPercent = ($discPercent == '')? 0 : $discPercent;
		$oPo->PaymentGroupCode = $paymentterms;
		$oPo->Comments = $remarks; //SET REMARKS
		$oPo->NumAtCard = $numatcard;
		
		if (isset($shipto) && $shipto != '')
        {
            $oPo->Address2 = $shipto;
        }
		
		if (isset($billto) && $billto != '')
        {
            $oPo->Address = $billto;
        }

		$oPo->Series = $series;
		
		if($selDocCur != '') 
		{
			if($selDocCur != 'PHP') 
			{
				$oPo->DocRate = $txtDocRate;
				$oPo->DocCurrency = $selDocCur;
			}
		}
		
		$oPo->SalesPersonCode  = $salesemployee;
		
		if (isset($owner) && $owner != '')
        {
            $oPo->DocumentsOwner = $owner;
        }

		if($servicetype == 'I'){
			$oPo->DocType = 0;
		}else{
			$oPo->DocType = 1;
		}
		
		//Insert Details
		if (json_decode($json) != null){

			//DECODE JSON
			$json = json_decode($json, true);
			$ctr = -1;
			foreach ($json as $key => $value) {
				
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
					//$value[12] - Item Details

					//Catch Blank Numerics
					$value[1] = $value[1] == '' ? 0 : $value[1];
					$value[2] = $value[2] == '' ? 0 : $value[2];
					$value[5] = $value[5] == '' ? 0 : $value[5];
					$value[6] = $value[6] == '' ? 0 : $value[6];
					$value[7] = $value[7] == '' ? 0 : $value[7];
					$value[8] = $value[8] == '' ? 0 : $value[8];
					$value[9] = $value[9] == '' ? 0 : $value[9];
					//End Catch Blank Numerics

					if($value[10] == 'N'){ // Check if Free Text
					
						
						//Insert None Free Text
						$oPo->Lines->ItemCode = $value[0];
						$oPo->Lines->ItemDescription = $value[17];
						
						$oPo->Lines->Quantity = $value[1]; //change to inventory
						$oPo->Lines->WarehouseCode = $value[3];
						$oPo->Lines->VatGroup = $value[4];
						$oPo->Lines->ItemDetails = $value[12];
						
						if ($basentry != '' && isset($value[11]) && $value[11] != '') 
						{
                            $oPo->Lines->BaseEntry = $basentry;
                            $oPo->Lines->BaseLine = $value[11];
                            $oPo->Lines->BaseType = 1470000113;
						}
						
						$oPo->Lines->UnitPrice = 0;
						$oPo->Lines->DiscountPercent = 0;
						$oPo->Lines->PriceAfterVAT = 0;
						

						if($value[13] != '') //Department
						{
							$oPo->Lines->CostingCode = $value[13];
						}
						
						if($value[14] != '') //Project
						{
							$oPo->Lines->ProjectCode = $value[14];
						}
						
						if($value[15] != '') //Employee
						{
							$oPo->Lines->CostingCode2 = $value[15];
						}

						if($value[16] != '') //Equipment
						{
							$oPo->Lines->CostingCode3 = $value[16];
						}
						
						
						$oPo->Lines->Add();
						//End Insert None Free Text

						$ctr += 1;
						
					}else{
						//$value[0] - LineText
						//Insert Item Type Details Free Text

						$oPo->SpecialLines->LineType = 0;
				        $oPo->SpecialLines->AfterLineNumber = $ctr;
				        $oPo->SpecialLines->LineText = $value[0];
				        $oPo->SpecialLines->Add();
						
						//End Insert Item Type Details Free Text
						
					}//End Check Free Text

				}else{
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


					//Insert Service Type Details

					
	        		$oPo->Lines->ItemDescription = $value[0];
	       			$oPo->Lines->AccountCode = $value[1];
	       			//$oPo->Lines->UnitPrice = $value[2];
	       			$oPo->Lines->PriceAfterVAT = $value[4];
	       			$oPo->Lines->VatGroup = $value[3];

					//---- OcrCodes ----//
					if($value[7] != '') //Department
					{
						$oPo->Lines->CostingCode = $value[7];
					}
					
					if($value[8] != '') //Project
					{
						$oPo->Lines->ProjectCode = $value[8];
					}
					
					if($value[9] != '') //Employee
					{
						$oPo->Lines->CostingCode2 = $value[9];
					}

					if($value[10] != '') //Equipment
					{
						$oPo->Lines->CostingCode3 = $value[10];
					}


	       			$oPo->Lines->Add(); 
					//End Insert Service Type Details

				} //End Check Service Type

			}

		} //End if
		//End Insert Details

		//Add PO
		$retval = $oPo->Add();
		$vCmp->GetNewObjectCode($docentry);

		if ($retval != 0) {
			$errmsg .= $vCmp->GetLastErrorDescription;
			$err += 1;
		}else
		{
			$oPo = $vCmp->GetBusinessObject(22);
			$oPo->GetByKey($docentry);
			
			if($servicetype == 'I')
			{
				if (json_decode($json2) != null)
				{

				//DECODE JSON
				$json2 = json_decode($json2, true);
			
				$a = 0;
				
					foreach ($json2 as $key => $value1) 
					{
						$value1[2] = $value1[2] == '' ? 0 : $value1[2];
						$value1[5] = $value1[5] == '' ? 0 : $value1[5];
						$value1[6] = $value1[6] == '' ? 0 : $value1[6];
						
						$oPo->Lines->SetCurrentLine($a);
						$oPo->Lines->ItemCode = $value1[0];
						
						if($value1[5] == 0)
						{
							$oPo->Lines->PriceAfterVAT = $value1[6];
						}
						else
						{
							$oPo->Lines->UnitPrice = $value1[2];
							//$oPo->Lines->PriceAfterVAT = $value1[6];
							$oPo->Lines->DiscountPercent = $value1[5];
						}
						
						$a++;
					}
				}
			
				$retval = $oPo->Update();
				
				if ($retval != 0) 
				{
					$errmsg .= $vCmp->GetLastErrorDescription;
					$err += 1;
				}
				else
				{

				}
			}
			
		}
		//End Add PO


		//END DI API HERE
		//=============================================================================================
	} // End if DI API
}


if($err == 0){
	odbc_commit($MSSQL_CONN);
	echo 'true*Successfully Added PO # ' . $docentry;
}else{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>