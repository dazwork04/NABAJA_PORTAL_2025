<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval'])){
	$srchval = str_replace("'", "''", $_GET['srchval']);
	$itemqry = "USE [".$_SESSION['MDdb']."]; SELECT TOP 50 T0.DocEntry,T0.DocNum,T1.Name,T0.Remarks,CONVERT(VARCHAR(10),T0.DocDate,101) AS DocDate,T0.DocStatus 
												FROM [@OPRQ] T0
												LEFT JOIN [@OUSR] T1
												ON T0.Requester = T1.UserCode
												WHERE (T0.DocNum LIKE '%$srchval%' OR T1.Name LIKE '%$srchval%' OR T0.Remarks LIKE '%$srchval%' OR CONVERT(VARCHAR(10),T0.DocDate,101) LIKE '%$srchval%')
												ORDER BY T0.DocEntry";
}else{
	$itemcode = $_POST['itemcode'];
	$itemqry = "USE [".$_SESSION['MDdb']."]; SELECT TOP 50 T0.DocEntry,T0.DocNum,T1.Name,T0.Remarks,CONVERT(VARCHAR(10),T0.DocDate,101) AS DocDate,T0.DocStatus 
												FROM [@OPRQ] T0
												LEFT JOIN [@OUSR] T1
												ON T0.Requester = T1.UserCode 
												WHERE T0.DocEntry > '".$itemcode."'
												ORDER BY T0.DocEntry";
}


?>



<?php
	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) {
		echo '<tr class="srch">
				<td class="item-1 hidden">'.odbc_result($qry, 'DocEntry').'</td>
				<td class="item-2">'.odbc_result($qry, 'DocNum').'</td>
				<td class="item-3">'.odbc_result($qry, 'Name').'</td>
				<td class="item-4">'.odbc_result($qry, 'Remarks').'</td>
				<td class="item-5">'.odbc_result($qry, 'DocDate').'</td>
				<td class="item-6 hidden">'.odbc_result($qry, 'DocStatus').'</td>
		      </tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
