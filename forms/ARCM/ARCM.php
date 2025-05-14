<?php 
	include_once('../../include/head_top.php');
	include_once('../../config/config.php');

$BaseEntry = !isset($_GET['BaseEntry'])? '' : $_GET['BaseEntry'];
$DocEntry = !isset($_GET['DocEntry'])? '' : $_GET['DocEntry'];
if($DocEntry == '') {
	$hidden = '';
}
else
{
	$hidden = 'hidden';
}

$empid = $_SESSION['SESS_EMP'];
$name = $_SESSION['SESS_NAME'];

?>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<i class="fa fa-edit fa-fw"></i> <span id="mod-title"></span>
						<div class="pull-right">
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" data-target="#ARListModal">A/R Credit Memo List <span class="glyphicon glyphicon-list"></span></button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" id="btnCancelDoc" disabled data-toggle="modal" data-target="#cancel_inv_modal">Cancel <span class="glyphicon glyphicon-remove"></span></button>
							</div>
							<div class="btn-group hidden">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" id="btnCloseDoc" disabled>Close <span class="fa fa-ban fa-fw"></span></button>
							</div>
							<div class="btn-group">
								<div class="dropdown">
								<button type="button" class="btn btn-default btn-xs" data-toggle="dropdown" id="btnPrint" disabled>Print 
									<span class="glyphicon glyphicon-print"></span>
									<span class="caret"></span></button></button>
										<ul id="btnSelectPrint" class="dropdown-menu">
											<li><a href="#CI">Charge Invoice</a></li>
											<li><a href="#SI">Transaction Invoice</a></li>
										</ul>
								</div>
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
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Customers</td>
											<td width="65%"  style="padding-top: 2px;  padding-bottom: 2px;">
												<input readonly="" type="hidden" class="form-control input-sm" id="txtDocEntry" name="txtDocEntry" value="<?php echo $DocEntry?>">
												<input readonly="" type="hidden" class="form-control input-sm" id="txtBaseEntry" name="txtBaseEntry" value="<?php echo $BaseEntry?>">
												<input readonly="" type="hidden" class="form-control input-sm" id="txtBaseEntry1" name="txtBaseEntry1" value="">
												<input id="txtVendor" type="text" name="txtVendor" class="form-control input-sm required" value="">
											</td>
											<td width="5%" style="padding-top: 2px;  padding-bottom: 2px;">
												<button type="button" class="input-sm" data-toggle="modal" data-target="#BPModal"><span class="fa fa-list fa-fw"></span></button>
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Name</td>
											<td width="65%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtName" type="text" name="txtName" class="form-control input-sm required" value="">
											</td>
											<td width="5%" style="padding-top: 2px;  padding-bottom: 2px;">
												<button type="button" class="input-sm hidden" id="btnAddCustomer"><span class="fa fa-plus fa-fw"></span></button>
											</td>
										</tr>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="25%">Customer Name</td>
											<td width="65%"  style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtCustName" type="text" name="txtCustName" class="form-control input-sm" />		
												<select class="form-control input-sm hidden" id="selContactPerson" name="selContactPerson"></select>
											</td>
											<td width="5%" style="padding-top: 2px;  padding-bottom: 2px;">
												<button type="button" class="input-sm hidden" data-toggle="modal" data-target="#ContactPersonModal"><span class="fa fa-list fa-fw"></span></button>
											</td>
											
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">AR Ref. No.</td>
											<td width="75%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtVendorRefNo" type="text" name="txtVendorRefNo" class="form-control input-sm">
											</td>
										</tr>
									</tbody>
								</table>
								<table width="100%" border="0">
									<tbody>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="28%" style="padding-top: 2px;  padding-bottom: 2px;">
												<select class="form-control input-sm" name="selCurSource" id="selCurSource" onchange="getDocCur(this);">
													<option value="L">Local Currency</option>
													<option value="S">System Currency</option>
													<option value="C">BP Currency</option>
												</select>
											</td>
											<td width="33%" style="padding-top: 2px;  padding-bottom: 2px;">
												<select class="form-control input-sm required" id="selDocCur" name="selDocCur">
													<option value="PHP">PHP</option>
												</select> 	 
											</td>
											<td width="33%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" class="form-control input-sm required" name="txtDocRate" id="txtDocRate" value="1.000000">
												<input type="hidden" class="form-control input-sm" name="txtListNum" id="txtListNum" value="">
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-4 col-md-4">	
							<div class="form-group">
								<table width="100%" border="0">
									<tbody>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="20%">SI No.</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtSINo" type="text" name="txtSINo" class="form-control input-sm" maxlength="10">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="20%">Invoice Type</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<select class="form-control input-sm" id="selInvoiceType" name="selInvoiceType"></select>
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="20%">Service Card No.</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtServCardNo" type="text" name="txtServCardNo" class="form-control input-sm" maxlength="10">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="20%">Amount Originally Paid</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtAmtOrigPaid" type="text" name="txtAmtOrigPaid" class="form-control input-sm" maxlength="10">
												<span style="color:	#CD5C5C"><I>* Number Only..</I></span>
											</td>
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
											<td width="25%">Doc No.</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												
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
											<td width="5%">&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Status</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtDocStatus" type="text" name="txtDocStatus" style="width:100%" class="form-control input-sm" readonly>
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Posting Date</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtPostingDate" type="text" name="txtPostingDate" style="width:100%" class="form-control input-sm required">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Due Date</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtDeliveryDate" type="txtDeliveryDate" name="txtDeliveryDate" style="width:100%" class="form-control input-sm required">
												<td width="5%">&nbsp;</td>
											</td>
										</tr>
										<tr>
											<td width="25%">Document Date</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtDocDate" type="text" name="txtDocDate" style="width:100%" class="form-control input-sm required">
											</td>
											<td width="5%">&nbsp;</td>
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
                                <li>
									<a href="#logistics" data-toggle="tab">Logistics</a>
                                </li>
                                <li>
									<a href="#accounting" data-toggle="tab">Accounting</a>
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
													<!--<input type="button" class="btn btn-success btn-xs" id="btnFreeText" value="Add Free Text">-->
												</div>
											</div>
										</div>									
										<div class="table-responsive" style="height: 250px; width:100%; border: solid lightblue 1px; resize: vertical; overflow:auto;">
											<div id="ModDetails">
												<!--DOM-->
											</div>
										</div>
									
									</div>
									<!-- /.panel -->
                                </div>
                                <div class="tab-pane fade" id="logistics">
                                    <div class="table-responsive" style="height: 180px; width:100%; border: solid lightblue 2px;">
										<table width="25%" border="0">
											<tbody>
												<tr>
													<td width="5%">Ship To
														<select class="form-control input-sm" name="selShipTo" id="selShipTo"></select>
													</td>
													<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;">
														<textarea class="form-control input-sm" rows="3" name="txtShipTo" id="txtShipTo"></textarea>
													</td>
												</tr>
												<tr>
													<td>Bill To
														<select class="form-control input-sm" name="selBillTo" id="selBillTo"></select>
													</td>
													<td style="padding-top: 2px;  padding-bottom: 2px;">
														<textarea class="form-control input-sm" rows="3" name="txtBillTo" id="txtBillTo"></textarea>
													</td>
												</tr>
											</tbody>	
										</table>		
									</div>
                                </div>
                                <div class="tab-pane fade" id="accounting">
                                    <div class="table-responsive" style="height: 180px; width:100%; border: solid lightblue 2px;">
										<table width="25%" border="0">
											<tbody>
												<tr>
													<td width="25%">Payment Terms</td>
													<td width="75%" style="padding-top: 2px;  padding-bottom: 2px;">
														<select type="text" class="form-control input-sm" id="txtPayment" name="txtPayment"></select>
													</td>
												</tr>
											</tbody>	
										</table>
									</div>
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
											<td width="25%">Sales Employee</td>
											<td width="70%%" style="padding-top: 2px;  padding-bottom: 2px;">
												<select type="text" class="form-control input-sm" id="txtSalesEmployee" name="txtSalesEmployee"></select>
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Owner</td>
											<td width="65%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtOwner" type="text" name="txtOwner" class="form-control input-sm" style="width:100%" value="<?php echo $name; ?>" readonly>
												<input type="hidden" id="txtOwnerCode" name="txtOwnerCode" value="<?php echo $empid; ?>"/>
											</td>
											<td width="5%" style="padding-top: 2px;  padding-bottom: 2px;">
												<button type="button" class="input-sm hidden" data-toggle="modal" data-target="#OwnerModal"><span class="fa fa-list fa-fw"></span></button>
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Remarks</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;"><textarea rows="5" cols="60" id="txtRemarksF" name="txtRemarksF"></textarea></td>
										</tr>
										
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%" align="right">
												&nbsp;
											</td>
											<td width="75%" style="padding-top: 2px;  padding-bottom: 2px;">
												<button id="btnSave" name="btnSave" type="button" data-toggle="modal" data-target="#add_inv_modal">&nbsp;&nbsp;&nbsp;Add <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
												<button class="hidden" id="btnUpdate" name="btnUpdate" type="button" data-toggle="dropdown">&nbsp;&nbsp;&nbsp;Update <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
											</td>	
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!-- /.col-lg-4 -->
						
						<div class="col-lg-4 col-md-4">
							<div class="form-group">
								<table width="100%" border="0">
									<tbody>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="20%">SO No.</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtSONo" type="text" name="txtSONo" class="form-control input-sm" maxlength="10">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="20%">TIN No.</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtTinNo" type="text" name="txtTinNo" class="form-control input-sm" maxlength="10">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="20%">Address</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtCustAdd" type="text" name="txtCustAdd" class="form-control input-sm" maxlength="60"> 
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="20%">Contact Info</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtContactInfo" type="text" name="txtContactInfo" class="form-control input-sm" maxlength="15">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="20%">Email Address</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtEmail" type="text" name="txtEmail" class="form-control input-sm" maxlength="100">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="20%">Birthdate</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtBirthdate" type="date" name="txtBirthdate" class="form-control input-sm">
											</td>
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
											<td width="25%">Total Before Disc</td>
											<td width="70%" colspan="3" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="TotBefDisc" type="text" name="TotBefDisc" class="form-control input-sm" style="width:100%" readonly>
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Discount</td>
											<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtDiscPercentF" type="text" name="txtDiscPercentF" class="form-control input-sm" style="width:100%">
											</td>
											<td width="5%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" class="form-control input-sm" style="width:100%" value="%" readonly>
											</td>
											<td width="45%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtDiscAmtF" type="text" name="txtDiscAmtF" class="form-control input-sm" style="width:100%" readonly>
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Tax</td>
											<td width="70%" colspan="3" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtTaxF" type="text" name="txtTaxF" class="form-control input-sm" style="width:100%" readonly>
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Total</td>
											<td width="70%" colspan="3" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtTotalPaymentDue" type="text" name="txtTotalPaymentDue" class="form-control input-sm" style="width:100%" readonly>
											</td>
											<td width="5%">&nbsp;</td>
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
											 	<div class="dropdown">
												  <button id="btnCopyFromHide" type="button" data-toggle="dropdown">Copy From
												  <span class="caret"></span></button>
													<ul id="btnCopyFrom" class="dropdown-menu">
														<!-- <li><a href="#DR">Delivery</a></li> -->
														<li><a href="#SO">Sales Order</a></li>
													</ul>
												</div>
											</td>
											<td width="25%">
												<div class="dropdown hidden">
												  <button id="btnCpy" type="button" disabled data-toggle="dropdown">Copy To
												  <span class="caret"></span></button>
													<ul id="btnCopy" class="dropdown-menu">
														<li><a href="#CM">Credit Memo</a></li>
													</ul>
												</div>
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


