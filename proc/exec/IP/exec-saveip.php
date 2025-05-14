<?php

include_once('../../../config/config.php');

$docentry = '';
$docno = '';
$err = 0;
$errmsg = '';

$basentry = $_POST['basentry'];
$customer = $_POST['customer'];
$name = $_POST['name'];
$billto = $_POST['billto'];
$contactperson = $_POST['contactperson'];

$postingdate = $_POST['postingdate'];
$duedate = $_POST['duedate'];
$documentdate = $_POST['documentdate'];

$reference = $_POST['reference'];
$remarks = $_POST['remarks'];
$series = $_POST['series'];
$bplid = $_POST['bplid'];

$totalcash = $_POST['totalcash'];
$primaryformitem = $_POST['primaryformitem'];
$glaccountcash = $_POST['glaccountcash'];

$totalbanktransfer = $_POST['totalbanktransfer'];
$banktransferreference = $_POST['banktransferreference'];
$transferdate = $_POST['transferdate'];
$glaccountbanktransfer = $_POST['glaccountbanktransfer'];

$totalcheck = $_POST['totalcheck'];
$checkduedate = $_POST['checkduedate'];
$checkno = $_POST['checkno'];
$checkaccount = $_POST['checkaccount'];
$checkbranch = $_POST['checkbranch'];
$checkbank = $_POST['checkbank'];
$checkcountry = $_POST['checkcountry'];
$glaccountcheck = $_POST['glaccountcheck'];

//creditcard
/* $creditcardname = $_POST['creditcardname'];
$glaaccountcreditcard = $_POST['glaaccountcreditcard'];
$creditcardno = $_POST['creditcardno'];
$validuntil = $_POST['validuntil'];
$idno = $_POST['idno'];
$telephoneno = $_POST['telephoneno'];
$paymentmethod = $_POST['paymentmethod'];
$amountdue = $_POST['amountdue'];
$nopayments = $_POST['nopayments'];
$partialpayment = $_POST['partialpayment'];
$addpayment = $_POST['addpayment'];
$voucherno = $_POST['voucherno']; */
$transactiontype = $_POST['transactiontype'];
$txtPrjCode = $_POST['txtPrjCode'];

$radCategory = $_POST['radCategory'];

