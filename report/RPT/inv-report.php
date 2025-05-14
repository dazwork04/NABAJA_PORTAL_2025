<?php

include_once('../../config/config.php');

require_once __DIR__ . '/../../mpdf/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../mpdf/custom/temp/dir/path']);

$date = date('m-d-Y');

$htmlheader = '';
$htmldetails = '';
$html = '';
$remarks = '';

$empid = $_SESSION['SESS_EMP'];
$datefrom = $_GET['datefrom'];
$dateto = $_GET['dateto'];

$cmpDetails = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT UPPER(T0.CompnyName) as CompnyName, 
																				T0.CompnyAddr, 
																				T1.Street, 
																				T1.City
																		FROM OADM T0, ADM1 T1");
																		
																		odbc_fetch_row($cmpDetails);
	
		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
										SELECT T0.DocNum,
												T0.DocDate,
												T1.ItemCode, 
												T1.Dscription, 
												T1.Quantity, 
												T1.FromWhsCod, 
												T1.WhsCode, 
												T1.StockPrice, 
												T1.Quantity * T1.StockPrice AS LineTotal
										FROM OWTR T0
										INNER JOIN WTR1 T1 ON T0.DocEntry = T1.DocEntry
										WHERE T0.DocDate BETWEEN '$datefrom' AND '$dateto'
										ORDER BY T0.DocDate ASC");
		
		while (odbc_fetch_row($qry)) 
		{
			
			$htmldetails .= '<tr>
								<td width="5%"><center>'.odbc_result($qry, 'DocNum').'</center></td>
								<td width="5%"><center>'.date_format(date_create(odbc_result($qry, 'DocDate')),'m/d/Y').'</center></td>
								<td width="5%">'.odbc_result($qry, 'ItemCode').'</td>
								<td width="5%">'.odbc_result($qry, 'Dscription').'</td>
								<td width="5%" align="right">'.number_format(odbc_result($qry, 'Quantity'),2).'</td>
								<td width="5%"><center>'.odbc_result($qry, 'FromWhsCod').'</center></td>
								<td width="5%"><center>'.odbc_result($qry, 'WhsCode').'</center></td>
								<td width="5%" align="right">'.number_format(odbc_result($qry, 'StockPrice'),2).'</td>
								<td width="5%" align="right">'.number_format(odbc_result($qry, 'LineTotal'),2).'</td>
							</tr>';
							$DocTotal += odbc_result($qry, 'LineTotal');
		}	

$html .= '<div class="row">
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
							<td colspan="4"><center><h2>INVENTORY MONITORING</h2></center></td>
						</tr>
						<tr>
							<td colspan="4"><center><h3><span style="color:gray">Date : '.date_format(date_create($datefrom),'m/d/Y').' - '.date_format(date_create($dateto),'m/d/Y').'</span></h3></center></td>
						</tr>
						
					</thead>
				</table>
				
				<table width="100%" border="1">
					<thead>
						<tr bgcolor="lightgray">
							<th width="5px"><center>IT REF</center></th>
							<th width="5px"><center>DOC DATE</center></th>
							<th width="5px"><center>ITEM CODE</center></th>
							<th width="5px"><center>DESCRIPTION</center></th>
							<th width="5px"><center>QUANTITY</center></th>
							<th width="5px"><center>FROM WHSE</center></th>
							<th width="5px"><center>TO WHSE</center></th>
							<th width="5px"><center>UNIT COST</center></th>
							<th width="5px"><center>AMOUNT</center></th>
						</tr>
					</thead>
					<tbody>
						'.$htmldetails.'
						<tr bgcolor="lightgray">
							<th  colspan="8" align="right">TOTAL</th>
							<th width="5px" align="right">'.number_format($DocTotal,2).'</th>
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