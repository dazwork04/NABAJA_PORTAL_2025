<?php

include_once('../../../config/config.php');
$vendor = isset($_GET['vendor']) ? $_GET['vendor'] : '';
if($vendor != '')
{
	if(isset($_GET['srchval']))
	{
		$srchval = str_replace("'", "''", $_GET['srchval']);
		
		$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.DocEntry,T0.DocNum,T0.Comments,T0.DocDate,T0.NumAtCard,T0.DocStatus , T0.CardName, T0.CANCELED
													FROM [ORDR] T0
													WHERE (T0.DocNum LIKE '%$srchval%' OR T0.Comments LIKE '%$srchval%' OR CONVERT(VARCHAR(10),T0.DocDate,101) LIKE '%$srchval%' OR T0.NumAtCard LIKE '%$srchval%' OR T0.CardName LIKE '%$srchval%') AND T0.DocStatus <> 'C' AND T0.CardCode = '".$vendor."' 
													ORDER BY T0.DocEntry";
	}
	else
	{
		$itemcode = $_POST['itemcode'];
		
		$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.DocEntry,T0.DocNum,T0.Comments,T0.DocDate,T0.NumAtCard,T0.DocStatus , T0.CardName, T0.CANCELED
													FROM [ORDR] T0
													WHERE T0.DocEntry > '".$itemcode."' AND T0.CardCode = '".$vendor."' 
													AND T0.DocStatus <> 'C'
													ORDER BY T0.DocEntry";
	}
}
else
{
	if(isset($_GET['srchval']))
	{
		$srchval = str_replace("'", "''", $_GET['srchval']);
		
		$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.DocEntry,T0.DocNum,T0.Comments,T0.DocDate,T0.NumAtCard,T0.DocStatus , T0.CardName, T0.CANCELED
													FROM [ORDR] T0
													WHERE (T0.DocNum LIKE '%$srchval%' OR T0.Comments LIKE '%$srchval%' OR CONVERT(VARCHAR(10),T0.DocDate,101) LIKE '%$srchval%' OR T0.NumAtCard LIKE '%$srchval%' OR T0.CardName LIKE '%$srchval%') AND T0.DocStatus <> 'C'  
													ORDER BY T0.DocEntry";
	}
	else
	{
		$itemcode = $_POST['itemcode'];
		
		$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.DocEntry,T0.DocNum,T0.Comments,T0.DocDate,T0.NumAtCard,T0.DocStatus , T0.CardName, T0.CANCELED
													FROM [ORDR] T0
													WHERE T0.DocEntry > '".$itemcode."'
													AND T0.DocStatus <> 'C'
													ORDER BY T0.DocEntry";
	}
}
	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) {
			if(odbc_result($qry, 'DocStatus') == 'C')
				{
					$DocStatus = 'Closed';
				}
				else
				{
					$DocStatus = 'Open';
				}
				
				if(odbc_result($qry, 'CANCELED') == 'Y')
				{
					$Canceled = 'Canceled';
				}
				else
				{
					$Canceled = $DocStatus;
				}
				
		echo '<tr class="srch">
				<td class="item-1 hidden">'.odbc_result($qry, 'DocEntry').'</td>
				<td class="item-2">'.odbc_result($qry, 'DocNum').'</td>
				<td class="item-7">'.odbc_result($qry, 'CardName').'</td>
				<td class="item-3">'.odbc_result($qry, 'Comments').'</td>
				<td class="item-4">'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
				<td class="item-5">'.odbc_result($qry, 'NumAtCard').'</td>
				<td class="item-6">'. $Canceled . '</td>
				
		      </tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
