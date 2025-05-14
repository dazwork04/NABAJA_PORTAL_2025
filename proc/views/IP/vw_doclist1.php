<?php include_once('../../../config/config.php'); 


?>

<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblDocument">
        <thead>
            <tr>
                <th class="hidden">DocEntry</th>
                <th>Doc No.</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Ref No.</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php
			$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']. "]; SELECT TOP 20 DocEntry, DocDate, CardName, CounterRef, Comments FROM ORCT ORDER BY DocEntry DESC");
			while (odbc_fetch_row($qry)) 
			{
                echo '<tr class="srch">
						<td class="item-1 hidden">' . odbc_result($qry, 'DocEntry') . '</td>
						<td class="item-2">' . odbc_result($qry, 'DocEntry') . '</td>
						<td class="item-3">' . date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))) . '</td>
						<td class="item-4">' . odbc_result($qry, 'CardName') . '</td>
						<td class="item-5">' . odbc_result($qry, 'CounterRef') . '</td>
						<td class="item-6">' . odbc_result($qry, 'Comments') . '</td>
						
					  </tr>';
            }
            odbc_free_result($qry);
            odbc_close($MSSQL_CONN);
            ?>
        </tbody>
    </table>
</div>
