<?php 
include_once('../../../config/config.php');
$vendorcode = $_GET['vendorcode'];
?>

<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblWTaxCode">
	  <thead>
	    <tr>
			<th>WTax Code</th>
			<th>WTax Name</th>
			<th>Rate</th>
			<th class="hidden">Category</th>
		</tr>
	  </thead>
	  <tbody>
	  	<?php
			
			$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT T0.CardCode, T0.WTCode, T1.WTName, T1.Rate, T1.Category
																	FROM CRD4 T0 
																	INNER JOIN OWHT T1 ON T0.WTCode = T1.WTCode
																	WHERE T0.CardCode = '$vendorcode' AND T1.Inactive = 'N'");
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch" pageloadid="'.odbc_result($qry, 'WTCode').'">
						<td class="item-1">'.odbc_result($qry, 'WTCode').'</td>
						<td class="item-2">'.utf8_encode(odbc_result($qry, 'WTName')).'</td>
						<td class="item-3">'.number_format(odbc_result($qry, 'Rate'),2,'.',',').'</td>
						<td class="item-4 hidden">'.odbc_result($qry, 'Category').'</td>
					  </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
