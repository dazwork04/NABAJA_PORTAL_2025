<?php  
include_once('../../config/config.php');

//Load Warehouse
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT WhsCode,WhsName FROM OWHS");
//End Load Warehouse

$html = '';
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

//Close Connection
odbc_close($MSSQL_CONN);
//End Close Connection

?>

<!--Item Details-->
<!--==========================================================-->
<div class="table-responsive woverflow mousescroll">
	<table class="table table-hover table-bordered table-condensed" id="tblDetails">
		<thead>
			<tr>
				<th style="width:50px;">Row #</th>
				<th style="width:50px;">Action</th>
				
				<th style="min-width:100px;">Warehouse Code</th>
				<th style="min-width:100px;">Warehouse Name</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $html?>
					
		</tbody>
	</table>
</div>

<!--End Item Details-->
<!--==========================================================-->
