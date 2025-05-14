<?php include_once('../../../config/config.php');?>

<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblInvData">
	  <thead>
	    <tr>
			<th>Item</th>
			<th>Item Name</th>
			<th>Enterprises<br>In Stock</th>
			<th>Traders<br>In Stock</th>

	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$itemcode = $_GET['itemcode'];
	  		
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.ItemCode, 
																		T0.ItemName, 
																		(SELECT T1.OnHand FROM [HIRAM_LIVE].[dbo].[OITW] T1 WHERE T1.WhsCode = '01' AND T1.ItemCode = T0.ItemCode) AS Enterprises,
																		(SELECT T2.OnHand FROM [357TRADERS_LIVE].[dbo].[OITW] T2 WHERE T2.WhsCode = '01' AND T2.ItemCode = T0.ItemCode) AS Traders
																FROM OITM T0
																WHERE T0.ItemCode = '$itemcode'");
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch">
						<td class="item-1">'.odbc_result($qry, 'ItemCode').'</td>
						<td class="item-2">'.odbc_result($qry, 'ItemName').'</td>
						<td class="item-3">'.number_format(odbc_result($qry, 'Enterprises'),2).'</td>
						<td class="item-4">'.number_format(odbc_result($qry, 'Traders'),2).'</td>
				      </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
