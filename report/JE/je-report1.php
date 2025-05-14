
<?php
session_start();
include_once('../../config/config.php');

require_once __DIR__ . '/../../mpdf/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../mpdf/custom/temp/dir/path']);

$mpdf->AddPageByArray([
	'margin-top' => 5,
    'margin-bottom' => 5,
	'margin-left' => 5,
	'margin-right' => 5,
]);

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$docentry = $_GET['docentry'];
$TransId = 0;
$Debit = 0;
$Credit = 0;
$TotalDebit = 0;
$TotalCredit = 0;
$date = date('M d, Y');
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
		SELECT 
			T0.TransId, 
			T0.RefDate, 
			T0.Ref1, 
			CASE WHEN T1.Account = T1.ShortName THEN T1.Account ELSE T1.ShortName END AS Account,
			CASE WHEN T1.Account = T1.ShortName THEN T2.AcctName ELSE (SELECT CardName FROM OCRD WHERE CardCode = T1.ShortName) END AS AcctName,
			T1.Debit,
			T1.Credit,
			T1.ProfitCode,
			T4.PrcName AS DeptName,
			T1.Project,
			T5.PrjName AS PrjName,
			T1.OcrCode2,
			T6.PrcName AS EmpName,
			T1.OcrCode3,
			T7.PrcName AS EquipName,
			CASE WHEN T1.WTLiable = 'N' THEN 'No' ELSE 'Yes' END AS WTLiable, 
			T1.VatGroup,
			T1.LineMemo
		FROM OJDT T0
		INNER JOIN JDT1 T1 ON T0.TransId = T1.TransId
		LEFT JOIN OACT T2 ON T1.Account = T2.AcctCode
		LEFT JOIN OPRC T4 ON T1.ProfitCode = T4.PrcCode
		LEFT JOIN OPRJ T5 ON T1.Project = T5.PrjCode
		LEFT JOIN OPRC T6 ON T1.OcrCode2 = T6.PrcCode
		LEFT JOIN OPRC T7 ON T1.OcrCode3 = T7.PrcCode
		WHERE T0.TransId = $docentry
		ORDER BY T1.Line_ID ASC ");
		
while (odbc_fetch_row($qry)) 
{
	if($TransId != odbc_result($qry, "TransId") || $TransId == 0)
	{
		$RefDate = date('m/d/Y' ,strtotime(odbc_result($qry, 'RefDate')));
		$Ref1 = utf8_encode(odbc_result($qry, 'Ref1'));
	}
	else
	{
		$RefDate = '';
		$Ref1 = '';
	}
		
	$TransId = odbc_result($qry, "TransId");
	
	$Account = odbc_result($qry, "Account");
	$AcctName = utf8_encode(odbc_result($qry, "AcctName"));
	$Debit = number_format(odbc_result($qry, "Debit"),2);
	$Credit = number_format(odbc_result($qry, "Credit"),2);
	$ProfitCode = utf8_encode(odbc_result($qry, "ProfitCode"));
	$DeptName = utf8_encode(odbc_result($qry, "DeptName"));
	$Project = utf8_encode(odbc_result($qry, "Project"));
	$PrjName = utf8_encode(odbc_result($qry, "PrjName"));
	$OcrCode2 = utf8_encode(odbc_result($qry, "OcrCode2"));
	$EmpName = utf8_encode(odbc_result($qry, "EmpName"));
	$OcrCode3 = utf8_encode(odbc_result($qry, "OcrCode3"));
	$EquipName = utf8_encode(odbc_result($qry, "EquipName"));
	$WTLiable = utf8_encode(odbc_result($qry, "WTLiable"));
	$VatGroup = utf8_encode(odbc_result($qry, "VatGroup"));
	$LineMemo = utf8_encode(odbc_result($qry, "LineMemo"));
	
	$htmldetails .= '<tr>
									   <td width="10%" style="padding: 1px; ">'.$RefDate.'</td>
									   <td width="10%" style="padding: 1px; ">'.$Ref1.'</td>
									   <td width="16%" style="padding: 1px; ">'.$AcctName.'</td>
									   <td width="40%" style="padding: 1px; ">'.$LineMemo.'</td>
									   <td width="8%" style="padding: 1px; " align="right">'.$Debit.'</td>
									   <td width="8%" style="padding: 1px; " align="right">'.$Credit.'</td>
									   <td width="8%" style="padding: 1px; " align="right">'.$ProfitCode.''.$Project.''.$OcrCode2.''.$OcrCode3.'</td>
									</tr>';
	$no++;
	$TotalDebit += odbc_result($qry, "Debit");
	$TotalCredit += odbc_result($qry, "Credit");
}

$html .= '
		<div class="row">
            <div class="col-lg-12">
				<table width="100%" border="0">
					<thead>
						<tr>
							<td  width="100%" align="center" valign="center"><span style="font-size: 18pt; color:black;"><b>'.odbc_result($cmpDetails, 'CompnyName').'</b></span><br>
						</tr>
						<tr>
							<td  width="100%" align="center" valign="center"><span style="font-size: 15pt; color:black;"><b>General Journal</b></span><br>
						</tr>
					</thead>
				</table>
				<br>
				
				<table width="100%" border="0">
					<thead>
						<tr>
							<th style="border-top:1px solid black; border-bottom:1px solid black; padding-top:5px; padding-bottom:5px;"><center>DATE</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>REF. NO.</center></th>3
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>ACCOUNT<br>DESCRIPTION</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>TRANSACTION<br>DESCRIPTION</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>DEBIT<br>AMOUNT</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>CREDIT<br>ACCOUNT</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>JOB ID</center></th>
						</tr>
					</thead>
					<tbody >
						'.$htmldetails.'
						<tr>
							<th style="border-top:1px solid black; border-bottom:1px solid black; padding-top:5px; padding-bottom:5px;"><center>&nbsp;</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>&nbsp;</center></th>3
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>&nbsp;</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>TOTAL</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="right">'.number_format($TotalDebit,2).'</th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;" align="right"><center>'.number_format($TotalCredit,2).'</center></th>
							<th style="border-top:1px solid black; border-bottom:1px solid black;"><center>&nbsp;</center></th>
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
//$mpdf->SetHTMLFooter($htmlfooter);

$stylesheet = file_get_contents('../../mpdf/mpdf_css/rpt_1-report.css');
$mpdf->WriteHTML($stylesheet,1);

$mpdf->WriteHTML(utf8_encode($html));

$mpdf->Output();
exit;

?>