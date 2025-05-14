
<?php
session_start();
include_once('../../config/config.php');

require_once __DIR__ . '/../../mpdf/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../mpdf/custom/temp/dir/path']);

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'Letter']);

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$docentry = $_GET['docentry'];

$date = date('M d, Y');
$htmlheader = '';
$htmldetails = '';
$htmlremarks = '';
$html = '';
$remarks = '';
$no = 1;
$htmlfooter = '';
$htmldiscount = '';

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
				T0.Address,
				T0.Address2,
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
				T0.DiscSum, 
				T0.DiscPrcnt,
				T1.Quantity,
				T1.Price,
				T1.PriceAfVat,
				T1.GTotal,
				T0.DocTotal,
				T5.PymntGroup,
				T1.ItemCode,
				T1.Dscription,
				T1.LineTotal,
				T1.unitMsr,
				T2.InvntryUom,
				T6.Phone1,
				T6.Fax,
				T1.Text		
		
        FROM [OPOR] T0
        INNER JOIN [POR1] T1 ON T0.DocEntry = T1.DocEntry
		LEFT JOIN [OCTG] T5 ON T0.GroupNum = T5.GroupNum
		LEFT JOIN [OCRD] T6 ON T0.CardCode = T6.CardCode
		LEFT JOIN [dbo].OITM T2 ON T1.ItemCode = T2.ItemCode
        WHERE T0.DocEntry = '$docentry' ");
		
$height = 550;
	 
while (odbc_fetch_row($qry)) 
{
	
	$DocNum = odbc_result($qry, 'DocNum');
	$CardCode = odbc_result($qry, 'CardCode');
	$CardName = odbc_result($qry, 'CardName');
	$Address = utf8_decode(odbc_result($qry, 'Address'));
	$Address2 = utf8_decode(odbc_result($qry, 'Address2'));
	$LicTradNum = odbc_result($qry, 'LicTradNum');
	$NumAtCard = odbc_result($qry, 'NumAtCard');
	$DiscPrcnt = odbc_result($qry, 'DiscPrcnt');
	$DiscSum = odbc_result($qry, 'DiscSum');
	$Phone1 = odbc_result($qry, 'Phone1');
	$Phone1 = odbc_result($qry, 'Phone1');
	$Fax = odbc_result($qry, 'Fax');
	$DocDate = date('M d, Y' ,strtotime(odbc_result($qry, 'DocDate')));
	$DocDueDate = date('M d, Y' ,strtotime(odbc_result($qry, 'DocDueDate')));
	
	$PymntGroup = odbc_result($qry, 'PymntGroup');
	$Text = odbc_result($qry, 'Text');
	
	$SubTotal = odbc_result($qry, 'SubTotal');
	$DocTotal = odbc_result($qry, 'DocTotal');
	$VatSum = odbc_result($qry, 'VatSum');
	
	if(strlen(odbc_result($qry, 'Dscription')) >= 1 &&  strlen(odbc_result($qry, 'Dscription')) <= 50)
	{
		$minusheight = 30;
	}
	elseif(strlen(odbc_result($qry, 'Dscription')) >= 51 &&  strlen(odbc_result($qry, 'Dscription')) <= 100)
	{
		$minusheight = 60;
	}
	elseif(strlen(odbc_result($qry, 'Dscription')) >= 100 &&  strlen(odbc_result($qry, 'Dscription')) <= 150)
	{
		$minusheight = 90;
	}
	elseif(strlen(odbc_result($qry, 'Dscription')) >= 151 &&  strlen(odbc_result($qry, 'Dscription')) <= 200)
	{
		$minusheight = 120;
	}
	else
	{
		$minusheight = 30;
	}
	
	
	$htmldetails .= '<tr style="border-bottom: none;">
                       <td width="10px" style="padding: 2px; border-bottom: none; border-left: 1px solid black; border-right: 1px solid black;">'.odbc_result($qry, 'ItemCode').'</td>
                        <td width="50px%" style="padding: 2px; border-bottom: none; border-left: 1px solid black; border-right: 1px solid black;">'.odbc_result($qry, 'Dscription').'</td>
						<td width="10px%" style="padding: 2px; border-bottom: none; border-left: 1px solid black; border-right: 1px solid black;"><center>'.odbc_result($qry, 'InvntryUom').'</center></td>
						<td width="10px%" style="padding: 2px; border-bottom: none; border-left: 1px solid black; border-right: 1px solid black;"><center>'.number_format(odbc_result($qry, 'Quantity'),2).'</center></td>
                        <td width="10px%" style="padding: 2px; border-bottom: none; border-left: 1px solid black; border-right: 1px solid black;" align="right">'.number_format(odbc_result($qry, 'PriceAfVat'),2).'</td>
                        <td width="10px%" style="padding: 2px; border-bottom: none; border-left: 1px solid black; border-right: 1px solid black;" align="right">'.number_format(odbc_result($qry, 'GTotal'),2).'</td>
                    </tr>';
	$no++;
	
	$height = $height - $minusheight;
	$GTotal += odbc_result($qry, 'GTotal');
	$LineTotal += odbc_result($qry, 'LineTotal');
	
}

	if($DiscPrcnt != 0)
	{
		$TotalDiscountAmount = $GTotal * ($DiscPrcnt / 100);
		$htmldiscount = '<tr style="border-bottom: none;">
						   <td width="10px" style="padding: 2px; border-bottom: none; border-left: 1px solid black; border-right: 1px solid black;"></td>
							<td width="50px%" style="padding: 2px; border-bottom: none; border-left: 1px solid black; border-right: 1px solid black;">Discount : '.number_format($DiscPrcnt,2).'%</td>
							<td width="10px%" style="padding: 2px; border-bottom: none; border-left: 1px solid black; border-right: 1px solid black;"></td>
							<td width="10px%" style="padding: 2px; border-bottom: none; border-left: 1px solid black; border-right: 1px solid black;"></td>
							<td width="10px%" style="padding: 2px; border-bottom: none; border-left: 1px solid black; border-right: 1px solid black;" align="right"></td>
							<td width="10px%" style="padding: 2px; border-bottom: none; border-left: 1px solid black; border-right: 1px solid black;" align="right">'.number_format($TotalDiscountAmount,2).'</td>
						</tr>';
						
		$minusheight = 30;				
	}
	else
	{
		$htmldiscount = '';
	}

