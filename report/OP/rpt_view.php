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
	$APVRange = " AND T2.CounterRef BETWEEN '$txtDisbursementListFrom' AND '$txtDisbursementListTo'";
	$APVDateRange = "";
	$HeaderTitle = $txtDisbursementListFrom . ' to ' . $txtDisbursementListTo;
}
else
{
	if($txtDateFrom != '' && $txtDateTo != '')
	{
		$APVDateRange = " AND T2.DocDate BETWEEN '$txtDateFrom' AND '$txtDateTo'";
		
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
			<th align="left">Vendor Name</th>
			<th><center>Ref. No.</center></th>
			<th><center>Account<br>Description</center></th>
			<th><center>Line<br>Description</center></th>
			<th><center>Debit Amount</center></th>
			<th><center>Credit Amount</center></th>
			<th><center>Job Id</center></th>
			<th><center>Status</center></th>
		</tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, " USE [".$_SESSION['mssqldb']."];
						SELECT T2.DocEntry,
							T2.DocDate, 
							T2.CardName, 
							T2.CounterRef, 
							T0.Account,
							T4.AcctName, 
							T0.Debit, 
							T0.Credit, 
							T3.Descrip, 
							T2.PrjCode, 
							T5.PrjName,
							CASE WHEN T2.CANCELED = 'Y' 
									THEN 'Cancelled'
								ELSE 'Closed' 
								END AS DocStatus
						FROM JDT1 T0
						LEFT JOIN OJDT T1 ON T0.TransId = T1.TransId
						LEFT JOIN OVPM T2 ON T0.TransId = T2.TransId
						LEFT JOIN VPM4 T3 ON T2.DocEntry = T3.DocNum AND T0.Account = T3.AcctCode AND (T0.BalDueDeb = T3.SumApplied OR T0.BalDueCred = T3.SumApplied)
						LEFT JOIN OACT T4 ON T0.Account = T4.AcctCode
						LEFT JOIN OPRJ T5 ON T2.PrjCode = T5.PrjCode
						WHERE (T0.Debit != 0 OR T0.Credit != 0)
						".$APVDateRange."
						".$APVRange."
						ORDER BY T2.CounterRef ASC, ISNULL(T3.LineId, 999) ASC");
						
						while (odbc_fetch_row($qry)) 
						{
							echo '<tr>
										<td class="item-0 hidden">'.odbc_result($qry, 'DocEntry').'</td>
										<td>'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
										<td>'.utf8_encode(odbc_result($qry, 'CardName')).'</td>
										<td align="center">'.odbc_result($qry, 'CounterRef').'</td>
										<td align="left">'.odbc_result($qry, 'AcctName').'</td>
										<td align="left">'.utf8_encode(odbc_result($qry, 'Descrip')).'</td>
										<td align="right">'.number_format(odbc_result($qry, 'Debit'),2).'</td>
										<td align="right">'.number_format(odbc_result($qry, 'Credit'),2).'</td>
										<td align="center">'.odbc_result($qry, 'PrjCode') . '-' . odbc_result($qry, 'PrjName') . '</td>
										<td align="right">'.odbc_result($qry, 'DocStatus').'</td>
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
	
