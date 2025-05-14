<?php include_once('../../../config/config.php');

$countrycode = $_GET['countrycode'];
$bankcode = $_GET['bankcode'];
$customercode = $_GET['customercode'];

$selectedbankbranch = isset($_GET['branch']) ? $_GET['branch'] : '';

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT BankCode, Account, Branch FROM DSC1 WHERE BankCode = '$bankcode'");
echo '<option value="">-Select-</option>';
while (odbc_fetch_row($qry)) {
	echo '<option value="'.odbc_result($qry, 'Branch').' - '.odbc_result($qry, 'Account').'">'.odbc_result($qry, 'Branch').' - '.odbc_result($qry, 'Account').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