$json = $_POST['json'];
$jsoncredit = $_POST['jsoncredit'];

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
		$oRct = $vCmp->GetBusinessObject(24);
			
		if($radCategory == 'Vendor')
		{
		
			$oRct->CardCode = $customer;
			$oRct->CardName = $name;
			$oRct->Address = $billto;
			$oRct->DocDate = $postingdate;
			$oRct->DueDate = $duedate;
			$oRct->TaxDate = $documentdate;
			$oRct->CounterReference = $reference;
			$oRct->Remarks = $remarks;
			$oRct->Series = $series;
			
			if($txtPrjCode != '') //ProjectCode
			{
				$oRct->ProjectCode = $txtPrjCode;
			}
		}
		else
		{
			$oRct->DocType = 1;
			$oRct->UserFields->Fields["U_BPVendor"]->Value = $customer;
			$oRct->CardName = $name;
			$oRct->Address = $billto;
			$oRct->DocDate = $postingdate;
			$oRct->DueDate = $duedate;
			$oRct->TaxDate = $documentdate;
			$oRct->CounterReference = $reference;
			
			if($txtPrjCode != '') //ProjectCode
			{
				$oRct->ProjectCode = $txtPrjCode;
			}
		}
		
		if (isset($totalcash) && $totalcash > 0) 
		{
            $oRct->CashSum = isset($totalcash) ? $totalcash : 0;
			$oRct->CashAccount = $glaccountcash;
        }

         if (isset($totalbanktransfer) && $totalbanktransfer > 0) 
		 {
            $oRct->TransferSum = isset($totalbanktransfer) ? $totalbanktransfer : 0;
            $oRct->TransferDate = $transferdate;
            $oRct->TransferReference = $banktransferreference;
            $oRct->TransferAccount = $glaccountbanktransfer;
        }

        if (isset($totalcheck) && $totalcheck > 0) 
		{
            $oRct->CheckAccount = $glaccountcheck;
            $oRct->Checks->CheckSum = isset($totalcheck) ? $totalcheck : 0;
            $oRct->Checks->CheckNumber = $checkno;
            $oRct->Checks->DueDate = $checkduedate;
            $oRct->Checks->Branch = $checkbranch;
            $oRct->Checks->BankCode = $checkbank;
            $oRct->Checks->CountryCode = $checkcountry;
            $oRct->Checks->Add();
        }
		
						/* itArr.push('"' + $(this).find('input.creditcardname').val() + '"');
				itArr.push('"' + $(this).find('input.glaaccountcreditcard').val() + '"');
				itArr.push('"' + $(this).find('input.creditcardno').val() + '"');
				itArr.push('"' + $(this).find('input.validuntil').val() + '"');
				itArr.push('"' + $(this).find('input.amountdue').val().replace(/,/g, '') + '"');
				itArr.push('"' + $(this).find('input.voucherno').val() + '"');
				itArr.push('"' + $(this).find('input.lineid').val() + '"'); */
				
		$jsoncredit = json_decode($jsoncredit, true);
		
		if ($jsoncredit != '') 
		{
			
			
			foreach ($jsoncredit as $key => $value1) 
			{
				$value1[4] = $value1[4] == '' ? 0 : $value1[4];
				$oRct->CreditCards->PaymentMethodCode = 1;
				$oRct->CreditCards->AdditionalPaymentSum = 0;
				$oRct->CreditCards->CardValidUntil = $value1[3];
				$oRct->CreditCards->CreditAcct = $value1[1];
				$oRct->CreditCards->CreditCard = $value1[0];
				$oRct->CreditCards->CreditCardNumber = $value1[2];
				$oRct->CreditCards->CreditSum = $value1[4];
				$oRct->CreditCards->VoucherNum = $value1[5];
				$oRct->CreditCards->NumOfPayments = 1;
				
				/* $oRct->CreditCards->AdditionalPaymentSum = 0;
				$oRct->CreditCards->CardValidUntil = $validuntil;
				$oRct->CreditCards->CreditAcct = $glaaccountcreditcard;
				$oRct->CreditCards->CreditCard = $creditcardname;
				$oRct->CreditCards->CreditCardNumber = $creditcardno;
				$oRct->CreditCards->CreditSum = $amountdue; */
				//$oRct->CreditCards->FirstPaymentSum = $amountdue;
				/* $oRct->CreditCards->VoucherNum = $voucherno;
				$oRct->CreditCards->NumOfPayments = $nopayments; */
				//$oRct->CreditCards->OwnerIdNum = $idno;
				//$oRct->CreditCards->OwnerPhone = $telephoneno;
				//$oRct->CreditCards->PaymentMethodCode = $paymentmethod;
				$oRct->CreditCards->Add();
				
			}
			
			
            
        }
		
       if (json_decode($json) != null) 
		{
            //DECODE JSON
            $json = json_decode($json, true);
            $ctr = 1;
			if($radCategory == 'Vendor')
			{
				foreach ($json as $key => $value) 
				{
					$value[1] = $value[1] == '' ? 0 : $value[1];
					$value[2] = $value[2] == '' ? 0 : $value[2];
					
					if($value[3] != '') //Department
					{
						$oRct->Invoices->DistributionRule = $value[3];
					}
					
					if($value[4] != '') //Employee
					{
						$oRct->Invoices->DistributionRule2 = $value[4];
					}
					
					if($value[5] != '') //Equipment
					{
						$oRct->Invoices->DistributionRule3 = $value[5];
					}
          $oRct->Invoices->DocEntry = $value[0];
          $oRct->Invoices->InvoiceType = $value[6]; 
					$oRct->Invoices->SumApplied = $value[2];
					$oRct->Invoices->Add();
					$ctr += 1;
				}
			}
			else
			{
				foreach ($json as $key => $value) 
				{
					$value[0] = $value[0] == '' ? 0 : $value[0]; //acctcode
					//$value[1] = $value[1] == '' ? 0 : $value[1]; //docremarks
					$value[2] = $value[2] == '' ? 0 : $value[2]; //taxgroup
					$value[3] = $value[3] == '' ? 0 : $value[3]; //price
					
					if($value[4] != '') //Department
					{
						$oRct->AccountPayments->ProfitCenter = $value[4];
					}
					
					if($value[5] != '') //Employee
					{
						$oRct->AccountPayments->ProfitCenter2 = $value[5];
					}
					
					if($value[6] != '') //Equipment
					{
						$oRct->AccountPayments->ProfitCenter3 = $value[6];
					}
					
					$oRct->AccountPayments->AccountCode = $value[0];
					$oRct->AccountPayments->Decription = $value[1];
					$oRct->AccountPayments->VatGroup = $value[2];
					$oRct->AccountPayments->SumPaid = $value[3];
					//$oRct->AccountPayments->SumPaid = 5000;
					
					$oRct->AccountPayments->Add();
				}
			}
        }
       
        $retval = $oRct->Add();
		
        $vCmp->GetNewObjectCode($docentry);

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

if ($err == 0) 
{
	echo 'true*Operation successfully completed.';
} 
else 
{
    echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);
?>