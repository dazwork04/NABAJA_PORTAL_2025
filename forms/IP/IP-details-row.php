<tr>
	<td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center"></td>
	<td style="padding-top: 2px;  padding-bottom: 2px;">
		<div class="input-group acctcodeCont">
			<input class="form-control input-sm acctcode required" disabled/>
			<span style="height: 18px; padding: 0 4px; margin: 0;" class="input-group-addon" data-toggle="modal" data-target="#AcctModal1"><span class="glyphicon glyphicon-list"></span></span>
		</div>
	</td>
	<td style="padding-top: 2px;  padding-bottom: 2px;">
		<input class="form-control input-sm acctname required" disabled/>
	</td>
		<td style="padding-top: 2px;  padding-bottom: 2px;">
		<input class="form-control input-sm docremarks" maxlength="254"/>
	</td>
	<td style="padding-top: 2px;  padding-bottom: 2px;">
		<select class="form-control input-sm taxgroup">
			<option value=""></option>
		</select>
	</td>
	<td style="padding-top: 2px;  padding-bottom: 2px;">
		<input onkeypress="return isNumberKey(event)" class="form-control input-sm price" maxlength="13"/>
	</td>
	<td class="hidden">
		<div class="input-group departmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
			<input type="hidden" class="form-control input-sm departmentcode" readonly/>
			<input class="form-control input-sm departmentname" readonly/>
			<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#DepartmentModal"><span class="glyphicon glyphicon-list"></span></span>
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
		<input type="hidden" class="form-control input-sm lineno" value="0" readonly/>
		<input type="hidden" class="form-control input-sm item_index" readonly/>
		<input type="hidden" class="form-control input-sm wtaxindex" value="0" readonly/>
	</td>
</tr>