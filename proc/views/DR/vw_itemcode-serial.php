<?php 
include_once('../../../config/config.php');

	$ItemCodeSerial = $_POST['ItemCodeSerial'];
															
		$qrySelect = odbc_exec($MSSQL_CONN, " SELECT T0.ItemCode, T0.SysNumber, T0.Quantity, T0.CommitQty, T1.DistNumber FROM [".$_SESSION['mssqldb']."].[dbo].[OSRQ] T0
											JOIN [".$_SESSION['mssqldb']."].[dbo].[OSRN] T1 ON T0.ItemCode = T1.ItemCode AND T0.SysNumber = T1.SysNumber 
											WHERE T0.Quantity != 0 AND T1.ItemCode = '$ItemCodeSerial'");
											//WHERE T0.CommitQty IS NULL OR T0.CommitQty = 0 AND T0.Quantity != 0 AND T1.ItemCode = '$ItemCodeSerial'");
		?>
		<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tbl_Serial">
			<thead>
				<tr>
					<th width="5%"></th>
					<th>Available Serial No.</th>
					<th>Allocated</th>
				</tr>	
			</thead>
			<tbody>
				<?php
					while (odbc_fetch_row($qrySelect)) 
					{
						?>
						<tr>
							<td width="5%"><input type="checkbox" id="CheckSerial" name="CheckSerial[]" value="<?php echo odbc_result($qrySelect, 'SysNumber'); ?>"></td>
							<td><?php echo odbc_result($qrySelect, 'DistNumber'); ?></td>
							<td>No</td>
						</tr>
						<?php
					}
				?>	
			</tbody>
		</table>
		
		<script>
		$(document).ready(function() {
			$('#tbl_Serial').DataTable({
					responsive: true
			});
		});
		</script>
		
		<?php
		
		odbc_free_result($qrySelect);
	
	odbc_close($MSSQL_CONN);

?>