<?php 
include_once('../../../config/config.php');

	$lineno = $_POST['lineno'];
	$docno = $_POST['docno'];
															
		$qrySelect = odbc_exec($MSSQL_CONN, "SELECT T0.DocNum, T0.DocLine, T2.DistNumber, SUM(T1.AllocQty) AllocQty, T1.SysNumber
											FROM [".$_SESSION['mssqldb']."].[dbo].[OITL] T0 
											JOIN [".$_SESSION['mssqldb']."].[dbo].[ITL1] T1 ON T0.LogEntry = T1.LogEntry
											JOIN [".$_SESSION['mssqldb']."].[dbo].[OSRN] T2 ON T1.SysNumber = T2.SysNumber AND T0.ItemCode = T2.ItemCode
											WHERE T0.DocNum = '$docno' AND T0.DocType = 15 AND DocLine = '$lineno'
											GROUP BY T0.DocNum, T0.DocLine, T2.DistNumber, T1.SysNumber
											HAVING SUM(T1.AllocQty)=0 ");
		?>
		<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="">
			<thead>
				<tr>
					<th width="5%"></th>
					<th>Selected Serial No.</th>
				</tr>	
			</thead>
			<tbody>
				<?php
					while (odbc_fetch_row($qrySelect)) 
					{
						?>
						<tr>
							<td width="5%"></td>
							<td><?php echo odbc_result($qrySelect, 'DistNumber'); ?></td>
						</tr>
						<?php
					}
				?>	
			</tbody>
		</table>
		
		<?php
		
		odbc_free_result($qrySelect);
	
	odbc_close($MSSQL_CONN);

?>