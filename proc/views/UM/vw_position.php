<?php include_once('../../../config/config.php');

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT T0.posID,T0.name 
																FROM OHPS T0 
																ORDER BY T0.name");
echo '<option value="">-Select-</option>';
while (odbc_fetch_row($qry)) {
	
	echo '<option value="'.odbc_result($qry, 'posID').'">'.odbc_result($qry, 'name').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
