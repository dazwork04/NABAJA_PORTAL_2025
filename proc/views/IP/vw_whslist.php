<?php include_once('../../../config/config.php');?>

<div class="table-responsive" style="max-height:350px;overflow-y:scroll;">
	<table class="table table-condensed table-hover" id="tblWhs">
	  <thead>
	    <tr>
	      <th>Warehouse Code</th>
	      <th>Warehouse Name</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT WhsCode,WhsName FROM OWHS ORDER BY WhsCode");
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch">
						<td class="item-1">'.odbc_result($qry, 1).'</td>
						<td class="item-2">'.odbc_result($qry, 2).'</td>
				      </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
