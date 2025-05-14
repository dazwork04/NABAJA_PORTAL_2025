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
	$APVRange = " AND T0.DocEntry BETWEEN '$txtAPVListFrom' AND '$txtAPVListTo'";
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
			<th>P.R. Date</th>
			<th>P.R. Ref.<br>No.</th>
			<th>P.R. Remarks</th>
			<th>P.R. Total</th>
			<th>P.R. Status</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, " USE [".$_SESSION['mssqldb']."];
						SELECT T0.DocEntry,
									T0.DocDate,
									T0.CardName,
									T0.NumAtCard,
									T0.Comments,
									T0.DocTotal,
									CASE WHEN T0.DocStatus = 'O' THEN 'Open' ELSE
										CASE WHEN T0.CANCELED = 'Y' THEN 'Canceled' ELSE 'Closed' END
									END AS DocStatus
							FROM OPRQ T0
							WHERE T0.CANCELED = 'N' 
							".$APVDateRange."
							".$APVRange."
							ORDER BY T0.DocDate ASC, T0.DocEntry ASC");
						
						while (odbc_fetch_row($qry)) 
						{
							echo '<tr>
										<td class="item-0 hidden">'.odbc_result($qry, 'DocEntry').'</td>
										<td>'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
										<td align="center">'.odbc_result($qry, 'DocEntry').'</td>
										<td align="center">'.odbc_result($qry, 'Comments').'</td>
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
        /* $(tblView).DataTable({
            responsive: true
        }); */
    });
    </script>
	
