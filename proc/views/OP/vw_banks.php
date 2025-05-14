<?php include_once('../../../config/config.php');

$countrycode = $_GET['countrycode'];
$selectedbank = isset($_GET['bankcode']) ? $_GET['bankcode'] : '';


$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT BankCode, BankName FROM ODSC WHERE CountryCod = '$countrycode'");
echo '<option value="">-Select-</option>';
while (odbc_fetch_row($qry)) {
	echo '<option value="'.odbc_result($qry, 'BankCode').'" '.($selectedbank==odbc_result($qry, 'BankCode') ? 'selected' : '').' >'.odbc_result($qry, 'BankName').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
