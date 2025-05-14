
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
$GrandTotalQuantity = 0;
$GrandTotalDebit = 0;
$GrandTotalCredit = 0;

$cmpDetails = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT UPPER(T0.CompnyName) as CompnyName, 
																			T0.CompnyAddr, 
																			T1.Street, 
																			T1.City
																	FROM OADM T0, ADM1 T1");
odbc_fetch_row($cmpDetails);

$qry = odbc_exec($MSSQL_CONN, " 
		 SELECT DISTINCT
			-- JDT1 --
			A.TransId,
			A.Account,
			CASE WHEN A.Debit != 0 THEN 
				CASE WHEN D.LineTotal IS NULL THEN SUM(A.Debit) ELSE D.LineTotal END  
			ELSE 0 END AS Debit,
			CASE WHEN A.Credit != 0 THEN 
				CASE WHEN D.LineTotal IS NULL THEN SUM(A.Credit) ELSE D.LineTotal END 
			ELSE 0 END AS Credit,

			-- OJDT --
			A1.LocTotal,

			-- OACT --
			B.AcctCode,
			B.AcctName,

			-- OPCH --
			C.DocEntry,
			C.CardCode,
			C.CardName,
			C.NumAtCard,
			C.DocDate,
			C.DocDueDate,
			C.Comments,

			-- PCH1 --
			D.ItemCode,
			D.Quantity,
			ISNULL(D.Dscription,C.CardName) 'Dscription',
			D.OcrCode,
			D.Project,
			D.OcrCode2,
			D.OcrCode3,
			ISNULL(D.VisOrder,999) 'VisOrder',
			ISNULL(D.LineNum,999) 'LineNum',

			E.PrcName AS 'DeptName',
			F.PrjName AS 'ProjName',
			G.PrcName AS 'EmpName',
			H.PrcName AS 'EquipName'

		FROM JDT1 A
			LEFT JOIN OJDT A1 ON A.TransId = A1.TransId
			LEFT JOIN OACT B ON A.Account = B.AcctCode
			LEFT JOIN OPCH C ON A.TransId = C.TransId
			LEFT JOIN PCH1 D ON C.DocEntry = D.DocEntry AND A.Account = D.AcctCode
			LEFT JOIN OPRC E ON D.OcrCode = E.PrcCode
			LEFT JOIN OPRJ F ON D.Project = F.PrjCode
			LEFT JOIN OPRC G ON D.OcrCode2 = G.PrcCode
			LEFT JOIN OPRC H ON D.OcrCode3 = H.PrcCode
		WHERE C.DocEntry = $docentry

		GROUP BY 
			A.TransId,
			A.Account,
			B.AcctCode,
			B.AcctName,
			A.Account,
			A.Debit,
			A.Credit,
			A1.LocTotal,
			B.AcctCode,
			B.AcctName,
			C.DocEntry,
			C.CardCode,
			C.CardName,
			C.NumAtCard,
			C.DocDate,
			C.DocDueDate,
			C.Comments,
			D.ItemCode,
			D.Quantity,
			D.Dscription,
			D.OcrCode,
			D.Project,
			D.OcrCode2,
			D.OcrCode3,
			D.VisOrder,
			D.LineNum,
			D.LineTotal,
			E.PrcName,
			F.PrjName,
			G.PrcName,
			H.PrcName 
		ORDER BY C.DocEntry DESC, ISNULL(D.LineNum,999) ASC");		
	 
while (odbc_fetch_row($qry)) 
{
	$CardCode = odbc_result($qry, 'CardCode');
	$CardName = odbc_result($qry, 'CardName');
	$DocDate = date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate')));
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
	
	if(odbc_result($qry, 'Quantity') == 0)
	{
		$Quantity = '';
	}
	else
	{
		$Quantity = number_format(odbc_result($qry, 'Quantity'),2);
	}

	if(odbc_result($qry, 'Debit') == 0 && odbc_result($qry, 'Credit') == 0)
	{
		
	}
	else
	{
		if($iddocentry != odbc_result($qry, 'DocEntry') && $iddocentry != '')
		{
			$htmldetails .= '<tr>
						   <td width="220px" style="word-wrap: break-word;" align="right"><b>TOTAL</b></td>
						   <td width="100px" style="border-bottom:2px solid black; border-top:2px solid black;" align="right"><b>'.number_format($TotalQuantity,2).'</b></td>
						   <td width="100px" style="border-bottom:2px solid black; border-top:2px solid black;" align="right"><b>'.number_format($TotalDebit,2).'</b></td>
						   <td width="100px" style="border-bottom:2px solid black; border-top:2px solid black;" align="right"><b>'.number_format($TotalCredit,2).'</b></td>
						   <td width="100px"><center>&nbsp;</center></td>
						   <td width="100px"><center>&nbsp;</center></td>
						   <td width="100px"><center>&nbsp;</center></td>
						   <td width="100px"><center>&nbsp;</center></td>
						</tr>';
						
			$TotalQuantity = 0;
			$TotalDebit = 0;
			$TotalCredit = 0;
		}
		
		if($iddocentry != odbc_result($qry, 'DocEntry'))
		{
			$DocDueDate = date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDueDate')));			
			$htmldetails .= '<tr>
						   <td width="220px" colspan="3" style="padding:5px; word-wrap: break-word;"><span style="color:blue;"><b>'.$CardCode.' &nbsp;'.$CardName.'</b></span></td>
						   <td width="100px"><center>&nbsp;</center></td>
						   <td width="100px" colspan="2"><span style="color:blue;"><b><center>'.$NumAtCard.'</center></b></span></td>
						   <td width="100px" colspan="2"><span style="color:blue;"><center><b>'.$DocDueDate.'</b></center></span></td>
						</tr>';
						
			$Comments =  odbc_result($qry, 'Comments');
		}
						
		$htmldetails .= '<tr>
										   <td width="220px" style="word-wrap: break-word;"><b>'.odbc_result($qry, 'AcctName').'</b><br>'.odbc_result($qry, 'Dscription').'</td>
										   <td width="100px" align="right">'.$Quantity.'</td>
										   <td width="100px" align="right">'.$Debit.'</td>
										   <td width="100px" align="right">'.$Credit.'</td>
										   <td width="100px"><center>'.odbc_result($qry, 'OcrCode').''.odbc_result($qry, 'Project').''.odbc_result($qry, 'OcrCode2').''.odbc_result($qry, 'OcrCode3').'</center></td>
										   <td width="100px"><center>'.odbc_result($qry, 'DeptName').''.odbc_result($qry, 'ProjName').''.odbc_result($qry, 'EmpName').''.odbc_result($qry, 'EquipName').'</center></td>
										   <td width="100px"><center>'.$Comments.'</center></td>
										   <td width="100px"><center>'.$DocDueDate.'</center></td>
			
										</tr>';
		
	}
	$Comments = '';
	$DocDueDate = '';
	$TotalQuantity += odbc_result($qry, 'Quantity');
	$TotalDebit += odbc_result($qry, 'Debit');
	$TotalCredit += odbc_result($qry, 'Credit');
	

	$no++;
	
	$iddocentry = odbc_result($qry, 'DocEntry');
	
	$GrandTotalQuantity += odbc_result($qry, 'Quantity');
	$GrandTotalDebit += odbc_result($qry, 'Debit');
	$GrandTotalCredit += odbc_result($qry, 'Credit');
}

if($GrandTotalQuantity == 0)
{
	$GrandTotalQuantity = '';
}
else
{
	$GrandTotalQuantity = number_format($GrandTotalQuantity,2);
}
	
$htmlheader = '<table width="100%" border="0">
					<thead>
						<tr>
							<td  width="100%"><center><img src="../../img/Logo2apv.jpg" width="500" height="80"><center></td>
						</tr>
					</thead>
				</table>';


$html .= '
		<div class="row">
            <div class="col-lg-12">
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
							<th width="220px" style="border-top:2px solid black;" align="left"><span style="color:blue;">&nbsp;&nbsp;Account Description</span></th>
							<th width="100px" style="border-top:2px solid black;"><center></center></th>
							<th width="100px" style="border-top:2px solid black;"><center></center></th>
							<th width="100px" style="border-top:2px solid black;"><center></center></th>
							<th width="100px" style="border-top:2px solid black;" colspan="2"><span style="color:blue;"><center>Reference</center></span></th>
							<th width="100px" style="border-top:2px solid black;" colspan="2"><span style="color:blue;"><center>Date</center></span></th>
						</tr>
						<tr>
							<th style="border-bottom:2px solid black;" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Line Description</th>
							<th style="border-bottom:2px solid black;"><center>Qty.<br>Received</center></th>
							<th style="border-bottom:2px solid black;"><center>Debit Amt.</center></th>
							<th style="border-bottom:2px solid black;"><center>Credit Amt.</center></th>
							<th style="border-bottom:2px solid black;"><center>Code</center></th>
							<th style="border-bottom:2px solid black;"><center>Description</center></th>
							<th style="border-bottom:2px solid black;"><center>Cust. Inv<br>Other Reference</center></th>
							<th style="border-bottom:2px solid black;"><center>Due Date</center></th>
						</tr>
					</thead>
					<tbody>
						
						'.$htmldetails.'
						<tr>
						   <td width="220px" style="word-wrap: break-word;" align="right"><b>GRAND TOTAL</b></td>
						   <td width="100px" style="border-bottom:2px solid black; border-top:2px solid black;" align="right"><b>'.$GrandTotalQuantity.'</b></td>
						   <td width="100px" style="border-bottom:2px solid black; border-top:2px solid black;" align="right"><b>'.number_format($GrandTotalDebit,2).'</b></td>
						   <td width="100px" style="border-bottom:2px solid black; border-top:2px solid black;" align="right"><b>'.number_format($GrandTotalCredit,2).'</b></td>
						   <td width="100px"><center>&nbsp;</center></td>
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
$mpdf->SetHTMLHeader($htmlheader);
$mpdf->AddPageByArray([
	'margin-top' => 30,
    'margin-bottom' => 45,
	'margin-left' => 5,
	'margin-right' => 5,
]);
$mpdf->SetHTMLFooter($htmlfooter);

$stylesheet = file_get_contents('../../mpdf/mpdf_css/apv-report.css');
$mpdf->WriteHTML($stylesheet,1);

$mpdf->WriteHTML(utf8_encode($html));

$mpdf->Output();
exit;

?>