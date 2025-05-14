<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval']))
{
	$srchval = str_replace("'", "''", $_GET['srchval']);
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT PrcCode, PrcName FROM OPRC WHERE DimCode = 2 AND Active = 'Y' AND Locked = 'N' AND (PrcCode LIKE '%".$srchval."%' OR PrcName LIKE '%".$srchval."%') ORDER BY PrcCode";
}
else
{
	$itemcode = $_POST['itemcode'];
	
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT PrcCode, PrcName FROM OPRC WHERE DimCode = 2 AND Active = 'Y' AND Locked = 'N' AND PrcCode > '".$itemcode."' ORDER BY PrcCode";	
}


?>



<?php
	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) {
		echo '<tr class="srch">
				<td class="item-1">'.odbc_result($qry, 'PrcCode').'</td>
				<td class="item-2">'.odbc_result($qry, 'PrcName').'</td>
		      </tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
