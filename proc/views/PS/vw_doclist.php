<?php include_once('../../../config/config.php');
?>

<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblDocument">
	  <thead>
	    <tr>
			<th class="hidden">Project Code</th>
			<th style="min-width:50px;">Project Code</th>
			<th style="min-width:50px;">Project Name</th>
		</tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
																					SELECT TOP 20 T0.PrjCode, 
																							T0.PrjName,
																							T0.Active
																					FROM OPRJ T0
																					ORDER BY T0.PrjCode DESC");
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
	  </tbody>
    </table>
</div>

