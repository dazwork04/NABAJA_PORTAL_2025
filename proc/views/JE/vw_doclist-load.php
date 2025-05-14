<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval']))
{
	$srchval = str_replace("'", "''", $_GET['srchval']);
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.TransId, 
																					CONVERT(varchar, T0.RefDate, 101) AS RefDate, 
																					T0.Ref1, 
																					T0.Memo 
																				FROM OJDT T0
																				WHERE T0.TransType = 30 AND CONVERT(varchar, T0.RefDate, 101)  LIKE   '%$srchval%' OR T0.Ref1 LIKE '%$srchval%' OR T0.Memo LIKE '%$srchval%' 
																				ORDER BY T0.TransId DESC";
}
else
{
	$itemcode = $_POST['itemcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.TransId, CONVERT(varchar, T0.RefDate, 101) AS RefDate, T0.Ref1, T0.Memo FROM OJDT T0
												WHERE T0.TransId < '".$itemcode."' AND T0.TransType = 30
												ORDER BY T0.TransId DESC";
}

	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	
	while (odbc_fetch_row($qry)) 
	{
		echo '<tr class="srch">
						<td class="item-1 hidden">'.odbc_result($qry, 'TransId').'</td>
						<td class="item-2">'.odbc_result($qry, 'TransId').'</td>
						<td class="item-3">'.odbc_result($qry, 'RefDate').'</td>
						<td class="item-4">'.odbc_result($qry, 'Ref1').'</td>
						<td class="item-4">'.odbc_result($qry, 'Memo').'</td>
					</tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
