<?php

include_once('../../../config/config.php');
$itemcode = isset($_GET['itemcode']) ? $_GET['itemcode'] : '';
$barcode = isset($_GET['barcode']) ? $_GET['barcode'] : '';

if(isset($_GET['srchval'])){
	$srchval = str_replace("'", "''", $_GET['srchval']);
	$barcodeqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 100 BcdCode,ItemCode FROM OBCD WHERE ItemCode = '".$itemcode."' AND BcdCode LIKE '%".$srchval."%' ORDER BY BcdCode";
}else{
	$barcode = $_GET['barcode'];
	$barcodeqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 100 BcdCode,ItemCode FROM OBCD WHERE ItemCode = '".$itemcode."' AND BcdCode > '".$barcode."' ORDER BY BcdCode";	
}
?>



<?php
	$qry = odbc_exec($MSSQL_CONN, $barcodeqry);
//        echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	while (odbc_fetch_row($qry)) {
		echo '<tr class="srch" pageloadid="'.odbc_result($qry, 'BcdCode').'">
				<td class="item-1">'.odbc_result($qry, 'ItemCode').'</td>
				<td class="item-2">'.odbc_result($qry, 'BcdCode').'</td>
		      </tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
