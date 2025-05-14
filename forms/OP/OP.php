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
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" data-target="#DisbursementListModal">Disbursement List <span class="glyphicon glyphicon-list"></span></button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" data-target="#cancel_modal" id="btnCancelDoc">Cancel <span class="glyphicon glyphicon-remove"></span></button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs" data-toggle="dropdown" id="btnPrint" disabled>Print 
									<span class="glyphicon glyphicon-print"></span>
									<span class="caret"></span></button>
										<ul id="btnSelectPrint" class="dropdown-menu">
											<li><a href="#CH">Check</a></li>
											<li><a href="#CV">Check Voucher</a></li>
										</ul>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" onclick="reload();">New <span class="fa fa-plus fa-fw"></span></button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="modal" data-target="#DocumentModal">Find <span class="fa fa-search fa-fw"></span></button>
							</div>
						</div>
				</div>
				<form class="form-horizontal" id="IPForm">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-4 col-md-4">	
								<table width="100%" border="0">
									<tbody>
										<tr id="trBpCode">
											<td width="30%">BP Code</td>
											<td width="65%"  style="padding-top: 2px;  padding-bottom: 2px;">
												<input readonly="" type="hidden" class="form-control input-sm" id="txtDocEntry" name="txtDocEntry" >
												<input readonly="" type="hidden" class="form-control input-sm" id="txtBaseEntry" name="txtBaseEntry" value="<?php echo $BaseEntry?>">
												<input id="txtCustomer" type="text" name="txtCustomer" class="form-control input-sm required" maxlength="15">
											</td>
											<td width="5%" style="padding-top: 2px;  padding-bottom: 2px;">
												<button type="button" class="input-sm" data-toggle="modal" data-target="#BPModal"><span class="fa fa-list fa-fw"></span></button>
											</td>
										</tr>
										<tr>
											<td width="30%">BP Name</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtName" type="text" name="txtName" class="form-control input-sm required" maxlength="100">
											</td>
										</tr>
										<tr>
											<td width="30%">Bill To</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<textarea class="form-control input-sm" rows="3" name="txtBillTo" id="txtBillTo" maxlength="254"></textarea>
											</td>
										</tr>
										<tr>
											<td width="30%">Remit To</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtRemitTo" type="text" name="txtRemitTo" class="form-control input-sm" maxlength="50">
											</td>
										</tr>
										<tr class="hidden">
											<td width="30%">Contact Person</td>
											<td width="65%"  style="padding-top: 2px;  padding-bottom: 2px;">
												<select class="form-control input-sm" id="selContactPerson" name="selContactPerson"></select>
											</td>
											<td width="5%" style="padding-top: 2px;  padding-bottom: 2px;">
												<!--<button type="button" class="input-sm" data-toggle="modal" data-target="#ContactPersonModal"><span class="fa fa-list fa-fw"></span></button>-->
											</td>
										</tr>
										<tr>
											<td width="30%">Project</td>
											<td width="65%"  style="padding-top: 2px;  padding-bottom: 2px;">
												<div class="input-group projectCont">
													<input type="hidden" id="txtPrjCode" name="txtPrjCode" class="form-control input-sm" readonly/>
													<input type="text" id="txtPrjName" name="txtPrjName" class="form-control input-sm" readonly/>
													<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#ProjectModal"><span class="glyphicon glyphicon-list"></span></span>
												</div>
											</td>
											<td width="5%" style="padding-top: 2px;  padding-bottom: 2px;">
											</td>
										</tr>
									</tbody>
								</table>
						</div>
						<div class="col-lg-4 col-md-4">	
							<div class="form-group">
								<table width="100%" border="0">
									<tbody>
										<tr>
											<td width="5%"></td>
											<td width="5%" style="padding-top: 2px;  padding-bottom: 2px;">
												<center><input type="radio" id="radCategory" name="radCategory" value="Vendor" checked></center>
											</td>
											<td width="90%" style="padding-top: 2px;  padding-bottom: 2px;">Vendor</td>
										</tr>
										<tr>
											<td width="5%"></td>
											<td width="5%" style="padding-top: 2px;  padding-bottom: 2px;">
												<center><input type="radio" id="radCategory" name="radCategory" value="Account"></center>
											</td>
											<td width="90%" style="padding-top: 2px;  padding-bottom: 2px;">Account</td>
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
											<td width="25%">Posting Date</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtPostingDate" type="text" name="txtPostingDate" style="width:100%" class="form-control input-sm required">
											</td>
											<td width="5%">&nbsp;</td>
										</tr>
										<tr>
											<td width="25%">Due Date</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtDueDate" type="text" name="txtDueDate" style="width:100%" class="form-control input-sm required">
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
											<td width="25%">Reference</td>
											<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
												<input id="txtReference" type="text" name="txtReference" style="width:100%" class="form-control input-sm" maxlength="50">
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
							<div class="panel panel-default">
								<div class="panel-heading">
									<select class="input-sm hidden" id="cmbServiceType" name="cmbServiceType">
										<option value="I">Item</option>
										<option value="S">Service</option>
									</select>
									&nbsp;
									<div class="pull-right">
										<div class="btn-group">
											<input type="button" class="btn btn-primary btn-xs hidden" id="btnAddRow" value="Add Row">
											<input type="button" class="btn btn-danger btn-xs hidden" id="btnDelRow" value="Delete Row">
											<input type="button" class="btn btn-info btn-xs" id="btnSelectAll" value="Select All">
											<input type="button" class="btn btn-danger btn-xs" id="btnDeselectAll" value="Deselect All">
											<input type="button" class="btn btn-success btn-xs" id="btnAddInSequence" value="Add in Sequence">
										</div>
									</div>
								</div>
								<div class="table-responsive" style="height: 180px; width:100%; border: solid lightblue 1px; resize: vertical; overflow:auto;">
									<div id="ModDetails">
										<!--DOM-->
									</div>
								</div>								
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<span style="font-size:10"><b>PAYMENT MEANS</b></span>
							<ul class="nav nav-tabs">
								<li class="" id="CheckTabLi"><a data-toggle="pill" href="#CheckTab">Check</a></li>
								<li class="" id="BankTransferTabLi"><a data-toggle="pill" href="#BankTransferTab" >Bank Transfer</a></li>
								<li class="" id="CreditCardTabLi"><a data-toggle="pill" href="#CreditCardTab">Credit Card</a></li>
								<li class="active" id="CashTabLi"><a data-toggle="pill" href="#CashTab">Cash</a></li>
							</ul>

							<div class="tab-content">
								<div id="CheckTab" class="tab-pane fade">
									<div class="row">
										<div class="col-lg-4 col-md-4">
											<table width="100%" border="0">
												<tbody>
													<tr class="hidden">
														<td width="30%">GL Account</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<div class="input-group acctcodeCont" style="height: 18px; padding: 0 4px; margin: 0;">
																<input readonly class="form-control input-sm acctcode" id="txtGLAccountCheck" name="txtGLAccountCheck" />
																<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#AcctModal"><span class="glyphicon glyphicon-list"></span></span>
															</div>
														</td>
													</tr>
													<tr class="hidden">
														<td width="30%">Account Name</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input readonly class="form-control input-sm" id="txtGLAccountCheckName" name="txtGLAccountCheckName" />
														</td>
													</tr>
												</tbody>	
											</table>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12">
											<hr>
											<table class="table table-hover table-bordered table-condensed" id="tblChecks">
												<thead>
													<tr>
														<th style="min-width:50px;" class="hidden">Row #</th>
														<th style="min-width:150px;">Due Date</th>
														<th style="min-width:100px;">Country</th>
														<th style="min-width:100px;">Bank Name</th>
														<th style="min-width:100px;">Branch</th>
														<th style="min-width:100px;">Account</th>
														<th style="min-width:100px;">Check No.</th>
														<th style="min-width:100px;">Amount</th>
													</tr>
												</thead>
												<tbody>
													<tr>
													<td class="hidden"><input type="text" class="form-control input-sm" id="txtCheckRowNo" name="txtCheckRowNo"></td>
													<td>
														<div id="txtCheckDueDateCont" class="input-group" style="height: 18px; padding: 0 4px; margin: 0;">
															<input type="text" class="form-control input-sm required" id="txtCheckDueDate" name="txtCheckDueDate">
															<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;"><span class="glyphicon glyphicon-calendar"></span></span>
														</div>
													</td>
													<td>
														<select type="text" class="form-control input-sm" id="txtCountry" name="txtCountry"></select>
													</td>
													<td>
														<select type="text" class="form-control input-sm" id="txtBankName" name="txtBankName"></select>
													</td>
													<td>
														<select type="text" class="form-control input-sm" id="txtBranch" name="txtBranch"></select>
													</td>
													<td>
														<input type="text" class="form-control input-sm" id="txtCheckAccount" name="txtCheckAccount" readonly>
													</td>
													<td><input type="text" onkeypress="return isNumberKey(event)" class="form-control input-sm" id="txtCheckNo" name="txtCheckNo" maxlength="10"></td>
													
													<td><input type="text" onkeypress="return isNumberKey(event)" class="form-control input-sm numeric" id="txtCheckAmount" name="txtCheckAmount"></td>
												</tr>
												</tbody>
											</table>										
										</div>
									</div>
								</div>
								<div id="BankTransferTab" class="tab-pane fade">
									<div class="row">
										<div class="col-lg-4 col-md-4">
											<br>
											<table width="100%" border="0">
												<tbody>
													<tr>
														<td width="30%">GL Account</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<div class="input-group acctcodeCont">
																<input readonly class="form-control input-sm acctcode" id="txtGLAccountBankTransfer" name="txtGLAccountBankTransfer" />
																<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#AcctModal"><span class="glyphicon glyphicon-list"></span></span>
															</div>
														</td>
													</tr>
													<tr>
														<td width="30%">Account Name</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input readonly class="form-control input-sm acctcode" id="txtGLAccountBankTransferName" name="txtGLAccountBankTransferName" />
														</td>
													</tr>
													<tr>
														<td width="30%">Transfer Date</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<div id="txtTransferDateCont" class="input-group">
																<input type="text" class="form-control input-sm" id="txtTransferDate" name="txtTransferDate">
																<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;"><span class="glyphicon glyphicon-calendar"></span></span>
															</div>
														</td>
													</tr>
													<tr>
														<td width="30%">Reference</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input type="text" class="form-control input-sm" id="txtBankTransferReference" name="txtBankTransferReference" maxlength="27">
														</td>
													</tr>
													<tr>
														<td width="30%">Total Bank Transfer</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input type="text" onkeypress="return isNumberKey(event)"  class="form-control input-sm numeric" id="txtTotalBankTransfer" name="txtTotalBankTransfer">
														</td>
													</tr>
												</tbody>	
											</table>
											<hr>
										</div>
									</div>
								</div>
								<div id="CashTab" class="tab-pane fade active in">
									<div class="row">
										<div class="col-lg-4 col-md-4">
											<br>
											<table width="100%" border="0">
												<tbody>
													<tr>
														<td width="30%">GL Account</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<div class="input-group acctcodeCont">
																<input readonly class="form-control input-sm acctcode required" id="txtGLAccountCash" name="txtGLAccountCash" />
																<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#AcctModal"><span class="glyphicon glyphicon-list"></span></span>
															</div>
														</td>
													</tr>
													<tr>
														<td width="30%">Account Name</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input readonly class="form-control input-sm" id="txtGLAccountCashName" name="txtGLAccountCashName" />
														</td>
													</tr>
													<tr class="hidden">
														<td width="30%">Primary Form Item</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<select class="form-control input-sm" id="txtPrimaryFormItem" name="txtPrimaryFormItem">
																<option value="1">Payments for Invoices from Customers</option>
															</select>
														</td>
													</tr>
													<tr>
														<td width="30%">Total Cash</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input type="text" onkeypress="return isNumberKey(event)" class="form-control input-sm numeric" id="txtTotalCash" name="txtTotalCash">
														</td>
													</tr>
												</tbody>	
											</table>
											<hr>
										</div>
									</div>
								</div>
								<div id="CreditCardTab" class="tab-pane fade">
									<div class="row">
										<div class="col-lg-4 col-md-4">
											<br>
											<table width="100%" border="0">
												<tbody>
													<tr>
														<td width="30%">Credit Card Name</td>
														<td width="40%" style="padding-top: 2px;  padding-bottom: 2px;">
															<select class="form-control input-sm" id="selCreditCardName" name="selCreditCardName">
																
															</select>
														</td>
													</tr>
													<tr>
														<td width="30%">GL Account</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input class="form-control input-sm" id="txtGLAccountCreditCard" name="txtGLAccountCreditCard" />
														</td>
													</tr>
													<tr>
														<td width="30%">Amount Due</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input type="text" onkeypress="return isNumberKey(event)" class="form-control input-sm numeric" id="txtAmountDue" name="txtAmountDue" />
														</td>
													</tr>
													<tr>
														<td width="30%">Voucher No.</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input class="form-control input-sm" id="txtVoucherNo" name="txtVoucherNo" maxlength="20"/>
														</td>
													</tr>
													<tr>
														<td width="30%">No. of Payments</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input class="form-control input-sm" id="txtNoPayments" name="txtNoPayments" value = "1" readonly/>
														</td>
													</tr>
													<tr>
														<td width="30%">First Partial Payment</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input class="form-control input-sm" id="txtPartialPayment" name="txtPartialPayment" readonly/>
														</td>
													</tr>
													<tr>
														<td width="30%">Each Add. Payment</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input class="form-control input-sm" id="txtAddPayment" name="txtAddPayment" readonly/>
														</td>
													</tr>
													
													<tr class="hidden">
														<td width="30%">Credit Card No.</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input class="form-control input-sm" id="txtCreditCardNo" name="txtCreditCardNo" maxlength="4" />
														</td>
													</tr>
													<tr class="hidden">
														<td width="30%">Valid Until</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input class="form-control input-sm" id="txtValidUntil" name="txtValidUntil"/>
														</td>
													</tr>
													<tr  class="hidden">
														<td width="30%">ID No.</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input class="form-control input-sm" id="txtIdNo" name="txtIdNo" />
														</td>
													</tr>
													<tr  class="hidden">
														<td width="30%">Telephone No.</td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input class="form-control input-sm" id="txtTelephoneNo" name="txtTelephoneNo" />
														</td>
													</tr>
													<tr>
														<td width="30%"></td>
														<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
															<button id="btnNewCreditCard" name="btnNewCreditCard" type="button" class="hidden">&nbsp;&nbsp;&nbsp;New <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
															<button id="btnSaveCreditCard" name="btnSaveCreditCard" type="button">&nbsp;&nbsp;&nbsp;Save <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
														</td>
													</tr>
												</tbody>	
											</table>
											<hr>
										</div>
										<div class="col-lg-4 col-md-4">
											<br>
											<table width="100%" border="1" id="tblCreditCard">
												<thead>
													<tr>
														<th><center>#</center></th>
														<th>Credit Card Payment</th>
														<th class="hidden">Credit Card No.</th>
														<th class="hidden">GL Account</th>
														<th class="hidden">Credit Card Name</th>
														<th class="hidden">Valid Until</th>
														<th class="hidden">Amount Due</th>
														<th class="hidden">Voucher No.</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>	
											</table>
											<table width="100%" border="0">
												<tbody>
													<tr class="hidden">
														<td width="10%">Payment Method</td>
														<td width="40%" style="padding-top: 2px;  padding-bottom: 2px;">
															<select class="form-control input-sm" id="selPaymentMethod" name="selPaymentMethod">
																
															</select>
														</td>
													</tr>
													<tr class="hidden">
														<td width="10%">Transaction Type</td>
														<td width="40%" style="padding-top: 2px;  padding-bottom: 2px;">
															<input class="form-control input-sm" id="txtTransactionType" name="txtTransactionType" />
														</td>
													</tr>
												</tbody>	
											</table>
											<hr>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-4 col-md-4">
								<table width="100%" border="0">
										<tbody>
											<tr>
												<td width="30%">Remarks</td>
												<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
													<input type="text" class="form-control input-sm" id="txtComments" name="txtComments"  maxlength="254">
												</td>
											</tr>
											<tr>
												<td width="30%">Journal Remarks</td>
												<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
													<input type="text" class="form-control input-sm" id="txtJournalRemarks" name="txtJournalRemarks"  maxlength="254">
												</td>
											</tr>
										</tbody>	
									</table>
						</div>
						<div class="col-lg-4 col-md-4">
							<div class="form-group">
								
							</div>
						</div>
						<div class="col-lg-4 col-md-4">
							<div class="form-group">
								<table width="100%" border="0">
										<tbody>
											<tr id="trPaymentAccount" class="">
												<td width="25%">Payment on Account</td>
												<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
													<input type="text" readonly class="form-control input-sm" id="txtPaymentOnAccount" name="txtPaymentOnAccount">
												</td>
												<td width="5%">&nbsp;</td>
											</tr>
											<tr id="trNetTotal" class="hidden">
												<td width="25%">Net Total</td>
												<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
													<input type="text" readonly class="form-control input-sm" id="txtNetTotal" name="txtNetTotal">
												</td>
												<td width="5%">&nbsp;</td>
											</tr>
											<tr id="trTotalTax" class="hidden">
												<td width="25%">Total Tax</td>
												<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
													<input type="text" readonly class="form-control input-sm" id="txtTotalTax" name="txtTotalTax">
												</td>
												<td width="5%">&nbsp;</td>
											</tr>
											<tr>
												<td width="25%">Total Amount Due</td>
												<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
													<input type="text" readonly class="form-control input-sm" id="txtTotalAmountDue" name="txtTotalAmountDue">
												</td>
												<td width="5%">&nbsp;</td>
											</tr>
											<tr>
												<td width="25%">Open Balance</td>
												<td width="70%" style="padding-top: 2px;  padding-bottom: 2px;">
													<input type="text" readonly class="form-control input-sm" id="txtOpenBalance" name="txtOpenBalance">
												</td>
												<td width="5%">&nbsp;</td>
											</tr>
										</tbody>	
									</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-4 col-md-4">
							<div class="form-group">
								<table width="100%" border="0">
									<tbody>
										<tr>
											<td width="25%" align="right">&nbsp;</td>
											<td width="75%" style="padding-top: 2px;  padding-bottom: 2px;">&nbsp;</td>	
										</tr>
										<tr>
											<td width="25%" align="right">
													<button id="btnSave" name="btnSave" type="button" data-toggle="modal" data-target="#add_modal">&nbsp;&nbsp;&nbsp;Add <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
													<button class="hidden" id="btnUpdate" name="btnUpdate" type="button" data-toggle="dropdown">&nbsp;&nbsp;&nbsp;Update <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
											</td>
											<td width="75%" style="padding-top: 2px;  padding-bottom: 2px;"></td>	
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

