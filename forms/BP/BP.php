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
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" data-target="#BPModal">Find <span class="fa fa-search fa-fw"></span></button>
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
											<td width="25%">Category</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<select class="form-control input-sm required" id="selCategory" name="selCategory">
													<option value="">-Select-</option>
													<option value="0" category-val="C">Customer</option>
													<option value="1" category-val="S">Vendor</option>
												</select>
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Code</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" class="form-control input-sm" id="txtBPCode" name="txtBPCode" value="" maxlength="15">
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Name</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" class="form-control input-sm required" id="txtBPName" name="txtBPName" value="" maxlength="100">
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Group</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<select class="form-control input-sm required" id="selGroup" name="selGroup">
												</select>
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

<div class="modal fade" id="BPModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Business Partner 
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-2">
						Search Item : 
					</div>
					<div class="col-lg-4">
						<input type="text" name="BPSearch" class="form-control input-sm" placeholder="Search..." />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
						<div id="BPCont">
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
					Business Partner
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
						<button type="button" id="btnSaveBP" class="form-control btn btn-success" data-dismiss="modal">Yes</button>
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
<script src="../../js/BP/bp.js"></script>

