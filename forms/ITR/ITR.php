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
							<!--<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" id="btnCancelDoc" disabled>Cancel <span class="glyphicon glyphicon-remove"></span></button>
							</div>-->
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" data-target="#ListModal">ITR List <span class="glyphicon glyphicon-list"></span></button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" id="btnCloseDoc" disabled>Close <span class="fa fa-ban fa-fw"></span></button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" id="btnPrint" disabled>Print <span class="glyphicon glyphicon-print"></span></button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" onclick="reload();">New<span class="fa fa-plus fa-fw"></span></button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" data-target="#DocumentModal">Find<span class="fa fa-search fa-fw"></span></button>
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
											<td width="25%">&nbsp;Branch</td>
											<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
												<input class="hidden" readonly="" type="text" class="form-control input-sm" id="txtDocEntry" name="txtDocEntry">
												<input class="hidden" readonly="" type="text" class="form-control input-sm" id="txtBaseEntry" name="txtBaseEntry" value="<?php echo $BaseEntry?>">
												<input readonly id="txtBusinessPartner" type="text" name="txtBusinessPartner" class="form-control input-sm" value="">
												<button type="button" class="input-sm hidden" data-toggle="modal" data-target="#BPModal"><span class="fa fa-list fa-fw"></span></button>
											</td>
										</tr>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="25%">&nbsp;Name</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input readonly id="txtName" type="text" name="txtName" class="form-control input-sm" value="">
											</td>
										</tr>
										<!--<tr class="hidden">
											<td width="25%">&nbsp;</td>
											<td width="75%"  style="padding-top: 2px;  padding-bottom: 2px;">
												<input disabled id="txtContactPerson" type="text" name="txtContactPerson" class="form-control input-sm">
											</td>
										</tr>
										<tr class="hidden">
											<td width="25%">&nbsp;</td>
											<td width="75%" style="padding-top: 2px;  padding-bottom: 2px;">
												&nbsp;<textarea disabled rows="3" class="form-control input-sm" id="txtShipTo" name="txtShipTo"></textarea>
											</td>
										</tr>-->
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">&nbsp;From Warehouse</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<div id="txtFromWarehouseCont" class="input-group">
													<input type="text" class="form-control input-sm required" id="txtFromWarehouse" name="txtFromWarehouse" aria-whscode="" value="" readonly>
													<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#WhsModal"><span class="glyphicon glyphicon-list"></span></span>
												</div>
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">&nbsp;To Warehouse</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<div id="txtToWarehouseCont" class="input-group">
													<input type="text" class="form-control input-sm required" id="txtToWarehouse" name="txtToWarehouse" aria-whscode="" readonly>
													<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#WhsModal"><span class="glyphicon glyphicon-list"></span></span>
												</div>
											</td>
										</tr>
										<!--<tr>
											<td width="25%">Pricelist</td>
											<td width="75%" style="padding-top: 2px;  padding-bottom: 2px;">
												<select type="text" class="form-control input-sm" id="txtPriceList" name="txtPriceList">

												</select>
											</td>
										</tr>-->
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-4 col-md-4">	
							
						</div>
						<div class="col-lg-4 col-md-4">	
							<div class="form-group">
								<table width="100%" border="0">
									<tbody>
										<tr>
											<td width="25%">Doc No.</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<div class="input-group-btn hidden">
													<button id="btnSeries" type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
													<button id="btnSeriesDD" type="button" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
														<span class="caret"></span>
														<span class="sr-only">Toggle Dropdown</span>
													</button>
													<ul id="SeriesList" class="dropdown-menu" role="menu">
														<li><a class="series" val-series="120016676" val-seriesnum="15" val-bplid="1">PO</a></li>
														<li><a class="series" val-series="410012963" val-seriesnum="38" val-bplid="1">Art_PO</a></li>
													</ul>
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
												<input readonly id="txtPostingDate" type="text" name="txtPostingDate" style="width:100%" class="form-control input-sm required">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Delivery Date</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtDeliveryDate" type="txtDeliveryDate" name="txtDeliveryDate" style="width:100%" class="form-control input-sm required">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Document Date</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input readonly id="txtDocDate" type="text" name="txtDocDate" style="width:100%" class="form-control input-sm required">
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
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="contents">
                                   <div class="panel panel-default">
										<div class="panel-heading">
										
											<!-- 1Item/Service Type  -->
											&nbsp;
											<select class="input-sm required hidden" id="cmbServiceType" name="cmbServiceType">
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
											<td width="25%">Sales Employee</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<select type="text" class="form-control input-sm" id="txtSalesEmployee" name="txtSalesEmployee"></select>
											</td>
											
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Journal Remarks</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" class="form-control input-sm" id="txtJournalRemarks" name="txtJournalRemarks" value="Inventory Transfer Request -" maxlength="254">
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">&nbsp;</td>
											<td width="70%">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">&nbsp;</td>
											<td width="70%">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%" align="right"></td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<button id="btnSave" name="btnSave" type="button" data-toggle="dropdown">&nbsp;&nbsp;&nbsp;Add <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
												<button id="btnUpdate" name="btnUpdate" class="hidden" type="button" data-toggle="dropdown">&nbsp;&nbsp;&nbsp;Update <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!-- /.col-lg-4 -->
						
						<div class="col-lg-4 col-md-4">
							
						</div>
						<div class="col-lg-4 col-md-4">
							<div class="form-group">
								<table width="100%" border="0">
									<tbody>
										<tr class="hidden">
											<td width="25%">Pick and Pack Remarks</td>
											<td width="70%%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" class="form-control input-sm" id="txtPickAndPackRemarks" name="txtPickAndPackRemarks" value="">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Remarks</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<textarea class="form-control input-sm" name="txtRemarksF" rows="5" cols="60" id="txtRemarksF" maxlength="254"></textarea>
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
									</tbody>
								</table>
								<table width="100%" border="0">
									<tbody>
										<tr>
											<td width="25%">
												&nbsp;
											</td>
										</tr>
										<tr>
											<td width="25%">
												&nbsp;
											</td>
											<td width="25%">
											 	<div class="dropdown hidden">
												  <button id="btnCpyFrm" type="button" data-toggle="dropdown">Copy From
												  <span class="caret"></span></button>
													<ul id="btnCopyFrom" class="dropdown-menu">
														<li><a href="#SO">Sales Order</a></li>
													</ul>
												</div>
											</td>
											<td width="25%">
												<div class="dropdown hidden">
												  <button id="btnCpy" type="button" disabled data-toggle="dropdown">Copy To
												  <span class="caret"></span></button>
													<ul id="btnCopy" class="dropdown-menu">
														<li><a href="#IT">Inventory Transfer</a></li>
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
						<input type="text" name="ItemSearch" id="ItemSearch" class="form-control input-sm" placeholder="Search..." />
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
				<input type="text" name="BPSearch" class="form-control input-sm" placeholder="Search..." />
				<div class="modal-body" id="BPCont">
				
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
<!-- /Serial modal -->

