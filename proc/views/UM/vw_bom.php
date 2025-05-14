<?php  include_once('../../../config/config.php'); ?>

<?php 
//Global  Variables
$taxcode2 = '';
//End Global Variables

//Load Tax Code
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT Code,Name,Rate FROM OVTG WHERE Inactive = 'N' AND Category='O'");
//End Load Tax Code

while (odbc_fetch_row($qry)) {
	$taxcode2 .= '<option val'.odbc_result($qry, "Code").' val-rate="'.number_format(odbc_result($qry, "Rate"),4,'.','.').'" value="'.odbc_result($qry, "Code").'">'. odbc_result($qry, "Code") .' - '. utf8_encode(odbc_result($qry, "Name")) .'</option>';	
}

//Free Result
odbc_free_result($qry);
//End Free Result


$itemcode = $_GET['itemcode'];
$qry = odbc_exec($MSSQL_CONN, " 
			SELECT T1.Code,T2.ItemName,T2.InvntryUom,(T1.Quantity / T0.Qauntity) AS Quantity FROM OITT T0
			INNER JOIN ITT1 T1
			ON T0.Code = T1.Father
			INNER JOIN OITM T2
			ON T1.Code = T2.ItemCode
			WHERE T0.Code = '$itemcode'
			ORDER BY T1.ChildNum");
$ctr = 2;

while (odbc_fetch_row($qry)) {
	$ItemCode = odbc_result($qry, "Code");
	$ItemName = odbc_result($qry, "ItemName");
	$InvntryUom = odbc_result($qry, "InvntryUom");
	$Quantity = number_format(odbc_result($qry, "Quantity"),2,'.',',');
	$taxcode = $taxcode2;


	//Check if Closed
	$disabled = '';
	
	//End Check if Closed

	echo '
		<tr>
			<td class="rowno text-center">
				<a class="invdata" data-toggle="modal" data-target="#InvDataModal"><span class="glyphicon glyphicon-arrow-right"></span></a> '.$ctr.'
			</td>
			<td>
				<div class="input-group itemcodeCont">
					<input '.$disabled.' class="form-control input-sm itemcode required" value="'.$ItemCode.'" />
					<span class="input-group-addon" data-toggle="modal" data-target="#ItemModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>

			</td>
			<td><input '.$disabled.' class="form-control input-sm itemname" value="'.$ItemName.'" /></td>
			<td><input '.$disabled.' class="form-control input-sm qty required numericvalidate" value="'.$Quantity.'"></td>
			<td><input readonly class="form-control input-sm uom">'.$InvntryUom.'</td>
			<td><input '.$disabled.' class="form-control input-sm price required numeric numericvalidate"></td>
			<td>
				<div class="input-group warehouseCont">
					<input '.$disabled.' class="form-control input-sm warehouse required" />
					<span class="input-group-addon" data-toggle="modal" data-target="#WhsModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
			<td><select '.$disabled.' class="form-control input-sm taxcode">'.$taxcode.'</select></td>
			<td><input '.$disabled.' class="form-control input-sm discount numeric"></td>
			<td><input '.$disabled.' class="form-control input-sm grossprice numeric"></td>
			<td><input readonly class="form-control input-sm taxamount numeric"></td>
			<td><input readonly class="form-control input-sm linetotal numeric"></td>
			<td><input readonly class="form-control input-sm grosstotal numeric"></td>
			<td><input readonly class="form-control input-sm lineno"></td>
			<td class="ftext text-center">N</td>
			
		</tr>';
		

$ctr += 1;
} // End For


odbc_free_result($qry);
odbc_close($MSSQL_CONN);


