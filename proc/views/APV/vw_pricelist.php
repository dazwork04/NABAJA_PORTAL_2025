<?php include_once('../../../config/config.php');


	$itemcode = $_POST['itemcode'];
	$listnum = $_POST['listnum'];
	
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT FORMAT(Price,'N2') as Price FROM ITM1 
												WHERE ItemCode = '$itemcode' AND PriceList = '1'";

	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) 
		{
			echo odbc_result($qry, 'Price');
			
		}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>