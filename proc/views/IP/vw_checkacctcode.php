<?php 
include_once('../../../config/config.php');

$acctcode = $_POST['acctcode'];

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT AcctName,FormatCode,AcctCode 
	FROM OACT 
	WHERE frozenFor = 'N'
	AND FormatCode = '$acctcode' OR AcctCode = '$acctcode'");

odbc_fetch_row($qry);

echo odbc_result($qry, 'AcctName') . ';' . odbc_result($qry, 'FormatCode') . ';' . odbc_result($qry, 'AcctCode');

odbc_free_result($qry);

odbc_close($MSSQL_CONN);

?>