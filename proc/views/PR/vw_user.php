<?php include_once('../../../config/config.php');?>

<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblRequester">
	  <thead>
	    <tr>
	      <th>User Code</th>
	      <th>User Name</th>
	      <th class="hidden">User ID</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 50 USERID, USER_CODE, U_NAME, Branch, Department FROM OUSR ORDER BY USERID");
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch">
						<td class="item-1">'.odbc_result($qry, 2).'</td>
						<td class="item-2">'.odbc_result($qry, 3).'</td>
						<td class="hidden item-3">'.odbc_result($qry, 1).'</td>
						<td class="hidden item-10">'.odbc_result($qry, 4).'</td>
						<td class="hidden item-11">'.odbc_result($qry, 5).'</td>
				      </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
