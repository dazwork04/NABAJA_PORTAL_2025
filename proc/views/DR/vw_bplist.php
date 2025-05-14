<?php include_once('../../../config/config.php');
$CardType = $_GET['CardType'];

?>
<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblBP">
	  <thead>
	    <tr>
	      <th>BP Code</th>
	      <th>BP Name</th>
	      <th class="hidden">Balance</th>
	      <th class="hidden">Contact Person</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.CardCode,T0.CardName,T0.Balance,T0.CntctPrsn, T0.Currency, T0.ListNum, T0.GroupNum
																  			FROM OCRD T0 
																  			WHERE T0.CardType = '$CardType' AND frozenFor = 'N'
																  			ORDER BY T0.CardCode");
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch">
						<td class="item-1">'.odbc_result($qry, 'CardCode').'</td>
						<td class="item-2">'.utf8_encode(odbc_result($qry, 'CardName')).'</td>
						<td class="item-3 hidden">'.number_format(odbc_result($qry, 'Balance'),2,'.',',').'</td>
						<td class="item-4 hidden">'.odbc_result($qry, 'CntctPrsn').'</td>
						<td class="item-5 hidden">'.odbc_result($qry, 'GroupNum').'</td>
						<td class="item-6 hidden">'.odbc_result($qry, 'Currency').'</td>
						<td class="item-7 hidden">'.odbc_result($qry, 'ListNum').'</td>
				      </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
