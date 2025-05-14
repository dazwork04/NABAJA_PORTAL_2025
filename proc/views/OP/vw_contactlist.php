
<?php include_once('../../../config/config.php');
$cardcode = $_GET['cardcode'];
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT * FROM OCPR WHERE CardCode = '$cardcode' ");
echo '<option value="" >- Select -</option>';
while (odbc_fetch_row($qry)) {
	echo '<option value="'.odbc_result($qry, 'CntctCode').'" >'.odbc_result($qry, 'Name').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>