<!-- /Department modal -->
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

<!-- Account  Modal-->
<div class="modal fade bs-example-modal-lg" id="AcctModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Account List
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

<!-- Account  Modal-->
<div class="modal fade bs-example-modal-lg" id="AcctModal1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Account List
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-1">
						Search : 
					</div>
					<div class="col-lg-4">
						<input type="text" name="AcctSearch1" class="form-control input-sm" placeholder="Search..." />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg-12">
						<div id="AcctCont1">
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
				Disbursement List
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
<div class="modal fade" id="add_modal">
	<div class="modal-dialog">
		<div class="col-xs-12">
			<div class="panel panel-success">
				<div class="panel-heading">
					Outgoing Payment
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
						<button type="button" id="btnSaveOP" class="form-control btn btn-success" data-dismiss="modal">Yes</button>
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
					Outgoing Payment
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
						<button type="button" id="btnCancelOP" class="form-control btn btn-success" data-dismiss="modal">Yes</button>
					</div>
				</div>
				<div class="panel-footer">
					
				</div>
			</div>
		</div>
	</div>
</div>

<!-- OP List Modal  -->
<div class="modal fade" id="DisbursementListModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:80%;">
		<div class="panel panel-info">
			<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>
				Disbursement List Parameter 
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
										<input type="text" id="txtDisbursementListFrom" name="txtDisbursementListFrom" class="form-control input-sm"/>
									</td>
								</tr>
								<tr>
									<td width="30%">Ref. No. To : </td>
									<td width="70%"  style="padding-top: 2px;  padding-bottom: 2px;">
										<input type="text" id="txtDisbursementListTo" name="txtDisbursementListTo" class="form-control input-sm"/>
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
					<button type="button" id="btnDisbursementListVIEW" class="btn btn-xs btn-info">VIEW <span class="fa fa-eye fa-fw"></span></button>
					<button type="button" id="btnDisbursementListEXCEL" class="btn btn-xs btn-info">EXCEL <span class="fa fa-download fa-fw"></span></button>
					<button type="button" id="btnDisbursementListPDF" class="btn btn-xs btn-info">PDF <span class="fa fa-file-o fa-fw"></span></button>
				</div>
				<a href="#" data-dismiss="modal" class="btn btn-xs btn-default">Close</a>
            </div>
		</div>
	</div>
</div>
<!-- OP List modal -->

<script src="../../bootstrap/vendor/jquery/jquery.min.js"></script>
	<script>
    $(document).ready(function() {
        $('#tbl_business_partner').DataTable({
                responsive: true
        });
    });
    </script>
	

<?php include_once('../../include/head_bottom.php') ?>
<script src="../../js/OP/op.js"></script>

