<?php 
include_once('../../../config/config.php');

$BranchCode =	$_SESSION['SESS_BRANCH'];
$barcode = $_POST['barcode'];

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
				SELECT DISTINCT 
					   -- OPDN --
					   T0.DocNum,
					   T0.DocEntry,
					   T0.CardCode,
					   T0.CardName,
					   T0.ObjType,
					   -- PDN1 --
					   T1.ItemCode,
					   T1.Dscription,
					   T1.Quantity,
					   T1.LineNum,
					   T1.Price,
					   T1.PriceAfVAT,
					   REPLICATE('0',10-LEN(RTRIM(T1.U_Barcode))) + RTRIM(T1.U_Barcode) AS 'U_Barcode',
					   T1.LineStatus,

					   -- OITM --
					   T2.FrgnName,

					   -- OSRN --
					   T5.SysNumber,
					   T3.LocCode,
					   T5.DistNumber
					
				FROM OPDN T0
					 INNER JOIN PDN1 T1 ON T0.DocEntry = T1.DocEntry
					 INNER JOIN OITM T2 ON T1.ItemCode = T2.ItemCode
					 LEFT JOIN OITL T3 ON T1.DocEntry = T3.ApplyEntry AND T1.LineNum = T3.ApplyLine AND T1.ObjType = T3.ApplyType
					 LEFT JOIN ITL1 T4 ON T3.LogEntry = T4.LogEntry
					 LEFT JOIN OSRN T5 ON T4.ItemCode = T5.ItemCode AND T4.MdAbsEntry = T5.AbsEntry
				WHERE T1.U_Barcode IS NOT NULL AND REPLICATE('0',10-LEN(RTRIM(T1.U_Barcode))) + RTRIM(T1.U_Barcode) = $barcode
				ORDER BY T0.DocEntry ASC");

odbc_fetch_row($qry);

echo odbc_result($qry, 'ItemCode') . ';' . odbc_result($qry, 'FrgnName') . ';' . odbc_result($qry, 'DistNumber') . ';' . odbc_result($qry, 'SysNumber'). ';' . odbc_result($qry, 'Quantity');

odbc_free_result($qry);

odbc_close($MSSQL_CONN);

?>