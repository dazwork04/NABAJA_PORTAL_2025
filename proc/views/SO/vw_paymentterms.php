<?php include_once('../../../config/config.php');

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT GroupNum,PymntGroup,ExtraDays FROM OCTG ORDER BY GroupNum");
while (odbc_fetch_row($qry)) {
	echo '<option value="'.odbc_result($qry, 'GroupNum').'" aria-extradays="'.odbc_result($qry, 'ExtraDays').'">'.odbc_result($qry, 'PymntGroup').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
