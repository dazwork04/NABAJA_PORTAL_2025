<?php include_once('../../../config/config.php');?>

<div class="table-responsive" style="max-height:350px;overflow-y:scroll;">
	<table class="table table-condensed table-hover" id="tblInvData">
	  <thead>
	    <tr>
	      <th>Item</th>
	      <th>Item Name</th>
	      <th>Warehouse Name</th>
	      <th>On Hand</th>

	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$itemcode = $_GET['itemcode'];
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT T0.ItemCode,T2.ItemName,T1.WhsName,T0.OnHand 
	  																		FROM OITW T0
																			INNER JOIN OWHS T1
																			ON T0.WhsCode = T1.WhsCode
																			INNER JOIN OITM T2
																			ON T0.ItemCode = T2.ItemCode
																			WHERE T0.ItemCode = '$itemcode'");
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch">
						<td class="item-1">'.odbc_result($qry, 1).'</td>
						<td class="item-2">'.odbc_result($qry, 2).'</td>
						<td class="item-3">'.odbc_result($qry, 3).'</td>
						<td class="item-4">'.number_format(odbc_result($qry, 4),2,'.',',').'</td>
				      </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
