<?php 
include_once('../../../config/config.php');


$itemcode = $_POST['itemcode'];


$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
				SELECT T0.ItemCode, 
					T0.ItemName, 
					T0.OnHand, 
					T0.InvntryUom, 
					T0.BuyUnitMsr, 
					T0.ManBtchNum, 
					T0.NumInBuy, 
					T0.TreeType, 
					T0.TreeQty, 
					T0.DfltWH,
					T1.Price
			FROM OITM T0
			LEFT JOIN ITM1 T1 ON T0.ItemCode = T1.ItemCode AND T1.PriceList = 1
			WHERE T0.frozenFor = 'N' 
			AND T0.ItemCode = '$itemcode'");

odbc_fetch_row($qry);

echo odbc_result($qry, 'ItemCode') . ';' . odbc_result($qry, 'ItemName') . ';' . odbc_result($qry, 'OnHand') . ';' . odbc_result($qry, 'InvntryUom') . ';' . odbc_result($qry, 'BuyUnitMsr') . ';' . odbc_result($qry, 'ManBtchNum') . ';' . odbc_result($qry, 'NumInBuy') . ';' . odbc_result($qry, 'TreeType') . ';' . odbc_result($qry, 'TreeQty') . ';' . odbc_result($qry, 'DfltWH') . ';' . odbc_result($qry, 'Price');

odbc_free_result($qry);

odbc_close($MSSQL_CONN);

?>