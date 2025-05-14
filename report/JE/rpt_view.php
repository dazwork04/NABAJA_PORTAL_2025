<?php 
include_once('../../config/config.php'); 

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$txtDateFrom = $_POST['txtDateFrom'];
$txtDateTo = $_POST['txtDateTo'];
$txtRefListFrom = $_POST['txtRefListFrom'];
$txtRefListTo = $_POST['txtRefListTo'];

if($txtRefListFrom != '' && $txtRefListTo != '')
{
	$APVRange = " AND T0.Ref1 BETWEEN '$txtRefListFrom' AND '$txtRefListTo'";
	$APVDateRange = "";
	$HeaderTitle = $txtRefListFrom . ' to ' . $txtRefListTo;
}
else
{
	if($txtDateFrom != '' && $txtDateTo != '')
	{
		$APVDateRange = " AND T0.RefDate BETWEEN '$txtDateFrom' AND '$txtDateTo'";
		
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
			<th>Ref. No.</th>
			<th>Date</th>
			<th>Debit</th>
			<th>Credit</th>
			<th>Remarks</th>
			<th class='hidden'>DocEntry</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, " USE [".$_SESSION['mssqldb']."]; SELECT T0.TransId, 
																					T0.RefDate, 
																					T0.Ref1, 
																					T0.Memo,
																					T0.LocTotal
																			FROM OJDT T0 
																			WHERE T0.TransType = 30
																			".$APVDateRange."
																			".$APVRange."
																			ORDER BY T0.Ref1 DESC");
						while (odbc_fetch_row($qry)) 
						{
							echo '<tr>
										
										<td>'.utf8_encode(odbc_result($qry, 'Ref1')).'</td>
										<td>'.date('m/d/Y' ,strtotime(odbc_result($qry, 'RefDate'))).'</td>
										<td align="right">'.number_format(odbc_result($qry, 'LocTotal'),2).'</td>
										<td align="right">'.number_format(odbc_result($qry, 'LocTotal'),2).'</td>
										<td>'.utf8_encode(odbc_result($qry, 'Memo')).'</td>
										<td class="item-0 hidden">'.odbc_result($qry, 'TransId').'</td>
										
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
	
