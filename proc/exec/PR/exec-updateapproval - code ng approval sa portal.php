<?php

include_once('../../../config/config.php');

	//Header
	$docentry = '';
	$docno = '';
	$err = 0;
	$errmsg = '';

	$json = $_POST['json'];
	
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
	/* $vCmp->DbServerType = 15;
	$vCmp->server = "SUPERSPEED-DEV7";
	$vCmp->UseTrusted = false;
	$vCmp->DBusername = "sa";
	$vCmp->DBpassword = "devs@p2021";
	$vCmp->CompanyDB = "GSDC_LIVE1";
	$vCmp->username = "HOMACADILO";
	$vCmp->password = "1234";
	$vCmp->LicenseServer = "SUPERSPEED-DEV7:30000"; */
	
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
		$sCmp = $vCmp->GetCompanyService();
			
		$oApprovalRequestsService = $sCmp->GetBusinessService(122);
		$oApprovalRequestsParams = $oApprovalRequestsService->GetDataInterface(1);
		$oApprovalRequest = $oApprovalRequestsService->GetDataInterface(0);
		$oApprovalRequestParams = $oApprovalRequestsService->GetDataInterface(2);
		
		$oApprovalRequestsParams = $oApprovalRequestsService->GetAllApprovalRequestsList();
				
        if (json_decode($json) != null) 
		{
			//DECODE JSON
            $json = json_decode($json, true);
            $ctr = -1;
            foreach ($json as $key => $value) 
			{ 
				//$value[0] - WddCode
				//$value[1] - Decision
				//$value[2] - Remarks
				
				$oApprovalRequestParams->Code = $value[0];
				
				$oApprovalRequest = $oApprovalRequestsService->GetApprovalRequest($oApprovalRequestParams);
				$oApprovalRequestDecision = $oApprovalRequest->ApprovalRequestDecisions->Add();
				$oApprovalRequestDecision->Remarks = $value[2];
				$oApprovalRequestDecision->Status = $value[1];
				/* oApprovalRequestDecision.ApproverUserName = SAPB1UserName;
				oApprovalRequestDecision.ApproverPassword = SAPB1Password; */
				
				$oApprovalRequestsService->UpdateRequest($oApprovalRequest);
			}
		}
	  
	}
}

if ($err == 0) 
{
    odbc_commit($MSSQL_CONN);
    echo 'true*Successfully Approved.';
} 
else 
{
  echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);
?>