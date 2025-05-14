<?php include_once('../../../config/config.php');

$selCategory = $_GET['selCategory'];
$selCategoryval = $_GET['selCategoryval'];
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT GroupCode, GroupName FROM OCRG WHERE GroupType = '$selCategoryval'");
while (odbc_fetch_row($qry)) 
{
	echo '<option value="'.odbc_result($qry, 'GroupCode').'" >'.odbc_result($qry, 'GroupName').'</option>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
