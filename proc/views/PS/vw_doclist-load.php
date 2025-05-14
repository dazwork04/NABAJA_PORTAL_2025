<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval']))
{
	$srchval = str_replace("'", "''", $_GET['srchval']);
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; 
																					SELECT TOP 20 T0.PrjCode, 
																							T0.PrjName,
																							T0.Active
																					FROM OPRJ T0
																					WHERE (T0.PrjCode LIKE '%$srchval%' 
																					OR T0.PrjName LIKE '%$srchval%')
																					ORDER BY T0.PrjCode DESC";
}
else
{
	$itemcode = $_POST['itemcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; 
																					SELECT TOP 20 T0.PrjCode, 
																							T0.PrjName,
																							T0.Active
																					FROM OPRJ T0
																					WHERE T0.PrjCode < '".$itemcode."'
																					ORDER BY T0.PrjCode DESC";
}

	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	
	while (odbc_fetch_row($qry)) 
	{
		echo '<tr class="srch">
						<td class="item-1 hidden">'.odbc_result($qry, 'PrjCode').'</td>
						<td class="item-2">'.odbc_result($qry, 'PrjCode').'</td>
						<td class="item-3">'.odbc_result($qry, 'PrjName').'</td>
						<td class="item-7 hidden">'.odbc_result($qry, 'Active').'</td>
					</tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
