<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval'])){
	$srchval = str_replace("'", "''", $_GET['srchval']);
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 50 USERID, USER_CODE, U_NAME, Branch, Department FROM OUSR WHERE (USER_CODE LIKE '%".$srchval."%' OR U_NAME LIKE '%".$srchval."%') ORDER BY USERID";
}else{
	$itemcode = $_POST['itemcode'];
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 50 USERID, USER_CODE, U_NAME, Branch, Department FROM OUSR WHERE USER_CODE > '".$itemcode."' ORDER BY USERID";	
}


?>



<?php
	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) {
		echo '<tr class="srch">
				<td class="item-1">'.odbc_result($qry, 'USER_CODE').'</td>
				<td class="item-2">'.odbc_result($qry, 'U_NAME').'</td>
				<td class="hidden item-3">'.odbc_result($qry, 'USERID').'</td>
						<td class="hidden item-10">'.odbc_result($qry, 'Branch').'</td>
						<td class="hidden item-11">'.odbc_result($qry, 'Department').'</td>
		      </tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
