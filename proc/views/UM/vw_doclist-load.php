<?php

include_once('../../../config/configmd.php');

if(isset($_GET['srchval'])){
	$srchval = str_replace("'", "''", $_GET['srchval']);
	$itemqry = "USE [".$_SESSION['MDdb']."]; SELECT TOP 50 T0.UserId,T0.UserCode,T0.Name,T0.UserPass,T0.UserType,T0.Status,T0.sapuser,T0.sappass 
												FROM [@OUSR] T0
												
												WHERE (T0.UserCode LIKE '%$srchval%' OR T0.Name LIKE '%$srchval%' OR T0.UserType LIKE '%$srchval%' OR T0.Status LIKE '%$srchval%')
												ORDER BY T0.UserId";
}else{
	$itemcode = $_POST['itemcode'];
	$itemqry = "USE [".$_SESSION['MDdb']."]; SELECT TOP 50 T0.UserId,T0.UserCode,T0.Name,T0.UserPass,T0.UserType,T0.Status,T0.sapuser,T0.sappass 
												FROM [@OUSR] T0
												
												WHERE T0.UserId > '".$itemcode."'
												ORDER BY T0.UserId";
}


?>



<?php
	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) {
		echo '<tr class="srch">
				<td class="item-1 hidden">'.odbc_result($qry, 'UserId').'</td>
				<td class="item-2">'.odbc_result($qry, 'UserCode').'</td>
				<td class="item-3">'.odbc_result($qry, 'Name').'</td>
				<td class="item-4">'.odbc_result($qry, 'UserPass').'</td>
				<td class="item-5">'.odbc_result($qry, 'UserType').'</td>
				<td class="item-6">'.odbc_result($qry, 'Status').'</td>
		      </tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
