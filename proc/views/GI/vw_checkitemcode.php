<?php 
include_once('../../../config/config.php');


$itemcode = $_POST['itemcode'];

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT T0.ItemCode, T0.ItemName, T0.OnHand, T0.InvntryUom
        , T0.BuyUnitMsr, T0.ManBtchNum, T0.NumInBuy, T0.TreeType, T0.TreeQty, T1.DecreasAc, T1.IncreasAc
	FROM OITM T0
        LEFT JOIN OITB T1
        ON T0.ItmsGrpCod = T1.ItmsGrpCod
	WHERE T0.frozenFor = 'N' 
	AND T0.ItemCode = '$itemcode'");

odbc_fetch_row($qry);

echo odbc_result($qry, 'ItemCode') . ';' . odbc_result($qry, 'ItemName') . ';' . odbc_result($qry, 'OnHand') . ';' 
        . odbc_result($qry, 'InvntryUom') . ';' . odbc_result($qry, 'BuyUnitMsr') . ';' . odbc_result($qry, 'ManBtchNum') . ';' 
        . odbc_result($qry, 'NumInBuy') . ';' . odbc_result($qry, 'TreeType') . ';' . odbc_result($qry, 'TreeQty') . ';' 
        . odbc_result($qry, 'DecreasAc') . ';' . odbc_result($qry, 'IncreasAc') . ';' ;

odbc_free_result($qry);

odbc_close($MSSQL_CONN);

?>