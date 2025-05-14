<?php 
include_once('../../config/config.php'); 

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$txtDateFromGRPO = $_POST['txtDateFromGRPO'];
$txtDateToGRPO = $_POST['txtDateToGRPO'];
$txtRRListFrom = $_POST['txtRRListFrom'];
$txtRRListTo = $_POST['txtRRListTo'];

if($txtRRListFrom != '' && $txtRRListTo != '')
{
	$APVRange = " AND T0.NumAtCard BETWEEN '$txtRRListFrom' AND '$txtRRListTo'";
	$APVDateRange = "";
	$HeaderTitle = $txtRRListFrom . ' to ' . $txtRRListTo;
}
else
{
	if($txtDateFromGRPO != '' && $txtDateToGRPO != '')
	{
		$APVDateRange = " AND T0.DocDate BETWEEN '$txtDateFromGRPO' AND '$txtDateToGRPO'";
		
		$HeaderTitle = date('m/d/Y' ,strtotime($txtDateFromGRPO)) . ' to ' . date('m/d/Y' ,strtotime($txtDateToGRPO));
	}
	else
	{
		$APVDateRange = "";
		$HeaderTitle = "";
	}
	
	$APVRange = "";
}

?>

	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblViewGRPO">
	  <thead>
	    <tr>
			<th class='hidden'>DocEntry</th>
			<th>R.R. Date</th>
			<th>Vendor Name</th>
			<th>R.R. Ref.<br>No.</th>
			<th>SI/DR Ref.<br>No.</th>
			<th>R.R. Total</th>
			<th>R.R. Status</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, " USE [".$_SESSION['mssqldb']."];
						SELECT T0.DocEntry,
								T0.DocDate,
								T0.CardName,
								T0.NumAtCard,
								CASE WHEN T0.U_SiDr IS NULL THEN T0.Comments ELSE T0.U_SiDr END AS CustInvNo,
								T0.DocTotal,
								CASE WHEN T0.DocStatus = 'O' THEN 'Open' ELSE
									CASE WHEN T0.CANCELED = 'Y' THEN 'Canceled' ELSE 'Closed' END
								END AS DocStatus
						FROM OPDN T0
						WHERE T0.DocEntry != '' 
						".$APVDateRange."
						".$APVRange."
						ORDER BY T0.DocDate ASC, T0.NumAtCard ASC");
						
						while (odbc_fetch_row($qry)) 
						{
							echo '<tr>
										<td class="item-0 hidden">'.odbc_result($qry, 'DocEntry').'</td>
										<td>'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
										<td>'.utf8_encode(odbc_result($qry, 'CardName')).'</td>
										<td>'.odbc_result($qry, 'NumAtCard').'</td>
										<td>'.odbc_result($qry, 'CustInvNo').'</td>
										<td align="right">'.number_format(odbc_result($qry, 'DocTotal'),2).'</td>
										<td><center>'.odbc_result($qry, 'DocStatus').'</center></td>
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
		$('#tblViewGRPO').dataTable();
		$('div.dataTables_filter input').focus();
    });
    </script>
	
