
<?php
session_start();
include_once('../../config/config.php');
date_default_timezone_set("Asia/Manila");

require_once __DIR__ . '/../../mpdf/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../mpdf/custom/temp/dir/path']);

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$docentry = $_GET['docentry'];

$date = date('h:i:s A  F d, Y');
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

$qry = odbc_exec($MSSQL_CONN, "EXEC [dbo].[usp_PLERM_APV_Item] $docentry");
		
$height = 605;
	 
while (odbc_fetch_row($qry)) 
{
	$CardCode = odbc_result($qry, 'CardCode');
	$CardName = odbc_result($qry, 'CardName');
	$DocDate = date('M d, Y' ,strtotime(odbc_result($qry, 'DocDate')));
	$DocDueDate = date('M d, Y' ,strtotime(odbc_result($qry, 'DocDueDate')));
	$Comments =  odbc_result($qry, 'Comments');
	$NumAtCard = odbc_result($qry, 'NumAtCard');
	
	if(odbc_result($qry, 'Debit') == 0)
	{
		$Debit = '';
	}
	else
	{
		$Debit = number_format(odbc_result($qry, 'Debit'),2);
	}
	
	if(odbc_result($qry, 'Credit') == 0)
	{
		$Credit = '';
	}
	else
	{
		$Credit = number_format(odbc_result($qry, 'Credit'),2);
	}
/* $DocNum = odbc_result($qry, 'DocNum');
	$CardCode = odbc_result($qry, 'CardCode');
	$CardName = odbc_result($qry, 'CardName');
	$Address = utf8_decode(odbc_result($qry, 'Address'));
	$Address2 = utf8_decode(odbc_result($qry, 'Address2'));
	$LicTradNum = odbc_result($qry, 'LicTradNum');
	$NumAtCard = odbc_result($qry, 'NumAtCard');
	$Phone1 = odbc_result($qry, 'Phone1');
	$Phone1 = odbc_result($qry, 'Phone1');
	$Fax = odbc_result($qry, 'Fax');
	$DocDate = date('M d, Y' ,strtotime(odbc_result($qry, 'DocDate')));
	$DocDueDate = date('M d, Y' ,strtotime(odbc_result($qry, 'DocDueDate')));
	
	$PymntGroup = odbc_result($qry, 'PymntGroup');
	$Text = odbc_result($qry, 'Text');
	
	$SubTotal = odbc_result($qry, 'SubTotal');
	$DocTotal = odbc_result($qry, 'DocTotal');
	$VatSum = odbc_result($qry, 'VatSum'); */
	
	$htmldetails .= '<tr>
								   <td width="220px" style="word-wrap: break-word;"><b>'.odbc_result($qry, 'AcctName').'</b><br>'.odbc_result($qry, 'Dscription').'</td>
								   <td width="100px"><center>'.$Debit.'</center></td>
								   <td width="100px"><center>'.$Credit.'</center></td>
								   <td width="100px"><center>'.odbc_result($qry, 'DeptName').'</center></td>
								   <td width="100px"><center>'.odbc_result($qry, 'EmpName').'</center></td>
								   <td width="100px"><center>&nbsp;</center></td>
								</tr>';
	$no++;
	
	$TotalDebit += odbc_result($qry, 'Debit');
	$TotalCredit += odbc_result($qry, 'Credit');
}

