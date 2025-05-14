<?php 
include_once('../../config/config.php'); 

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$txtDateFrom = $_POST['txtDateFrom'];
$txtDateTo = $_POST['txtDateTo'];
$txtAPDPListFrom = $_POST['txtAPDPListFrom'];
$txtAPDPListTo = $_POST['txtAPDPListTo'];

if($txtAPDPListFrom != '' && $txtAPDPListTo != '')
{
	$APDPRange = " AND T0.NumAtCard BETWEEN '$txtAPDPListFrom' AND '$txtAPDPListTo'";
	$APDPDateRange = "";
	$HeaderTitle = $txtAPDPListFrom . ' to ' . $txtAPDPListTo;
}
else
{
	if($txtDateFrom != '' && $txtDateTo != '')
	{
		$APDPDateRange = " AND T0.DocDate BETWEEN '$txtDateFrom' AND '$txtDateTo'";
		
		$HeaderTitle = date('m/d/Y' ,strtotime($txtDateFrom)) . ' to ' . date('m/d/Y' ,strtotime($txtDateTo));
	}
	else
	{
		$APDPDateRange = "";
		$HeaderTitle = "";
	}
	
	$APDPRange = "";
}

?>

	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblView">
	  <thead>
	    <tr>
			<th class='hidden'>DocEntry</th>
			<th>A.P.D.P Date</th>
			<th>Vendor Code</th>
			<th>Vendor Name</th>
			<th>A.P.D.P Ref.<br>No.</th>
			<th>Cust. Inv. No.</th>
			<th>Net Due</th>
			<th>A.P.D.P Total</th>
			<th>A.P.D.P Status</th>
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
										CASE WHEN T0.U_SiDr IS NULL THEN T0.Comments ELSE T0.U_SiDr
										END AS CustInvNo,
										T0.DocTotal - T0.PaidToDate AS NetDue,
										CASE WHEN T0.PaidToDate = 0 THEN 'Unpaid' 
										WHEN T0.PaidToDate = T0.DocTotal THEN 'Paid'
										ELSE 'Partial' END AS DocStatus
						FROM ODPO T0
						WHERE T0.DocEntry != ''
						".$APDPDateRange."
						".$APDPRange."
						
						ORDER BY T0.NumAtCard ASC");
						
						while (odbc_fetch_row($qry)) 
						{
							echo '<tr>
										<td class="item-0 hidden">'.odbc_result($qry, 'DocEntry').'</td>
										<td>'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
										<td>'.utf8_encode(odbc_result($qry, 'CardCode')).'</td>
										<td>'.utf8_encode(odbc_result($qry, 'CardName')).'</td>
										<td align="center">'.odbc_result($qry, 'NumAtCard').'</td>
										<td align="center">'.odbc_result($qry, 'CustInvNo').'</td>
										<td align="right">'.number_format(odbc_result($qry, 'NetDue'),2).'</td>
										<td align="right">'.number_format(odbc_result($qry, 'DocTotal'),2).'</td>
										<td align="center">'.odbc_result($qry, 'DocStatus').'</td>
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
    });
    </script>
	
