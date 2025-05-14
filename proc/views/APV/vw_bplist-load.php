<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval'])){
	$srchval = str_replace("'", "''", $_GET['srchval']);
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.CardCode,T0.CardName,T0.Balance,T0.CntctPrsn, T0.GroupNum , T0.Currency, T0.ListNum, T0.DebPayAcct, T1.AcctName 
																							FROM OCRD T0
																							LEFT JOIN OACT T1 ON T0.DebPayAcct = T1.AcctCode
																							WHERE T0.frozenFor = 'N' AND T0.CardType='S' AND (T0.CardCode LIKE '%".$srchval."%' OR T0.CardName LIKE '%".$srchval."%') ORDER BY T0.CardCode";
}else{
	$cardcode = $_POST['itemcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.CardCode,T0.CardName,T0.Balance,T0.CntctPrsn, T0.GroupNum , T0.Currency, T0.ListNum, T0.DebPayAcct, T1.AcctName 
																							FROM OCRD T0
																							LEFT JOIN OACT T1 ON T0.DebPayAcct = T1.AcctCode
																							WHERE T0.frozenFor = 'N' AND T0.CardType='S' AND T0.CardCode > '".$cardcode."' ORDER BY T0.CardCode";	
}

	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) {
		echo '<tr class="srch">
				<td class="item-1">'.odbc_result($qry, 'CardCode').'</td>
				<td class="item-2">'.odbc_result($qry, 'CardName').'</td>
				<td class="item-3 hidden">'.number_format(odbc_result($qry, 'Balance'),2,'.',',').'</td>
				<td class="item-4 hidden">'.odbc_result($qry, 'CntctPrsn').'</td>
				<td class="item-5 hidden">'.odbc_result($qry, 'GroupNum').'</td>
				<td class="item-6 hidden">'.odbc_result($qry, 'Currency').'</td>
				<td class="item-7 hidden">'.odbc_result($qry, 'ListNum').'</td>
				<td class="item-8 hidden">'.odbc_result($qry, 'DebPayAcct').'</td>
				<td class="item-9 hidden">'.odbc_result($qry, 'AcctName').'</td>
		      </tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
