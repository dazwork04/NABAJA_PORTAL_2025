<?php 
	include_once('../../include/head_top.php');
	include_once('../../config/config.php');

$BaseEntry = !isset($_GET['BaseEntry'])? '' : $_GET['BaseEntry'];
$DocEntry = !isset($_GET['DocEntry'])? '' : $_GET['DocEntry'];

$empid = $_SESSION['SESS_EMP'];
$name = $_SESSION['SESS_NAME'];
$toemail = $_SESSION['SESS_EMAIL'];

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
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" data-target="#APVListModal">PR List <span class="glyphicon glyphicon-list"></span></button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" data-target="#cancel_modal" id="btnCancelDoc" disabled>Cancel <span class="glyphicon glyphicon-remove"></span></button>
							</div>
							<div class="btn-group hidden">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" id="btnCloseDoc" disabled>Close <span class="fa fa-ban fa-fw"></span></button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" id="btnPrint" disabled>Print <span class="glyphicon glyphicon-print"></span></button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" onclick="reload();">New <span class="fa fa-plus fa-fw"></span></button>
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
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="25%">Requester</td>
											<td width="70%"  style="padding-top: 0px;  padding-bottom: 2px;">
												<input readonly="" type="hidden" class="form-control input-sm" id="txtDocEntryAn" name="txtDocEntryAn" value="<?php echo $DocEntry?>">
												<input readonly="" type="hidden" class="form-control input-sm" id="txtDocEntry" name="txtDocEntry" value="<?php echo $DocEntry?>">
												<input readonly="" type="hidden" class="form-control input-sm" id="txtBaseEntry" name="txtBaseEntry" value="<?php echo $BaseEntry?>">
												<input readonly="" type="hidden" class="form-control input-sm" id="txtDocType" name="txtDocType" value="">
												<button id="btnRequesterType" type="button" class="hidden" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" requestertype-val="2">User</button>
                            					<div id="txtRequesterCont" class="input-group" style="height: 18px; padding: 0 0px; margin: 0;">
													<input type="text" readonly class="form-control input-sm required" aria-label="..." id="txtRequester" name="txtRequester" value="<?php echo $empid; ?>">
													<span class="input-group-addon <?php echo $hide; ?>" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#RequesterModal"><span class="glyphicon glyphicon-list"></span></span>
												</div>
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Requester Name</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" class="form-control input-sm" id="txtRequesterName" name="txtRequesterName" value="<?php echo $name; ?>" readonly>
											</td>
										</tr>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="25%">PR Ref.</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" class="form-control input-sm" id="txtDocRef" name="txtDocRef" value="" maxlength="30" autofocus>
											</td>
										</tr>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="25%">Project</td>
											<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
												<select type="text" class="form-control input-sm disabled hidden" id="txtBranch" name="txtBranch"></select>
												<select type="text" class="form-control input-sm disabled hidden" id="txtDepartment" name="txtDepartment"></select>
											</td>
										</tr>
										
									</tbody>
								</table>
								
							</div>
						</div>
						<div class="col-lg-4 col-md-4">	
							<div class="form-group">
								<table width="100%" border="0" class="hidden">
									<tbody>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="20%">Approval Status 1</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtAppStatus" type="text" name="txtAppStatus" style="width:100%" class="form-control input-sm" disabled>
											</td>
											<td width="5%">&nbsp;&nbsp;&nbsp;</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="20%">Approval Status 2</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtAppStatus1" type="text" name="txtAppStatus1" style="width:100%" class="form-control input-sm" disabled>
											</td>
											<td width="5%">&nbsp;&nbsp;&nbsp;</td>
										</tr>
									</tbody>
								</table>	
							</div>
							<div class="form-group hidden">
								<table width="100%" border="0">
									<tbody>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="20%">PO Ref.</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input readonly id="txtPORef" type="text" name="txtPORef" style="width:100%" class="form-control input-sm">
											</td>
											<td width="5%">&nbsp;&nbsp;&nbsp;</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="20%">Email To</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<textarea rows="3" cols="40" id="txtToEmail" name="txtToEmail"><?php echo $toemail; ?></textarea>
											</td>
											<td width="5%">&nbsp;&nbsp;&nbsp;</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="20%">Vendor</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<div id="txtRequesterCont" class="input-group" style="height: 18px; padding: 0 0px; margin: 0;">
													<input id="txtVendor" type="text" name="txtVendor" class="form-control input-sm">
													<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#BPModal"><span class="glyphicon glyphicon-list"></span></span>
												</div>
											</td>
											<td width="5%">&nbsp;&nbsp;&nbsp;</td>
										</tr>
									</tbody>
								</table>
							</div>	
						</div>
						<div class="col-lg-4 col-md-4">	
							<div class="form-group">
								<table width="100%" border="0">
									<tbody>
										<tr>
											<td width="25%">Doc No.</td>
											<td width="70%%" style="padding-top: 2px;  padding-bottom: 2px;">
												
												<div class="input-group-btn hidden">
													<!-- Button and dropdown menu -->
													<button id="btnSeries" type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
													<button id="btnSeriesDD" type="button" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
														<span class="caret"></span>
														<span class="sr-only">Toggle Dropdown</span>
													</button>
													<!--Series-->
													<ul id="SeriesList" class="dropdown-menu" role="menu">
														<li><a class="series" val-series="120016676" val-seriesnum="15" val-bplid="1">PO</a></li>
														<li><a class="series" val-series="410012963" val-seriesnum="38" val-bplid="1">Art_PO</a></li>
													</ul>
													<!--End Series-->
												</div>
												<input id="txtDocNo" type="text" name="txtDocNo" style="width:100%" aria-label="..." class="form-control input-sm" disabled>
											</td>
											<td width="5%">&nbsp;&nbsp;&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Status</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtDocStatus" type="text" name="txtDocStatus" style="width:100%" class="form-control input-sm" readonly>
											</td>
											<td width="5%">&nbsp;&nbsp;&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Posting Date</td>
											<td width="75%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtPostingDate" type="text" name="txtPostingDate" style="width:100%" class="form-control input-sm required">
											</td>
											<td width="5%">&nbsp;&nbsp;&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Valid Until</td>
											<td width="75%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtValidUntilDate" type="text" name="txtValidUntilDate" style="width:100%" class="form-control input-sm required">
											</td>
											<td width="5%">&nbsp;&nbsp;&nbsp;</td>
										</tr>
										<tr class="hidden">
											<td width="25%">Document Date</td>
											<td width="75%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtDocDate" type="text" name="txtDocDate" style="width:100%" class="form-control input-sm">
											</td>
											<td width="5%">&nbsp;&nbsp;&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Required Date</td>
											<td width="75%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" class="form-control input-sm required" id="txtRequiredDate" name="txtRequiredDate">
											</td>
											<td width="5%">&nbsp;&nbsp;&nbsp;</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#contents" data-toggle="tab">Contents</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="contents">
                                   <div class="panel panel-default">
										<div class="panel-heading">
										
											Item/Service Type 
										
											<select class="input-sm required" id="cmbServiceType" name="cmbServiceType">
												<option value="I">Item</option>
												<option value="S">Service</option>
											</select>
											<div class="pull-right">
												<div class="btn-group">
													<input type="button" class="btn btn-primary btn-xs" id="btnAddRow" value="Add Row">
													<input type="button" class="btn btn-danger btn-xs" id="btnDelRow" value="Delete Row">
													<input type="button" class="btn btn-success btn-xs hidden" id="btnFreeText" value="Add Free Text">
												</div>
											</div>
										</div>									
									
									
										<div class="table-responsive" style="height: 180px; width:100%; border: solid lightblue 1px; resize: vertical; overflow:auto;">
											<div id="ModDetails">
												<!--DOM-->
											</div>
										</div>
									
									</div>
									<!-- /.panel -->
                                </div>
                            </div>
						</div>
						<!-- /.col-lg-12 -->
					</div>
					<!-- /.row (nested) -->
					<div class="row">
						<div class="col-lg-4 col-md-4">
							<div class="form-group">
								<table width="100%" border="0">
									<tbody>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Owner</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtOwner" type="text" name="txtOwner" class="form-control input-sm" style="width:100%" value="<?php echo $name; ?>" readonly>
												<input type="hidden" id="txtOwnerCode" name="txtOwnerCode" value="<?php echo $empid; ?>" readonly/>
											</td>
											<td width="5%" style="padding-top: 2px;  padding-bottom: 2px;">
												<button class="<?php echo $hide; ?>" type="button" class="input-sm" data-toggle="modal" data-target="#OwnerModal"><span class="fa fa-list fa-fw"></span></button>
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Remarks</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;"><textarea rows="5" cols="60" id="txtRemarksF" name="txtRemarksF"  maxlength="253"></textarea></td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">&nbsp;</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">&nbsp;</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">
											</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<button id="btnSave" name="btnSave" type="button" data-toggle="modal" data-target="#add_modal">&nbsp;&nbsp;&nbsp;Add <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
												<button id="btnUpdate" class="hidden" name="btnUpdate" type="button" data-toggle="dropdown">&nbsp;&nbsp;&nbsp;Update <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
											</td>	
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!-- /.col-lg-4 -->
						
						<div class="col-lg-4 col-md-4">
							<div class="form-group hidden">
								<table width="100%" border="0">
									<tbody>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="20%">Mode of &nbsp; Shipment</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtMShip" type="text" name="txtMShip" style="width:100%" class="form-control input-sm" maxlength="30">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="20%">Disapproved Remarks</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;"><textarea readonly rows="3" cols="40" id="txtDisappRemarks" name="txtDisappRemarks"></textarea></td>
											<td width="5%">&nbsp;</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-4 col-md-4">
							<div class="form-group">
								<table width="100%" border="0">
									<tbody>
										<tr>
											<td width="25%">Total Before Discount</td>
											<td width="70%" colspan="3" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="TotBefDisc" type="text" name="TotBefDisc" class="form-control input-sm" style="width:100%" readonly>
											</td>
											<td width="5%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
										</tr>
										<tr class="hidden">
											<td width="25%">Discount</td>
											<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtDiscPercentF" type="text" name="txtDiscPercentF" class="form-control input-sm" style="width:100%">
											</td>
											<td width="5%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" class="form-control input-sm" style="width:100%" value="%" readonly>
											</td>
											<td width="50%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtDiscAmtF" type="text" name="txtDiscAmtF" class="form-control input-sm" style="width:100%" readonly>
											</td>
										</tr>
										<tr>
											<td width="25%">Tax</td>
											<td width="70%" colspan="3" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtTaxF" type="text" name="txtTaxF" class="form-control input-sm" style="width:100%" readonly>
											</td>
											<td width="5%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Total Payment Due</td>
											<td width="70%" colspan="3" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtTotalPaymentDue" type="text" name="txtTotalPaymentDue" class="form-control input-sm" style="width:100%" readonly>
											</td>
											<td width="5%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
										</tr>
									</tbody>
								</table>
								<br>
								<table width="100%" border="0">
									<tbody>
										<tr>
											<td width="25%">
												&nbsp;
											</td>
											<td width="25%">
												
											</td>
											<td width="25%">
												
											</td>
											<td width="25%">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td width="25%">
												&nbsp;
											</td>
											<td width="25%">
												
											</td>
											<td width="25%">
												
											</td>
											<td width="25%">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td width="25%">
												&nbsp;
											</td>
											<td width="25%">
											 	<button id="btnDuplicate" class="hidden" name="btnDuplicate" type="button" data-toggle="modal" data-target="#duplicate_modal">&nbsp;&nbsp;&nbsp;Duplicate <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
												
											</td>
											<td width="25%">
												
											</td>
											<td width="25%">
												&nbsp;
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!-- /.col-lg-8 -->
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


