<?php 
	include('../../config/config.php');
	$OwnerCode = $_SESSION['SESS_EMP'];
?>
<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblListofcustomer" style="font-size:10px">
	<thead>
		<tr>
			<th style="min-width:20px;"><center><input type="checkbox" id="selectAll" name="selectAll" data-sorter="false"></center></th>
			<th style="min-width:100px;">Customer Code</th>
			<th style="min-width:100px;">Customer Name</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT CardCode, CardName FROM OCRD WHERE CardType = 'C' AND frozenFor = 'N' ORDER BY CardName ASC");
			while (odbc_fetch_row($qry)) {
				
				echo '<tr class="srch">
						<td class="item-0"><center><input type="checkbox" class="itemselected" id="chkBP[]" name="chkBP[]" value="'.odbc_result($qry, 'CardCode').'"></center></td>
						<td class="item-1">'.odbc_result($qry, 'CardCode').'</td>
						<td class="item-2">'.odbc_result($qry, 'CardName').'</td>
					</tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
		?>
	</tbody>
</table>

