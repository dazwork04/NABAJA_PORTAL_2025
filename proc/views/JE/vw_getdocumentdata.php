<?php
include_once('../../../config/config.php');

$docentry = $_GET['docentry'];

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."];
		SELECT 
			T0.TransId, 
			T0.RefDate, 
			T0.DueDate, 
			T0.TaxDate, 
			T0.Ref1, 
			T0.Memo,
			T0.AutoVAT,
			T0.AutoWT,
			T0.LocTotal,
			CASE WHEN T0.StornoToTr IS NULL THEN 
			CASE WHEN (SELECT COUNT(*) FROM OJDT T1 WHERE T1.TransType = 30 AND T1.StornoToTr = T0.TransId) = 1 THEN 'Y' ELSE 'N' END 
			ELSE 'Y' END AS CANCELED
		FROM OJDT T0
		WHERE T0.TransId = '$docentry'
		ORDER BY T0.TransId ASC");

$arr = array();

while (odbc_fetch_row($qry)) {
	
	$arr[] = array("TransId" => odbc_result($qry, 'TransId'),
				"RefDate" => date('m/d/Y' ,strtotime(odbc_result($qry, 'RefDate'))),
				"DueDate" => date('m/d/Y' ,strtotime(odbc_result($qry, 'DueDate'))),
				"TaxDate" => date('m/d/Y' ,strtotime(odbc_result($qry, 'TaxDate'))),
				"Ref1" => utf8_encode(odbc_result($qry, 'Ref1')),
				"Memo" => utf8_encode(odbc_result($qry, 'Memo')),
				"AutoVAT" => utf8_encode(odbc_result($qry, 'AutoVAT')),
				"AutoWT" => utf8_encode(odbc_result($qry, 'AutoWT')),
				"CANCELED" => utf8_encode(odbc_result($qry, 'CANCELED')),
				"LocTotal" => number_format(odbc_result($qry, 'LocTotal'),2)
				);
            
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);


echo json_encode($arr);
?>