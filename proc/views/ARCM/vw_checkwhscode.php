<?php 
include_once('../../../config/config.php');


$whscode = $_POST['whscode'];

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT WhsCode, WhsName FROM OWHS 
	WHERE WhsCode = '$whscode'");

odbc_fetch_row($qry);

echo odbc_result($qry, 'WhsCode') . ';' . odbc_result($qry, 'WhsName');

odbc_free_result($qry);

odbc_close($MSSQL_CONN);

?>