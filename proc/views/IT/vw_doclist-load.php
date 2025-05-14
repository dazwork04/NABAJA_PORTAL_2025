<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval'])){
	$srchval = str_replace("'", "''", $_GET['srchval']);
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; 
            SELECT TOP 20 T0.DocEntry
            ,T0.DocNum
            ,T0.Comments
            ,T0.DocDate
            ,T0.Filler
            ,T1.WhsName
            ,T0.DocStatus, T0.CardName
            from OWTR T0
            left join OWHS T1 on T0.Filler = T1.WhsCode
            WHERE T0.Comments NOT LIKE '%canceled%' AND T0.DataSource != 'N' AND (T0.DocNum LIKE '%$srchval%' OR T0.Comments LIKE '%$srchval%' OR CONVERT(VARCHAR(10),T0.DocDate,101) LIKE '%$srchval%' OR T1.WhsName LIKE '%$srchval%' OR T0.CardName LIKE '%$srchval%')
            order by T0.DocEntry DESC";
}else{
	$itemcode = $_POST['itemcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; 
            SELECT TOP 20 T0.DocEntry
            ,T0.DocNum
            ,T0.Comments
            ,T0.DocDate
            ,T0.Filler
            ,T1.WhsName
            ,T0.DocStatus, T0.CardName
            from OWTR T0
            left join OWHS T1 on T0.Filler = T1.WhsCode
            WHERE  T0.Comments NOT LIKE '%canceled%' AND T0.DataSource != 'N' AND T0.DocEntry > '".$itemcode."'";
}


?>



<?php
	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) {
		echo '<tr class="srch">
				<td class="item-1 hidden">'.odbc_result($qry, 'DocEntry').'</td>
				<td class="item-2">'.odbc_result($qry, 'DocNum').'</td>
				
				<td class="item-3">'.odbc_result($qry, 'Comments').'</td>
				<td class="item-4">'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
				<td class="item-5">'.odbc_result($qry, 'WhsName').'</td>
				<td class="item-7 hidden">'.odbc_result($qry, 'CardName').'</td>
				<td class="item-6 hidden">'.odbc_result($qry, 'DocStatus').'</td>
		      </tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
