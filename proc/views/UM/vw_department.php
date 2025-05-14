<?php include_once('../../../config/config.php');

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT T0.Code,T0.Name,T0.Remarks 
																FROM OUDP T0 
																ORDER BY T0.Name");
echo '<option value="">-Select-</option>';
while (odbc_fetch_row($qry)) {
	
	echo '<option value="'.odbc_result($qry, 'Code').'">'.odbc_result($qry, 'Name').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
