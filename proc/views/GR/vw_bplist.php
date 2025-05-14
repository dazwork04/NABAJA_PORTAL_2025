<?php include_once('../../../config/config.php');
$CardType = $_GET['CardType'];
?>
<div class="table-responsive" style="max-height:350px;overflow-y:scroll;">
	<table class="table table-condensed table-hover" id="tblBP">
	  <thead>
	    <tr>
	      <th>BP Code</th>
	      <th>BP Name</th>
	      <th>Balance</th>
	      <th class="hidden">Contact Person</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 100 T0.CardCode,T0.CardName,T0.Balance,T0.CntctPrsn 
																  			FROM OCRD T0 
																  			WHERE T0.CardType = '$CardType' AND frozenFor = 'N'
																  			ORDER BY T0.CardCode");
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch">
						<td class="item-1">'.odbc_result($qry, 'CardCode').'</td>
						<td class="item-2">'.odbc_result($qry, 'CardName').'</td>
						<td class="item-3">'.number_format(odbc_result($qry, 'Balance'),2,'.',',').'</td>
						<td class="item-4 hidden">'.odbc_result($qry, 'CntctPrsn').'</td>
				      </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
