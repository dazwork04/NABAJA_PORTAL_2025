<?php include_once('../../../config/config.php');

	$itemcode = $_POST['itemcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT 
																								CASE WHEN T1.UDF1 IS NULL THEN T2.StockAct ELSE T1.StockAct END AS InventoryAccount,
																								CASE WHEN T1.UDF1 IS NULL THEN T4.AcctName ELSE T3.AcctName END AS AccountName
																						FROM OITM T0
																						LEFT JOIN OGAR T1 ON T0.U_SubGrpName = T1.UDF1
																						LEFT JOIN OGAR T2 ON T0.ItmsGrpCod = T2.ItmsGrpCod
																						LEFT JOIN OACT T3 ON T1.StockAct = T3.AcctCode
																						LEFT JOIN OACT T4 ON T2.StockAct = T4.AcctCode
																						WHERE T0.ItemCode = '$itemcode' ";

	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) 
		{
			echo odbc_result($qry, 'InventoryAccount') . '-' . odbc_result($qry, 'AccountName');
			
		}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>