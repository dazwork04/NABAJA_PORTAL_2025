<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval']))
{
	$srchval = str_replace("'", "''", $_GET['srchval']);
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 
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
										AND (T0.ItemCode LIKE '%".$srchval."%' OR T0.ItemName LIKE '%".$srchval."%')
										ORDER BY T0.ItemCode, T0.ItemName";
}
else
{
	$itemcode = $_POST['itemcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 
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
										AND T0.ItemCode > '".$itemcode."' 
										ORDER BY T0.ItemCode, T0.ItemName";	
}

	$qry = odbc_exec($MSSQL_CONN, $itemqry);
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
