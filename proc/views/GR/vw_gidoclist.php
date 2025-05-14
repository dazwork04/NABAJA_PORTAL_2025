<?php include_once('../../../config/config.php');
?>

<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblPODocument">
		<thead>
			<tr>
				<th class="hidden">DocEntry</th>
				<th>GI Doc No.</th>	  
				<th>GI Date</th>
				<th>Remarks</th>
				<th class="hidden">Document Status</th>
			</tr>
		</thead>
		<tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.DocEntry,T0.DocNum,T0.Comments,T0.DocDate,T0.DocStatus, (SELECT COUNT(DISTINCT T1.LineStatus) AS LineStatus FROM IGE1 T1 WHERE T1.DocEntry = T0.DocEntry AND T1.LineStatus = 'O') AS DocStatus1 
																		FROM [OIGE] T0
																	ORDER BY T0.DocEntry DESC");
			while (odbc_fetch_row($qry)) 
			{
				if(odbc_result($qry, 'DocStatus1') != 0)
				{
					echo '<tr class="srch">
							<td class="item-1 hidden">'.odbc_result($qry, 'DocEntry').'</td>
							<td class="item-2">'.odbc_result($qry, 'DocNum').'</td>
							<td class="item-4">'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
							<td class="item-3">'.odbc_result($qry, 'Comments').'</td>
							<td class="item-6 hidden">'.odbc_result($qry, 'DocStatus').'</td>
						  </tr>';
				}
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
		</tbody>
    </table>
</div>
