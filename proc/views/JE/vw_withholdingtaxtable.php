<?php 
include_once('../../../config/config.php');
$BPCode = $_GET['BPCode'];
$amount = $_GET['amount'];
$debitcredit = $_GET['debitcredit'];
$TotalWtax  = 0;
?>

<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblWTaxCode">
	  <thead>
	    <tr>
			<th>WTax Code</th>
			<th>WTax Name</th>
			<th>Rate</th>
			<th>Amount</th>
			<th class="hidden">Category</th>
			<th class="hidden">Account</th>
			<th class="hidden">AcctName</th>
			<th class="hidden">TotalWtax</th>
			<th class="hidden">debitcredit</th>
		</tr>
	  </thead>
	  <tbody>
	  	<?php
			
			$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT T0.CardCode, T0.WTCode, T1.WTName, T1.Rate, T1.Category, T1.Account, T2.AcctName
																	FROM CRD4 T0 
																	INNER JOIN OWHT T1 ON T0.WTCode = T1.WTCode
																	LEFT JOIN OACT T2 ON T1.Account = T2.AcctCode
																	WHERE T0.CardCode = '$BPCode' AND T1.Inactive = 'N'");
			while (odbc_fetch_row($qry)) 
			{
				$TotalWtax = $amount * (odbc_result($qry, 'Rate')/100);
				echo '<tr class="srch" pageloadid="'.odbc_result($qry, 'WTCode').'">
						<td class="item-1">'.odbc_result($qry, 'WTCode').'</td>
						<td class="item-2">'.utf8_encode(odbc_result($qry, 'WTName')).'</td>
						<td class="item-3">'.number_format(odbc_result($qry, 'Rate'),2,'.',',').'</td>
						<td class="item-4">'.number_format($amount,2).'</td>
						<td class="item-5 hidden">'.odbc_result($qry, 'Category').'</td>
						<td class="item-6 hidden">'.odbc_result($qry, 'Account').'</td>
						<td class="item-7 hidden">'.odbc_result($qry, 'AcctName').'</td>
						<td class="item-8 hidden">'.number_format($TotalWtax,2).'</td>
						<td class="item-9 hidden">'.$debitcredit.'</td>
					  </tr>';
			}
			odbc_free_result($qry);
			odbc_close($MSSQL_CONN);
	  	?>
	  </tbody>
    </table>
</div>
