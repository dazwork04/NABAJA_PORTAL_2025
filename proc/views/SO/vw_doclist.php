<?php include_once('../../../config/config.php');
$empid = $_SESSION['SESS_EMP'];
$name = $_SESSION['SESS_NAME'];
$OwnerCode = '';
$OwnerCode = $_SESSION['SESS_EMP'];


/* if($empid == 0)
{
	$hide = '';
}
else
{
	$hide = 'hidden';
} */
?>

<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblDocument">
	  <thead>
	    <tr>
			<th class="hidden">DocEntry</th>
			<th style="min-width:50px;">SO Doc. No.</th>
			<th style="min-width:50px;">Customer Name</th>
			<th style="min-width:50px;">Remarks</th>
			<th style="min-width:50px;">Doc. Date</th>
			<th style="min-width:50px;">Ref. No.</th>
			<th style="min-width:50px;">Status</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
		
			if($OwnerCode != '') {
	  		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.DocEntry, T0.DocNum, T0.Comments, 
																			T0.DocDate, 
																			T0.NumAtCard,T0.DocStatus, T0.CardName, T0.CANCELED
																			FROM [ORDR] T0
																			ORDER BY T0.DocEntry DESC");
			}
			else{
				$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 T0.DocEntry, T0.DocNum, T0.Comments, 
																			T0.DocDate,  
																			T0.NumAtCard,T0.DocStatus, T0.CardName, T0.CANCELED
																			FROM [ORDR] T0
																			ORDER BY T0.DocEntry DESC");
			}
			while (odbc_fetch_row($qry)) {
				
				if(odbc_result($qry, 'DocStatus') == 'C')
				{
					$DocStatus = 'Closed';
				}
				else
				{
					$DocStatus = 'Open';
				}
				
				if(odbc_result($qry, 'CANCELED') == 'Y')
				{
					$Canceled = 'Canceled';
				}
				else
				{
					$Canceled = $DocStatus;
				}
				
				echo '<tr class="srch">
						<td class="item-1 hidden">'.odbc_result($qry, 'DocEntry').'</td>
						<td class="item-2">'.odbc_result($qry, 'DocNum').'</td>
						<td class="item-7">'.odbc_result($qry, 'CardName').'</td>
						<td class="item-3">'.odbc_result($qry, 'Comments').'</td>
						<td class="item-4">'.date('Y/m/d' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
						<td class="item-5">'.odbc_result($qry, 'NumAtCard').'</td>
						<td class="item-6">'. $Canceled . '</td>
					</tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