$html .= '
		<div class="row">
            <div class="col-lg-12">
				<table width="100%" border="0">
					<thead>
						<tr>
							<td  width="60%" rowspan="2"><img src="../../img/logo2.jpg" width="300" height="90"></td>
							<td  width="40%" align="left" valign="top"><span style="font-size: 20pt; color:gray;"><b>PURCHASE ORDER</b></span><br>
						</tr>
						<tr>
							<td align="left" valign="bottom"><b><h2>No. <b><u><span style="font-size: 17pt;">'.$NumAtCard.'</span></u></b></h2></b></td>
						</tr>
					</thead>
				</table>
				<br>
				<table width="100%" border="0" style="font-size:9pt;">
					<tbody style="padding:5px!important">
						<tr>
							<td width="10%">Supplier</td>
							<td width="2%"><center>:</center></td>
							<td width="45%"><b>'.$CardName.'</b></td>
							<td width="3%"><center>&nbsp;</center></td>
							<td width="12%">Date</td>
							<td width="2%"><center>:</center></td>
							<td width="26%" align="left"><b>'.$DocDate.'</b></td>
						</tr>
						<tr>
							<td width="10%"></td>
							<td width="2%"><center></center></td>
							<td width="45%" rowspan="2"><b>'.$Address.'</b></td>
							<td width="2%"><center>&nbsp;</center></td>
							<td width="12%">Terms</td>
							<td width="2%"><center>:</center></td>
							<td width="26%" align="left"><b>'.$PymntGroup.'</b></td>
						</tr>
						<tr>
							<td width="10%"></td>
							<td width="2%"><center></center></td>
							
							<td width="2%"><center>&nbsp;</center></td>
							<td width="12%">Del. Date</td>
							<td width="2%"><center>:</center></td>
							<td width="26%" align="left"><b>'.$DocDueDate.'</b></td>
						</tr>
						<tr>
							<td width="10%"></td>
							<td width="2%"><center></center></td>
							<td width="45%"><b></b></td>
							<td width="2%"><center>&nbsp;</center></td>
							<td width="12%"></td>
							<td width="2%"><center></center></td>
							<td width="26%" align="left"><b></b></td>
						</tr>
						<tr>
							<td width="10%">Ship To</td>
							<td width="2%"><center>:</center></td>
							<td width="45%" rowspan="2" align="left" valign="top"><b>'.$Address2.'</b></td>
							<td width="3%"><center>&nbsp;</center></td>
							<td width="12%">Tel No.</td>
							<td width="2%"><center>:</center></td>
							<td width="26%" align="left"><b>'.$Phone1.'</b></td>
						</tr>
						<tr>
							<td width="10%"></td>
							<td width="2%"><center></center></td>
							<td width="2%"><center>&nbsp;</center></td>
							<td width="12%"></td>
							<td width="2%"><center>&nbsp;</center></td>
							<td width="26%" align="left"><b></b></td>
						</tr>
						<tr>
							<td width="10%"></td>
							<td width="2%"><center></center></td>
							<td width="45%"><b></b></td>
							<td width="2%"><center>&nbsp;</center></td>
							<td width="12%">TIN No.</td>
							<td width="2%"><center>:</center></td>
							<td width="26%" align="left"><b>'.$LicTradNum.'</b></td>
						</tr>
					</tbody>
				</table>
				<br>
				<table height="100%" width="100%" border="0" style="font-size:9pt;">
					<thead>
						<tr style="border:1px solid black ">
							<th style="border:1px solid black;"><center>ITEM CODE</center></th>
							<th style="border:1px solid black;"><center>ITEM DESCRIPTION</center></th>3
							<th style="border:1px solid black;"><center>U/M</center></th>
							<th style="border:1px solid black;"><center>QUANTITY</center></th>
							<th style="border:1px solid black;"><center>UNIT COST</center></th>
							<th style="border:1px solid black;"><center>AMOUNT</center></th>
						</tr>
					</thead>
					<tbody >
						'.$htmldetails.'
						'.$htmldiscount.'
						<tr style="border-bottom: none;">
							 <td width="10%" style="border-bottom: 1px solid black; border-left: 1px solid black; height:'.$height - 15 .'px;"></td>
							 <td width="10%" style="border-bottom: 1px solid black; border-left: 1px solid black;"></td>
							 <td width="10%" style="border-bottom: 1px solid black; border-left: 1px solid black;"></td>
							 <td width="10%" style="border-bottom: 1px solid black; border-left: 1px solid black;"></td>
							 <td width="10%" style="border-bottom: 1px solid black; border-left: 1px solid black;"></td>
							 <td width="10%" style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;"></td>
						</tr>
						<tr>
							 <td width="10%" >&nbsp;</td>
							 <td width="10%" ></td>
							 <td width="10%" ></td>
							 <td width="10%" ></td>
							 <td width="10%" style="border-bottom: 1px solid black; border-top: 1px solid black; border-left: 1px solid black;"><b>AMOUNT</b></td>
							 <td width="10%" style="border-bottom: 1px solid black; border-top: 1px solid black; border-right: 1px solid black;" align="right"><b>'.number_format($LineTotal,2).'</b></td>
						</tr>
						<tr>
							 <td width="10%" >&nbsp;</td>
							 <td width="10%" ></td>
							 <td width="10%" ></td>
							 <td width="10%" ></td>
							 <td width="10%" style="border-bottom: 1px solid black; border-top: 1px solid black; border-left: 1px solid black;"><b>VAT 12%</b></td>
							 <td width="10%" style="border-bottom: 1px solid black; border-top: 1px solid black; border-right: 1px solid black;" align="right"><b>'.number_format($VatSum,2).'</b></td>
						</tr>
						<tr>
							 <td width="10%">&nbsp;</td>
							 <td width="10%" ></td>
							 <td width="10%" ></td>
							 <td width="10%" ></td>
							 <td width="10%" style="border-bottom: 1px solid black; border-top: 1px solid black; border-left: 1px solid black;"><b>TOTAL</b></td>
							 <td width="10%" style="border-bottom: 1px solid black; border-top: 1px solid black; border-right: 1px solid black;" align="right"><b>'.number_format($DocTotal,2).'</b></td>
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
							<td width="30%">Processed By :</td>
							<td width="10%"><center><b>&nbsp;</b></center></td>
							<td width="60%"><center>Approved By :</center></td>
						</tr>
						<tr>
							<td width="30%"><center><b>&nbsp;</b></center></td>
							<td width="10%"><center><b>&nbsp;</b></center></td>
							<td width="60%"><center><b>&nbsp;</b></center></td>
						</tr>
						<tr>
							<td width="30%"><center><b>&nbsp;</b></center></td>
							<td width="10%"><center><b>&nbsp;</b></center></td>
							<td width="60%"><center><b>&nbsp;</b></center></td>
						</tr>
						<tr>
							<td width="30%"><center><b>_______________________________</b></center></td>
							<td width="10%"><center><b></b></center></td>
							<td width="60%"><center><b>_________________________________________________________</b></center></td>
						</tr>
						<tr>
							<td width="30%"><center><b>Rodel V. Santos</b></center></td>
							<td width="10%"><center><b>&nbsp;</b></center></td>
							<td width="60%"><center><b>Maria Katrina Martinez Fuentes/Maria Khristina M. Fontillas</b></center></td>
						</tr>
						<tr>
							<td width="30%"></td>
							<td width="10%"><center><b>&nbsp;</b></center></td>
							<td width="60%"></td>
						</tr>
						<tr>
							<td width="30%">F-PCDC-PUR-003/Rev.1/EFF:01/08/18</td>
							<td width="10%"><center><b>&nbsp;</b></center></td>
							<td width="60%"></td>
						</tr>
					</tbody>
				</table>';
$mpdf->SetWatermarkText('');
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->showWatermarkText = true;
/* $mpdf->AddPageByArray([
	'margin-bottom' => 45,
]); */
$mpdf->SetHTMLFooter($htmlfooter);

$stylesheet = file_get_contents('../../mpdf/mpdf_css/rpt_1-report.css');
$mpdf->WriteHTML($stylesheet,1);

$mpdf->WriteHTML(utf8_encode($html));

$mpdf->Output();
exit;

?>