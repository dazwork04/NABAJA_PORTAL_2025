
<?php
session_start();
include_once('../../config/config.php');

require_once __DIR__ . '/../../mpdf/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../mpdf/custom/temp/dir/path']);

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$docentry = $_GET['docentry'];


$date = date('m-d-Y');
$htmlheader = '';
$htmldetails = '';
$htmlremarks = '';
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
				T1.Dscription,
				T1.LineTotal,
				T1.FromWhsCod,
				T1.WhsCode,
				T1.unitMsr,
				T2.WhsName AS FromWhsName,
				T3.WhsName AS ToWhsName,
				T1.Text		
		
        FROM [OWTR] T0
        INNER JOIN [WTR1] T1 ON T0.DocEntry = T1.DocEntry
		LEFT JOIN OWHS T2 ON T1.FromWhsCod = T2.WhsCode
		LEFT JOIN OWHS T3 ON T1.WhsCode = T3.WhsCode
		WHERE T0.DocEntry = '$docentry' ");
		 
while (odbc_fetch_row($qry)) 
{
	$CardName = odbc_result($qry, 'CardName');
	$Address = odbc_result($qry, 'Address');
	$Comments = odbc_result($qry, 'Comments');
	$DocDate = odbc_result($qry, 'DocDate');
	$ReqDate = odbc_result($qry, 'ReqDate');
	$DocTotal = odbc_result($qry, 'DocTotal');
	$Text = odbc_result($qry, 'Text');

	if($Comments == '')
	{
		$Remarks = '&nbsp;';
	}
	else
	{
		$Remarks = 'REMARKS';
	}
	
	$htmldetails .= '<tr>
                        <td width="10%" style="padding: 15px;"><center>'.$no.'</center></td>
						<td width="10%" style="padding: 15px;"><center>'.number_format(odbc_result($qry, 'Quantity'),2).'</center></td>
                        <td width="10%" style="padding: 15px;"><center>'.odbc_result($qry, 'unitMsr').'</center></td>
                        <td width="40%" style="padding: 15px;">'.odbc_result($qry, 'Dscription').'</td>
                        <td width="20%" style="padding: 15px;"><center>'.odbc_result($qry, 'FromWhsName').'</center></td>
                        <td width="15%" style="padding: 15px;">'.odbc_result($qry, 'ToWhsName').'</td>
                    </tr>';
	//$DocTotal += odbc_result($qry, 'Amount'); */
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
							<td align="right"><b><h2>INVENTORY TRANSFER</h2></b></td>
						</tr>
					</thead>
				</table>
				<br>
				<table width="100%" border="0" style="font-size:8pt;">
					<tbody>
						<tr>
							<td width="5%" colspan="2">'.$Remarks.'</td>
							<td width="35%"><b></b></td>
							<td width="12%">Posting Date</td>
							<td width="18%">: <b>'.$DocDate.'</b></td>
						</tr>
						<tr>
							<td width="5%" rowspan="2" colspan="2" valign="top"><b>'.$Comments.'</b></td>
							<td width="35%"></td>
							<td width="12%"></td>
							<td width="18%"></b></td>
						</tr>
						<tr>
							<td width="35%"><b></b></td>
							<td width="12%">Creator</td>
							<td width="18%">: </td>
						</tr>
						
					</tbody>
				</table>
				<br>
				
				<br>
				<table width="100%" border="1" style="font-size:8pt;">
					<thead>
						<tr bgcolor="lightgray">
							<th style="height:30px"><center>#</center></th>
							<th><center>QUANTITY</center></th>
							<th><center>UNIT</center></th>
							<th><center>DESCRIPTION</center></th>
							<th><center>FROM WH</center></th>
							<th><center>TO WH</center></th>
						</tr>
					</thead>
					<tbody>
						'.$htmldetails.'
					</tbody>
					<tfoot>
						<tr>
							<td colspan="4" bgcolor="lightgray">&nbsp;</td>
							<th bgcolor="lightgray"></th>
							<th bgcolor="lightgray" style="padding: 2px;" align="right"></th>
						</tr>
					</tfoot>
				</table>
				<br>
				<table width="100%" border="0" style="font-size:8pt;">
					<tbody>
						<tr>
							<td width="70%"><b></b></td>
							<td width="15%">Encoded by</td>
							<td width="15%">: </td>
						</tr>
						<tr>
							<td width="70%">&nbsp;</td>
							<td width="15%"><center></center></td>
							<td width="15%"><center></center></td>
						</tr>
						<tr>
							<td width="70%"><b></b></td>
							<td width="15%">Checked by</td>
							<td width="15%">: </td>
						</tr>
						<tr>
							<td width="70%">&nbsp;</td>
							<td width="15%"><center></center></td>
							<td width="15%"><center></center></td>
						</tr>
						<tr>
							<td width="70%"><b></b></td>
							<td width="15%">Received by</td>
							<td width="15%">: </td>
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


$stylesheet = file_get_contents('../../mpdf/mpdf_css/rpt_1-report.css');
$mpdf->WriteHTML($stylesheet,1);

$mpdf->WriteHTML(utf8_encode($html));

$mpdf->Output();
exit;

?>