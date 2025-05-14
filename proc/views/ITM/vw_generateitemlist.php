<?php include_once('../../../config/config.php');

$txtItemCodeFrom = $_POST['txtItemCodeFrom'];
$txtItemCodeTo = $_POST['txtItemCodeTo'];
?>

<div class="table-responsive" style="height: 200px; width:100%; border: solid lightblue 1px; resize: vertical; overflow:auto;">
	<table width="100%" border="1" class="table-striped" id="tblGenerateItem" bordercolor="lightblue">
	  <thead>
	    <tr>
			<th style="min-width:50px; height:30px;">&nbsp;Item Code</th>
	     	<th style="min-width:50px; height:30px;">&nbsp;Item Name</th>
			<th style="min-width:50px; height:30px;">&nbsp;Selling Price</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
			
			$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.ItemCode, T0.ItemName, T0.OnHand, T0.InvntryUom, T0.BuyUnitMsr, T0.ManBtchNum, T0.NumInBuy, T0.DfltWH, T0.ItmsGrpCod, T0.frozenFor, (SELECT T5.Price FROM ITM1 T5 WHERE T5.ItemCode = T0.ItemCode AND T5.PriceList = 1) AS Price 
																	FROM OITM T0 
																WHERE T0.ItemCode BETWEEN '$txtItemCodeFrom' AND '$txtItemCodeTo' AND T0.frozenFor = 'N'
																ORDER BY T0.ItemCode, T0.ItemName");
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch" pageloadid="'.odbc_result($qry, 'ItemCode').'">
						<td class="item-1" style="padding-top: 2px;  padding-bottom: 2px;"><input type="hidden" class="form-control input-sm itemcode" value="'.odbc_result($qry, 'ItemCode').'">'.odbc_result($qry, 'ItemCode').'</td>
						<td class="item-2">&nbsp;'.utf8_encode(odbc_result($qry, 'ItemName')).'</td>
						<td class="item-11" style="padding-top: 2px;  padding-bottom: 2px;"><input type="text" class="form-control input-sm itemcodeprice" value="'.number_format(odbc_result($qry, 'Price'),2).'" maxlength="9"></td>
				      </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