<!-- Department  Modal-->
<div class="modal fade bs-example-modal-lg" id="DepartmentModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">

	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Department List
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-1">
						Search : 
					</div>
					<div class="col-lg-4">
						<input type="text" name="DepartmentSearch" class="form-control input-sm" placeholder="Search..." />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
						<div id="DepartmentCont">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Department modal -->

<!-- Project  Modal-->
<div class="modal fade bs-example-modal-lg" id="ProjectModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">

	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Project List
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-1">
						Search : 
					</div>
					<div class="col-lg-4">
						<input type="text" name="ProjectSearch" class="form-control input-sm" placeholder="Search..." />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
						<div id="ProjectCont">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Project modal -->

<!-- Employee  Modal-->
<div class="modal fade bs-example-modal-lg" id="EmployeeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">

	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Employee List
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-1">
						Search : 
					</div>
					<div class="col-lg-4">
						<input type="text" name="EmployeeSearch" class="form-control input-sm" placeholder="Search..." />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
						<div id="EmployeeCont">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Employee modal -->

<!-- Equipment  Modal-->
<div class="modal fade bs-example-modal-lg" id="EquipmentModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Equipment List
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-1">
						Search : 
					</div>
					<div class="col-lg-4">
						<input type="text" name="EquipmentSearch" class="form-control input-sm" placeholder="Search..." />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
						<div id="EquipmentCont">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Equipment modal -->


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
					<div class="col-lg-2">
						Search Item : 
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
					<div class="col-lg-2">
						Search Item : 
					</div>
					<div class="col-lg-4">
						<input type="text" name="DocumentSearch" id="DocumentSearch" class="form-control input-sm" placeholder="Search..." />
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
				<div class="row">
					<div class="col-lg-12">
						<div id="InvDataCont">
						</div>
					</div>
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
				<div class="row">
					<div class="col-lg-2">
						Search Item : 
					</div>
					<div class="col-lg-4">
						<input type="text" name="OwnerSearch" class="form-control input-sm" placeholder="Search..." />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
						<div id="OwnerCont">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Owner Data modal -->

