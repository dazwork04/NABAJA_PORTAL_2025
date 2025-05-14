<?php include_once('../../../config/config.php');

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT SlpCode,SlpName FROM OSLP WHERE Active = 'Y' WHERE Active = 'Y' ORDER BY SlpCode ");
while (odbc_fetch_row($qry)) {
	echo '<option value="'.odbc_result($qry, 'SlpCode').'" >'.odbc_result($qry, 'SlpName').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
