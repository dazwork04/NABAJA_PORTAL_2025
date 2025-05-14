<?php

include('../../../config/config.php');

$itemcode = $_POST['itemcode'];

if($itemcode == "" )
{
	$qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "]; 
	SELECT ItemCode,
		ItemName, 
		OnHand, 
		InvntryUom, 
		BuyUnitMsr, 
		ManBtchNum, 
		NumInBuy, 
		TreeType, 
		TreeQty, 
		VatGroupPu, 
		VatGourpSa
	FROM OITM 
	WHERE frozenFor = 'N' ");

	odbc_fetch_row($qry);

	echo 
	odbc_result($qry, 'ItemCode') . ';' . 
	utf8_encode(odbc_result($qry, 'ItemName')) . ';' . 
	odbc_result($qry, 'OnHand') . ';' . 
	odbc_result($qry, 'InvntryUom') . ';' . 
	odbc_result($qry, 'BuyUnitMsr') . ';' . 
	odbc_result($qry, 'ManBtchNum') . ';' . 
	odbc_result($qry, 'NumInBuy') . ';' . 
	odbc_result($qry, 'TreeType') . ';' . 
	odbc_result($qry, 'TreeQty') . ';' . 
	odbc_result($qry, 'VatGroupPu') . ';' . 
	odbc_result($qry, 'VatGourpSa');

	odbc_free_result($qry);
}
else
{
	
	$qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "]; SELECT ItemCode, ItemName, OnHand, InvntryUom, BuyUnitMsr, ManBtchNum, NumInBuy, TreeType, TreeQty, VatGroupPu, VatGourpSa
	FROM OITM 
	WHERE frozenFor = 'N' 
	AND ItemCode = '$itemcode'");

	odbc_fetch_row($qry);

	echo 
	odbc_result($qry, 'ItemCode') . ';' . 
	utf8_encode(odbc_result($qry, 'ItemName')) . ';' . 
	odbc_result($qry, 'OnHand') . ';' . 
	odbc_result($qry, 'InvntryUom') . ';' . 
	odbc_result($qry, 'BuyUnitMsr') . ';' . 
	odbc_result($qry, 'ManBtchNum') . ';' . 
	odbc_result($qry, 'NumInBuy') . ';' . 
	odbc_result($qry, 'TreeType') . ';' . 
	odbc_result($qry, 'TreeQty') . ';' . 
	odbc_result($qry, 'VatGroupPu') . ';' . 
	odbc_result($qry, 'VatGourpSa');

	odbc_free_result($qry);

}
//odbc_close($MSSQL_CONN);
?>