<?php include_once('../../../config/config.php');

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT 
																																				T0.Code, 
																																				T0.Name, 
																																				T0.Rate
																																		FROM OVTG T0 
																																		WHERE T0.Inactive = 'N'  AND T0.Category = 'I'");
while (odbc_fetch_row($qry)) 
{
	echo '<option val'.odbc_result($qry, "Code").' val-rate="'.number_format(odbc_result($qry, "Rate"),4,'.','.').'" value="'.odbc_result($qry, "Code").' " >'.odbc_result($qry, 'Code').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
