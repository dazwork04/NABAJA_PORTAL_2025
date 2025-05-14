<?php include_once('../../../config/config.php'); 


?>

<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblDocument">
        <thead>
            <tr>
                <th class="hidden">DocEntry</th>
             
                <th>Date</th>
                <th>Customer/Vendor ID</th>
                <th>Customer/Vendor Name</th>
                <th>Ref No.</th>
                <th>Total</th>
                <th>Cash Account</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
			$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']. "]; SELECT TOP 1000 T0.DocEntry, 
																																															T0.DocNum, 
																																															T0.DocDate, 
																																															T0.CardCode, 
																																															T0.CardName, 
																																															T0.CounterRef, 
																																															T0.DocTotal, 
																																															T4.AcctCode AS CashAccount,
																																															T5.AcctCode AS TransferAccount,
																																															T6.AcctCode AS CheckAccount,
																																															T7.AcctCode AS CreditCardAccount,
																																															T0.JrnlMemo
																																															
																																												FROM OVPM T0
																																												LEFT JOIN OACT T4 ON T0.CashAcct =T4.AcctCode AND T4.Finanse = 'Y'
																																												LEFT JOIN OACT T5 ON T0.TrsfrAcct =T5.AcctCode AND T5.Finanse = 'Y'
																																												OUTER APPLY
																																												(
																																														SELECT TOP 1 * 
																																														FROM VPM1 T1
																																														WHERE T1.DocNum = T0.DocEntry
																																													) T1
																																												OUTER APPLY
																																												(
																																														SELECT TOP 1 * 
																																														FROM VPM3 T2
																																													WHERE T2.DocNum = T0.DocEntry
																																												) T2
																																												LEFT JOIN OACT T6 ON T1.CheckAct =T6.AcctCode AND T6.Finanse = 'Y'
																																												LEFT JOIN OACT T7 ON T2.CreditAcct =T7.AcctCode AND T7.Finanse = 'Y'
																																												ORDER BY CounterRef DESC");
			while (odbc_fetch_row($qry)) 
			{
				if(odbc_result($qry, 'JrnlMemo') == 'Canceled')
				{
					$Status = 'Canceled';
				}
				else
				{
					$Status = '';
				}
				echo '<tr class="srch">
						<td class="item-1 hidden">' . odbc_result($qry, 'DocEntry') . '</td>

						<td class="item-3">' . date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))) . '</td>
						<td class="item-4">' . odbc_result($qry, 'CardCode') . '</td>
						<td class="item-4">' . odbc_result($qry, 'CardName') . '</td>
						<td class="item-5">' . odbc_result($qry, 'CounterRef') . '</td>
						<td class="item-5" align="right">' . number_format(odbc_result($qry, 'DocTotal'),2) . '</td>
						<td class="item-5">' . odbc_result($qry, 'CheckAccount') . '' . odbc_result($qry, 'CashAccount') . '' . odbc_result($qry, 'TransferAccount') . '' . odbc_result($qry, 'CreditCardAccount') . '</td>
						<td class="item-5">' . $Status . '</td>
					 </tr>';
            }
            odbc_free_result($qry);
            odbc_close($MSSQL_CONN);
            ?>
        </tbody>
    </table>
</div>
