<?php 
	include_once('../../include/head_top.php');
	include_once('../../config/config.php');
?>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-info">
					<div class="panel-heading">
						<i class="fa fa-edit fa-fw"></i> User Management
                    </div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-4 col-md-4">	
								<div class="form-group">
									<form>
									<table width="100%" border="0">
										<tbody>
											<tr>
												<td>Change User Pass</td>
												<td colspan="2" style="padding-top: 2px;  padding-bottom: 2px;"><input type="password" id="UserPass" type="text" name="UserPass" style="width:100%" class="form-control input-sm required"></td>
											</tr>
											<tr>
												<td colspan="2" align="right">
													<button type="button" style="width:80px" id="btnChangePass" name="btnChangePass" ><span class="fa fa-save fa-fw"></span> Save</button>
												</td>
											</tr>
											
										</tbody>
									</table>
									</form>
								</div>
							</div>
							<div class="col-lg-4 col-md-4">
								
							</div>
							<!-- /.col-lg-8 -->
						</div>
						<!-- /.row -->
					</div>
					<!-- /.panel body -->
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		
		
	<!-- Owner Data Modal  -->
	<div class="modal fade bs-example-modal-lg" id="EmployeeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
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
	
	<!-- jQuery -->
    <script src="../../bootstrap/vendor/jquery/jquery.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
   
	<?php include_once('../../include/head_bottom.php') ?>
	<script src="../../js/UM/cp.js"></script>
	
	
	
	
	

	
	
	