<!-- Warehouse  Modal-->
<div class="modal fade bs-example-modal-lg" id="WhsModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">

	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Warehouse
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-2">
						Search Item : 
					</div>
					<div class="col-lg-4">
						<input type="text" name="WhsSearch" class="form-control input-sm" placeholder="Search..." />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
							<div id="WhsCont">
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Warehouse modal -->



<!-- Item Modal  -->
<div class="modal fade bs-example-modal-lg" id="ItemModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				List of Items
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
<!-- /Item modal -->



<!-- Account  Modal-->
<div class="modal fade bs-example-modal-lg" id="AcctModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Account
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-1">
						Search  : 
					</div>
					<div class="col-lg-4">
						<input type="text" name="AcctSearch" class="form-control input-sm" placeholder="Search..." />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
							<div id="AcctCont">
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
<!-- /Account modal -->


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



<!-- Inventory Data Modal  -->
<div class="modal fade bs-example-modal-lg" id="InvDataModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Inventory Data
			</div>
			<div class="panel-body">
				<div class="modal-body" id="InvDataCont">
                    <!--DOM-->
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Inventory Data modal -->

<!-- Inventory Data Modal  -->

<div class="modal fade" id="BPModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Business Partner 
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-1">
						Search : 
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
	
<!-- /Inventory Data modal -->

	<!-- Owner Data Modal  -->
	<div class="modal fade bs-example-modal-lg" id="OwnerModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
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

	<!-- Requesters Modal -->
    <div class="modal fade bs-example-modal-lg" id="RequesterModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg">
			<div class="panel panel-info">
				<div class="panel-heading">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
					List of Requesters 
				</div>
				<div class="panel-body">
					<input type="text" name="RequesterSearch" class="form-control input-sm" placeholder="Search..." />
					<div class="modal-body" id="RequesterCont">
					
					</div>
				
				</div>
			</div>
        </div>
    </div>
    <!-- /Requesters modal -->

