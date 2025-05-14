<?php
//ini_set('max_execution_time', 60000);
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
	$series = $_POST['series'];
	$bplid = $_POST['bplid'];
	$tpaymentdue = $_POST['tpaymentdue'];

	$servicetype = $_POST['servicetype'];

	//Details
	$json = $_POST['json'];
	//End Details
//End Variables

//Turn off autocommit
odbc_autocommit($MSSQL_CONN,false);
//End turn off autocommit

//Check for close document
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."]; SELECT T0.DocNum,T0.DocStatus FROM [@OPRQ] T0
																WHERE T0.DocEntry IN($basentry)");
while (odbc_fetch_row($qry)){
	if(odbc_result($qry, 'DocStatus') == 'C'){
		$errmsg .= odbc_result($qry, 'DocNum') . ' is already been closed!';
		$err += 1;
		break;
	}
}
odbc_free_result($qry);
//End Check for close document

if($err == 0){

	$vCmp=new COM("SAPbobsCOM.company") or die ("No connection");
	$vCmp->DbServerType = $_SESSION['dbver'];
	$vCmp->server = $_SESSION['mssqlserver'];
	$vCmp->UseTrusted = false;
	$vCmp->DBusername = $_SESSION['mssqluser'];
	$vCmp->DBpassword = $_SESSION['mssqlpass'];
	$vCmp->CompanyDB = $_SESSION['mssqldb'];
	$vCmp->username = $_SESSION['SESS_SAPUSER'];
	$vCmp->password = $_SESSION['SESS_SAPPASS'];
	$vCmp->LicenseServer = "".$_SESSION['LICENSE_SERVER'].":30000";
	$vCmp->SLDServer = $MSSQL_SERVER .':40000';

	$lRetCode = $vCmp->Connect;
	$errid = 0;
	$serr = '';

	$BaseRef = '';

	if ($lRetCode != 0) {
		$vCmp->GetLastError($err, $errmsg);
		$err += 1;
	}else{

		


		//DI API HERE
		//=============================================================================================

		//Header
		$oPo = $vCmp->GetBusinessObject(22);
		$oPo->CardCode = $vendor;
		$oPo->DocDate = $documentdate;
		$oPo->DocDueDate = $deliverydate;
		$oPo->TaxDate = $postingdate;
		$oPo->DiscountPercent = ($discPercent == '')? 0 : $discPercent;
		$oPo->PaymentGroupCode = $paymentterms;
		$oPo->Comments = $remarks; //SET REMARKS
		$oPo->NumAtCard = $numatcard;

		$oPo->Series = $series;
		//$oPo->BPL_IDAssignedToInvoice = $bplid;

		//$oPo->DocTotal = ($tpaymentdue == '')? 0 : $tpaymentdue;

		if($servicetype == 'I'){
			$oPo->DocType = 0;
		}else{
			$oPo->DocType = 1;
		}
		
		//End Header



		//Insert Details
		if (json_decode($json) != null){

			//DECODE JSON
			$json = json_decode($json, true);
			$ctr = 0;
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
					//$value[12] - DocEntry

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
						//$oPo->Lines->UnitPrice = $value[2];
						$oPo->Lines->PriceAfterVAT = $value[6]; 
						$oPo->Lines->Quantity = $value[1]; //change to inventory
						
						$oPo->Lines->WarehouseCode = $value[3];
						$oPo->Lines->VatGroup = $value[4];
						$oPo->Lines->DiscountPercent = $value[5];
				
						$oPo->Lines->Add();
						//End Insert None Free Text

						//Update PostedQty
						$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."]; UPDATE [@PRQ1] SET PostedQty = PostedQty + ".$value[1]." WHERE DocEntry = '{$value[12]}' AND LineNum = '".$value[11]."' AND LineStatus='O' AND ItemCode = '{$value[0]}' ");
						//ENd Update Posted Qty

						
					}else{
						//$value[0] - LineText
						//Insert Item Type Details Free Text

						$oPo->SpecialLines->LineType = 0;
				        $oPo->SpecialLines->AfterLineNumber = $ctr;
				        $oPo->SpecialLines->LineText = $value[0];
				        $oPo->SpecialLines->Add();
						
						//End Insert Item Type Details Free Text




				        $ctr += 1;
						
					}//End Check Free Text

				}else{
					//$value[0] - Remarks
					//$value[1] - Account Code
					//$value[2] - Price
					//$value[3] - taxcode
					//$value[4] - grossprice
					//$value[5] - taxamount
					//$value[6] - LineNo
					//$value[7] - DocEntry

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
	       			$oPo->Lines->Add(); 
					//End Insert Service Type Details


					//Update PostedQty
					$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."]; UPDATE [@PRQ1] SET PostedQty = PostedQty + ".$value[2]." WHERE DocEntry = '{$value[7]}' AND LineNum = '".$value[6]."' AND LineStatus='O'");
					//ENd Update Posted Qty

					

				} //End Check Service Type

				

				
				
			}

		} //End if
		//End Insert Details

		//Add PO
		$retval = $oPo->Add();
		$vCmp->GetNewObjectCode($docentry);

		if ($retval != 0) {
			$vCmp->GetLastError($err, $errmsg);
			$err += 1;
		}else{

			if($servicetype == 'I'){
				//Close Document if all lines are Closed
				$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."]; UPDATE [@PRQ1] 
					SET LineStatus = 'C' WHERE DocEntry IN($basentry) AND PostedQty = Quantity");


				$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."];  SELECT DISTINCT T0.DocEntry,T0.LineStatus,
																			 (
																				SELECT COUNT(*) FROM [@PRQ1] 
																				WHERE LineStatus = 'O' AND DocEntry = T0.DocEntry
																			 ) AS Res
																			 
																			  FROM [@PRQ1] T0 WHERE T0.DocEntry IN($basentry)");
				$dentry = '';
				while (odbc_fetch_row($qry)) {
					$result = odbc_result($qry, 'Res');
					
					if($result == 0){
						$dentry .= odbc_result($qry, 'DocEntry') . ',';
					}// End if
				}
	
				odbc_free_result($qry);
				$dentry = substr($dentry, 0, strlen($dentry) - 1);

				$dentry = ($dentry == '')? 0 : $dentry;

				$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."]; UPDATE [@OPRQ] SET DocStatus = 'C' WHERE DocEntry IN($dentry)");
				
				
				//ENd Close Document if all lines are Closed
			}else{

				//Close Document if all lines are Closed
				$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."]; UPDATE [@PRQ1] 
					SET LineStatus = 'C' WHERE DocEntry IN('$basentry') AND PostedQty >= Price");

				$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."];  SELECT DISTINCT T0.DocEntry,T0.LineStatus,
																			 (
																				SELECT COUNT(*) FROM [@PRQ1] 
																				WHERE LineStatus = 'O' AND DocEntry = T0.DocEntry
																			 ) AS Res
																			 
																			  FROM [@PRQ1] T0 WHERE AND T0.DocEntry IN($basentry)");

				
				$dentry = '';
				while (odbc_fetch_row($qry)) {
					$result = odbc_result($qry, 'Res');
					
					if($result == 0){
						$dentry .= odbc_result($qry, 'DocEntry') . ',';
						
					}// End if
				}
	
				odbc_free_result($qry);
				$dentry = substr($dentry, 0, strlen($dentry) - 1);
				$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."]; UPDATE [@OPRQ] SET DocStatus = 'C' WHERE DocEntry IN($dentry)");
				//ENd Close Document if all lines are Closed

			}

			//Get DocNo
			$getDN = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT DocNum FROM OPOR WHERE DocEntry = '$docentry'");
			odbc_fetch_row($getDN);
			$docno = odbc_result($getDN, 1);
			odbc_free_result($getDN);
			//End Get DocNo



		}
		//End Add PO


		//END DI API HERE
		//=============================================================================================
	} // End if DI API
}


if($err == 0){
	odbc_commit($MSSQL_CONN);
	echo 'true*Successfully Added PO ' . $docno;
}else{
	echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);


?>