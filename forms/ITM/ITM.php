<?php 
	include_once('../../include/head_top.php');
	include_once('../../config/config.php');

$empid = $_SESSION['SESS_EMP'];
$name = $_SESSION['SESS_NAME'];

if($empid == 0)
{
	$hide = '';
}
else
{
	$hide = 'hidden';
}
?>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<i class="fa fa-edit fa-fw"></i> <span id="mod-title"></span>
						<div class="pull-right">
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" id="btnPrint" disabled>Print <span class="glyphicon glyphicon-print"></span></button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" onclick="reload();">New <span class="fa fa-plus fa-fw"></span></button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" data-target="#ItemModal">Find <span class="fa fa-search fa-fw"></span></button>
							</div>
						</div>
				</div>
				<form class="form-horizontal" id="PRForm">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-4 col-md-4">	
							<div class="form-group">
								<table width="100%" border="0">
									<tbody>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%"></td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<select class="form-control input-sm required" id="selSeries" name="selSeries">
													<option value="">-Select-</option>
													<option value="Manual">Manual</option>
													<option value="SKU">SKU</option>
												</select>
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Item Code</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" class="form-control input-sm" id="txtItemCode" name="txtItemCode" value="" maxlength="50">
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Item Name</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" class="form-control input-sm required" id="txtItemName" name="txtItemName" value="" maxlength="200">
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Item Group</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<select class="form-control input-sm required" id="selGroup" name="selGroup">
												</select>
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Selling Price</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" class="form-control input-sm" id="txtSellingPrice" name="txtSellingPrice" value="" maxlength="9">
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Status</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<select class="form-control input-sm required" id="selStatus" name="selStatus">
													<option value="">-Select-</option>
													<option value="0">Active</option>
													<option value="1">Inactive</option>
												</select>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12">
							
						</div>
					</div>
					<!-- /.row (nested) -->
					<div class="row">
						<div class="col-lg-4 col-md-4">
							<div class="form-group">
								<table width="100%" border="0">
									<tbody>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">
											</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<button id="btnSave" name="btnSave" type="button" data-toggle="modal" data-target="#add_inv_modal">&nbsp;&nbsp;&nbsp;Add <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
												<button id="btnUpdate" class="hidden" name="btnUpdate" type="button" data-toggle="dropdown">&nbsp;&nbsp;&nbsp;Update <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
											</td>	
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-lg-4 col-md-4">
							<div class="form-group">
								<table width="100%" border="0">
									<tbody>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%"><b><u>Multiple Price Update</u></b></td>
											<td width="70%" colspan="3" style="padding-top: 2px;  padding-bottom: 2px;"></td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Item Code From</td>
											<td width="30%" style="padding-top: 2px;  padding-bottom: 2px;">
												<div class="input-group itemcodeFrom" style="height: 18px; padding: 0 4px; margin: 0;">
													<input type="text" class="form-control input-sm" id="txtItemCodeFrom" name="txtItemCodeFrom" maxlength="50" readonly>
													<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#ItemModalFrom"><span class="glyphicon glyphicon-list"></span></span>
												</div>
											</td>
											<td width="10%"><center>To</center></td>
											<td width="30%" style="padding-top: 2px;  padding-bottom: 2px;">
												<div class="input-group itemcodeFrom" style="height: 18px; padding: 0 4px; margin: 0;">
													<input type="text" class="form-control input-sm" id="txtItemCodeTo" name="txtItemCodeTo" maxlength="50" readonly>
													<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#ItemModalTo"><span class="glyphicon glyphicon-list"></span></span>
												</div>
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%"></td>
											<td width="70%" colspan="3" style="padding-top: 2px;  padding-bottom: 2px;" align="right">
												<button id="btnGenerateItems" name="btnGenerateItems" type="button">&nbsp;&nbsp;&nbsp;Generate <span class="fa fa-refresh fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>&nbsp;
											</td>
										</tr>
									</tbody>
								</table>	
							</div>
						</div>
						<div class="col-lg-4 col-md-4">
							<div class="form-group">
								<div id="resItems"></div>
							</div>
							<div class="form-group">
								<button id="btnUpdatePrielist" name="btnUpdatePrielist" class="hidden" type="button">&nbsp;&nbsp;&nbsp;Update Price <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>&nbsp;
							</div>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
	
<!-- MODAL SECTION -->

<!-- loading modal  -->
<div id="modal-load-init" class="modal fade" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="panel panel-info">
			<div class="panel-heading">
				Processing Please wait...
				<!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
			</div>
			<div class="panel-body">
				<div class="progress progress-striped active">
					<div id="progBar" class="progress-bar" style="width: 100%"></div>
				</div>
			
			</div>
		</div>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /loading modal -->

<div class="modal fade" id="ItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Item Master Data
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-2">
						Search Item : 
					</div>
					<div class="col-lg-4">
						<input type="text" name="ItemSearch" class="form-control input-sm" placeholder="Search..." />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
						<div id="ItemCont">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="ItemModalFrom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Item Master Data
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-2">
						Search Item : 
					</div>
					<div class="col-lg-4">
						<input type="text" name="ItemSearchFrom" class="form-control input-sm" placeholder="Search..." />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
						<div id="ItemContFrom">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="ItemModalTo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Item Master Data
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-2">
						Search Item : 
					</div>
					<div class="col-lg-4">
						<input type="text" name="ItemSearchTo" class="form-control input-sm" placeholder="Search..." />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
						<div id="ItemContTo">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="add_inv_modal">
	<div class="modal-dialog">
		<div class="col-xs-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					Item Master Data
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="panel-body">
					<div class="alert alert-success">
						<a href="#" class="alert-link">Alert! </a> Are you sure you want to Save this record?
					</div>
					<div class="pull-left">
						<button type="button" class="form-control btn btn-danger" data-dismiss="modal">No</button>
					</div>
					<div class="pull-right">
						<button type="button" id="btnSaveITM" class="form-control btn btn-success" data-dismiss="modal">Yes</button>
					</div>
				</div>
				<div class="panel-footer">
					
				</div>
			</div>
		</div>
	</div>
</div>

<script src="../../bootstrap/vendor/jquery/jquery.min.js"></script>

<?php include_once('../../include/head_bottom.php') ?>
<script src="../../js/ITM/itm.js"></script>

