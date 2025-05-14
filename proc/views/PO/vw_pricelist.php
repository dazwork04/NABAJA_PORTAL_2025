<?php include_once('../../../config/config.php');

	$itemcode = $_POST['itemcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT T0.Price 
																								FROM ITM1 T0
																								WHERE T0.ItemCode = '$itemcode' 
																								AND T0.PriceList = '1'";

	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) 
		{
			echo odbc_result($qry, 'Price');
			
		}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>