<!-- Serial Modal  -->
<div class="modal fade bs-example-modal-lg" id="SerialModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Serial Number Selection
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-6 col-md-6">
						<div id="SerialCont">
				
						</div>
						
						<table width="100%" border="0">
							<tbody>
								<tr>
									<td>
										<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" id="btnSerialNo">Select<span class="fa fa-check fa-fw"></span></button>
									</td>	
								</tr>	
							</tbody>
						</table>
						
					</div>
					<div class="col-lg-6 col-md-6">
						<div id="SerialList">
				
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="add_modal">
	<div class="modal-dialog">
		<div class="col-xs-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					Purchase Request
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
						<button type="button" id="btnSavePR" class="form-control btn btn-success" data-dismiss="modal">Yes</button>
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
					Purchase Request
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="panel-body">
					<div class="alert alert-success">
						<a href="#" class="alert-link">Alert! </a> Are you sure you want to Cancel this record?
					</div>
					<div class="pull-left">
						<button type="button" class="form-control btn btn-danger" data-dismiss="modal">No</button>
					</div>
					<div class="pull-right">
						<button type="button" id="btnCancelPR" class="form-control btn btn-success" data-dismiss="modal">Yes</button>
					</div>
				</div>
				<div class="panel-footer">
					
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="duplicate_modal">
	<div class="modal-dialog">
		<div class="col-xs-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					Purchase Request
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="panel-body">
					<div class="alert alert-success">
						<a href="#" class="alert-link">Alert! </a> Are you sure you want to Duplicate this record?
					</div>
					<div class="pull-left">
						<button type="button" class="form-control btn btn-danger" data-dismiss="modal">No</button>
					</div>
					<div class="pull-right">
						<button type="button" id="btnSavePR" class="form-control btn btn-success" data-dismiss="modal">Yes</button>
					</div>
				</div>
				<div class="panel-footer">
					
				</div>
			</div>
		</div>
	</div>
