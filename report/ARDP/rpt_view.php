<?php 
include_once('../../config/config.php'); 

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$txtDateFrom = $_POST['txtDateFrom'];
$txtDateTo = $_POST['txtDateTo'];
$txtARListFrom = $_POST['txtARListFrom'];
$txtARListTo = $_POST['txtARListTo'];

if($txtARListFrom != '' && $txtARListTo != '')
{
	$APVRange = " AND T0.NumAtCard BETWEEN '$txtARListFrom' AND '$txtARListTo'";
	$APVDateRange = "";
	$HeaderTitle = $txtARListFrom . ' to ' . $txtARListTo;
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
			<th class='hidden'>DocEntry</th>
			<th align="left">A/R DP Date</th>
			<th align="left">Vendor Code</th>
			<th align="left">Vendor Name</th>
			<th><center>A/R DP Ref. No.</center></th>
			<th><center>Remarks</center></th>
			<th><center>Net Due</center></th>
			<th><center>A/R DP Total</center></th>
			<th><center>A/R DP Status</center></th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, " USE [".$_SESSION['mssqldb']."];
						SELECT T0.DocEntry,
									T0.DocDate,
									T0.CardCode,
									T0.CardName,
									T0.NumAtCard,
									T0.DocTotal,
									T0.Comments,
									T0.DocTotal - T0.PaidToDate AS NetDue,
									CASE WHEN T0.PaidToDate = 0 THEN 'Unpaid' 
									WHEN T0.PaidToDate = T0.DocTotal THEN 'Paid'
									ELSE 'Partial' END AS DocStatus,
									CASE WHEN T0.DocStatus = 'O' 
										THEN 'Open' 
									ELSE CASE WHEN T0.CANCELED = 'Y' 
										THEN 'Cancelled'
									ELSE 'Closed' END
									END AS DocStatus1
					FROM ODPI T0
					WHERE T0.DocEntry != '' 
					".$APVDateRange."
					".$APVRange."
					ORDER BY T0.NumAtCard ASC");
						
						while (odbc_fetch_row($qry)) 
						{
							echo '<tr>
										<td class="item-0 hidden">'.odbc_result($qry, 'DocEntry').'</td>
										<td>'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
										<td>'.utf8_encode(odbc_result($qry, 'CardCode')).'</td>
										<td>'.utf8_encode(odbc_result($qry, 'CardName')).'</td>
										<td align="center">'.odbc_result($qry, 'NumAtCard').'</td>
										<td align="center">'.odbc_result($qry, 'Comments').'</td>
										<td align="right">'.number_format(odbc_result($qry, 'NetDue'),2).'</td>
										<td align="right">'.number_format(odbc_result($qry, 'DocTotal'),2).'</td>
										<td align="center">'.odbc_result($qry, 'DocStatus1').'/'.odbc_result($qry, 'DocStatus').'</td>
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
	
