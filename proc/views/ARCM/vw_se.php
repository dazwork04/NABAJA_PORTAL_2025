<?php include_once('../../../config/config.php');



if ($BranchCode == '') 
{
	echo '<option value="All">All</option>';
}
else 
{
	$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
			SELECT T0.branch, T1.Name, T0.salesPrson, T2.SlpName 
			FROM OHEM T0
			INNER JOIN OUBR T1 ON T0.branch = T1.Code
			LEFT JOIN OSLP T2 ON T0.salesPrson = T2.SlpCode
			WHERE T2.SlpName IS NOT NULL ORDER BY T2.SlpName");
			
	echo '<option value="All">All</option>';
	while (odbc_fetch_row($qry)) 
	{
		echo '<option value="'.odbc_result($qry, 'salesPrson').'">'.odbc_result($qry, 'SlpName').'</option>';
	}
	odbc_free_result($qry);
	odbc_close($MSSQL_CONN);
	
}


?>
