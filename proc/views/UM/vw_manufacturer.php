<?php include_once('../../../config/config.php');

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT T0.FirmCode,T0.FirmName
																FROM OMRC T0 
																ORDER BY T0.FirmName");
echo '<option value="">-Select-</option>';
while (odbc_fetch_row($qry)) {
	
	echo '<option value="'.odbc_result($qry, 'FirmCode').'">'.odbc_result($qry, 'FirmName').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
