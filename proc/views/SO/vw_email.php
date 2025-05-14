<?php 
include_once('../../../config/config.php');

																
		$qrySelect = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT * FROM [WEB-COMMON].[dbo].[@OUSR] WHERE position = 21" );
		?>
		<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tbl_Email">
			<thead>
				<tr>
					<th width="5%"></th>
					<th>Name</th>
					<th>Email Address</th>
				</tr>	
			</thead>
			<tbody>
				<?php
					while (odbc_fetch_row($qrySelect)) 
					{
						?>
						<tr>
							<td width="5%"><input type="checkbox" id="Email" name="Email[]" value="<?php echo odbc_result($qrySelect, 'per_email'); ?>"></td>
							<td><?php echo odbc_result($qrySelect, 'Name'); ?></td>
							<td><?php echo odbc_result($qrySelect, 'per_email'); ?></td>
						</tr>
						<?php
					}
				?>	
			</tbody>
		</table>
		
		<script>
		$(document).ready(function() {
			$('#tbl_Email').DataTable({
					responsive: true
			});
		});
		</script>
		
		<?php
		
		odbc_free_result($qrySelect);
	
	odbc_close($MSSQL_CONN);

?>