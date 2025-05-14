<?php include_once('../../../config/config.php');


	$itemcode = $_POST['itemcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 1 T0.DocEntry, T1.Price FROM OPRQ T0
										INNER JOIN PRQ1 T1 ON T0.DocEntry = T1.DocEntry 
										WHERE T0.CANCELED = 'N'
										ORDER BY T0.DocEntry DESC";

	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) 
		{
			echo odbc_result($qry, 'Price');
		}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>