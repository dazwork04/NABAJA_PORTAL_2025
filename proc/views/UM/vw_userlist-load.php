<?php

include_once('../../../config/config.php');

if(isset($_GET['srchval'])){
	$srchval = str_replace("'", "''", $_GET['srchval']);
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 50 T0.USERID,T0.USER_CODE,T0.U_NAME,T0.Department,T1.Name 
	  																		FROM OUSR T0
																			LEFT JOIN OUDP T1 ON
																			T0.Department = T1.Code 
																			WHERE (T0.USER_CODE LIKE '%".$srchval."%' OR T0.U_NAME LIKE '%".$srchval."%' OR T1.Name LIKE '%".$srchval."%') ORDER BY T0.USERID";
}else{
	$userid = $_POST['userid'];
	$itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 50 T0.USERID,T0.USER_CODE,T0.U_NAME,T0.Department,T1.Name 
	  																		FROM OUSR T0
																			LEFT JOIN OUDP T1 ON
																			T0.Department = T1.Code
																			WHERE T0.USERID > '".$userid."' ORDER BY T0.USERID";	
}


?>



<?php
	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) {
		echo '<tr class="srch">
				<td class="item-1 hidden">'.odbc_result($qry, 'USERID').'</td>
				<td class="item-2">'.odbc_result($qry, 'USER_CODE').'</td>
				<td class="item-3">'.odbc_result($qry, 'U_NAME').'</td>
				<td class="item-4">'.odbc_result($qry, 'Name').'</td>
				<td class="item-5 hidden">'.odbc_result($qry, 'Department').'</td>
		      </tr>';
	}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
