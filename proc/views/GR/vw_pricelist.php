<?php include_once('../../../config/config.php');

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT ListNum,ListName FROM OPLN ORDER BY ListNum");
while (odbc_fetch_row($qry)) {
	echo '<option value="'.odbc_result($qry, 'ListNum').'" >'.odbc_result($qry, 'ListName').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
<option value="-1">Last Purchase Price</option>
<option value="-2">Last Evaluated Price</option>
