<?php include_once('../../../config/configmd.php');?>

<div class="table-responsive" style="max-height:350px;overflow-y:scroll;">
	<table class="table table-condensed table-hover" id="tblDocument">
	  <thead>
	    <tr>
	      <th class="hidden">User ID</th>
	      <th>User Code</th>
	  	  <th>Name</th>
	      <th>Password</th>
	      <th>User Type</th>
	      <th>Status</th>
	    
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."]; SELECT TOP 100 T0.UserId,T0.UserCode,T0.Name,T0.UserPass,T0.UserType,T0.Status,T0.sapuser,T0.sappass 
																			FROM [@OUSR] T0
																			ORDER BY T0.UserId");
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch">
						<td class="item-1 hidden">'.odbc_result($qry, 'UserId').'</td>
						<td class="item-2">'.odbc_result($qry, 'UserCode').'</td>
						<td class="item-3">'.odbc_result($qry, 'Name').'</td>
						<td class="item-4">'.odbc_result($qry, 'UserPass').'</td>
						<td class="item-5">'.odbc_result($qry, 'UserType').'</td>
						<td class="item-6">'.odbc_result($qry, 'Status').'</td>
						
				      </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