$html .= '
		<div class="row">
            <div class="col-lg-12">
				<table width="100%" border="0">
					<thead>
						<tr>
							<td  width="100%"><center><img src="../../img/Logo2apv.jpg" width="500" height="80"><center></td>
						</tr>
					</thead>
				</table>
				<table width="100%" border="0">
					<tbody style="padding:5px!important">
						<tr>
							<td width="10%">Date</td>
							<td width="2%"><center>:</center></td>
							<td width="45%"><b>'.$DocDate.'</b></td>
							<td width="3%"><center>&nbsp;</center></td>
							<td width="12%"></td>
							<td width="2%"><center></center></td>
							<td width="26%" align="right"><b><h2><span style="font-size: 17pt;">'.$NumAtCard.'</span></h2></b></td>
						</tr>
					</tbody>
				</table>
				<table border="0" style="table-layout: fixed; white-space: normal!important; width:700px;">
					<thead>
						<tr>
							<th width="220px" style="border-top:2px solid black;" align="left">&nbsp;&nbsp;Account Description</th>
							<th width="100px" style="border-top:2px solid black;"><center></center></th>
							<th width="100px" style="border-top:2px solid black;"><center></center></th>
							<th width="100px" style="border-top:2px solid black;"><center>Cust. Inv.</center></th>
							<th width="100px" style="border-top:2px solid black;"><center>Due Date</center></th>
							<th width="100px" style="border-top:2px solid black;"><center>Other Ref. #</center></th>
						</tr>
						<tr>
							<th style="border-bottom:2px solid black;" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Line Description</th>
							<th style="border-bottom:2px solid black;"><center>Debit Amt.</center></th>
							<th style="border-bottom:2px solid black;"><center>Credit Amt.</center></th>
							<th style="border-bottom:2px solid black;"><center>Department</center></th>
							<th style="border-bottom:2px solid black;"><center>Employee</center></th>
							<th style="border-bottom:2px solid black;"><center></center></th>
						</tr>
					</thead>
				</table>	
				<table border="0" style="table-layout: fixed; white-space: normal!important; width:700px;">
					<tbody>
						<tr>
						   <td width="220px" style="padding:5px; word-wrap: break-word;"><b>'.$CardCode.' &nbsp;'.$CardName.'</b></td>
						   <td width="100px"><center>&nbsp;</center></td>
						   <td width="100px"><center>&nbsp;</center></td>
						   <td width="100px"><center>&nbsp;</center></td>
						   <td width="100px"><center><b>'.$DocDueDate.'</b></center></td>
						   <td width="100px"><center><b>'.$Comments.'</b></center></td>
						</tr>
						'.$htmldetails.'
						<tr>
						   <td width="220px" style="word-wrap: break-word;" align="right"><b>TOTAL</b></td>
						   <td width="100px" style="border-bottom:2px solid black; border-top:2px solid black;"><b><center>'.number_format($TotalDebit,2).'</center></b></td>
						   <td width="100px" style="border-bottom:2px solid black; border-top:2px solid black;"><b><center>'.number_format($TotalCredit,2).'</center></b></td>
						   <td width="100px"><center>&nbsp;</center></td>
						   <td width="100px"><center>&nbsp;</center></td>
						   <td width="100px"><center>&nbsp;</center></td>
						</tr>
					</tbody>
				</table>
				<br>
			</div>
          </div>
        ';
$htmlfooter = '<br>
				<table width="100%" border="0" style="font-size:9pt;">
					<tbody>
						<tr>
							<td width="33%"><b>Prepared By :</b></td>
							<td width="33%"><b>Checked By :</b></td>
							<td width="33%"><b>Approved By :</b></td>
						</tr>
						<tr>
							<td width="33%"><center><b>&nbsp;</b></center></td>
							<td width="33%"><center><b>&nbsp;</b></center></td>
							<td width="33%"><center><b>&nbsp;</b></center></td>
						</tr>
						<tr>
							<td width="33%"><center><b>&nbsp;</b></center></td>
							<td width="33%"><center><b>&nbsp;</b></center></td>
							<td width="33%"><center><b>&nbsp;</b></center></td>
						</tr>
						<tr>
							<td width="33%"><b>_______________________________</b></td>
							<td width="33%"><b>_______________________________</b></td>
							<td width="33%"><b>_______________________________</b></td>
						</tr>
						<tr>
							<td width="33%">&nbsp;&nbsp;&nbsp;Signature Over Printed Name</td>
							<td width="33%">&nbsp;&nbsp;&nbsp;Signature Over Printed Name</td>
							<td width="33%">&nbsp;&nbsp;&nbsp;Signature Over Printed Name</td>
						</tr>
						<tr>
							<td width="33%"></td>
							<td width="33%"><center><b>&nbsp;</b></center></td>
							<td width="33%"></td>
						</tr>
						<tr>
							<td width="33%"></td>
							<td width="33%"><center><b>&nbsp;</b></center></td>
							<td width="33%"></td>
						</tr>
					</tbody>
				</table>
				<table width="100%" border="0" style="font-size:9pt;">
				<tbody>
					<tr>
						<td width="33%" style="border-top:2px solid black;">F-PCDC-ACC-001/Rev.03/EFF:</td>
						<td width="33%" style="border-top:2px solid black;"><center><b>&nbsp;</b></center></td>
						<td width="33%" style="border-top:2px solid black;" align="right">'.$date.'</td>
					</tr>
				</tbody>
				</table>';
$mpdf->SetWatermarkText('');
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->showWatermarkText = true;
$mpdf->SetHTMLFooter($htmlfooter);

$stylesheet = file_get_contents('../../mpdf/mpdf_css/rpt_1-report.css');
$mpdf->WriteHTML($stylesheet,1);

$mpdf->WriteHTML(utf8_encode($html));

$mpdf->Output();
exit;

?>