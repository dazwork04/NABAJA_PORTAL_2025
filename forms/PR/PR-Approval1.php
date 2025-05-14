<?php 
	include_once('../../include/head_top.php');
	include_once('../../config/config.php');
	
?>

	
    <div id="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-info">
					<div class="panel-heading">
						Purchase Request Approval
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<table width="100%" class="table table-striped table-bordered table-hover" id="tbl_po_app">
							<thead>
								<tr>
									<th class="hidden">DocEntry</th>
									<th>#</th>
									<th>DATE</th>
									<th>PR REF.</th>
									<th>PROJECT NAME</th>
									<th>NAME</th>
									<th>TOTAL</th>
									<th>DECISION</th>
									<th>REMARKS</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$a = 1;
									
										
									$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
											SELECT T0.DocEntry,
													T0.DocDate,
													T0.U_PRref,
													T0.ReqName,
													T2.PrjName,
													T0.DocTotal
											FROM OPRQ T0
											LEFT JOIN OHEM T1 ON T0.Requester = T1.empID
											LEFT JOIN OPRJ T2 ON T1.ExtEmpNo = T2.PrjCode
											WHERE T0.DocStatus = 'O' AND T0.U_AppStatus = 'Pending' 
											ORDER BY DocEntry DESC");
									
									while (odbc_fetch_row($qry)) 
									{
										?>	
											<tr>
												<td class="docentry hidden"><?php echo odbc_result($qry, 'DocEntry'); ?></td>
												<td><?php echo '<input type="hidden" class="docentry" value="'.odbc_result($qry, 'docentry').'" readonly="readonly"><input type="checkbox" style="width:30px; height:30px;" class="itemselected" id="chkDoc[]" name="chkDoc[]" value="'.odbc_result($qry, 'docentry').'">'; ?></td>
												<td><?php echo date("m/d/Y", strtotime(odbc_result($qry, 'DocDate'))); ?></td>
												<td><?php echo odbc_result($qry, 'U_PRref'); ?></td>
												<td><?php echo odbc_result($qry, 'PrjName'); ?></td>
												<td><?php echo odbc_result($qry, 'ReqName'); ?></td>
												<td align="right"><?php echo number_format(odbc_result($qry, 'DocTotal'),2); ?></td>
												<td><center><?php echo '<select class="input-sm decision" id="selDecision" name="selDecision">
												<option value="Pending">Pending</option>
												<option value="Approved">Approved</option>
												<option value="Rejected">Rejected</option>
												</select>'; ?></center></td>
												<td><center><?php echo '<textarea class="input-sm remarks" id="txtARemarks" rows="4" cols="50" name="txtARemarks" maxlength="254"></textarea>'; ?></center></td>
											</tr>
										<?php
										$a++;
									}
									
									odbc_free_result($qry);

								?>
							</tbody>
							
						</table>
						<!-- /.table-responsive -->
					</div>
					<!-- /.panel-body -->
					<div class="panel-footer">
						<button type="button" data-toggle="dropdown" id="btnUpdateApproved" name="btnUpdateApproved" >&nbsp;&nbsp;&nbsp;Update <span class="fa fa-save fa-fw"></span>&nbsp;&nbsp;&nbsp;</button>
					</div>
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		
    </div>
    <!-- /#wrapper -->
	
	<!-- loading modal  -->
	<div id="disapp_modal" class="modal fade">
		<div class="modal-dialog">
			<div class="panel panel-info">
				<div class="panel-heading">
					Disapproved Remarks
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="panel-body">
					<textarea rows="3" cols="40" id="txtDisappRemarks" name="txtDisappRemarks"></textarea>
				</div>
				<div class="panel-footer">
					<button type="button" id="btnDisapp">Disapproved</button>
					<button type="button" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
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
		
	<!-- jQuery -->
    <script src="../../bootstrap/vendor/jquery/jquery.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#tbl_po_app').DataTable({
            responsive: true
        });
    });
    </script>

	<?php include_once('../../include/head_bottom.php') ?>
	<script src="../../js/PR/pr.js"></script>
	
	<script type="text/javascript">
	<!-- Script for submit data -->
		
	
	</script>
	
	