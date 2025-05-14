<?php include_once('../../../config/config.php');
 

	
?>
<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblDocument">
		<thead>
			<tr>
				<th class="hidden">DocEntry</th>
				<th>GI Doc No.</th>	  
				<th>Doc Date</th>
				<th>Ref. No.</th>
				<th>Remarks</th>
				<th class="hidden">Document Status</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.DocEntry,
																																								T0.DocNum,
																																								T0.Comments,
																																								T0.DocDate,
																																								T0.Ref2,
																																								T0.DocStatus 
																																	FROM [OIGE] T0
																																	
																																	ORDER BY T0.DocEntry DESC");
				while (odbc_fetch_row($qry)) {
					echo '<tr class="srch">
							<td class="item-1 hidden">'.odbc_result($qry, 'DocEntry').'</td>
							<td class="item-2">'.odbc_result($qry, 'DocNum').'</td>
							<td class="item-4">'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
							<td class="item-3">'.odbc_result($qry, 'Ref2').'</td>
							<td class="item-3">'.odbc_result($qry, 'Comments').'</td>
							<td class="item-6 hidden">'.odbc_result($qry, 'DocStatus').'</td>
						  </tr>';
				}
				odbc_free_result($qry);
				odbc_close($MSSQL_CONN);
			?>
		</tbody>
    </table>
</div>