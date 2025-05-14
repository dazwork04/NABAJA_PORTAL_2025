<?php 
include_once('../../../config/config.php');


$itemcode = $_POST['itemcode'];

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT ItemCode, ItemName, OnHand, InvntryUom, BuyUnitMsr, ManBtchNum, NumInBuy, TreeType, TreeQty 
	FROM OITM 
	WHERE validFor = 'Y' 
	AND ItemCode = '$itemcode'");

odbc_fetch_row($qry);

echo odbc_result($qry, 'ItemCode') . ';' . odbc_result($qry, 'ItemName') . ';' . odbc_result($qry, 'OnHand') . ';' . odbc_result($qry, 'InvntryUom') . ';' . odbc_result($qry, 'BuyUnitMsr') . ';' . odbc_result($qry, 'ManBtchNum') . ';' . odbc_result($qry, 'NumInBuy') . ';' . odbc_result($qry, 'TreeType') . ';' . odbc_result($qry, 'TreeQty');

odbc_free_result($qry);

odbc_close($MSSQL_CONN);

?>