<!-- Email Modal  -->
<div class="modal fade" id="EmailModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				List of Email
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<div id="EmailList">
				
						</div>
						<table width="100%" border="0">
							<tbody>
								<tr>
									<td>
										<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" id="btnEmail">Select<span class="fa fa-check fa-fw"></span></button>
									</td>	
								</tr>	
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Email modal -->

<!-- Serial Modal  -->
<div class="modal fade bs-example-modal-lg" id="SerialModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Serial Number Selection
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<div id="SerialCont">
				
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Serial modal -->

<!-- loading modal  -->
<div id="ContactPersonModal" class="modal fade" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="panel panel-info">
			<div class="panel-heading">
				Contact Person
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="panel-body">
				Enter Name : <input type="text" id="txtBPContactPerson" name="txtBPContactPerson">
			</div>
			<div class="panel-footer">
				<button type="button" id="btnAddContact" data-dismiss="modal">Add Contact</button>
				<button type="button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="add_inv_modal">
	<div class="modal-dialog">
		<div class="col-xs-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					Sales Invoice
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
						<button type="button" id="btnSaveInv" class="form-control btn btn-success" data-dismiss="modal">Yes</button>
					</div>
				</div>
				<div class="panel-footer">
					
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="cancel_inv_modal">
	<div class="modal-dialog">
		<div class="col-xs-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					Transaction Invoice
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="panel-body">
					<div class="alert alert-success">
						<a href="#" class="alert-link">Alert! </a> Are you sure you want to Cancelled this record?
					</div>
					<div class="pull-left">
						<button type="button" class="form-control btn btn-danger" data-dismiss="modal">No</button>
					</div>
					<div class="pull-right">
						<button type="button" id="btnCancelInv" class="form-control btn btn-success" data-dismiss="modal">Yes</button>
					</div>
				</div>
				<div class="panel-footer">
					
				</div>
			</div>
		</div>
	</div>
