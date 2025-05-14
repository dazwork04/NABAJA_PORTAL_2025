<?php include_once('../../../config/config.php');

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 1 Address2 FROM OPOR ORDER BY DocEntry DESC");
while (odbc_fetch_row($qry)) {
	echo odbc_result($qry, 'Address2');
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
