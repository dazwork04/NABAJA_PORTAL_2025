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
	$APVRange = " AND T0.Ref2 BETWEEN '$txtRefListFrom' AND '$txtRefListTo'";
	$APVDateRange = "";
	$HeaderTitle = $txtRefListFrom . ' to ' . $txtRefListTo;
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
			<th>I.T. Doc No.</th>
			<th>Date</th>
			<th>From Whse</th>
			<th>To Whse</th>
			<th>Remarks</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  		$qry = odbc_exec($MSSQL_CONN, " USE [".$_SESSION['mssqldb']."]; SELECT
																																		T0.DocEntry,
																																		T0.DocNum,
																																		T0.Comments,
																																		T0.DocDate,
																																		T0.Filler,
																																		T0.ToWhsCode,
																																		T0.DocStatus,
																																		T1.WhsName,
																																		T2.WhsName AS FromWhsname
																																		FROM OWTR T0
																																	LEFT JOIN OWHS T1 ON T0.ToWhsCode = T1.WhsCode
																																	LEFT JOIN OWHS T2 ON T0.Filler = T2.WhsCode
																																	WHERE T0.DocEntry != ''
																																	".$APVDateRange."
																																	".$APVRange."
																																	ORDER BY T0.DocEntry DESC");
						while (odbc_fetch_row($qry)) 
						{
							echo '<tr>
										<td class="item-0 hidden">'.odbc_result($qry, 'DocEntry').'</td>
										<td align="center">'.odbc_result($qry, 'DocNum').'</td>
										<td>'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
										<td>'.utf8_encode(odbc_result($qry, 'FromWhsname')).'</td>
										<td>'.utf8_encode(odbc_result($qry, 'WhsName')).'</td>
										<td>'.utf8_encode(odbc_result($qry, 'Comments')).'</td>
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
	
