<?php 
	include_once('../../include/head_top.php');
	include_once('../../config/config.php');
?>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-info">
					<div class="panel-heading">
						<i class="fa fa-edit fa-fw"></i> User Management
                    </div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-4 col-md-4">	
								<div class="form-group">
									<form>
									<table width="100%" border="0">
										<tbody>
											<tr>
												<td width="25%">User Id</td>
												<td width="75%" colspan="2" style="padding-top: 2px;  padding-bottom: 2px;">
													<input id="txtUserId" readonly type="hidden" name="txtUserId" style="width:100%" class="form-control input-sm">
														<div id="txtRequesterCont" class="input-group">
															<input type="text" readonly class="form-control input-sm" id="txtEmployeeId" name="txtEmployeeId">
															<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EmployeeModal"><span class="glyphicon glyphicon-list"></span></span>
														</div>
												</td>
											</tr>
											<tr>
												<td width="25%">Name</td>
												<td width="75%" colspan="2" style="padding-top: 2px;  padding-bottom: 2px;"><input id="txtName" type="text" name="txtName" style="width:100%" class="form-control input-sm required"></td>
											</tr>
											<tr>
												<td width="25%">Position</td>
												<td width="75%" colspan="2" style="padding-top: 2px;  padding-bottom: 2px;">
													<select id="Position" name="Position" class="form-control input-sm required">
													
													</select>
												</td>
											</tr>
											<tr>
												<td width="25%">Manufacturer</td>
												<td width="75%" colspan="2" style="padding-top: 2px;  padding-bottom: 2px;">
													<select id="Manufacturer" name="Manufacturer" class="form-control input-sm required">
													
													</select>
												</td>
											</tr>
											<tr>
												<td>Portal User Code</td>
												<td colspan="2" style="padding-top: 2px;  padding-bottom: 2px;"><input id="UserCode" type="text" name="UserCode" style="width:100%" class="form-control input-sm required"></td>
											</tr>
											<tr>
												<td>Portal User Pass</td>
												<td colspan="2" style="padding-top: 2px;  padding-bottom: 2px;"><input id="UserPass" type="text" name="UserPass" style="width:100%" class="form-control input-sm required"></td>
											</tr>
											<tr>
												<td>Department</td>
												<td colspan="2" style="padding-top: 2px;  padding-bottom: 2px;">
												<select id="Department" name="Department" class="form-control input-sm required">
													
												</select>
												</td>
											</tr>
											<tr>
												<td>SAP User Code</td>
												<td colspan="2" style="padding-top: 2px;  padding-bottom: 2px;"><input id="SapCode" type="text" name="SapCode" style="width:100%" class="form-control input-sm required"></td>
											</tr>
											<tr>
												<td>SAP User Pass</td>
												<td colspan="2" style="padding-top: 2px;  padding-bottom: 2px;"><input id="SapPass" type="text" name="SapPass" style="width:100%" class="form-control input-sm required"></td>
											</tr>
											<tr>
												<td>Status</td>
												<td colspan="2" style="padding-top: 2px;  padding-bottom: 2px;">
												<select id="Status" name="Status" class="form-control input-sm required">
													<option value="">-Select-</option>
													<option value="Active">Active</option>
													<option value="Deactivate">Deactivate</option>
												</select>
												</td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td colspan="2" style="padding-top: 2px;  padding-bottom: 2px;">&nbsp;</td>
											</tr>
											<tr>
												<td>
													Module to Access
												</td>
												<td style="border-top: 1px solid #cdd0d4; border-bottom: 1px solid #cdd0d4;  ">
													<input type="checkbox" id="UM" name="Module[]" value="UM"> User Management <br>
													<input type="checkbox" id="PR" name="Module[]" value="PR"> Purchase Request <br>
													<input type="checkbox" id="PO" name="Module[]" value="PO"> Purchase Order <br>
													<input type="checkbox" id="GRPO" name="Module[]" value="GRPO"> Receiving (GRPO) <br>
													<!--<input type="checkbox" id="SQ" name="Module[]" value="SQ">Sales Quotation <br> -->
													<input type="checkbox" id="SO" name="Module[]" value="SO"> Sales Order <br>
													<input type="checkbox" id="DR" name="Module[]" value="DR"> Delivery <br>
													
												</td>
												<td style="border-bottom: 1px solid #cdd0d4;">
													<input type="checkbox" id="GI" name="Module[]" value="GI"> Goods Issue <br>
													<input type="checkbox" id="GR" name="Module[]" value="GR"> Goods Receipt <br>
													<input type="checkbox" id="IT" name="Module[]" value="IT"> Inventory Transfer <br>
													<input type="checkbox" id="ITR" name="Module[]" value="ITR"> Inventory Transfer Request <br>
													<input type="checkbox" id="PRAP" name="Module[]" value="PRAP"> PR Approval <br>
													<!--<input type="checkbox" id="POAP" name="Module[]" value="POAP"> PO Approval <br>-->
													<input type="checkbox" id="SOAP" name="Module[]" value="SOAP"> SO Approval <br>
													<input type="checkbox" id="SOAPP" name="Module[]" value="SOAPP"> SO Approval 2<br>
													<!--<input class="hidden" type="checkbox" id="SOAP" name="Module[]" value="SOAP"> &nbsp; <br>-->
												</td>
											</tr>
											
											<tr>
												<td>&nbsp;</td>
												<td colspan="2" style="padding-top: 2px;  padding-bottom: 2px;">&nbsp;</td>
											</tr>
											
											<tr>
												<td>
													Multiple Manufacturer
												</td>
												<td style="border-top: 1px solid #cdd0d4; border-bottom: 1px solid #cdd0d4;  ">
												<?php
												$a = 0;
												$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 5 T0.FirmCode,T0.FirmName
																FROM OMRC T0 
																ORDER BY T0.FirmCode");
												while (odbc_fetch_row($qry)) 
												{	
													/* if($a = 5) {
														echo '</td>';
														echo '<td style="border-bottom: 1px solid #cdd0d4;">';
													} */
													?>	
														<input type="checkbox" id="<?php echo odbc_result($qry,'FirmCode'); ?>" name="Manu[]" value="<?php echo odbc_result($qry,'FirmCode'); ?>"> <?php echo odbc_result($qry,'FirmName'); ?> <br>
													<?php
													
													$a++;
												}
												
												odbc_free_result($qry);

												?>
												</td>
												<td style="border-top: 1px solid #cdd0d4; border-bottom: 1px solid #cdd0d4;  ">
												<?php
												$a = 0;
												$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 5 T0.FirmCode,T0.FirmName
																FROM OMRC T0 
																ORDER BY T0.FirmCode DESC");
												while (odbc_fetch_row($qry)) 
												{	
													/* if($a = 5) {
														echo '</td>';
														echo '<td style="border-bottom: 1px solid #cdd0d4;">';
													} */
													?>	
														<input type="checkbox" id="<?php echo odbc_result($qry,'FirmCode'); ?>" name="Manu[]" value="<?php echo odbc_result($qry,'FirmCode'); ?>"> <?php echo odbc_result($qry,'FirmName'); ?> <br>
													<?php
													
													$a++;
												}
												
												odbc_free_result($qry);

												?>
												</td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td colspan="2" style="padding-top: 2px;  padding-bottom: 2px;">&nbsp;</td>
											</tr>
											<tr>
												<td align="right">
													<button type="button" style="width:80px" id="btnNew" name="btnNew" onclick="reload();"><span class="fa fa-plus fa-fw"> </span>New</button>
												</td>
												<td colspan="2" align="right">
													<button type="button" style="width:80px" id="btnRemove" name="btnRemove"><span class="fa fa-trash fa-fw"> </span>Remove</button>
													<button type="button" style="width:80px" id="btnUpdate" name="btnUpdate"><span class="fa fa-pencil fa-fw"></span>Update</button>
													<button type="button" style="width:80px" id="btnSave" name="btnSave" ><span class="fa fa-save fa-fw"></span> Save</button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-lg-8 col-md-8">
								<div class="table-responsive" style="height: 350px; width:100%; border: solid black 2px;">
									<table class="table table-striped table-bordered table-hover table-condensed" id="tblUsersPortal">
										<thead>
											<tr>
												<th style="min-width:50px;">Emp Id</th>
												<th style="min-width:150px;">Name</th>
												<th style="min-width:50px;" class="hidden">Pos.Id</th>
												<th style="min-width:150px;">Pos. Name</th>
												<th style="min-width:50px;" class="hidden">Manu.Id</th>
												<th style="min-width:150px;">Manufacturer</th>
												<th style="min-width:150px;">Portal Code</th>
												<th style="min-width:150px;">Portal Pass</th>
												<th style="min-width:50px;" class="hidden">Dept </th>
												<th style="min-width:100px;">Department </th>
												<th style="min-width:150px;">SAP Code</th>
												<th style="min-width:150px;">SAP Pass</th>
												<th style="min-width:150px;">Status</th>
												<th style="min-width:150px;">Module</th>
											</tr>
										</thead>
										<tbody>
											<?php
				
												$qrySelect = odbc_exec($MSSQL_CONN, "SELECT T0.UserCode, T0.UserPass, T0.position as posid, 
												T1.name as position, T2.Name as Dept, T3.FirmName,
												T0.Name, T0.Department, T0.Status, T0.sappass, T0.sapuser, T0.forms, T0.manufacturer, T0.UserId, T0.empid, T0.multimanu

												FROM [WEB-COMMON].[dbo].[@OUSR] T0
												INNER JOIN [".$_SESSION['mssqldb']."].[dbo].[OHPS] T1 ON T0.position = T1.posID
												INNER JOIN [".$_SESSION['mssqldb']."].[dbo].[OUDP] T2 ON T0.Department = T2.Code
												INNER JOIN [".$_SESSION['mssqldb']."].[dbo].[OMRC] T3 ON T0.manufacturer = T3.FirmCode
												ORDER BY T0.empid ASC");
												
												while (odbc_fetch_row($qrySelect)) 
												{
													?>	
														<tr>
															<td class="item-14"><?php echo odbc_result($qrySelect,'empid'); ?></td>
															<td class="item-0 hidden"><center><?php echo odbc_result($qrySelect, 'UserId'); ?></center></td>
															<td class="item-1"><?php echo odbc_result($qrySelect, 'Name'); ?></td>
															<td class="item-9 hidden"><?php echo odbc_result($qrySelect, 'posid'); ?></td>
															<td class="item-11"><?php echo odbc_result($qrySelect, 'position'); ?></td>
															<td class="item-10 hidden"><?php echo odbc_result($qrySelect,'manufacturer'); ?></td>
															<td class="item-12"><?php echo odbc_result($qrySelect,'FirmName'); ?></td>
															<td class="item-2"><?php echo odbc_result($qrySelect, 'UserCode'); ?></td>
															<td class="item-3"><?php echo odbc_result($qrySelect, 'UserPass'); ?></td>
															<td class="item-4 hidden"><?php echo odbc_result($qrySelect, 'Department'); ?></td>
															<td class="item-13"><?php echo odbc_result($qrySelect, 'Dept'); ?></td>
															<td class="item-5"><?php echo odbc_result($qrySelect, 'SapUser'); ?></td>
															<td class="item-6"><?php echo odbc_result($qrySelect, 'SapPass'); ?></td>
															<td class="item-7"><?php echo odbc_result($qrySelect, 'Status'); ?></td>
															<td class="item-8"><?php echo odbc_result($qrySelect, 'forms'); ?></td>
															<td class="item-15 hidden"><?php echo odbc_result($qrySelect, 'multimanu'); ?></td>
														</tr>
													<?php
												}
												
												odbc_free_result($qrySelect);

											?>
										</tbody>
									</table>
								</form>									
								</div>
							</div>
							<!-- /.col-lg-8 -->
						</div>
						<!-- /.row -->
					</div>
					<!-- /.panel body -->
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		
	<!-- Owner Data Modal  -->
	<div class="modal fade bs-example-modal-lg" id="EmployeeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
		<div class="modal-dialog modal-lg">
			<div class="panel panel-info">
				<div class="panel-heading">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
					List of Employees 
				</div>
				<div class="panel-body">
					<input type="text" name="OwnerSearch" class="form-control input-sm" placeholder="Search..." />
					<div class="modal-body" id="OwnerCont">
					
					</div>
				
				</div>
			</div>
		</div>
	</div>
	<!-- /Owner Data modal -->
	
	<script src="../../bootstrap/vendor/jquery/jquery.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#tblUsersPortal').DataTable({
            responsive: true
        });
    });
    </script>
	
	<?php include_once('../../include/head_bottom.php') ?>
	<script src="../../js/UM/um.js"></script>
	
	
	
	
	

	
	
	
