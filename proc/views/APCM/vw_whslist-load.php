<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval']))
{
	$srchval = str_replace("'", "''", $_GET['srchval']);
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 50 WhsCode, WhsName FROM OWHS WHERE Inactive = 'N' AND (WhsCode LIKE '%".$srchval."%' OR WhsName LIKE '%".$srchval."%') ORDER BY WhsCode";
}
else
{
	$itemcode = $_POST['itemcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 50 WhsCode, WhsName FROM OWHS WHERE Inactive = 'N' AND WhsCode > '".$itemcode."' ORDER BY WhsCode";	
}

	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) {
		echo '<tr class="srch">
				<td class="item-1">'.odbc_result($qry, 'WhsCode').'</td>
				<td class="item-2">'.odbc_result($qry, 'WhsName').'</td>
		      </tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
