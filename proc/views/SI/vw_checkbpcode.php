<?php 
include_once('../../../config/config.php');


$bpcode = $_POST['bpcode'];

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT CardCode,CardName,Balance,CntctPrsn FROM OCRD
	WHERE frozenFor = 'N'
	AND CardCode = '$bpcode'");

odbc_fetch_row($qry);

echo odbc_result($qry, 'CardCode') . ';' . odbc_result($qry, 'CardName') . ';' . odbc_result($qry, 'Balance') . ';' . odbc_result($qry, 'CntctPrsn') ;

odbc_free_result($qry);

odbc_close($MSSQL_CONN);

?>