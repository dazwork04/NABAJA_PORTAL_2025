<?php include_once('../../../config/config.php');

$RefNo = $_POST['refno'];
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT COUNT(T0.NumAtCard) AS APVCount FROM OPCH T0 WHERE T0.NumAtCard = '$RefNo'");

while (odbc_fetch_row($qry)) 
{
	echo odbc_result($qry, 'APVCount');
}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
