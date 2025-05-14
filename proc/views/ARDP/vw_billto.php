<?php include_once('../../../config/config.php');

	$cardcode = $_GET['cardcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 1 T1.Street, T1.City, T1.State, T1.ZipCode, T2.Name
				FROM OCRD T0 
				INNER JOIN CRD1 T1 ON T0.CardCode = T1.CardCode
				INNER JOIN OCRY T2 ON T1.Country = T2.Code
				WHERE T0.CardCode = '$cardcode' AND T1.AdresType = 'B'
				ORDER BY T1.Address DESC";
				
				

	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) 
		{
			echo odbc_result($qry, 'Street') . ' ';
			echo odbc_result($qry, 'City') . ' ' . odbc_result($qry, 'State') . ' ' . odbc_result($qry, 'ZipCode') . ' ';
			echo odbc_result($qry, 'Name');
		}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>