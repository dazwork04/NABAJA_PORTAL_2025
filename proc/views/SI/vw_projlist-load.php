<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval']))
{
	$srchval = str_replace("'", "''", $_GET['srchval']);
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT PrjCode, PrjName FROM OPRJ WHERE Active = 'Y' AND Locked = 'N' AND (PrjCode LIKE '%".$srchval."%' OR PrjName LIKE '%".$srchval."%') ORDER BY PrjCode";
}
else
{
	$itemcode = $_POST['itemcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT PrjCode, PrjName FROM OPRJ WHERE Active = 'Y' AND Locked = 'N' AND PrjCode > '".$itemcode."' ORDER BY PrjCode";	
}


?>



<?php
	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) {
		echo '<tr class="srch">
				<td class="item-1">'.odbc_result($qry, 'PrjCode').'</td>
				<td class="item-2">'.odbc_result($qry, 'PrjName').'</td>
		      </tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
