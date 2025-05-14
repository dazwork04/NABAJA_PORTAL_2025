
	<table width="100%" border="1" id="tblDetails" bordercolor="lightblue">
		<thead>
			<tr>
				<th style="min-width:30px; height:30px;"><center>#</center></th>
				<th style="min-width:150px;">&nbsp;G/L Acct./BP Code</th>
				<th style="min-width:200px;">&nbsp;G/L Acct. Name/BP Name</th>
				<th style="min-width:100px;">&nbsp;Debit</th>
				<th style="min-width:100px;">&nbsp;Credit</th>
				<th style="min-width:100px;">&nbsp;Tax Group</th>
				<th style="min-width:100px;">&nbsp;W Tax</th>
				<th style="min-width:300px;">&nbsp;Remarks</th>
				<th style="min-width:200px;">&nbsp;Department</th>
				<th style="min-width:200px;">&nbsp;Project</th>
				<th style="min-width:200px;">&nbsp;Employees</th>
				<th style="min-width:200px;">&nbsp;Equipment</th>
				<th style="min-width:20px;" class="hidden">&nbsp;Line No.</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center">1</td>
				<td style="padding-top: 2px;  padding-bottom: 2px;">
					<div class="input-group acctcodeCont">
						<span style="height: 18px; padding: 0 4px; margin: 0;" class="input-group-addon" data-toggle="modal" data-target="#BPModal"><span class="glyphicon glyphicon-user"></span></span>
						<input class="form-control input-sm acctcode required" disabled/>
						<input type="hidden" class="form-control input-sm cat" disabled/>
						<span style="height: 18px; padding: 0 4px; margin: 0;" class="input-group-addon" data-toggle="modal" data-target="#AcctModal"><span class="glyphicon glyphicon-list"></span></span>
					</div>
				</td>
				<td style="padding-top: 2px;  padding-bottom: 2px;">
					<input class="form-control input-sm acctname required" disabled/>
				</td>
				<td style="padding-top: 2px;  padding-bottom: 2px;">
					<input class="form-control input-sm debit"/>
				</td>
				<td style="padding-top: 2px;  padding-bottom: 2px;">
					<input class="form-control input-sm credit"/>
				</td>
				<td style="padding-top: 2px;  padding-bottom: 2px;">
					<select class="form-control input-sm taxgroup">
						<option value=""></option>
					</select>
				</td>
				<td style="padding-top: 2px;  padding-bottom: 2px;">
					<select class="form-control input-sm wtax">
						<option value=""></option>
					</select>
				</td>
				<td style="padding-top: 2px;  padding-bottom: 2px;">
					<input class="form-control input-sm linememo" maxlength="254"/>
				</td>
				<td>
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
				<td>
					<div class="input-group employeeCont" style="height: 18px; padding: 0 4px; margin: 0;">
						<input type="hidden" class="form-control input-sm employeecode" readonly/>
						<input class="form-control input-sm employeename" readonly/>
						<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EmployeeModal"><span class="glyphicon glyphicon-list"></span></span>
					</div>
				</td>
				<td>
					<div class="input-group equipmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
						<input type="hidden" class="form-control input-sm equipmentcode" readonly/>
						<input class="form-control input-sm equipmentname" readonly/>
						<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EquipmentModal"><span class="glyphicon glyphicon-list"></span></span>
					</div>
				</td>
				<td class="hidden">
					<input type="hidden" class="form-control input-sm lineno" value="0" readonly/>
					<input type="hidden" class="form-control input-sm item_index" value="1" readonly/>
					<input type="hidden" class="form-control input-sm wtaxindex" value="0" readonly/>
				</td>
			</tr>
		</tbody>
	</table>