<!-- List Modal  -->
<div class="modal fade" id="ListModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:70%;">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				ITR List Parameter 
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
								<tr class="hidden">
									<td width="30%">Ref. No. From : </td>
									<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
										<input type="text" id="txtRefListFrom" name="txtRefListFrom" class="form-control input-sm"/>
									</td>
								</tr>
								<tr class="hidden">
									<td width="30%">Ref. No. To : </td>
									<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
										<input type="text" id="txtRefListTo" name="txtRefListTo" class="form-control input-sm"/>
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
					<button type="button" id="btnITRListVIEW" class="btn btn-xs btn-info">VIEW <span class="fa fa-eye fa-fw"></span></button>
					<button type="button" id="btnITRListEXCEL" class="btn btn-xs btn-info">EXCEL <span class="fa fa-download fa-fw"></span></button>
					<button type="button" id="btnITRListPDF" class="btn btn-xs btn-info">PDF <span class="fa fa-file-o fa-fw"></span></button>
				</div>
				<a href="#" data-dismiss="modal" class="btn btn-xs btn-default">Close</a>
            </div>
		</div>
	</div>
</div>
<!-- List modal -->

<!-- END MODAL SECTION -->

<!--THis will show upon right click-->
<!--<ul class='custom-menu'>
  <li data-action="second">Close</li>
  <li data-action="first">Cancel</li>
</ul>
<!--End THis will show upon right click-->
<script src="../../bootstrap/vendor/jquery/jquery.min.js"></script>

<?php include_once('../../include/head_bottom.php') ?>
<script src="../../js/ITR/itr.js"></script>

