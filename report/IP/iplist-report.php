<?php
session_start();
include_once('../../config/config.php');

require_once __DIR__ . '/../../mpdf/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../mpdf/custom/temp/dir/path']);

date_default_timezone_set('Asia/Manila');

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$txtDateFrom = $_GET['txtDateFrom'];
$txtDateTo = $_GET['txtDateTo'];
$txtReceiptListFrom = $_GET['txtReceiptListFrom'];
$txtReceiptListTo = $_GET['txtReceiptListTo'];

if($txtReceiptListFrom != '' && $txtReceiptListTo != '')
{
	$APVRange = " AND T0.CounterRef BETWEEN '$txtReceiptListFrom' AND '$txtReceiptListTo'";
	$APVDateRange = "";
	$HeaderTitle = $txtReceiptListFrom . ' to ' . $txtReceiptListTo;
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

$date = date('M d, Y h:i a');
$htmlheader = '';
$htmldetails = '';
$htmlremarks = '';
$html = '';
$remarks = '';
$no = 1;
$htmlfooter = '';

$cmpDetails = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT UPPER(T0.CompnyName) as CompnyName, 
																			T0.CompnyAddr, 
																			T1.Street, 
																			T1.City
																	FROM OADM T0, ADM1 T1");
odbc_fetch_row($cmpDetails);

$qry = odbc_exec($MSSQL_CONN, " 
		SELECT  T0.DocEntry, 
						T0.DocNum, 
						T0.DocDate, 
						T0.CardCode, 
						T0.CardName, 
						T0.CounterRef, 
						T0.DocTotal, 
						T4.AcctCode AS CashAccount,
						T5.AcctCode AS TransferAccount,
						T6.AcctCode AS CheckAccount,
						T7.AcctCode AS CreditCardAccount,
						T0.JrnlMemo,
						T1.CheckNum,
						CASE WHEN T0.CANCELED = 'Y' 
							THEN 'Cancelled'
						ELSE 'Closed' 
						END AS DocStatus
			FROM ORCT T0
			LEFT JOIN OACT T4 ON T0.CashAcct =T4.AcctCode AND T4.Finanse = 'Y'
			LEFT JOIN OACT T5 ON T0.TrsfrAcct =T5.AcctCode AND T5.Finanse = 'Y'
			OUTER APPLY
			(
				SELECT TOP 1 * 
				FROM RCT1 T1
				WHERE T1.DocNum = T0.DocEntry
			) T1
			OUTER APPLY
			(
					SELECT TOP 1 * 
					FROM RCT3 T2
				WHERE T2.DocNum = T0.DocEntry
			) T2
			LEFT JOIN OACT T6 ON T1.CheckAct =T6.AcctCode AND T6.Finanse = 'Y'
			LEFT JOIN OACT T7 ON T2.CreditAcct =T7.AcctCode AND T7.Finanse = 'Y'
		WHERE T0.DocEntry != '' 
		".$APVDateRange."
		".$APVRange."
		ORDER BY T0.CounterRef ASC");
		
while (odbc_fetch_row($qry)) 
{
	$htmldetails .= '<tr>
										<td width="10%" style="padding: 1px; ">'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
										<td width="10%" style="padding: 1px; ">'.utf8_encode(odbc_result($qry, 'CardCode')).'</td>
										<td width="25%" style="padding: 1px; ">'.utf8_encode(odbc_result($qry, 'CardName')).'</td>
										<td width="10%" style="padding: 1px; " align="center">'.odbc_result($qry, 'CheckNum').'</td>
										<td width="10%" style="padding: 1px; " align="center">'.odbc_result($qry, 'CounterRef').'</td>
										<td width="10%" style="padding: 1px; " align="right">'.number_format(odbc_result($qry, 'DocTotal'),2).'</td>
										<td width="10%" style="padding: 1px; " align="center">'.odbc_result($qry, 'CheckAccount') . '' . odbc_result($qry, 'CashAccount') . '' . odbc_result($qry, 'TransferAccount') . '' . odbc_result($qry, 'CreditCardAccount') .'</td>
										<td width="10%" style="padding: 1px; " align="center">'.odbc_result($qry, 'DocStatus').'</td>
									</tr>';
									

	$POTotal += odbc_result($qry, 'DocTotal');								
}

$htmlheader .= '<table width="100%" border="0">
					<thead>
						<tr>
							<td  width="100%" align="center" valign="center"><span style="font-size: 15pt; color:black;"><b>'.odbc_result($cmpDetails, 'CompnyName').'</b></span><br></td>
						</tr>
						<tr>
							<td  width="100%" align="center" valign="center"><span style="font-size: 12pt; color:black;"><b>Receipt List</b></span><br></td>
						</tr>
						<tr>
							<td  width="100%" align="center" valign="center"><span style="font-size: 8pt; color:black;"><b>'.$HeaderTitle.'</b></span><br></td>
						</tr>
					</thead>
				</table>';
$html .= '
		<div class="row">
            <div class="col-lg-12">
				<table width="100%" border="0">
					<thead>
						<tr>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="left">Date</th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="left">Customer Code</th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="left">Customer Name</th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>Check No.</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>Ref. No.</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>Amount</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>Account</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>Status</center></th>
					</tr>
					</thead>
					<tbody >
						'.$htmldetails.'
						<tr>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>&nbsp;</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>&nbsp;</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center></center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center></center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="right">TOTAL</th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="right">'.number_format($POTotal,2).'</th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="right"></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="right"></th>
						</tr>
					</tbody>
				</table>
				<br>
				
			</div>
          </div>
        ';

$mpdf->SetWatermarkText('');
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->showWatermarkText = true;
$mpdf->SetHTMLHeader($htmlheader);
$mpdf->AddPageByArray([
	'margin-top' => 30,
    'margin-bottom' => 10,
	'margin-left' => 5,
	'margin-right' => 5,
]);
$mpdf->SetHTMLFooter('<table width="100%" border="0">
													<tbody>	
														<tr>
															<td align="left">Print Date : <b>'.$date.'</td>
															<td align="right">Page <b>{PAGENO} </b> of {nbpg}</td>
														</tr>
													</tbody>
													</table>');


$stylesheet = file_get_contents('../../mpdf/mpdf_css/rpt_1-report.css');
$mpdf->WriteHTML($stylesheet,1);

$mpdf->WriteHTML(utf8_encode($html));

$mpdf->Output();
exit;

?>