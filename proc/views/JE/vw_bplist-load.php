<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval'])){
	$srchval = str_replace("'", "''", $_GET['srchval']);
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 CardCode,CardName,Balance,CntctPrsn, GroupNum, Currency, ListNum FROM OCRD WHERE frozenFor = 'N' AND (CardCode LIKE '%".$srchval."%' OR CardName LIKE '%".$srchval."%') ORDER BY CardCode";
}else{
	$cardcode = $_POST['itemcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 CardCode,CardName,Balance,CntctPrsn, GroupNum, Currency, ListNum FROM OCRD WHERE frozenFor = 'N' AND CardCode > '".$cardcode."' ORDER BY CardCode";	
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
		      </tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
