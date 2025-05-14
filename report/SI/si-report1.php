
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

$qry = odbc_exec($MSSQL_CONN, " 
		 SELECT	T0.DocEntry,
				T0.DocNum,
				T0.CardCode,
				T0.CardName,
				T0.Address,
				T0.LicTradNum,
				T0.DocDate,
				T0.DocDueDate,
				T0.NumAtCard,
				T0.DocTotal - T0.VatSum AS SubTotal,
				T0.VatSum,
				UPPER(T0.ReqName) as Requester,
				CONVERT(VARCHAR, T0.DocDate, 107) as DocDate,
				CONVERT(VARCHAR, T0.ReqDate, 107) as ReqDate,
				T0.Comments, 
				FORMAT(T1.Quantity, 'N0') as Quantity,
				T1.Price,
				T0.DocTotal,
				T5.PymntGroup,
				T1.ItemCode,
				T1.Dscription,
				T1.LineTotal,
				T1.unitMsr,
				T1.Text		
		
        FROM [OINV] T0
        INNER JOIN [INV1] T1 ON T0.DocEntry = T1.DocEntry
		LEFT JOIN [OCTG] T5 ON T0.GroupNum = T5.GroupNum
		
        WHERE T0.DocEntry = '$docentry' ");
		 
while (odbc_fetch_row($qry)) 
{
	
	$DocNum = odbc_result($qry, 'DocNum');
	$PrintDate = date('m/d/Y' ,strtotime(date('Y-m-d')));
	$CardCode = odbc_result($qry, 'CardCode');
	$CardName = odbc_result($qry, 'CardName');
	$Address = utf8_decode(odbc_result($qry, 'Address'));
	$LicTradNum = odbc_result($qry, 'LicTradNum');
	$NumAtCard = odbc_result($qry, 'NumAtCard');
	$Comments = odbc_result($qry, 'Comments');
	$DocDate = date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate')));
	$DocDueDate = date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDueDate')));
	$ReqDate = odbc_result($qry, 'ReqDate');
	$SubTotal = odbc_result($qry, 'SubTotal');
	$DocTotal = odbc_result($qry, 'DocTotal');
	$VatSum = odbc_result($qry, 'VatSum');
	$PymntGroup = odbc_result($qry, 'PymntGroup');
	$Text = odbc_result($qry, 'Text');
	//style="border:1px solid black "
	$htmldetails .= '<tr>
                        <td width="5%" style="padding: 15px;border:1px solid black"><center>'.$no.'</center></td>
						 <td width="40%" style="padding: 15px;border:1px solid black">'.odbc_result($qry, 'ItemCode').'</td>
                        <td width="40%" style="padding: 15px;border:1px solid black">'.odbc_result($qry, 'Dscription').'</td>
						<td width="10%" style="padding: 15px;border:1px solid black"><center>'.number_format(odbc_result($qry, 'Quantity'),2).'</center></td>
                        <td width="10%" style="padding: 15px;border:1px solid black"><center>'.odbc_result($qry, 'unitMsr').'</center></td>
                        <td width="20%" style="padding: 15px;border:1px solid black"><center>'.number_format(odbc_result($qry, 'Price'),2).'</center></td>
                        <td width="20%" style="padding: 15px;border:1px solid black" align="right">'.number_format(odbc_result($qry, 'LineTotal'),2).'</td>
                    </tr>';
	//$DocTotal += odbc_result($qry, 'Amount'); */
	$no++;				  
}

if($CompanyDb == '357TRADERS_LIVE')
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
						<tr >
							
							<td  >&nbsp;</td>
						</tr>
					
						
					</thead>
				</table>
				<br>
				<table width="100%" border="0" style="font-size:8pt;">
					<tbody>
						<tr>
							<td width="5%">&nbsp;</td>
							<td width="65%" rowspan="4" valign="top"></td>
						
							<td width="24%"></td>
							
							<td width="30%"><b><h1>SALES INVOICE</h1></b></td>
						</tr>
					</table>
				<br>	
				<table width="100%" border="0" style="font-size:8pt;" style="border:1px solid black;">
				<tbody style="padding:5px!important">
					
						<tr>
							<td colspan="12"><b>SOLD TO:  '.$CardName.'</b></td>
							<td colspan="8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td colspan="4"><b>SI NO: '.$DocNum.'</b></td>
						</tr>
						<tr>
							<td colspan="12" ><b>ADDRESS: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$Address.'</b></td>
							<td colspan="8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td colspan="4"><b>DATE:&nbsp;&nbsp;&nbsp;&nbsp; '.$DocDate.'</b></td>
						</tr>
						<tr>
							<td colspan="12" ><b>BUSINESS STYLE:</td>
							<td colspan="8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td colspan="4"></td>
						</tr>
						<tr>
							<td colspan="12" ><b></td>
							<td colspan="8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td colspan="4"><b>TIN:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$LicTradNum.'</b></td>
						</tr>
							
						<tr>
							<td colspan="12"><br><br></td>
							<td colspan="8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td colspan="4">PO No.:</td>
						</tr>
						<tr >
							<td colspan="12"><b>TIN/ SC-TIN: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$LicTradNum.'</b></td>
							<td colspan="8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td colspan="4"><b>TERMS:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$PymntGroup.'</b></td>
						</tr>
						
					</tbody>
				</table>
				<br>
				<table width="100%" border="0" style="font-size:8pt;">
					<thead>
						<tr bgcolor="lightgray"  style="border:1px solid black ">
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
					</tbody>
					<tfoot>
						<tr>
							<td colspan="1" >&nbsp;</td>
							<th style="text-align:left">PREPARED BY:</th>
							<th style="text-align:left">CHECKED BY:</th>
							<th style="text-align:left">RELEASED BY:</th>
							<td colspan="1" >&nbsp;</td>
							<th style="border:1px solid black ">12% VATABLE:</th>
							<th style="border:1px solid black ">TOTAL SALES(VAT INCLUSIVE):</th>
							
						</tr>
						<tr>
							<td colspan="5">Remarks</td>
							<th style="padding: 2px;border:1px solid black " align="right">PHP '.number_format($DocTotal,2).'</th>
							<th style="padding: 2px;border:1px solid black " align="right">PHP '.number_format($VatSum,2).'</th>
						</tr>
						<tr>
							
						<td colspan="5"></td>
							<th style="border:1px solid black ">VAT EXEMPT:</th>
							<th style="border:1px solid black ">LESS VAT:</th>
						</tr>
						<tr>
						<td colspan="5"></td>
							<th style="padding: 2px;border:1px solid black " align="right">PHP '.number_format($DocTotal,2).'</th>
							<th style="padding: 2px;border:1px solid black " align="right">PHP '.number_format($VatSum,2).'</th>
						</tr>
						<tr>
						<td colspan="5"></td>
							<th style="border:1px solid black ">VAT ZERO RATED:</th>
							<th style="border:1px solid black ">TOTAL:</th>
						</tr>
						<tr>
						<td colspan="5"></td>
							<th style="padding: 2px;border:1px solid black " align="right">PHP '.number_format($DocTotal,2).'</th>
							<th style="padding: 2px;border:1px solid black " align="right">PHP '.number_format($VatSum,2).'</th>
						</tr>
						<tr>
						<td colspan="5"></td>
							<th style="border:1px solid black ">VAT-12%:</th>
							<th style="border:1px solid black ">LESS: SC/PWD-DISC.:</th>
						</tr>
						<tr>
						<td colspan="5"></td>
							<th style="padding: 2px;border:1px solid black " align="right">PHP '.number_format($DocTotal,2).'</th>
							<th style="padding: 2px;border:1px solid black " align="right">PHP '.number_format($VatSum,2).'</th>
						</tr>

						<tr>
						<td colspan="5"></td>
							<th style="border:1px solid black ">TOTAL AMOUNT DUE:</th>
							<th style="padding: 2px;border:1px solid black " align="right">PHP '.number_format($VatSum,2).'</th>
						</tr>
					
					
							
							
						
					</tfoot>
				</table>
				
				<br>
				
				
				<br>
				<br>
				<br>
				<br>
				<table width="100%" height="50px" border="0" style="font-size:8pt;">
					<thead>
						<tr >
							<td width="20%" style="height:50px!important; border-top:1px solid black"><center>PRINT NAME:</center></td>
							<td width="10%" ></td>
							<td width="20%" style="height:50px!important; border-top:1px solid black"><center>SIGNATURE:</center></td>
							<td width="10%" ></td>
							<td width="20%" style="height:50px!important; border-top:1px solid black"><center>POSITION:</center></td>
							<td width="10%" ></td>
							<td width="20%" style="height:50px!important; border-top:1px solid black"><center>DATE:</center></td>
							
						</tr>
					</thead>
				</table>
				
				<br>
				<br>
				<br>
				<br>
				<table width="100%" height="50px" border="0" style="font-size:8pt;">
					<thead>
						
						<tr >
							<td width="30%" style="height:50px!important; "><b><center>""THIS SALES INVOICE SHALL BE VALID FOR FIVE (5) YEARS FROM THE DATE OF ATP""
							</center></b></td>
							
						</tr>
					
					</thead>
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