</div>

<!-- APV List Modal  -->
<div class="modal fade bs-example-modal-lg" id="APVListModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog" style="width:70%;">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				PR List Parameter 
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-3">
						<table width="100%" border="0">
							<tbody>
								<tr>
									<td width="30%">Date From : </td>
									<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
										<input type="date" id="txtDateFrom" name="txtDateFrom" class="form-control input-sm" value="<?php echo date('Y-m-01'); ?>"/>
									</td>
								</tr>
								<tr>
									<td width="30%">Date To : </td>
									<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
										<input type="date" id="txtDateTo" name="txtDateTo" class="form-control input-sm" value="<?php echo date('Y-m-t'); ?>"/>
									</td>
								</tr>
								<tr>
									<td width="30%">Ref. No. From : </td>
									<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
										<input type="text" id="txtAPVListFrom" name="txtAPVListFrom" class="form-control input-sm"/>
									</td>
								</tr>
								<tr>
									<td width="30%">Ref. No. To : </td>
									<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
										<input type="text" id="txtAPVListTo" name="txtAPVListTo" class="form-control input-sm"/>
									</td>
								</tr>
							</tbody>
						</table>	
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
						<div id="resView"></div>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="pull-right">
					<button type="button" id="btnAPVListVIEW" class="btn btn-xs btn-info">VIEW <span class="fa fa-eye fa-fw"></span></button>
					<button type="button" id="btnAPVListEXCEL" class="btn btn-xs btn-info">EXCEL <span class="fa fa-download fa-fw"></span></button>
					<button type="button" id="btnAPVListPDF" class="btn btn-xs btn-info">PDF <span class="fa fa-file-o fa-fw"></span></button>
				</div>
				<a href="#" data-dismiss="modal" class="btn btn-xs btn-default">Close</a>
            </div>
		</div>
	</div>
</div>
<!-- APV List modal -->

<script src="../../bootstrap/vendor/jquery/jquery.min.js"></script>

<?php include_once('../../include/head_bottom.php') ?>
<script src="../../js/PR/pr.js"></script>

