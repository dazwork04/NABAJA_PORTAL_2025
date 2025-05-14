<?php include_once('../../../config/config.php');

echo '<option value="" >-Select-</option>';
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT CreditCard, CardName FROM OCRC ORDER BY CreditCard ASC");
while (odbc_fetch_row($qry)) {
	echo '<option value="'.odbc_result($qry, 'CreditCard').'" val-cardname="'.odbc_result($qry, "CardName").'">'.odbc_result($qry, 'CardName').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
