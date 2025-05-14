<!-- Sales Report Modal -->
<form id="form-srpt-parameter">
	<div class="modal fade" id="srpt-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="panel panel-info">
				<div class="panel-heading">
					PR Monitoring Parameter
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="panel-body">
					<table width="100%" border="0">
						<thead>
							
						</thead>
						<tbody>
							<tr>
								<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;"><span style="font-size:10pt;">Date from :</span></td>
								<td width="80%" style="padding-top: 2px;  padding-bottom: 2px;"><input type="text" id="txtDateFrom" name="txtDateFrom" class="form-control input-sm required"/></td>
							</tr>
							<tr>
								<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;"><span style="font-size:10pt;">Date to :</span></td>
								<td width="80%" style="padding-top: 2px;  padding-bottom: 2px;"><input type="text" id="txtDateTo" name="txtDateTo" class="form-control input-sm required"/></td>
							</tr>
						</tbody>
					</table>	
				</div>
				<div class="panel-footer">
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td>
									<button type="button" class="btn btn-default btn-xs pull-left" data-dismiss="modal">Close</button>
									
									<button type="button" id="btnGrpt" class="btn btn-info btn-xs pull-right">Generate PDF</button>
									<!--<button type="button" id="btnExcel" class="btn btn-warning btn-xs pull-right">Generate Excel</button>-->
									
								</td>
							</tr>
					</table>
				</div>
			</div>
		</div><!-- /.modal-dialog -->
	</div>
	
	<div class="modal fade" id="invrpt-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="panel panel-info">
				<div class="panel-heading">
					Inventory Monitoring Parameter
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="panel-body">
					<table width="100%" border="0">
						<thead>
							
						</thead>
						<tbody>
							<tr>
								<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;"><span style="font-size:10pt;">Date from :</span></td>
								<td width="80%" style="padding-top: 2px;  padding-bottom: 2px;"><input type="date" id="txtDateFromInv" name="txtDateFromInv" class="form-control input-sm invrequired" value="<?php echo date('Y-m-d'); ?>" min="2018-01-01" max="2050-12-31"/></td>
							</tr>
							<tr>
								<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;"><span style="font-size:10pt;">Date to :</span></td>
								<td width="80%" style="padding-top: 2px;  padding-bottom: 2px;"><input type="date" id="txtDateToInv" name="txtDateToInv" class="form-control input-sm invrequired" value="<?php echo date('Y-m-d'); ?>" min="2018-01-01" max="2050-12-31"/></td>
							</tr>
						</tbody>
					</table>	
				</div>
				<div class="panel-footer">
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td>
									<button type="button" class="btn btn-default btn-xs pull-left" data-dismiss="modal">Close</button>
									
									<button type="button" id="btnInvRpt" class="btn btn-info btn-xs pull-right">Generate PDF</button>
									<!--<button type="button" id="btnExcel" class="btn btn-warning btn-xs pull-right">Generate Excel</button>-->
									
								</td>
							</tr>
					</table>
				</div>
			</div>
		</div><!-- /.modal-dialog -->
	</div>
	
		<div class="modal fade" id="insrpt-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="panel panel-info">
				<div class="panel-heading">
					In-Stock Monitoring Parameter
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="panel-body">
					<table width="100%" border="0">
						<thead>
							
						</thead>
						<tbody>
							<tr>
								<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;"><span style="font-size:10pt;">Whse/Project :</span></td>
								<td width="80%" style="padding-top: 2px;  padding-bottom: 2px;">
									<select id="selWhse" name="selWhse" class="form-control input-sm insrequired">
										<option value="">-Select-</option>
									</select>
								</td>
							</tr>
							<tr>
								<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;"><span style="font-size:10pt;">Item Group :</span></td>
								<td width="80%" style="padding-top: 2px;  padding-bottom: 2px;">
									<select id="selItemGroup" name="selItemGroup" class="form-control input-sm">
										<option value="">-Select-</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>	
				</div>
				<div class="panel-footer">
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td>
									<button type="button" class="btn btn-default btn-xs pull-left" data-dismiss="modal">Close</button>
									<button type="button" id="btnInsExe" class="btn btn-info btn-xs pull-right">Generate</button>&nbsp;
									<button type="button" id="btnInsRpt" class="btn btn-primary btn-xs pull-right">PDF</button>&nbsp;&nbsp;&nbsp;
									<!--<button type="button" id="btnExcel" class="btn btn-warning btn-xs pull-right">Generate Excel</button>-->
									
								</td>
							</tr>
					</table>
				</div>
			</div>
		</div><!-- /.modal-dialog -->
	</div>
</form>
<!-- Sales Report Modal -->

