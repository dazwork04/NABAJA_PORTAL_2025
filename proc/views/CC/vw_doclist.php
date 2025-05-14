<?php include_once('../../../config/config.php');
?>

<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblDocument">
	  <thead>
	    <tr>
			<th class="hidden">PrcCode</th>
			<th style="min-width:50px;">Center Code</th>
			<th style="min-width:50px;">Center Name</th>
			<th style="min-width:50px;">Dimension</th>
			<th style="min-width:50px;">Effective Date</th>
		</tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
																					SELECT TOP 20 T0.PrcCode, 
																							T0.PrcName, 
																							T0.DimCode, 
																							T1.DimDesc, 
																							CASE WHEN T0.ValidFrom = '' THEN '' ELSE CONVERT(VARCHAR(10),T0.ValidFrom,101) END AS EffectiveDate, 
																							T0.Active
																					FROM OPRC T0
																					INNER JOIN ODIM T1 ON T0.DimCode = T1.DimCode
																					ORDER BY T0.PrcCode DESC");
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
	  </tbody>
    </table>
</div>

