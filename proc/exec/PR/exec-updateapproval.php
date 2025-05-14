<?php

include_once('../../../config/config.php');

//Header
$docentry = '';
$docno = '';
$err = 0;
$errmsg = '';

$empid = $_SESSION['SESS_EMP'];

$json = $_POST['json'];
	
if ($err == 0) 
{
	if (json_decode($json) != null) 
	{
		//DECODE JSON
		$json = json_decode($json, true);
		$ctr = -1;
		foreach ($json as $key => $value) 
		{ 
			//$value[0] - docentry
			//$value[1] - Decision
			//$value[2] - Remarks
			
				$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; UPDATE OPRQ SET 
					U_AppStatus = '$value[1]',
					U_DateApproved = GETDATE(),
					U_ApprovedBy = '$empid',
					U_DecisionRemarks1 = '$value[2]'
				WHERE DocEntry = $value[0]");
				
				if(!$qry)
				{
					$err += 1;
					$errmsg .= 'Error (Error Code: '.odbc_error().') - '.odbc_errormsg();
				}
		}
	}
}

if ($err == 0) 
{
    odbc_commit($MSSQL_CONN);
    echo 'true*Operation completed successfully.';
} 
else 
{
  echo 'false*' . $errmsg;
}


odbc_close($MSSQL_CONN);
?>