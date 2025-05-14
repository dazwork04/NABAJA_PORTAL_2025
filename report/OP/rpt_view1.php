<?php 
include_once('../../config/config.php'); 

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$txtDateFrom = $_POST['txtDateFrom'];
$txtDateTo = $_POST['txtDateTo'];
$txtDisbursementListFrom = $_POST['txtDisbursementListFrom'];
$txtDisbursementListTo = $_POST['txtDisbursementListTo'];

if($txtDisbursementListFrom != '' && $txtDisbursementListTo != '')
{
	$APVRange = " AND T0.CounterRef BETWEEN '$txtDisbursementListFrom' AND '$txtDisbursementListTo'";
	$APVDateRange = "";
	$HeaderTitle = $txtDisbursementListFrom . ' to ' . $txtDisbursementListTo;
}
else
{
	if($txtDateFrom != '' && $txtDateTo != '')
	{
		$APVDateRange = " AND T0.DocDate BETWEEN '$txtDateFrom' AND '$txtDateTo'";
		
		$HeaderTitle = date('m/d/Y' ,strtotime($txtDateFrom)) . ' to ' . date('m/d/Y' ,strtotime($txtDateTo));
	}
	else
	{
		$APVDateRange = "";
		$HeaderTitle = "";
	}
	
	$APVRange = "";
}

?>

	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblView">
	  <thead>
	    <tr>
			<th class="hidden">DocEntry</th>
			<th align="left">Date</th>
			<th align="left">Vendor Code</th>
			<th align="left">Vendor Name</th>
			<th><center>Check No.</center></th>
			<th><center>Ref. No.</center></th>
			<th><center>Amount</center></th>
			<th><center>Account</center></th>
		</tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, " USE [".$_SESSION['mssqldb']."];
						SELECT  T0.DocEntry, 
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
										T0.JrnlMemo,
										T1.CheckNum
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
						WHERE T0.DocEntry != '' 
						".$APVDateRange."
						".$APVRange."
						ORDER BY T0.CounterRef ASC");
						
						while (odbc_fetch_row($qry)) 
						{
							echo '<tr>
										<td class="item-0 hidden">'.odbc_result($qry, 'DocEntry').'</td>
										<td>'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
										<td>'.utf8_encode(odbc_result($qry, 'CardCode')).'</td>
										<td>'.utf8_encode(odbc_result($qry, 'CardName')).'</td>
										<td align="center">'.odbc_result($qry, 'CheckNum').'</td>
										<td align="center">'.odbc_result($qry, 'CounterRef').'</td>
										<td align="right">'.number_format(odbc_result($qry, 'DocTotal'),2).'</td>
										<td align="center">'.odbc_result($qry, 'CheckAccount') . '' . odbc_result($qry, 'CashAccount') . '' . odbc_result($qry, 'TransferAccount') . '' . odbc_result($qry, 'CreditCardAccount') .'</td>
									</tr>';
						}
			
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
	
    <script>
	$(document).ready(function() 
	{
		
		$('#tblView').dataTable();
		$('div.dataTables_filter input').focus();
        /* $(tblView).DataTable({
            responsive: true
        }); */
    });
    </script>
	
