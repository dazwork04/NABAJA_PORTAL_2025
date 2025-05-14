<?php 
include_once('../../../config/config.php');

$BranchCode =	$_SESSION['SESS_BRANCH'];
$itemcode = $_POST['itemcode'];

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
				SELECT DISTINCT -- OIGN / Goods Receipt --
	   
					-- IGN1 / Goods Receipt Lines --
					
					A.DocEntry,
					B.ItemCode,
					E.FrgnName,
					REPLICATE('0',10-LEN(RTRIM(B.U_Barcode))) + RTRIM(B.U_Barcode) AS 'Barcode',
					B.WhsCode,
					K.DistNumber,
					K.SysNumber,
					L.Price
						
				FROM OIGN A
					 INNER JOIN IGN1 B ON A.DocEntry = B.DocEntry
					 INNER JOIN OITM E ON B.ItemCode = E.ItemCode
					 LEFT JOIN OITL I ON B.DocEntry = I.ApplyEntry AND B.LineNum = I.ApplyLine AND B.ObjType = I.ApplyType AND B.WhsCode = I.LocCode
					 LEFT JOIN ITL1 J ON I.LogEntry = J.LogEntry
					 LEFT JOIN OSRN K ON J.ItemCode = K.ItemCode AND J.MdAbsEntry = K.AbsEntry
					 LEFT JOIN ITM1 L ON B.ItemCode = L.ItemCode AND L.PriceList = 3
					 
					 WHERE B.WhsCode = '$BranchCode' AND E.frozenFor = 'N' AND A.CANCELED = 'N' AND B.Quantity > 0  AND K.DistNumber = '$itemcode'

					 
					 UNION ALL

				SELECT DISTINCT -- OWTR / Invetory Transfer --
					   
					-- WTR1 / Invetory Transfer Lines --
				
					A.DocEntry,
					B.ItemCode,
					E.FrgnName,
					REPLICATE('0',10-LEN(RTRIM(B.U_Barcode))) + RTRIM(B.U_Barcode) AS 'Barcode',
					B.WhsCode,
					K.DistNumber,
					K.SysNumber,
					L.Price

				FROM OWTR A
					 INNER JOIN WTR1 B ON A.DocEntry = B.DocEntry
					 INNER JOIN OITM E ON B.ItemCode = E.ItemCode
					 LEFT JOIN OITL I ON B.DocEntry = I.ApplyEntry AND B.LineNum = I.ApplyLine AND B.ObjType = I.ApplyType  AND B.WhsCode = I.LocCode
					 LEFT JOIN ITL1 J ON I.LogEntry = J.LogEntry
					 LEFT JOIN OSRN K ON J.ItemCode = K.ItemCode AND J.MdAbsEntry = K.AbsEntry
					 LEFT JOIN ITM1 L ON B.ItemCode = L.ItemCode AND L.PriceList = 3
					 
					 WHERE B.WhsCode = '$BranchCode' AND E.frozenFor = 'N' AND A.CANCELED = 'N' AND B.Quantity > 0 AND K.DistNumber = '$itemcode' ");

odbc_fetch_row($qry);

echo odbc_result($qry, 'ItemCode') . ';' . odbc_result($qry, 'FrgnName') . ';' . odbc_result($qry, 'DistNumber') . ';' . odbc_result($qry, 'SysNumber'). ';' . odbc_result($qry, 'Barcode'). ';' . odbc_result($qry, 'Price');

odbc_free_result($qry);

odbc_close($MSSQL_CONN);

?>