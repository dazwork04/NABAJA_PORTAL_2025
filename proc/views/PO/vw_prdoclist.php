<?php include_once('../../../config/config.php');
?>

<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblPRDocument">
	  <thead>
	    <tr>
			<th class="hidden">DocEntry</th>
			<th>PR Doc No.</th>
			<th>Posting Date</th>
			<th>Requester</th>
			<th>Remarks</th>
		</tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$servicetype = $_GET['servicetype'];
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT T0.DocEntry,
																				T0.DocNum,
																				T0.ReqName,
																				T0.Comments,
																				T0.DocDate,
																				T0.DocStatus, 
																				T0.CANCELED
																			FROM [OPRQ] T0
																			WHERE T0.DocStatus = 'O'
																			ORDER BY T0.DocEntry DESC");
			while (odbc_fetch_row($qry)) {
				if(odbc_result($qry, 'DocStatus') == 'C')
				{
					$DocStatus = 'Closed';
				}
				else
				{
					$DocStatus = 'Open';
				}
				
				if(odbc_result($qry, 'CANCELED') == 'Y')
				{
					$Canceled = 'Canceled';
				}
				else
				{
					$Canceled = $DocStatus;
				}
				echo '<tr class="srch">
						<td class="item-1 hidden">'.odbc_result($qry, 'DocEntry').'</td>
						<td class="item-2">'.odbc_result($qry, 'DocNum').'</td>
						<td class="item-3">'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
						<td class="item-5">'.odbc_result($qry, 'ReqName').'</td>
						<td class="item-8">'.odbc_result($qry, 'Comments').'</td>
					  </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
