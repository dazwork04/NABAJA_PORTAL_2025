<?php 
include_once('../../../config/config.php');


$bpcode = $_POST['bpcode'];

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT a.CardCode,a.CardName,a.Balance,a.CntctPrsn,a.BillToDef 
        , UPPER('/ ' + b.Street + char(9) + b.City + ' ' + b.State + ' ' + b.ZipCode + char(9) + b.Country) AS [BillToAddress]
        FROM OCRD a
        LEFT JOIN CRD1 b
        ON a.CardCode = b.CardCode
        AND a.BillToDef = b.Address
        AND b.AdresType = 'B' 
	WHERE a.frozenFor = 'N'
	AND a.CardCode = '$bpcode'");

odbc_fetch_row($qry);

echo odbc_result($qry, 'CardCode') . ';' . odbc_result($qry, 'CardName') . ';' . odbc_result($qry, 'Balance') . ';' . odbc_result($qry, 'CntctPrsn') . ';' . odbc_result($qry, 'BillToAddress') ;

odbc_free_result($qry);

odbc_close($MSSQL_CONN);

?>