<?php include_once('../../../config/config.php');
?>

<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblDocument">
	  <thead>
	    <tr>
			<th class="hidden">TransId</th>
			<th style="min-width:50px;">J.E. No.</th>
			<th style="min-width:50px;">Posting Date</th>
			<th style="min-width:50px;">Ref No.</th>
			<th style="min-width:50px;">Remarks</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
																					SELECT TOP 20 T0.TransId, 
																										CONVERT(varchar, T0.RefDate, 101) AS RefDate, 
																										T0.Ref1, 
																										T0.Memo 
																					FROM OJDT T0 
																					WHERE T0.TransType = 30
																					ORDER BY T0.TransId DESC");
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
	  </tbody>
    </table>
</div>

