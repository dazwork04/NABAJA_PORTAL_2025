<?php 
include_once('../../config/config.php'); 

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$txtDateFrom = $_POST['txtDateFrom'];
$txtDateTo = $_POST['txtDateTo'];
$txtAPVListFrom = $_POST['txtAPVListFrom'];
$txtAPVListTo = $_POST['txtAPVListTo'];

if($txtAPVListFrom != '' && $txtAPVListTo != '')
{
	$APVRange = " AND T0.NumAtCard BETWEEN '$txtAPVListFrom' AND '$txtAPVListTo'";
	$APVDateRange = "";
	$HeaderTitle = $txtAPVListFrom . ' to ' . $txtAPVListTo;
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
			<th>R.R. Date</th>
			<th>Vendor Name</th>
			<th>R.R. Ref.<br>No.</th>
			<!-- <th>SI/DR Ref.<br>No.</th> -->
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
								T0.Comments AS CustInvNo,
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
										
										<td align="right">'.number_format(odbc_result($qry, 'DocTotal'),2).'</td>
										<td>'.odbc_result($qry, 'DocStatus').'</td>
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
	
