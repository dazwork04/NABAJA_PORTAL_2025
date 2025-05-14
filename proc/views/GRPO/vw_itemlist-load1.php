<?php
session_start();
include_once('../../../config/config.php');

$manufacturer = $_SESSION['SESS_MAN'];
$pos = $_SESSION['SESS_POS'];
$multimanu = $_SESSION['SESS_MULTIMAN'];


if(isset($_GET['srchval']))
{
	
	$srchval = str_replace("'", "''", $_GET['srchval']);
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 30 T0.ItemCode, 
												T0.ItemName, 
												T0.OnHand, 
												T0.InvntryUom, 
												T0.BuyUnitMsr, 
												T0.ManBtchNum, 
												T0.NumInBuy,
												(SELECT T1.OnHand FROM [HIRAM_LIVE].[dbo].[OITW] T1 WHERE T1.WhsCode = '01' AND T1.ItemCode = T0.ItemCode) AS Enterprises,
												(SELECT T2.OnHand FROM [357TRADERS_LIVE].[dbo].[OITW] T2 WHERE T2.WhsCode = '01' AND T2.ItemCode = T0.ItemCode) AS Traders
										FROM OITM T0
										WHERE T0.frozenFor = 'N' 
										AND (T0.PrchseItem = 'Y' OR T0.InvntItem = 'Y')
										AND (T0.ItemCode LIKE '%".$srchval."%' OR T0.ItemName LIKE '%".$srchval."%') 
										ORDER BY T0.ItemCode, T0.ItemName";
}
else
{
	
	$itemcode = $_POST['itemcode'];
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 30 T0.ItemCode, 
												T0.ItemName, 
												T0.OnHand, 
												T0.InvntryUom, 
												T0.BuyUnitMsr, 
												T0.ManBtchNum, 
												T0.NumInBuy,
												(SELECT T1.OnHand FROM [HIRAM_LIVE].[dbo].[OITW] T1 WHERE T1.WhsCode = '01' AND T1.ItemCode = T0.ItemCode) AS Enterprises,
												(SELECT T2.OnHand FROM [357TRADERS_LIVE].[dbo].[OITW] T2 WHERE T2.WhsCode = '01' AND T2.ItemCode = T0.ItemCode) AS Traders
										FROM OITM T0
										WHERE T0.frozenFor = 'N' 
										AND (T0.PrchseItem = 'Y' OR T0.InvntItem = 'Y')
										AND T0.ItemCode > '".$itemcode."' 
										ORDER BY T0.ItemCode, T0.ItemName";
}


?>



<?php
	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) {
		echo '<tr class="srch" pageloadid="'.odbc_result($qry, 'ItemCode').'">
				<td class="item-1">'.odbc_result($qry, 'ItemCode').'</td>
				<td class="item-2">'.utf8_encode(odbc_result($qry, 'ItemName')).'</td>
				<td class="hidden item-3">'.number_format(odbc_result($qry, 'OnHand'),2,'.',',').'</td>
				<td class="hidden item-4">'.odbc_result($qry, 'InvntryUom').'</td>
				<td class="hidden item-5">'.odbc_result($qry, 'BuyUnitMsr').'</td>
				<td class="hidden item-6">'.odbc_result($qry, 'ManBtchNum').'</td>
				<td class="hidden item-7">'.odbc_result($qry, 'NumInBuy').'</td>
				<td class="item-8">'.number_format(odbc_result($qry, 'Enterprises'),2).'</td>
				<td class="item-9">'.number_format(odbc_result($qry, 'Traders'),2).'</td>
		      </tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
