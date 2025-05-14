<?php include_once('../../../config/config.php');

	$itemcode = $_POST['itemcode'];
	$listnum = $_POST['listnum'];
	$price = 0;
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT T0.Price 
																								FROM ITM1 T0
																								WHERE T0.ItemCode = '$itemcode' 
																								AND T0.PriceList = '1'";

	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) 
	{
		$price = odbc_result($qry, 'Price');
	}
		
echo number_format($price,4);
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>