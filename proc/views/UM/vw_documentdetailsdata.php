<?php  include_once('../../../config/config.php'); ?>

<?php 

$docentry = $_GET['docentry'];
$html = '';


//Load Warehouse
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT WhsCode,WhsName FROM OWHS");
//End Load Warehouse

$rowno = 1;
while (odbc_fetch_row($qry)) {
$html .= '<tr>
					
		          	<td class="text-center">'.$rowno.'</td>
		          	<td class="text-center"><input type="checkbox" aria-whscode="'.odbc_result($qry, 'WhsCode').'" class="whs"></td>
		          	<td>'.odbc_result($qry, 'WhsCode').'</td>
		          	<td>'.odbc_result($qry, 'WhsName').'</td>
		          </tr>';
	$rowno += 1;	
}

//Free Result
odbc_free_result($qry);
//End Free Result



//Load User
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."]; 
			SELECT T0.UserId,T0.Warehouse
			FROM [@USR1] T0 
			WHERE T0.UserId = '$docentry' 
			ORDER BY T0.UserId");
//End Load User

while (odbc_fetch_row($qry)) {
	$warehouse = odbc_result($qry, 'Warehouse');
	
	$html = str_replace('aria-whscode="'.$warehouse.'"', 'aria-whscode="'.$warehouse.'" checked', $html);
	
}

//Free Result
odbc_free_result($qry);
//End Free Result

//Output
echo $html;
//End Output


//Close Connection
odbc_close($MSSQL_CONN);
//End Close Connection



?>


