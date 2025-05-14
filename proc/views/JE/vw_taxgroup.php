<?php include_once('../../../config/config.php');

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT 
																																T0.Code, 
																																T0.Name, 
																																T0.Rate, 
																																T0.Account, 
																																T1.AcctName
																														FROM OVTG T0 
																														INNER JOIN OACT T1 ON T0.Account = T1.AcctCode
																														WHERE T0.Inactive = 'N' ");
echo '<option value="">-Select-</option>';
while (odbc_fetch_row($qry)) 
{
	echo '<option val'.odbc_result($qry, "Code").' val-rate="'.number_format(odbc_result($qry, "Rate"),4,'.','.').'" val-acctcode="'.odbc_result($qry, "Account").'" val-acctname="'.odbc_result($qry, "AcctName").'" value="'.odbc_result($qry, "Code").' " >'.odbc_result($qry, 'Code').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
