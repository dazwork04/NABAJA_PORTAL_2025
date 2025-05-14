<?php 
include_once('../../../config/config.php');

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.LinkAct_2, T1.AcctName AS AcctName2, T0.LinkAct_3, T2.AcctName AS AcctName3 FROM OACP T0
        LEFT JOIN OACT T1
        ON T0.LinkAct_2 = T1.AcctCode
        LEFT JOIN OACT T2
        ON T0.LinkAct_3 = T2.AcctCode
        WHERE YEAR(GETDATE()) = T0.[Year]");

odbc_fetch_row($qry);

echo odbc_result($qry, 'LinkAct_2') . ';' . odbc_result($qry, 'AcctName2') . ';' . odbc_result($qry, 'LinkAct_3') . ';' . odbc_result($qry, 'AcctName3');

odbc_free_result($qry);

odbc_close($MSSQL_CONN);

?>