<div class="modal fade" id="ewt2307-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="panel panel-info">
			<div class="panel-heading">
				EWT 2307 Parameter
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="panel-body">
				<table width="100%" border="0">
					<thead>
						
					</thead>
					<tbody>
						<tr>
							<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;"><span style="font-size:10pt;">APV Ref. No. :</span></td>
							<td width="80%" style="padding-top: 2px;  padding-bottom: 2px;"><input type="text" id="txtAPVRefNo" name="txtAPVRefNo" class="form-control input-sm ewtrequired" maxlenght="20"/></td>
						</tr>
						<tr>
							<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;"><span style="font-size:10pt;">Authorized Rep. :</span></td>
							<td width="80%" style="padding-top: 2px;  padding-bottom: 2px;"><input type="text" id="txtAuthoRep" name="txtAuthoRep" class="form-control input-sm ewtrequired" maxlenght="60" value="JOSEFINA G. BORJA"/></td>
						</tr>
						<tr>
							<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;"><span style="font-size:10pt;">Designation :</span></td>
							<td width="80%" style="padding-top: 2px;  padding-bottom: 2px;"><input type="text" id="txtDesignation" name="txtDesignation" class="form-control input-sm ewtrequired" maxlenght="60" value="ACCOUNTING SUPERVISOR"/></td>
						</tr>
						<tr>
							<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;"><span style="font-size:10pt;">TIN :</span></td>
							<td width="80%" style="padding-top: 2px;  padding-bottom: 2px;"><input type="text" id="txtTIN" name="txtTIN" class="form-control input-sm ewtrequired" maxlenght="60" value="430-334-302-000"/></td>
						</tr>
					</tbody>
				</table>	
			</div>
			<div class="panel-footer">
				<table width="100%" border="0">
					<tbody>
						<tr>
							<td>
								<button type="button" class="btn btn-default btn-xs pull-left" data-dismiss="modal">Close</button>
								
								<button type="button" id="btnEwt2307" class="btn btn-info btn-xs pull-right">Generate</button>
							</td>
						</tr>
				</table>
			</div>
		</div>
	</div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="apaging-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="panel panel-info">
			<div class="panel-heading">
				A/P Aging Parameter
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="panel-body">
				<table width="100%" border="0">
					<thead>
						
					</thead>
					<tbody>
						<tr>
							<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;"><span style="font-size:8pt;">As of Date :</span></td>
							<td width="80%" style="padding-top: 2px;  padding-bottom: 2px;"><input type="date" id="txtAsOfDate" name="txtAsOfDate" class="form-control input-sm asofdateaprequired" value="<?php echo date('Y-m-d'); ?>" min="2018-01-01" max="2050-12-31"/></td>
						</tr>
						<tr>
							<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;" valign="top"><span style="font-size:8pt;">Supplier :</span></td>
							<td width="80%" style="padding-top: 2px;  padding-bottom: 2px;">
								<div class="table-responsive" style="height: 220px; width:100%; border: solid lightblue 1px; resize: vertical; overflow:auto;">
									<div id="ListVendor">
									
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>	
			</div>
			<div class="panel-footer">
				<table width="100%" border="0">
					<tbody>
						<tr>
							<td>
								<button type="button" class="btn btn-default btn-xs pull-left" data-dismiss="modal">Close</button>
								
								<button type="button" id="btnAPAging" class="btn btn-info btn-xs pull-right">Generate</button>
							</td>
						</tr>
				</table>
			</div>
		</div>
	</div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="araging-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="panel panel-info">
			<div class="panel-heading">
				A/R Aging Parameter
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="panel-body">
				<table width="100%" border="0">
					<thead>
						
					</thead>
					<tbody>
						<tr>
							<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;"><span style="font-size:10pt;">As of Date :</span></td>
							<td width="80%" style="padding-top: 2px;  padding-bottom: 2px;"><input type="date" id="txtAsOfDate" name="txtAsOfDate" class="form-control input-sm asofdatearrequired" value="<?php echo date('Y-m-d'); ?>" min="2018-01-01" max="2050-12-31"/></td>
						</tr>
						<tr>
							<td width="20%" style="padding-top: 2px;  padding-bottom: 2px;" valign="top"><span style="font-size:8pt;">Customer :</span></td>
							<td width="80%" style="padding-top: 2px;  padding-bottom: 2px;">
								<div class="table-responsive" style="height: 220px; width:100%; border: solid lightblue 1px; resize: vertical; overflow:auto;">
									<div id="ListCustomer">
									
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>	
			</div>
			<div class="panel-footer">
				<table width="100%" border="0">
					<tbody>
						<tr>
							<td>
								<button type="button" class="btn btn-default btn-xs pull-left" data-dismiss="modal">Close</button>
								
								<button type="button" id="btnARAging" class="btn btn-info btn-xs pull-right">Generate</button>
							</td>
						</tr>
				</table>
			</div>
		</div>
	</div><!-- /.modal-dialog -->
</div>