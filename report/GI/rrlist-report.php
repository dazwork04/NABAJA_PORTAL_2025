
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
$txtAPVListFrom = $_GET['txtAPVListFrom'];
$txtAPVListTo = $_GET['txtAPVListTo'];

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
		SELECT T0.DocDate,
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
	$htmldetails .= '<tr>
									   <td width="15%" style="padding: 1px; ">'.date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))).'</td>
									   <td width="40%" style="padding: 1px; ">'.utf8_encode(odbc_result($qry, 'CardName')).'</td>
									   <td width="10%" style="padding: 1px; " align="center">'.odbc_result($qry, 'NumAtCard').'</td>
									   <td width="10%" style="padding: 1px; " align="center">'.odbc_result($qry, 'CustInvNo').'</td>
									   <td width="10%" style="padding: 1px; " align="right">'.number_format(odbc_result($qry, 'DocTotal'),2).'</td>
									</tr>';
									

	$TotalServed += odbc_result($qry, 'DocTotal');
	
	$POTotal += odbc_result($qry, 'DocTotal');								
}

$htmlheader .= '<table width="100%" border="0">
					<thead>
						<tr>
							<td  width="100%" align="center" valign="center"><span style="font-size: 15pt; color:black;"><b>'.odbc_result($cmpDetails, 'CompnyName').'</b></span><br></td>
						</tr>
						<tr>
							<td  width="100%" align="center" valign="center"><span style="font-size: 12pt; color:black;"><b>Receiving List</b></span><br></td>
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
							<td>Total Served : <b>'.number_format($TotalServed,2).'</b></td>
						</tr>
					</thead>
				</table>
				<table width="100%" border="0">
					<thead>
						<tr>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="left">R.R. Date</th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="left">Vendor Name</th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>R.R. Ref. No.</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>SI/DR Ref. No.</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>R.R. Total</center></th>
							
						</tr>
					</thead>
					<tbody >
						'.$htmldetails.'
						<tr>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>&nbsp;</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center></center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center></center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="right">TOTAL</th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="right">'.number_format($POTotal,2).'</th>
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