<?php 
	include_once('../../include/head_top.php');
	include_once('../../config/config.php');
	$selWhse = $_GET['selWhse'];
	$selItemGroup = $_GET['selItemGroup'];
	
	if($selItemGroup == '')
	{
		$selItemGroup1 = '';
	}
	else
	{
		$selItemGroup1 = " AND T0.ItmsGrpCod = '$selItemGroup' ";
	}
?>

		<div id="wrapper">
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading">
							In-Stock Monitoring
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
						
							<table width="100%" class="table table-striped table-bordered table-hover table-condensed" id="tblUsersPortal">
								<thead>
									<tr>
										<th>ItemCode</th>
										<th>ItemName</th>
										<th>UOM</th>
										<th>In Stock</th>
										<th>Unit Cost</th>
										<th>Whse/Project</th>
									</tr>
								</thead>
								<tbody>
									<?php
										
										$qrySelect = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
											SELECT T0.ItemCode, 
													T0.ItemName,
													T0.InvntryUom,
													T1.OnHand,
													T1.WhsCode,
													T2.WhsName,
													T0.AvgPrice
											FROM OITM T0
											INNER JOIN OITW T1 ON T0.ItemCode = T1.ItemCode
											INNER JOIN OWHS T2 ON T1.WhsCode = T2.WhsCode
											WHERE T1.WhsCode = '$selWhse'
											$selItemGroup1
										
										");
										
										while (odbc_fetch_row($qrySelect)) 
										{
											?>	
												<tr>
													<td><?php echo odbc_result($qrySelect,'ItemCode'); ?></td>
													<td><?php echo utf8_encode(odbc_result($qrySelect, 'ItemName')); ?></td>
													<td><?php echo odbc_result($qrySelect, 'InvntryUom'); ?></td>
													<td align="right"><?php echo number_format(odbc_result($qrySelect, 'OnHand'),2); ?></td>
													<td align="right"><?php echo number_format(odbc_result($qrySelect, 'AvgPrice'),2); ?></td>
													<td><?php echo odbc_result($qrySelect, 'WhsName'); ?></td>
												</tr>
											<?php
										}
										odbc_free_result($qrySelect);
									?>
								</tbody>
							</table>
							</form>									
						</div>
					</div>
				</div>
			</div>
		</div>
	
	<!-- jQuery -->
    <script src="../../bootstrap/vendor/jquery/jquery.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#tblUsersPortal').DataTable({
            responsive: true
        });
    });
    </script>
   
	<?php include_once('../../include/head_bottom.php') ?>
	
	
	
	
	
	

	
	
	
