<?php include_once('../../../config/config.php');
$manufacturer = $_SESSION['SESS_MAN'];
$pos = $_SESSION['SESS_POS'];
$multimanu = $_SESSION['SESS_MULTIMAN'];

?>

<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblItem">
	  <thead>
	    <tr>
	      <th>Item Code</th>
	      <th>Item Name</th>
		  <th class="">In Stock</th>
	      <th class="hidden">Inventory Uom</th>
	      <th class="hidden">Purchasing Uom</th>
	      <th class="hidden">ManBatch</th>
	      <th class="hidden">NumInBuy</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
		
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 
																		T0.ItemCode, 
																		T0.ItemName, 
																		T0.OnHand, 
																		T0.InvntryUom, 
																		T0.BuyUnitMsr, 
																		T0.ManBtchNum, 
																		T0.NumInBuy, 
																		T0.DfltWH
																FROM OITM T0
																WHERE T0.frozenFor = 'N' 
																ORDER BY T0.ItemCode, T0.ItemName");
		
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch" pageloadid="'.odbc_result($qry, 'ItemCode').'">
						<td class="item-1">'.odbc_result($qry, 'ItemCode').'</td>
						<td class="item-2">'.utf8_encode(odbc_result($qry, 'ItemName')).'</td>
						<td class="item-3">'.number_format(odbc_result($qry, 'OnHand'),2,'.',',').'</td>
						<td class="hidden item-4">'.odbc_result($qry, 'InvntryUom').'</td>
						<td class="hidden item-5">'.odbc_result($qry, 'BuyUnitMsr').'</td>
						<td class="hidden item-6">'.odbc_result($qry, 'ManBtchNum').'</td>
						<td class="hidden item-7">'.odbc_result($qry, 'NumInBuy').'</td>
						<td class="hidden item-8">'.odbc_result($qry, 'DfltWH').'</td>
				      </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
