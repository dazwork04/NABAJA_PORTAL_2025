<?php include_once('../../../config/config.php');

$cardcode = $_GET['cardcode'];

include_once('../../../config/config.php');

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT T1.Street, T1.City, T2.Name, T1.Address
																FROM OCRD T0 
																INNER JOIN CRD1 T1 ON T0.CardCode = T1.CardCode
																INNER JOIN OCRY T2 ON T1.Country = T2.Code
																WHERE T0.CardCode = '$cardcode' AND T1.AdresType = 'S' ");
while (odbc_fetch_row($qry)) {
	echo '<option value="'.odbc_result($qry, 'Address').'" >'.utf8_encode(odbc_result($qry, 'Street')).' ' .utf8_encode(odbc_result($qry, 'City')).' '.utf8_encode(odbc_result($qry, 'Name')).'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>