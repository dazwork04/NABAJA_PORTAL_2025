<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval'])){
	$srchval = str_replace("'", "''", $_GET['srchval']);
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.DocEntry,T0.DocNum,T0.Comments,T0.DocDate,T0.DocStatus,(SELECT COUNT(DISTINCT T1.LineStatus) AS LineStatus FROM IGE1 T1 WHERE T1.DocEntry = T0.DocEntry AND T1.LineStatus = 'O') AS DocStatus1
											FROM [OIGE] T0
										WHERE (T0.DocNum LIKE '%$srchval%' OR T0.Comments LIKE '%$srchval%' OR T0.DocDate LIKE '%$srchval%')
										ORDER BY T0.DocEntry  DESC";
}else{
	$itemcode = $_POST['itemcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.DocEntry,T0.DocNum,T0.Comments,T0.DocDate,T0.DocStatus ,(SELECT COUNT(DISTINCT T1.LineStatus) AS LineStatus FROM IGE1 T1 WHERE T1.DocEntry = T0.DocEntry AND T1.LineStatus = 'O') AS DocStatus1
											FROM [OIGE] T0
										WHERE T0.DocEntry > '".$itemcode."'
										ORDER BY T0.DocEntry DESC";
}

$qry = odbc_exec($MSSQL_CONN, $itemqry);
while (odbc_fetch_row($qry)) 
{
	if(odbc_result($qry, 'DocStatus1') != 0)
	{
		echo '<tr class="srch">
				<td class="item-1 hidden">'.odbc_result($qry, 'DocEntry').'</td>
				<td class="item-2">'.odbc_result($qry, 'DocNum').'</td>
				<td class="item-4">'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
				<td class="item-3">'.odbc_result($qry, 'Comments').'</td>
				<td class="item-6 hidden">'.odbc_result($qry, 'DocStatus').'</td>
			  </tr>';
	}
}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
