<?php include_once('../../../config/config.php');?>

<div class="table-responsive" style="max-height:350px;overflow-y:scroll;">
	<table class="table table-condensed table-hover" id="tblItem">
	  <thead>
	    <tr>
	      <th>Item Code</th>
	      <th>Item Name</th>
	      <th>In Stock</th>
	      <th class="hidden">Inventory Uom</th>
	      <th class="hidden">Purchasing Uom</th>
	      <th class="hidden">ManBatch</th>
	      <th class="hidden">NumInBuy</th>

	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 100 ItemCode, ItemName, OnHand, InvntryUom, BuyUnitMsr, ManBtchNum, NumInBuy FROM OITM WHERE frozenFor = 'N' AND (PrchseItem = 'Y' OR InvntItem = 'Y') ORDER BY ItemCode,ItemName");
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch" pageloadid="'.odbc_result($qry, 'ItemCode').'">
						<td class="item-1">'.odbc_result($qry, 'ItemCode').'</td>
						<td class="item-2">'.odbc_result($qry, 'ItemName').'</td>
						<td class="item-3">'.number_format(odbc_result($qry, 'OnHand'),2,'.',',').'</td>
						<td class="hidden item-4">'.odbc_result($qry, 'InvntryUom').'</td>
						<td class="hidden item-5">'.odbc_result($qry, 'BuyUnitMsr').'</td>
						<td class="hidden item-6">'.odbc_result($qry, 'ManBtchNum').'</td>
						<td class="hidden item-7">'.odbc_result($qry, 'NumInBuy').'</td>
				      </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
