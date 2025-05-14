<?php include_once('../../../config/config.php');?>

<div class="table-responsive" style="max-height:350px;overflow-y:scroll;">
	<table class="table table-condensed table-hover" id="tblBarcode">
	  <thead>
	    <tr>
	      <th>Item Code</th>
	      <th>Barcode</th>

	    </tr>
	  </thead>
	  <tbody>
	  	<?php
                        $itemcode = isset($_GET['itemcode']) ? $_GET['itemcode'] : '';
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 100 BcdCode,ItemCode FROM OBCD WHERE ItemCode = '".$itemcode."' ORDER BY BcdCode");
			while (odbc_fetch_row($qry)) {
				echo '<tr class="srch" pageloadid="'.odbc_result($qry, 'BcdCode').'">
						<td class="item-1">'.odbc_result($qry, 'ItemCode').'</td>
						<td class="item-2">'.odbc_result($qry, 'BcdCode').'</td>
				      </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
