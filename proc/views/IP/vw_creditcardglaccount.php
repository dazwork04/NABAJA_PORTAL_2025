<?php include_once('../../../config/config.php');

$CreditCard = $_GET['creditcard'];

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT * FROM OCRC WHERE CreditCard = '$CreditCard' ORDER BY CreditCard ASC");
while (odbc_fetch_row($qry)) {
	echo odbc_result($qry, 'AcctCode');
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
