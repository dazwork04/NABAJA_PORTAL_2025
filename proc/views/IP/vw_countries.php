<?php include_once('../../../config/config.php');

$selectedcountry = isset($_GET['countrycode']) ? $_GET['countrycode'] : '';

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT Name, Code FROM OCRY WHERE Code IN (SELECT CountryCod FROM ODSC) ORDER BY Code");
echo '<option value=""></option>';
while (odbc_fetch_row($qry)) {
	echo '<option value="'.odbc_result($qry, 'Code').'" '.($selectedcountry==odbc_result($qry, 'Code') ? 'selected' : '').' >'.odbc_result($qry, 'Name').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