</div>

	<div class="modal fade bs-example-modal-lg" id="SOModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg">
			<div class="panel panel-info">
				<div class="panel-heading">                   
                    Delivery List
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-2">
							Search Item : 
						</div>
						<div class="col-lg-4">
							<input type="text" name="DRSearch" class="form-control input-sm" placeholder="Search..." />
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-lg-12">
							<div id="SOCont">
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
	
	<div class="modal fade bs-example-modal-lg" id="SO1Modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg">
			<div class="panel panel-info">
				<div class="panel-heading">                   
                    Sales Quote List
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-2">
							Search Item : 
						</div>
						<div class="col-lg-4">
							<input type="text" name="SOSearch" class="form-control input-sm" placeholder="Search..." />
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-lg-12">
							<div id="SO1Cont">
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>

	<div id="void-alert" class="modal modal-flex fade" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<div class="col-xs-12">
				<div class="panel panel-success">
					<div class="panel-heading">
						Sales Invoice 
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="panel-body">
						<div class="alert alert-success">
							<a href="#" class="alert-link">Alert! </a> You are beyond max discount. Please enter Password...
						</div>
						<div class="form-group">
							<label></label>
							<input type="password" class="form-control input-lg" id="txtVoidPassword" name="txtVoidPassword" maxlength="15" autofocus />
						</div>
						<div class="pull-left">
							<button type="button" class="form-control btn btn-danger" data-dismiss="modal">No</button>
						</div>
						<div class="pull-right">
							<button type="button" id="btnSaveInv1" class="form-control btn btn-success" data-dismiss="modal">Yes</button>
						</div>
					</div>
					<div class="panel-footer">
						
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- AR List Modal  -->
<div class="modal fade" id="ARListModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:70%;">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				A/R Credit Memo List Parameter 
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
										<input type="text" id="txtARListFrom" name="txtARListFrom" class="form-control input-sm"/>
									</td>
								</tr>
								<tr>
									<td width="30%">Ref. No. To : </td>
									<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
										<input type="text" id="txtARListTo" name="txtARListTo" class="form-control input-sm"/>
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
					<button type="button" id="btnARListVIEW" class="btn btn-xs btn-info">VIEW <span class="fa fa-eye fa-fw"></span></button>
					<button type="button" id="btnARListEXCEL" class="btn btn-xs btn-info">EXCEL <span class="fa fa-download fa-fw"></span></button>
					<button type="button" id="btnARListPDF" class="btn btn-xs btn-info">PDF <span class="fa fa-file-o fa-fw"></span></button>
				</div>
				<a href="#" data-dismiss="modal" class="btn btn-xs btn-default">Close</a>
            </div>
		</div>
	</div>
</div>
<!-- AR List modal -->
	
<script>
	function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode < 45 || charCode > 57)
		{
			return false;
		}
		else
		{
			return true;	
		}	
	}
</script>
	

<?php include_once('../../include/head_bottom.php') ?>

<script src="../../js/ARCM/arcm.js"></script>

