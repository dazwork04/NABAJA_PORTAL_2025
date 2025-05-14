<?php 
	include_once('../../include/head_top.php');
	include_once('../../config/config.php');

$BaseEntry = !isset($_GET['BaseEntry'])? '' : $_GET['BaseEntry'];
$CompanyName = $_SESSION['mssqldb'];
?>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<i class="fa fa-edit fa-fw"></i> <span id="mod-title"></span>
						<div class="pull-right">
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" data-target="#ListModal">GI List <span class="glyphicon glyphicon-list"></span></button>
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
							<div class="btn-group" style="display: none">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" data-target="#GRModal">Goods Receipt <span class="fa fa-search fa-fw"></span></button>
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
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">&nbsp;</td>
											<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
												<input readonly="" type="hidden" class="form-control input-sm" id="txtDocEntry" name="txtDocEntry">
												<input readonly="" type="hidden" class="form-control input-sm" id="txtBaseEntry" name="txtBaseEntry" value="<?php echo $BaseEntry?>">
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Price List</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<select class="form-control input-sm" id="txtPriceList" name="txtPriceList"></select>
											</td>
										</tr>
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
											<td width="25%">Posting Date</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtPostingDate" type="text" name="txtPostingDate" style="width:100%" class="form-control input-sm required">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Document Date</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtDocDate" type="text" name="txtDocDate" style="width:100%" class="form-control input-sm required">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Ref. 2</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtRef2" type="text" name="txtRef2" style="width:100%" class="form-control input-sm required" maxlength="100">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr class="hidden">
											<td width="25%">Service Card No.</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtServCardNo" type="text" name="txtServCardNo" class="form-control input-sm" maxlength="10">
											</td>
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
										
											&nbsp;
										
											<select class="input-sm required hidden" id="cmbServiceType" name="cmbServiceType">
												<option value="I">Item</option>
												<option value="S">Service</option>
											</select>
											<div class="pull-right">
												<div class="btn-group">
													<input type="button" class="btn btn-primary btn-xs" id="btnAddRow" value="Add Row">
													<input type="button" class="btn btn-danger btn-xs" id="btnDelRow" value="Delete Row">
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
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="25%">SO Reference</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtSORef" type="text" name="txtSORef" style="width:100%" class="form-control input-sm">
											</td>
										</tr>
										<tr class="hidden">
											<td width="5%">&nbsp;</td>
											<td width="25%">Reason for Goods Receipt</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<select class="form-control input-sm" id="txtRGoodsIssue" name="txtRGoodsIssue">
													<option value="1">Change items</option>
													<option value="2">Replacement - new unit from the warehouse</option>
													<option value="3">Issuance of freebies</option>
													<option value="4">DR</option>
												</select>
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Remarks</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;"><textarea rows="5" cols="60" id="txtRemarksF" name="txtRemarksF" maxlength="254"></textarea></td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%">Journal Remark</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input type="text" class="form-control input-sm" id="txtJournalRemarks" name="txtJournalRemarks" value="Goods Issue">
											</td>
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%" align="right">
											</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												
											</td>	
										</tr>
										<tr>
											<td width="5%">&nbsp;</td>
											<td width="25%" align="right">
											</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<button id="btnSave" name="btnSave" type="button" data-toggle="dropdown">&nbsp;&nbsp;&nbsp;Add <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
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
										<tr>
											<td>
											 	<div class="dropdown">
													<button type="button" id="btnCpyFrm" data-toggle="dropdown">Copy From
													<span class="caret"></span></button>
													<ul id="btnCopyFrom" class="dropdown-menu">
														<li><a href="#RRListModal" data-toggle="modal" data-target="#RRListModal">Receiving</a></li>
													</ul>
												</div>
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
					<div class="col-lg-1">
						Search : 
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
					<div class="col-lg-1">
						Search : 
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
						Search : 
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


<!-- Document List Modal  -->
<div class="modal fade bs-example-modal-lg" id="GRModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">

	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Goods Receipt List
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
						<div id="DocumentContGR">
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
				GI List Parameter 
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
										<input type="text" id="txtRefListFrom" name="txtRefListFrom" class="form-control input-sm"/>
									</td>
								</tr>
								<tr>
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
					<button type="button" id="btnGIListVIEW" class="btn btn-xs btn-info">VIEW <span class="fa fa-eye fa-fw"></span></button>
					<button type="button" id="btnGIListEXCEL" class="btn btn-xs btn-info">EXCEL <span class="fa fa-download fa-fw"></span></button>
					<button type="button" id="btnGIListPDF" class="btn btn-xs btn-info">PDF <span class="fa fa-file-o fa-fw"></span></button>
				</div>
				<a href="#" data-dismiss="modal" class="btn btn-xs btn-default">Close</a>
            </div>
		</div>
	</div>
</div>
<!-- List modal -->

<!-- RR List Modal  -->
<div class="modal fade" id="RRListModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:70%;">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				RR List Parameter 
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-4">
						<table width="100%" border="0">
							<tbody>
								<tr>
									<td width="30%">Date From : </td>
									<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
										<input type="date" id="txtDateFromGRPO" name="txtDateFromGRPO" class="form-control input-sm" value="<?php echo date('Y-m-01'); ?>"/>
									</td>
								</tr>
								<tr>
									<td width="30%">Date To : </td>
									<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
										<input type="date" id="txtDateToGRPO" name="txtDateToGRPO" class="form-control input-sm" value="<?php echo date('Y-m-t'); ?>"/>
									</td>
								</tr>
								<tr>
									<td width="30%">Ref. No. From : </td>
									<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
										<input type="text" id="txtRRListFrom" name="txtRRListFrom" class="form-control input-sm"/>
									</td>
								</tr>
								<tr>
									<td width="30%">Ref. No. To : </td>
									<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
										<input type="text" id="txtRRListTo" name="txtRRListTo" class="form-control input-sm"/>
									</td>
								</tr>
							</tbody>
						</table>	
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
						<div id="resViewGRPO"></div>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="pull-right">
					<button type="button" id="btnRRListVIEW" class="btn btn-xs btn-info">VIEW <span class="fa fa-eye fa-fw"></span></button>
					<!--<button type="button" id="btnRRListEXCEL" class="btn btn-xs btn-info">EXCEL <span class="fa fa-download fa-fw"></span></button>
					<button type="button" id="btnRRListPDF" class="btn btn-xs btn-info">PDF <span class="fa fa-file-o fa-fw"></span></button>-->
				</div>
				<a href="#" data-dismiss="modal" class="btn btn-xs btn-default">Close</a>
            </div>
		</div>
	</div>
</div>
<!-- RR List modal -->

<script src="../../bootstrap/vendor/jquery/jquery.min.js"></script>

<?php include_once('../../include/head_bottom.php') ?>
<script src="../../js/GI/gi.js"></script>

