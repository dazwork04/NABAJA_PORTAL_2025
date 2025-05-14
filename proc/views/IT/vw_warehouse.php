<?php include_once('../../../config/config.php');
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT WhsCode,WhsName FROM OWHS ORDER BY WhsCode");
echo '<option value="" >-Select-</option>';
while (odbc_fetch_row($qry)) {
	echo '<option value="'.odbc_result($qry, 'WhsCode').'" >'.odbc_result($qry, 'WhsName').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
