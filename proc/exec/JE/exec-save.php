<?php

include_once('../../../config/config.php');

	$docentry = '';
	$err = 0;
	$errmsg = '';

	$txtRefNo = $_POST['txtRefNo'];
	$txtRemarks = $_POST['txtRemarks'];
	$txtRefDate = $_POST['txtRefDate'];
	$txtDueDate = $_POST['txtDueDate'];
	$txtTaxDate = $_POST['txtTaxDate'];
	$txtAutomaticTax = $_POST['txtAutomaticTax'];
	$txtManageWTax = $_POST['txtManageWTax'];

	$json = $_POST['json'];

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

	if ($lRetCode != 0) 
	{
		$errmsg .= $vCmp->GetLastErrorDescription;
		$err += 1;
	}
	else
	{
		$oJdt = $vCmp->GetBusinessObject(30);
		
		$oJdt->Reference = $txtRefNo;
		$oJdt->Memo = $txtRemarks;
		$oJdt->ReferenceDate = $txtRefDate;
		$oJdt->DueDate = $txtDueDate;
		$oJdt->TaxDate = $txtTaxDate;
		
		if($txtAutomaticTax != 0)
		{
			$oJdt->AutoVAT = $txtAutomaticTax;
		}
		
		if($txtManageWTax != 0)
		{
			$oJdt->AutomaticWT = $txtManageWTax;
		}
	
		
		if (json_decode($json) != null)
		{
			$json = json_decode($json, true);
		
			foreach ($json as $key => $value) 
			{
				$value[1] = $value[1] == '' ? 0 : $value[1];
				$value[2] = $value[2] == '' ? 0 : $value[2];
					
				if($value[7] == 'BP')
				{
					$oJdt->Lines->ShortName = $value[0];
				}
				else
				{
					$oJdt->Lines->AccountCode = $value[0];
				}
				
				$oJdt->Lines->Debit = $value[1];
				$oJdt->Lines->Credit = $value[2];
				$oJdt->Lines->LineMemo = $value[10];
				
				if($txtAutomaticTax != 0)
				{
					if($value[8] != '')
					{
						$oJdt->Lines->TaxGroup = $value[8];
					}
				}
				
				if($txtManageWTax != 0)
				{
					if($value[9] == 'Y')
					{
						$oJdt->Lines->WTLiable = 1;
					}
				}
				
				if($value[3] != '') //Department
				{
					$oJdt->Lines->CostingCode = $value[3];
				}
				
				if($value[4] != '') //Project
				{
					$oJdt->Lines->ProjectCode = $value[4];
				}
				
				if($value[5] != '') //Employee
				{
					$oJdt->Lines->CostingCode2 = $value[5];
				}

				if($value[6] != '') //Equipment
				{
					$oJdt->Lines->CostingCode3 = $value[6];
				}
							
				$oJdt->Lines->Add(); 
			}
		}
		
		$retval = $oJdt->Add();
		
		if ($retval != 0) 
		{
			$errmsg .= $vCmp->GetLastErrorDescription;
			$err += 1;
		}
		else
		{
			$vCmp->GetNewObjectCode($docentry);
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