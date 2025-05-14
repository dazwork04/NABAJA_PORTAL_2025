<?php include_once('../../../config/config.php');

?>

<style>
table th {
	position: -webkit-sticky; 
	position: sticky;
	top: 0;
	z-index: 10; 
	background: #fff;
	outline: thin solid lightblue;
}
</style>
<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblAcct">
	  <thead>
	    <tr>
	      <th>Account Name</th>
	      <th>Account Number</th>
	      <th class='hidden'>Account Code</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 AcctName,FormatCode,AcctCode FROM OACT WHERE frozenFor = 'N' ORDER BY AcctName");
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch">
						<td class="item-1">'.utf8_encode(odbc_result($qry, 'AcctName')).'</td>
						<td class="item-2">'.odbc_result($qry, 'FormatCode').'</td>
						<td class="item-3 hidden">'.odbc_result($qry, 'AcctCode').'</td>
				      </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
