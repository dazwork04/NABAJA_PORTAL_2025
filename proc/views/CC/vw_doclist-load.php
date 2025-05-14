<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval']))
{
	$srchval = str_replace("'", "''", $_GET['srchval']);
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.PrcCode, 
																							T0.PrcName, 
																							T0.DimCode, 
																							T1.DimDesc, 
																							CASE WHEN T0.ValidFrom = '' THEN '' ELSE CONVERT(VARCHAR(10),T0.ValidFrom,101) END AS EffectiveDate, 
																							T0.Active
																					FROM OPRC T0
																					INNER JOIN ODIM T1 ON T0.DimCode = T1.DimCode
																					WHERE (CASE WHEN T0.ValidFrom = '' THEN '' ELSE CONVERT(VARCHAR(10),T0.ValidFrom,101) END LIKE '%$srchval%' 
																					OR T0.PrcCode LIKE '%$srchval%' 
																					OR T0.PrcName LIKE '%$srchval%' 
																					OR T1.DimDesc LIKE '%$srchval%')
																					ORDER BY T0.PrcCode DESC";
}
else
{
	$itemcode = $_POST['itemcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.PrcCode, 
																							T0.PrcName, 
																							T0.DimCode, 
																							T1.DimDesc, 
																							CASE WHEN T0.ValidFrom = '' THEN '' ELSE CONVERT(VARCHAR(10),T0.ValidFrom,101) END AS EffectiveDate, 
																							T0.Active
																					FROM OPRC T0
																					INNER JOIN ODIM T1 ON T0.DimCode = T1.DimCode
																					WHERE T0.PrcCode < '".$itemcode."'
																					ORDER BY T0.PrcCode DESC";
}

	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	
	while (odbc_fetch_row($qry)) 
	{
		echo '<tr class="srch">
						<td class="item-1 hidden">'.odbc_result($qry, 'PrcCode').'</td>
						<td class="item-2">'.odbc_result($qry, 'PrcCode').'</td>
						<td class="item-3">'.utf8_encode(odbc_result($qry, 'PrcName')).'</td>
						<td class="item-4">'.odbc_result($qry, 'DimDesc').'</td>
						<td class="item-5">'.odbc_result($qry, 'EffectiveDate').'</td>
						<td class="item-6 hidden">'.odbc_result($qry, 'DimCode').'</td>
						<td class="item-7 hidden">'.odbc_result($qry, 'Active').'</td>
					</tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
