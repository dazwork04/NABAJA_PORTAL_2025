<?php include_once('../../../config/config.php'); 
?>
<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblITRDocument">
        <thead>
            <tr>
                <th class="hidden">DocEntry</th>
                <th>ITR Doc No.</th>
				<th>ITR Date</th>
                <th>Remarks</th>
                <th>From Warehouse</th>
                <th class="hidden">BP Name</th>
                <th class="hidden">Document Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "]; 
                        select top 20 T0.DocEntry
                        ,T0.DocNum
                        ,T0.Comments
                        ,T0.DocDate
                        ,T0.Filler
                        ,T1.WhsName
                        ,T0.DocStatus, T0.CardName
                        from OWTQ T0
                        left join OWHS T1 on T0.Filler = T1.WhsCode
                        WHERE T0.DocStatus <> 'C'
                        order by T0.DocEntry
                        ");
            while (odbc_fetch_row($qry)) {
                echo '<tr class="srch">
						<td class="item-1 hidden">' . odbc_result($qry, 'DocEntry') . '</td>
						<td class="item-2">' . odbc_result($qry, 'DocNum') . '</td>
						<td class="item-4">' . date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))) . '</td>
						<td class="item-3">' . odbc_result($qry, 'Comments') . '</td>
						
						<td class="item-5">' . odbc_result($qry, 'WhsName') . '</td>
                        <td class="item-7 hidden">'.odbc_result($qry, 'CardName').'</td>
						<td class="item-6 hidden">' . odbc_result($qry, 'DocStatus') . '</td>
				      </tr>';
            }
            odbc_free_result($qry);
            odbc_close($MSSQL_CONN);
            ?>
        </tbody>
    </table>
</div>
