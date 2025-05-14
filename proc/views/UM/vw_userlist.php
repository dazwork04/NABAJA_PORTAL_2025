<?php include_once('../../../config/config.php');

?>
<div class="table-responsive" style="max-height:350px;overflow-y:scroll;">
	<table class="table table-condensed table-hover" id="tblUser">
	  <thead>
	    <tr>
	      <th class="hidden">User ID</th>
	      <th>User Code</th>
	      <th>Name</th>
	      <th>Department</th>
	      <th class="hidden">Department Code</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT T0.USERID,T0.USER_CODE,T0.U_NAME,T0.Department,T1.Name 
	  																		FROM OUSR T0
																			LEFT JOIN OUDP T1 ON
																			T0.Department = T1.Code");
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch">
						<td class="item-1 hidden">'.odbc_result($qry, 'USERID').'</td>
						<td class="item-2">'.odbc_result($qry, 'USER_CODE').'</td>
						<td class="item-3">'.odbc_result($qry, 'U_NAME').'</td>
						<td class="item-4">'.odbc_result($qry, 'Name').'</td>
						<td class="item-5 hidden">'.odbc_result($qry, 'Department').'</td>
				      </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
