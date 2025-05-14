<?php include_once('../../../config/config.php');

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT ItmsGrpCod, ItmsGrpNam FROM OITB ORDER BY ItmsGrpCod");
echo '<option value="" >-Select-</option>';
while (odbc_fetch_row($qry)) {
	echo '<option value="'.odbc_result($qry, 'ItmsGrpCod').'" >'.odbc_result($qry, 'ItmsGrpNam').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
