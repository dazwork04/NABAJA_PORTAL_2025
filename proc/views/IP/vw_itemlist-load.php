<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval'])){
	$srchval = str_replace("'", "''", $_GET['srchval']);
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 100 ItemCode, ItemName, OnHand, InvntryUom, BuyUnitMsr, ManBtchNum, NumInBuy FROM OITM WHERE frozenFor = 'N' AND (PrchseItem = 'Y' OR InvntItem = 'Y') AND (ItemCode LIKE '%".$srchval."%' OR ItemName LIKE '%".$srchval."%') ORDER BY ItemCode,ItemName";
}else{
	$itemcode = $_POST['itemcode'];
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 50 ItemCode, ItemName, OnHand, InvntryUom, BuyUnitMsr, ManBtchNum, NumInBuy FROM OITM WHERE frozenFor = 'N' AND (PrchseItem = 'Y' OR InvntItem = 'Y') AND ItemCode > '".$itemcode."' ORDER BY ItemCode,ItemName";	
}


?>



<?php
	$qry = odbc_exec($MSSQL_CONN, $itemqry);
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
