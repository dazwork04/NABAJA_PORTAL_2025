<?php include_once('../../../config/config.php');

$countrycode = $_GET['countrycode'];
$bankcode = $_GET['bankcode'];
$customercode = $_GET['customercode'];

$selectedbankbranch = isset($_GET['branch']) ? $_GET['branch'] : '';

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT AbsEntry, Branch, Account FROM OCRB WHERE Country = '$countrycode' AND CardCode = '$customercode' AND BankCode = '$bankcode'");
echo '<option value=""  ></option>';
while (odbc_fetch_row($qry)) {
	echo '<option value="'.odbc_result($qry, 'Branch').'" aria-checkaccount="'
                .odbc_result($qry, 'Account').'" '.($selectedbankbranch==odbc_result($qry, 'Branch') ? 'selected' : '')
                .'>'.odbc_result($qry, 'Branch').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
