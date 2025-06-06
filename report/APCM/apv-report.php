
<?php
session_start();
include_once('../../config/config.php');

require_once __DIR__ . '/../../mpdf/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../mpdf/custom/temp/dir/path']);

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

$cmpDetails = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT UPPER(T0.CompnyName) as CompnyName, 
																																											T0.CompnyAddr, 
																																											T1.Street, 
																																											T1.City
																																									FROM OADM T0, ADM1 T1");
odbc_fetch_row($cmpDetails);

$qry = odbc_exec($MSSQL_CONN, " 
		 SELECT	T0.DocEntry,
				T0.DocNum,
				T0.CardCode,
				T0.CardName,
				T0.Address2,
				T0.LicTradNum,
				T0.DocDate,
				T0.DocDueDate,
				T0.NumAtCard,
				T0.DocTotal - T0.VatSum AS SubTotal,
				T0.VatSum,
				T0.DocDate,
		        T0.Comments, 
				T1.Quantity,
				T1.Price,
				T0.DocTotal,
				T5.PymntGroup,
				T1.ItemCode,
				T1.Dscription,
				T1.LineTotal,
				T1.unitMsr,
				T1.Text		
		
        FROM [OPCH] T0
        INNER JOIN [PCH1] T1 ON T0.DocEntry = T1.DocEntry
		LEFT JOIN [OCTG] T5 ON T0.GroupNum = T5.GroupNum
		
        WHERE T0.DocEntry = '$docentry' ");
		 
while (odbc_fetch_row($qry)) 
{
	
	$DocNum = odbc_result($qry, 'DocNum');
	$PrintDate = date('m/d/Y' ,strtotime(date('Y-m-d')));
	$CardCode = odbc_result($qry, 'CardCode');
	$CardName = odbc_result($qry, 'CardName');
	$Address = utf8_decode(odbc_result($qry, 'Address2'));
	$LicTradNum = odbc_result($qry, 'LicTradNum');
	$NumAtCard = odbc_result($qry, 'NumAtCard');
	$Comments = odbc_result($qry, 'Comments');
	$DocDate = date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate')));
	$DocDueDate = date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDueDate')));
	$SubTotal = odbc_result($qry, 'SubTotal');
	$DocTotal = odbc_result($qry, 'DocTotal');
	$VatSum = odbc_result($qry, 'VatSum');
	$PymntGroup = odbc_result($qry, 'PymntGroup');
	$Text = odbc_result($qry, 'Text');
	
		$htmldetails .= '<tr>
                        <td width="2%" style="padding: 2px;border:1px solid black"><center>'.$no.'</center></td>
						 <td width="10%" style="padding: 2px;border:1px solid black">'.odbc_result($qry, 'ItemCode').'</td>
                        <td width="20%" style="padding: 2px;border:1px solid black">'.odbc_result($qry, 'Dscription').'</td>
						<td width="10%" style="padding: 2px;border:1px solid black"><center>'.number_format(odbc_result($qry, 'Quantity'),2).'</center></td>
                        <td width="10%" style="padding: 2px;border:1px solid black"><center>'.odbc_result($qry, 'unitMsr').'</center></td>
                        <td width="10%" style="padding: 2px;border:1px solid black" align="right">'.number_format(odbc_result($qry, 'Price'),2).'</td>
                        <td width="10%" style="padding: 2px;border:1px solid black" align="right">'.number_format(odbc_result($qry, 'LineTotal'),2).'</td>
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
							<td align="right"><b><h2>A/P INVOICE</h2></b></td>
						</tr>
					</thead>
				</table>
				<br>
				<table width="100%" border="0" style="font-size:20pt;" style="border:1px solid black;">
					<tbody style="padding:5px!important">
						<tr>
							<td width="12%">Supplier Code</td>
							<td width="2%"><center>:</center></td>
							<td width="58%"><b>'.$CardCode.'</b></td>
							<td width="12%">APV No.</td>
							<td width="5%"><center>:</center></td>
							<td width="12%" align="right"><b>'.$DocNum.'</b></td>
						</tr>
						<tr>
							<td width="12%">Supplier Name</td>
							<td width="2%"><center>:</center></td>
							<td width="58%"><b>'.$CardName.'</b></td>
							<td width="12%">Print Date</td>
							<td width="5%"><center>:</center></td>
							<td width="12%" align="right"><b>'.$date.'</b></td>
						</tr>
						<tr>
							<td width="12%">Address</td>
							<td width="2%"><center>:</center></td>
							<td width="58%"><b>'.$Address.'</b></td>
							<td width="12%">Date</td>
							<td width="5%"><center>:</center></td>
							<td width="12%" align="right"><b>'.$DocDate.'</b></td>
						</tr>
						<tr>
							<td width="12%"></td>
							<td width="2%"><center></center></td>
							<td width="58%"><b></b></td>
							<td width="12%">Due Date</td>
							<td width="5%"><center>:</center></td>
							<td width="12%" align="right"><b>'.$DocDueDate.'</b></td>
						</tr>
						<tr>
							<td width="12%">TIN</td>
							<td width="2%"><center>:</center></td>
							<td width="58%"><b></b></td>
							<td width="12%"></td>
							<td width="5%"><center></center></td>
							<td width="12%" align="right"><b></b></td>
						</tr>
					</tbody>
				</table>
				<br>
				<table width="100%" border="1">
					<thead>
						<tr bgcolor=""  style="border:1px solid black ">
							<th style="height:30px;border:1px solid black"><center>#</center></th>
							<th style="border:1px solid black "><center>ITEM CODE</center></th>
							<th style="border:1px solid black "><center>ITEM DESCRIPTION</center></th>
							<th style="border:1px solid black "><center>QTY</center></th>
							<th style="border:1px solid black "><center>UOM</center></th>
							<th style="border:1px solid black "><center>PRICE</center></th>
							<th style="border:1px solid black "><center>TOTAL</center></th>
						</tr>
					</thead>
					<tbody>
						'.$htmldetails.'
						<tr>
							<td colspan="5" rowspan="3"><center>***************nothing to follow**************</center></td>
							<td><center><b>SUB TOTAL</b></center></td>
							<td align="right"><b>'.number_format($SubTotal,2).'</b></td>
						</tr>
						<tr>
							<td><center><b>TAX</b></center></td>
							<td align="right"><b>'.number_format($VatSum,2).'</b></td>
						</tr>
						<tr>
							<td><center><b>TOTAL</b></center></td>
							<td align="right"><b>'.number_format($DocTotal,2).'</b></td>
						</tr>
					</tbody>
				</table>
				<table width="100%" border="0">
					<tbody>
						<tr>
							<td >Remarks : <b>'.$Comments.'</b></td>
						</tr>
						</tbody>
				</table>
				<br>
				<table width="100%" border="0">
					<tbody>
						<tr>
							<td width="33%"><center><b>_______________________________</b></center></td>
							<td width="33%"><center><b></b></center></td>
							<td width="33%"><center><b>_______________________________</b></center></td>
						</tr>
						<tr>
							<td width="33%"><center><b>Prepared By</b></center></td>
							<td width="33%"><center><b>&nbsp;</b></center></td>
							<td width="33%"><center><b>Approved By</b></center></td>
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