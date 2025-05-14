<?php
session_start();
include_once('../../config/config.php');

require_once __DIR__ . '/../../mpdf/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../mpdf/custom/temp/dir/path']);

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];
$UserCode = $_SESSION['SESS_USERCODE'];

$docentry = $_GET['docentry'];

$date = date('m-d-Y');
$htmlheader = '';
$htmldetails = '';
$htmlremarks = '';
$html = '';
$remarks = '';
$no = 1;

$cmpDetails = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT UPPER(T0.CompnyName) as CompnyName, 
																																											T0.CompnyAddr, 
																																											T1.Street, 
																																											T1.City
																																									FROM OADM T0, ADM1 T1");
odbc_fetch_row($cmpDetails);

$qry = odbc_exec($MSSQL_CONN, " 
		 SELECT	T0.DocEntry,
				T0.CardName,
				T0.Address,
				UPPER(T0.ReqName) as Requester,
				CONVERT(VARCHAR, T0.DocDate, 107) as DocDate,
				CONVERT(VARCHAR, T0.ReqDate, 107) as ReqDate,
				T0.Comments, 
				FORMAT(T1.Quantity, 'N0') as Quantity,
				T1.Price,
				T0.DocTotal,
				T5.PymntGroup,
				T1.Dscription,
				T1.LineTotal,
				T1.unitMsr,
				T1.Text		
		
        FROM [OIGN] T0
        INNER JOIN [IGN1] T1 ON T0.DocEntry = T1.DocEntry
		LEFT JOIN [OCTG] T5 ON T0.GroupNum = T5.GroupNum
		
        WHERE T0.DocEntry = '$docentry' ");
		 
while (odbc_fetch_row($qry)) 
{
	$CardName = odbc_result($qry, 'CardName');
	$Address = odbc_result($qry, 'Address');
	$Comments = odbc_result($qry, 'Comments');
	$DocDate = odbc_result($qry, 'DocDate');
	$ReqDate = odbc_result($qry, 'ReqDate');
	$Price = number_format(odbc_result($qry, 'Price'),2);
	$LineTotal = number_format(odbc_result($qry, 'LineTotal'),2);
	$DocTotal = odbc_result($qry, 'DocTotal');
	
	$PymntGroup = odbc_result($qry, 'PymntGroup');
	$Text = odbc_result($qry, 'Text');
	
	$htmldetails .= '<tr>
                        <td width="5%" style="padding: 15px;"><center>'.$no.'</center></td>
						<td width="10%" style="padding: 15px;"><center>'.number_format(odbc_result($qry, 'Quantity'),2).'</center></td>
                        <td width="10%" style="padding: 15px;"><center>'.odbc_result($qry, 'unitMsr').'</center></td>
                        <td width="40%" style="padding: 15px;">'.odbc_result($qry, 'Dscription').'</td>
                        <td width="20%" style="padding: 15px;"><center>'.$Price.'</center></td>
                        <td width="15%" style="padding: 15px;" align="right">'.$LineTotal.'</td>
                    </tr>';
	$no++;				  
}

$html .= '
		<div class="row">
            <div class="col-lg-12">
				<table width="100%" border="0">
					<thead>
						<tr>
							<td  width="15%"><img src="../../img/logo.jpg" width="90" height="70"></td>
							<td  width="85%" align="left" valign="top"><span style="font-size: 16pt"><b>'.odbc_result($cmpDetails, 'CompnyName').'</b></span><br>
								<span style="font-size: 8pt">'.odbc_result($cmpDetails, 'Street').'</span><br>
								<span style="font-size: 8pt">'.odbc_result($cmpDetails, 'City').'</span>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td align="right"><b><h2>GOODS RECEIPT</h2></b></td>
						</tr>
					</thead>
				</table>
				<br>
				<table width="100%" border="0" style="font-size:8pt;">
					<tbody>
						<tr>
							<td width="5%">TO :</td>
							<td width="65%"><b>'.$CardName.'</b></td>
							<td width="12%">&nbsp;</td>
							<td width="18%">&nbsp;</b></td>
						</tr>
						<tr>
							<td width="5%">&nbsp;</td>
							<td width="65%" rowspan="4" valign="top"><b>'.utf8_decode($Address).'</b></td>
							<td width="12%">DATE</td>
							<td width="18%">: <b>'.$DocDate.'</b></td>
						</tr>
						<tr>
							<td width="5%">&nbsp;</td>
							<td width="12%">&nbsp;</td>
							<td width="18%">&nbsp;</td>
						</tr>
					</tbody>
				</table>
				<br>
				<table width="100%" border="1" style="font-size:8pt;">
					<thead>
						<tr bgcolor="lightgray">
							<th style="height:30px"><center>#</center></th>
							<th><center>QUANTITY</center></th>
							<th><center>UNIT</center></th>
							<th><center>DESCRIPTION</center></th>
							<th><center>UNIT PRICE</center></th>
							<th><center>TOTAL PRICE</center></th>
						</tr>
					</thead>
					<tbody>
						'.$htmldetails.'
					</tbody>
					<tfoot>
						<tr>
							<td colspan="4" bgcolor="lightgray">&nbsp;</td>
							<th bgcolor="lightgray">TOTAL <br> VAT INCLUSIVE</th>
							<th bgcolor="lightgray" style="padding: 2px;" align="right">PHP '.number_format($DocTotal,2).'</th>
						</tr>
					</tfoot>
				</table>
				<br>
				<table width="100%" border="0" style="font-size:8pt;">
					<tbody>
						<tr>
							<td width="70%"><b>REMARKS:</b></td>
							<td width="15%"><b>&nbsp;</b></td>
							<td width="15%"></td>
						</tr>
						<tr>
							<td width="70%">'.$Comments.'</td>
							<td width="15%" colspan="2"><center>__________________________</center></td>
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