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
$txtDisbursementListFrom = $_GET['txtDisbursementListFrom'];
$txtDisbursementListTo = $_GET['txtDisbursementListTo'];

if($txtDisbursementListFrom != '' && $txtDisbursementListTo != '')
{
	$APVRange = " AND T2.CounterRef BETWEEN '$txtDisbursementListFrom' AND '$txtDisbursementListTo'";
	$APVDateRange = "";
	$HeaderTitle = $txtDisbursementListFrom . ' to ' . $txtDisbursementListTo;
}
else
{
	if($txtDateFrom != '' && $txtDateTo != '')
	{
		$APVDateRange = " AND T2.DocDate BETWEEN '$txtDateFrom' AND '$txtDateTo'";
		
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
$Debit = 0;
$Credit = 0;

$cmpDetails = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT UPPER(T0.CompnyName) as CompnyName, 
																			T0.CompnyAddr, 
																			T1.Street, 
																			T1.City
																	FROM OADM T0, ADM1 T1");
odbc_fetch_row($cmpDetails);

$qry = odbc_exec($MSSQL_CONN, " 
						SELECT T2.DocEntry,
							T2.DocDate, 
							T2.CardName, 
							T2.CounterRef, 
							T0.Account,
							T4.AcctName, 
							T0.Debit, 
							T0.Credit, 
							T3.Descrip, 
							T2.PrjCode, 
							T5.PrjName,
							CASE WHEN T2.CANCELED = 'Y' 
									THEN 'Cancelled'
								ELSE 'Closed' 
								END AS DocStatus
						FROM JDT1 T0
						LEFT JOIN OJDT T1 ON T0.TransId = T1.TransId
						LEFT JOIN OVPM T2 ON T0.TransId = T2.TransId
						LEFT JOIN VPM4 T3 ON T2.DocEntry = T3.DocNum AND T0.Account = T3.AcctCode AND (T0.BalDueDeb = T3.SumApplied OR T0.BalDueCred = T3.SumApplied)
						LEFT JOIN OACT T4 ON T0.Account = T4.AcctCode
						LEFT JOIN OPRJ T5 ON T2.PrjCode = T5.PrjCode
						WHERE (T0.Debit != 0 OR T0.Credit != 0)
						".$APVDateRange."
						".$APVRange."
						ORDER BY T2.CounterRef ASC, ISNULL(T3.LineId, 999) ASC");
		
while (odbc_fetch_row($qry)) 
{
	$htmldetails .= '<tr>
					   <td width="10%" style="padding: 1px; ">'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
					   <td width="20%" style="padding: 1px; ">'.utf8_encode(odbc_result($qry, 'CardName')).'</td>
					   <td width="10%" style="padding: 1px; ">'.utf8_encode(odbc_result($qry, 'CounterRef')).'</td>
					   <td width="10%" style="padding: 1px; " align="left">'.odbc_result($qry, 'AcctName').'</td>
					   <td width="10%" style="padding: 1px; " align="center">'.odbc_result($qry, 'Descrip').'</td>
					   <td width="10%" style="padding: 1px; " align="right">'.number_format(odbc_result($qry, 'Debit'),2).'</td>
					   <td width="10%" style="padding: 1px; " align="right">'.number_format(odbc_result($qry, 'Credit'),2).'</td>
					   <td width="10%" style="padding: 1px; " align="center">'.odbc_result($qry, 'PrjCode') . '-' . odbc_result($qry, 'PrjName') . '</td>
					   <td width="10%" style="padding: 1px; " align="center">'.odbc_result($qry, 'DocStatus').'</td>
					</tr>';
					
	$Debit += odbc_result($qry, 'Debit');
	$Credit += odbc_result($qry, 'Credit');
}

$htmlheader .= '<table width="100%" border="0">
					<thead>
						<tr>
							<td  width="100%" align="center" valign="center"><span style="font-size: 15pt; color:black;"><b>'.odbc_result($cmpDetails, 'CompnyName').'</b></span><br></td>
						</tr>
						<tr>
							<td  width="100%" align="center" valign="center"><span style="font-size: 12pt; color:black;"><b>Disbursement List</b></span><br></td>
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
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="left">Vendor Name</th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="left">Ref. No.</th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>Account<br>Description</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>Line<br>Description</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>Debit<br>Amount</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>Credit<br>Amount</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>Job Id</center></th>
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
							<th style="border-top:1px solid black; border-bottom:1px solid black;"></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="right">'.number_format($Debit,2).'</th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="right">'.number_format($Credit,2).'</th>
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