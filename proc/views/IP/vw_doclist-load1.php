<?php

include_once('../../../config/config.php');


if (isset($_GET['srchval'])) {
    $srchval = str_replace("'", "''", $_GET['srchval']);
	
    $itemqry = "USE [" . $_SESSION['mssqldb'] . "]; 
					SELECT TOP 20 T0.DocEntry, T0.DocDate, T0.CardName, T0.CounterRef, T0.Comments FROM ORCT T0
					WHERE 
					(T0.DocNum LIKE '%$srchval%' 
					OR T0.DocEntry LIKE '%$srchval%' 
					OR T0.DocDate LIKE '%$srchval%' 
					OR T0.CardName LIKE '%$srchval%' 
					OR T0.CounterRef LIKE '%$srchval%' 
					OR T0.Comments LIKE '%$srchval%')
			
					ORDER BY T0.DocEntry DESC
					";
} else {
    $itemcode = $_POST['itemcode'];

    $itemqry = "USE [" . $_SESSION['mssqldb'] . "]; 
				SELECT TOP 20 DocEntry, DocDate, CardName, CounterRef, Comments FROM ORCT WHERE DocEntry < '" . $itemcode . "'
					ORDER BY DocEntry DESC";
}
?>



<?php

$qry = odbc_exec($MSSQL_CONN, $itemqry);
while (odbc_fetch_row($qry)) {
    echo '<tr class="srch">
			<td class="item-1 hidden">' . odbc_result($qry, 'DocEntry') . '</td>
			<td class="item-2">' . odbc_result($qry, 'DocEntry') . '</td>
			<td class="item-3">' . date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))) . '</td>
			<td class="item-4">' . odbc_result($qry, 'CardName') . '</td>
			<td class="item-5">' . odbc_result($qry, 'CounterRef') . '</td>
			<td class="item-6">' . odbc_result($qry, 'Comments') . '</td>
        </tr>';
}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);
?>
