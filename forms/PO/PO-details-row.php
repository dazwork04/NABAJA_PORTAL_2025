<?php  
include_once('../../config/config.php');
$servicetype = $_GET['servicetype']; 
$taxcode = '';
//End Global Variables

//Load Tax Code
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT Code,Name,Rate FROM OVTG WHERE Inactive = 'N' AND Category='I' ORDER BY CASE WHEN Code = 'IVAT-N' THEN '1' ELSE Code END ASC");
//End Load Tax Code

while (odbc_fetch_row($qry)) {
	$taxcode .= '<option val-rate="'.number_format(odbc_result($qry, "Rate"),4,'.','.').'" value="'.odbc_result($qry, "Code").'">'. odbc_result($qry, "Code") .' - '. utf8_encode(odbc_result($qry, "Name")) .'</option>';	
}

//Free Result
odbc_free_result($qry);
//End Free Result

//Close Connection
odbc_close($MSSQL_CONN);
//End Close Connection



if($servicetype == 'I'){
	if(!isset($_GET['freetext'])){
?>
		<tr>
			<td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center"></td>
			<td style="padding-top: 2px;  padding-bottom: 2px;">
				<div class="input-group itemcodeCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input class="form-control input-sm itemcode required" />
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#ItemModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
			<td class="hidden"><input class="form-control input-sm glaccount" readonly/></td>
			<td><input class="form-control input-sm itemname" title="" value="" onclick="updateTitle(this);" maxlength="200"/></td>
			<td><input onkeypress="return isNumberKey(event)" class="form-control input-sm qty required numericvalidate">
				<div class="input-group qtyCont hidden" style="height: 18px; padding: 0 4px; margin: 0;">
					<input type="hidden" class="form-control input-sm serialno" id="SerialNo[]" name="SerialNo[]">
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#SerialModal">
					<span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
			<td><input readonly class="form-control input-sm uom"></td>
			<td><input class="form-control input-sm price required numeric"></td>
			<td>
				<div class="input-group warehouseCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input class="form-control input-sm warehouse required" value=""/>
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#WhsModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
			<td><select class="form-control input-sm taxcode"><?php echo $taxcode ?></select></td>
			<td><input class="form-control input-sm discount numeric"></td>
			<td><input class="form-control input-sm grossprice numeric"></td>
			<td class="hidden"><input readonly class="form-control input-sm taxamount numeric"></td>
			<td><input readonly class="form-control input-sm linetotal numeric"></td>
			<td class="hidden"><input class="form-control input-sm itemdetails" maxlength="1000"></td>
			<td class="hidden"><input readonly class="form-control input-sm grosstotal numeric"></td>
			<td class="hidden">
				<div class="input-group departmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input type="hidden" class="form-control input-sm departmentcode" readonly/>
					<input class="form-control input-sm departmentname" readonly/>
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#DepartmentModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
			<td>
				<div class="input-group projectCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input type="hidden" class="form-control input-sm projectcode" readonly/>
					<input class="form-control input-sm projectname" readonly/>
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#ProjectModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
			<td class="hidden">
				<div class="input-group employeeCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input type="hidden" class="form-control input-sm employeecode" readonly/>
					<input class="form-control input-sm employeename" readonly/>
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EmployeeModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
			<td class="hidden">
				<div class="input-group equipmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input type="hidden" class="form-control input-sm equipmentcode" readonly/>
					<input class="form-control input-sm equipmentname" readonly/>
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EquipmentModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
			<td class="hidden">
				<input readonly class="form-control input-sm lineno">
				<input readonly class="form-control input-sm deleterow" value="N">
			</td>
			<td class="ftext text-center hidden">N</td>
			
		</tr>
		<!--End Item Type-->
	<?php }else{ ?>
		<tr>
			<td class="rowno text-center"></td>
			<td colspan="12"><textarea class="form-control input-sm remarks"></textarea></td>
			<td><input readonly class="form-control input-sm lineno"></td>
			<td class="ftext text-center">Y</td>
		</tr>
	<?php } ?>

<?php }else{ ?>	
	<tr>
        <td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center"></td>
        <td style="padding-top: 2px;  padding-bottom: 2px;"><textarea class="form-control input-sm remarks required" maxlength="100"></textarea></td>
        <td>
            <div class="input-group acctcodeCont" style="height: 18px; padding: 0 4px; margin: 0;">
                <input class="form-control input-sm acctcode required" />
                <span style="height: 18px; padding: 0 4px; margin: 0;" class="input-group-addon" data-toggle="modal" data-target="#AcctModal"><span class="glyphicon glyphicon-list"></span></span>
            </div>
        </td>
        <td><input class="form-control input-sm acctname"></td>
        <td><input onkeypress="return isNumberKey(event);" class="form-control input-sm price numeric"></td>
        <td><select class="form-control input-sm taxcode"><?php echo $taxcode ?></select></td>
        <td><input onkeypress="return isNumberKey(event);" class="form-control input-sm grossprice numeric"></td>
        <td><input readonly class="form-control input-sm taxamount numeric"></td>	
		<td class="hidden">
			<div class="input-group departmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
				<input type="hidden" class="form-control input-sm departmentcode" readonly/>
				<input class="form-control input-sm departmentname" readonly/>
				<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#DepartmentModal"><span class="glyphicon glyphicon-list"></span></span>
			</div>
		</td>
		<td>
			<div class="input-group projectCont" style="height: 18px; padding: 0 4px; margin: 0;">
				<input type="hidden" class="form-control input-sm projectcode" readonly/>
				<input class="form-control input-sm projectname" readonly/>
				<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#ProjectModal"><span class="glyphicon glyphicon-list"></span></span>
			</div>
		</td>
		<td class="hidden">
			<div class="input-group employeeCont" style="height: 18px; padding: 0 4px; margin: 0;">
				<input type="hidden" class="form-control input-sm employeecode" readonly/>
				<input class="form-control input-sm employeename" readonly/>
				<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EmployeeModal"><span class="glyphicon glyphicon-list"></span></span>
			</div>
		</td>
		<td class="hidden">
			<div class="input-group equipmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
				<input type="hidden" class="form-control input-sm equipmentcode" readonly/>
				<input class="form-control input-sm equipmentname" readonly/>
				<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EquipmentModal"><span class="glyphicon glyphicon-list"></span></span>
			</div>
		</td>
        <td class="hidden"><input readonly class="form-control input-sm lineno"></td>	
    </tr>
<?php } ?>

<script>
    function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode != 46 && charCode != 45 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
		return true;	
	}
</script>