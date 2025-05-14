<?php 
	include_once('../../include/head_top.php');
	include_once('../../config/config.php');

$BaseEntry = !isset($_GET['BaseEntry'])? '' : $_GET['BaseEntry'];

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
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" data-target="#cancel_modal" id="btnCancelDoc" disabled>Remove <span class="glyphicon glyphicon-remove"></span></button>
							</div>
							<div class="btn-group">
								<!--<button type="button" class="btn btn-default btn-xs dropdown-toggle" id="btnPrint" disabled>Print <span class="glyphicon glyphicon-print"></span></button>-->
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" id="btnNew">New <span class="fa fa-plus fa-fw"></span></button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" data-target="#DocumentModal">Find <span class="fa fa-search fa-fw"></span></button>
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
											<td width="25%">Cost Center</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" id="txtPrcCode" name="txtPrcCode" class="form-control input-sm required" maxlength="8">
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Name</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" id="txtPrcName" name="txtPrcName" class="form-control input-sm" maxlength="30">
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Dimension</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<select id="selDimension" name="selDimension" class="form-control input-sm">
													<?php
														$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT * FROM ODIM WHERE DimActive = 'Y' ");
														
														while (odbc_fetch_row($qry)) 
														{
															echo '<option value="'.odbc_result($qry, 'DimCode').'">'.odbc_result($qry, 'DimDesc').'</option>';
														}
													?>
													
												</select>
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Effective Date</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" id="txtEffectiveDate" name="txtEffectiveDate" class="form-control input-sm" >
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Active</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="checkbox" id="ChkActive" name="ChkActive" checked>
											</td>
										</tr>
									</tbody>
								</table>
								<table width="100%" border="0">
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-4 col-md-4">	
							
						</div>
						<div class="col-lg-4 col-md-4">	
							<div class="form-group">
								
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
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;"></td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">
												<button id="btnSave" name="btnSave" type="button" data-toggle="modal" data-target="#add_modal">&nbsp;&nbsp;&nbsp;Add <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
												<button id="btnUpdate" class="hidden" name="btnUpdate" type="button" data-toggle="dropdown">&nbsp;&nbsp;&nbsp;Update <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
											</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;"></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-4 col-md-4">
							
						</div>
						<div class="col-lg-4 col-md-4">
							
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
				<button type="button" class="close" data-dismiss="modal">&times;</button>
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

<div class="modal fade" id="add_modal">
	<div class="modal-dialog">
		<div class="col-xs-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					Cost Center
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
						<button type="button" id="btnSaveCC" class="form-control btn btn-success" data-dismiss="modal">Yes</button>
					</div>
				</div>
				<div class="panel-footer">
					
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="cancel_modal">
	<div class="modal-dialog">
		<div class="col-xs-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					Cost Center
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="panel-body">
					<div class="alert alert-success">
						<a href="#" class="alert-link">Alert! </a> Are you sure you want to Remove this record?
					</div>
					<div class="pull-left">
						<button type="button" class="form-control btn btn-danger" data-dismiss="modal">No</button>
					</div>
					<div class="pull-right">
						<button type="button" id="btnCancelCC" class="form-control btn btn-success" data-dismiss="modal">Yes</button>
					</div>
				</div>
				<div class="panel-footer">
					
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Document List Modal  -->
<div class="modal fade bs-example-modal-lg" id="DocumentModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">

	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Document List
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-1">
						Search : 
					</div>
					<div class="col-lg-4">
						<input type="text" name="DocumentSearch" class="form-control input-sm" placeholder="Search..." />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
						<div id="DocumentCont">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
<!-- /Document List modal -->

<script src="../../bootstrap/vendor/jquery/jquery.min.js"></script>

<?php include_once('../../include/head_bottom.php') ?>
<script src="../../js/CC/cc.js"></script>

