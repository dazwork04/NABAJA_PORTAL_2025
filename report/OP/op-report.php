
<?php
session_start();
include_once('../../config/config.php');

require_once __DIR__ . '/../../mpdf/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../mpdf/custom/temp/dir/path']);
//$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [190, 150]]);

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$docentry = $_GET['docentry'];

$date = date('m/d/Y');
$htmlheader = '';
$htmldetails = '';
$htmlremarks = '';
$html = '';
$remarks = '';
$no = 1;

$qry = odbc_exec($MSSQL_CONN, " USE [".$_SESSION['mssqldb']."]; EXEC [dbo].[usp_CheckVoucher] '".$docentry."'  ");
		 
while (odbc_fetch_row($qry)) 
{
	$DocNum = odbc_result($qry, 'DocNum');
	$CardName = odbc_result($qry, 'CardName');
	$CounterRef = odbc_result($qry, 'CounterRef');
	$CheckNum = odbc_result($qry, 'CheckNum');
	$DocDate = date('M d, Y' ,strtotime(odbc_result($qry, 'DocDate')));
	
		$htmldetails .= '<tr>
											<td width="5%"></td>
											<td width="60%">'.odbc_result($qry, 'NumAtCard').' '.odbc_result($qry, 'APVComments').' </td>
											<td width="20%" align="right"><b>'.number_format(odbc_result($qry, 'PaidSum'),2).'</b></td>
                     </tr>';

	$no++;	
	$TotalPaidSum += odbc_result($qry, 'PaidSum');
}

$html .= '
		<div class="row">
            <div class="col-lg-12">
				<br>
				<br>
				<br>
				<br>
				<table width="100%" border="0">
					<tbody style="padding:5px!important">
						<tr>
							<td width="5%"></td>
							<td width="60%"></td>
							<td width="20%" align="right"><b>'.$CheckNum.'</b></td>
						</tr>
						<tr>
							<td width="5%"></td>
							<td width="60%"><b>'.$CardName.'</b></td>
							<td width="20%" align="right"><b>'.$DocDate.'</b></td>
						</tr>
						<tr>
							<td width="5%"></td>
							<td width="60%"></td>
							<td width="15%" align="right"><b>'.$CounterRef.'</b></td>
						</tr>
					</tbody>
				</table>
				<br>
				<br>
				<table width="100%" border="0">
	<tbody>
		'.$htmldetails.'
	</tbody>
	</table>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<table width="100%" border="0">
		<tbody>
			<tr>
				 <td width="70%" align="right"><b>TOTAL</b></td>
				 <td width="30%" align="right"><b>'.number_format($TotalPaidSum,2).'</b></td>
			 </tr>
		</tbody>
	</table>
	<br>
	<br>
	<br>
	<table width="100%" border="0">
		<tbody>
			<tr>
				<td width="1%"><b></b></td>
				<td width="30%"><b>CYNDI ROQUE</b></td>
				<td width="20%"><b>JO GIMENO</b></td>
				<td width="30%"><b>MARIA KHRISTINA FONTILLAS</b></td>
				<td width="20%"><b></b></td>
			</tr>
		</tbody>
	</table>
			</div>
          </div>
        ';

$mpdf->SetWatermarkText('');
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->showWatermarkText = true;


$stylesheet = file_get_contents('../../mpdf/mpdf_css/cv-report.css');
$mpdf->WriteHTML($stylesheet,1);

$mpdf->WriteHTML(utf8_encode($html));

$mpdf->Output();
exit;

?>