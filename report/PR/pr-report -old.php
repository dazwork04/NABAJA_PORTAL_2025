<?php
session_start();
include_once('../../config/config.php');

require_once __DIR__ . '/../../mpdf/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../mpdf/custom/temp/dir/path']);

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$docentry = $_GET['docentry'];
$CompanyDb = $_GET['CompanyDb'];

$date = date('m-d-Y');
$htmlheader = '';
$htmldetails = '';
$htmlremarks = '';
$remarks = '';
$no = 1;

$cmpDetails = odbc_exec($MSSQL_CONN, "USE [".$CompanyDb."]; SELECT UPPER(REPLACE(CompnyName,'LIVE - ','')) as CompnyName,CompnyAddr FROM OADM");
odbc_fetch_row($cmpDetails);

$qry = odbc_exec($MSSQL_CONN, " USE [".$CompanyDb."];
		SELECT	T0.DocEntry,
				UPPER(T0.ReqName) as Requester,
				CONVERT(VARCHAR, T0.DocDate, 107) as DocDate,
				CONVERT(VARCHAR, T0.ReqDate, 107) as ReqDate,
				T0.Comments, 
				FORMAT(T1.Quantity, 'N0') as Quantity,
				T1.Price,
				T0.DocTotal,
				T1.Dscription,
				T1.LineTotal,
				UPPER(CONCAT(T2.firstName, ' ', T2.lastName)) AS RequesterName,
				T1.Text
				
		FROM [OPRQ] T0
		INNER JOIN [PRQ1] T1 ON T0.DocEntry = T1.DocEntry
		LEFT JOIN [OHEM] T2 ON T0.Requester = T2.empID
				
		WHERE T0.DocEntry = '$docentry' ");
		 
while (odbc_fetch_row($qry)) 
{
	$Comments = odbc_result($qry, 'Comments');
	$DocDate = odbc_result($qry, 'DocDate');
	$ReqDate = odbc_result($qry, 'ReqDate');
	$DocTotal = odbc_result($qry, 'DocTotal');
	
	$RequesterName = odbc_result($qry, 'RequesterName');
	
	$htmldetails .= '<tr>
                        <td style="padding: 2px;"><center>'.$no.'</center></td>
                        <td style="padding: 2px;">'.odbc_result($qry, 'Dscription').'</td>
                        <td style="padding: 2px;"><center>'.number_format(odbc_result($qry, 'Price'),2).'</center></td>
                        <td style="padding: 2px;"><center>'.number_format(odbc_result($qry, 'Quantity'),2).'</center></td>
                        <td style="padding: 2px;" align="right">'.number_format(odbc_result($qry, 'LineTotal'),2).'</td>
                        <td style="padding: 2px;" align="center">'.odbc_result($qry, 'Text').'</td>
                     </tr>';
	//$DocTotal += odbc_result($qry, 'Amount'); */
	$no++;				  
}

if($CompanyDb == 'GSDC_LIVE1')
{
	$header = '<tr>
					<td  width="85%" align="left" align="top"><img src="../../img/357LogoPrint.jpg" width="250" height="30"><br>
						<span style="font-size: 8pt">Rizal St. Brgy. Washington, Surigao City, Philippines</span><br>
						<span style="font-size: 8pt">(086) 310-0305 '.htmlentities('•').' 0917-688028 '.htmlentities('•').' Email: traders@357group.com.ph<br>
						<span style="font-size: 8pt"><b>PARRUCHO, GEJA LILIA KANG</b> - Proprietor '.htmlentities('•').' VAT Reg. TIN 924-294-329-000</td>
				</tr>
				';
}
else
{
	$header = '<tr>
					<td  width="15%"><img src="../../img/HELogoPrint.jpg" width="100" height="80"></td>
					<td  width="85%" align="left" valign="top"><span style="font-size: 16pt"><b>'.odbc_result($cmpDetails, 'CompnyName').'</b></span><br>
						<span style="font-size: 8pt">Borromeo cor. Burgos St. Brgy. Washington, Surigao City, Philippines</span><br>
						<span style="font-size: 8pt">(086) 826-4101 '.htmlentities('•').' (086) 232-6211 '.htmlentities('•').' 0998-5445299<br>
						<span style="font-size: 8pt">(Email) enterprises@hiram.com.ph '.htmlentities('•').' (Website) https://www.hiram.com.ph<br>
						<span style="font-size: 8pt"><b>'.htmlentities('CAÑEDO, FRANCO BORJA').'</b> - Proprietor<br>
						<span style="font-size: 8pt">VAT Reg. TIN 400-276-352-000</td>
				</tr>';
}

$html .= '
		<div class="row">
            <div class="col-lg-12">
				<table width="100%" border="0">
					<thead>
						'.$header.'
						<tr>
							<td colspan="4"><center>&nbsp;</center></td>
						</tr>
						<tr>
							<td colspan="4"><center><h3>PURCHASE REQUISITION FORM</h3></center></td>
						</tr>
					</thead>
				</table>
				<br>
				<table width="100%" border="0" style="font-size:8pt;">
					<tbody>
						<tr>
							<td width="12%">&nbsp;</td>
							<td width="50%">&nbsp;</td>
							<td width="12%">&nbsp;</td>
							<td width="26%">&nbsp;</td>
						</tr>
						<tr>
							<td width="12%">Date Needed</td>
							<td width="50%">: <b>'.$ReqDate.'</b></td>
							<td width="12%">Date Prepared</td>
							<td width="26%">: <b>'.$DocDate.'</b></td>
						</tr>
						<tr>
							<td width="12%">&nbsp;</td>
							<td width="50%">&nbsp;</td>
							<td width="12%">&nbsp;</td>
							<td width="26%">&nbsp;</td>
						</tr>
					</tbody>
				</table>
				<br>
				<table width="100%" border="1" style="font-size:8pt;">
					<thead>
						<tr bgcolor="lightgray">
							<th><center>#</center></th>
							<th><center>DESCRIPTION / <br> PARTICULARS</center></th>
							<th><center>UNIT PRICE</center></th>
							<th><center>QUANTITY</center></th>
							<th><center>TOTAL PRICE</center></th>
							<th><center>REMARKS</center></th>
						</tr>
					</thead>
					<tbody>
						'.$htmldetails.'
						
						<tr>
							<td colspan="6"><center>*** nothing follows ***</center><br>&nbsp;</td>
						</tr>
						
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3" bgcolor="lightgray">&nbsp;</td>
							<th bgcolor="lightgray">TOTAL</th>
							<th bgcolor="lightgray" style="padding: 2px;" align="right">PHP '.number_format($DocTotal,2).'</th>
							<td bgcolor="lightgray">&nbsp;</td>
						</tr>
					</tfoot>
				</table>
				<br>
				<table width="100%" border="0" style="font-size:8pt;">
					<tbody>
						<tr>
							<td>Remarks : '.$Comments.'</td>
						</tr>
					</tbody>
				</table>
				<br>
				<table width="100%" border="0" style="font-size:8pt;">
					<tbody>
						<tr>
							<td>PREPARED BY :</td>
							<td><center>&nbsp;</center></td>
							<td><center>&nbsp;</center></td>
							<td><center>&nbsp;</center></td>
							<td>APPROVED BY :</td>
						</tr>
						<tr>
							<td><center>&nbsp;</center></td>
							<td><center>&nbsp;</center></td>
							<td><center>&nbsp;</center></td>
							<td><center>&nbsp;</center></td>
							<td><center>&nbsp;</center></td>
						</tr>
						<tr>
							<td><center>&nbsp;</center></td>
							<td><center>&nbsp;</center></td>
							<td><center>&nbsp;</center></td>
							<td><center>&nbsp;</center></td>
							<td><center>&nbsp;</center></td>
						</tr>
						<tr>
							<td><b>'.strtoupper($PreparedBy).'</b></td>
							<td><center><b>&nbsp;</b></center></td>
							<td><center><b>&nbsp;</b></center></td>
							<td><center><b>&nbsp;</b></center></td>
							<td><center><b>&nbsp;</b></center></td>
						</tr>
						<tr>
							<td><b>&nbsp;</b></td>
							<td><center><b>&nbsp;</b></center></td>
							<td><center><b>&nbsp;</b></center></td>
							<td><center><b>&nbsp;</b></center></td>
							<td><center><b>&nbsp;</b></center></td>
							
						</tr>
					</tbody>
				</table>
			</div>
          </div>
        ';

$mpdf->SetWatermarkText('');
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->showWatermarkText = true;


$stylesheet = file_get_contents('../../mpdf/mpdf_css/rpt_1-report.css');
$mpdf->WriteHTML($stylesheet,1);

$mpdf->WriteHTML(utf8_encode($html));

$mpdf->Output();
exit;

?>