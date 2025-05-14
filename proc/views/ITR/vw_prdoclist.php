<?php include_once('../../../config/config.php');?>

<div class="table-responsive" style="max-height:350px;overflow-y:scroll;">
	<table class="table table-condensed table-hover" id="tblPRDocument">
	  <thead>
	    <tr>
	      <th class="hidden">DocEntry</th>
	      <th>Document Number</th>
	      <th>Name</th>
	      <th>Remarks</th>
	      <th>Document Date</th>
	      <th class="hidden">Document Status</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$servicetype = $_GET['servicetype'];
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."]; SELECT T0.DocEntry,T0.DocNum,T1.Name,T0.Remarks,CONVERT(VARCHAR(10),T0.DocDate,101) AS DocDate,T0.DocStatus 
																			FROM [@OPRQ] T0
																			LEFT JOIN [@OUSR] T1
																			ON T0.Requester = T1.UserCode
																			WHERE T0.DocStatus = 'O' AND ServiceType = '$servicetype'
																			ORDER BY T0.DocEntry");
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch">
						<td class="item-1 hidden">'.odbc_result($qry, 'DocEntry').'</td>
						<td class="item-2">'.odbc_result($qry, 'DocNum').'</td>
						<td class="item-3">'.odbc_result($qry, 'Name').'</td>
						<td class="item-4">'.odbc_result($qry, 'Remarks').'</td>
						<td class="item-5">'.odbc_result($qry, 'DocDate').'</td>
						<td class="item-6 hidden">'.odbc_result($qry, 'DocStatus').'</td>
				      </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
