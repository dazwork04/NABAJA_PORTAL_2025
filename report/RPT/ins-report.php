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
$selWhse = $_GET['selWhse'];
$selItemGroup = $_GET['selItemGroup'];

if($selItemGroup == '')
{
	$selItemGroup1 = '';
}
else
{
	$selItemGroup1 = " AND T0.ItmsGrpCod = '$selItemGroup' ";
}

$cmpDetails = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT UPPER(T0.CompnyName) as CompnyName, 
																				T0.CompnyAddr, 
																				T1.Street, 
																				T1.City
																		FROM OADM T0, ADM1 T1");
																		
																		odbc_fetch_row($cmpDetails);
																		
		$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
										SELECT T0.ItemCode, 
													T0.ItemName,
													T0.InvntryUom,
													T1.OnHand,
													T1.WhsCode,
													T2.WhsName,
													T0.AvgPrice
											FROM OITM T0
											INNER JOIN OITW T1 ON T0.ItemCode = T1.ItemCode
											INNER JOIN OWHS T2 ON T1.WhsCode = T2.WhsCode
											WHERE T1.WhsCode = '$selWhse'
											$selItemGroup1");
		
		while (odbc_fetch_row($qry)) 
		{
			
			$htmldetails .= '<tr>
								<td width="5px"><center>'.odbc_result($qry, 'ItemCode').'</center></td>
								<td width="5px">'.utf8_encode(odbc_result($qry, 'ItemName')).'</td>
								<td width="5px"><center>'.odbc_result($qry, 'InvntryUom').'</center></td>
								<td width="5px" align="right">'.number_format(odbc_result($qry, 'OnHand'),2).'</td>
								<td width="5px" align="right">'.number_format(odbc_result($qry, 'AvgPrice'),2).'</td>
								<td width="5px"><center>'.odbc_result($qry, 'WhsName').'</center></td>
							</tr>';
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
							<td colspan="4"><center><h2>IN-STOCK MONITORING</h2></center></td>
						</tr>
						<tr>
							<td colspan="4"><center><h3><span style="color:gray"></span></h3></center></td>
						</tr>
						
					</thead>
				</table>
				
				<table width="100%" border="1">
					<thead>
						<tr bgcolor="lightgray">
							<th width="5px"><center>ITEM CODE</center></th>
							<th width="5px"><center>DESCRIPTION</center></th>
							<th width="5px"><center>UOM</center></th>
							<th width="5px"><center>IN STOCK</center></th>
							<th width="5px"><center>UNIT COST</center></th>
							<th width="5px"><center>WHSE</th>
						</tr>
					</thead>
					<tbody>
						'.$htmldetails.'
						<tr bgcolor="lightgray">
							<td colspan="6">&nbsp;</td>
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