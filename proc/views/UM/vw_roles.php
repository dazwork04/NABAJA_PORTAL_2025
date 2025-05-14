<?php include_once('../../../config/configmd.php');

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."]; SELECT DocEntry,RoleCode,RoleName 
																FROM [@ORLE]
																ORDER BY RoleName");

echo '<option></option>';
while (odbc_fetch_row($qry)) {
	echo '<option value="'.odbc_result($qry, 'DocEntry').'">'.odbc_result($qry, 'RoleName').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
