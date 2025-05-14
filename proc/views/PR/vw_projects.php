<?php include_once('../../../config/config.php');

$empid = $_SESSION['SESS_EMP'];
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT T1.PrjName 
																FROM OHEM T0
																LEFT JOIN OPRJ T1 ON T0.ExtEmpNo = T1.PrjCode
																WHERE T0.empID = $empid");
while (odbc_fetch_row($qry)) 
{
	echo odbc_result($qry, 'PrjName');
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);


?>
