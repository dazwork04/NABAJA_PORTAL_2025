<?php
session_start();
include_once('../../config/config.php');

date_default_timezone_set('Asia/Manila');

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$txtDateFrom = $_GET['txtDateFrom'];
$txtDateTo = $_GET['txtDateTo'];
$txtRefListFrom = $_GET['txtRefListFrom'];
$txtRefListTo = $_GET['txtRefListTo'];

if($txtRefListFrom != '' && $txtRefListTo != '')
{
	$APVRange = " AND T0.Ref2 BETWEEN '$txtRefListFrom' AND '$txtRefListTo'";
	$APVDateRange = "";
	$HeaderTitle = $txtRefListFrom . ' to ' . $txtRefListTo;
}
else
{
	if($txtDateFrom != '' && $txtDateTo != '')
	{
		$APVDateRange = " AND T0.DocDate BETWEEN '$txtDateFrom' AND '$txtDateTo'";
		
		$HeaderTitle = date('m/d/Y' ,strtotime($txtDateFrom)) . ' to ' . date('m/d/Y' ,strtotime($txtDateTo));
	}
	else
	{
		$APVDateRange = "";
		$HeaderTitle = "";
	}
	
	$APVRange = "";
}


$date = date('m/d/Y h:i a');
$htmlheader = '';
$htmldetails = '';
$htmlremarks = '';
$html = '';
$remarks = '';
$no = 1;
$htmlfooter = '';
$setData = '';
$setData1 = '';
$setData2 = '';
$TotalNetDue = 0;
$POTotal = 0;

$cmpDetails = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT UPPER(T0.CompnyName) as CompnyName, 
																			T0.CompnyAddr, 
																			T1.Street, 
																			T1.City
																	FROM OADM T0, ADM1 T1");
odbc_fetch_row($cmpDetails);

$qry = odbc_exec($MSSQL_CONN, " 
		SELECT T0.DocEntry,
					T0.DocNum,
					T0.Comments,
					T0.DocDate,
					T0.Ref2,
					T0.DocStatus 
		FROM [OIGE] T0
		WHERE T0.DocEntry != ''
		".$APVDateRange."
		".$APVRange."
		ORDER BY T0.DocEntry DESC");
		
		$no = 1;
		
		$DateRange = $HeaderTitle;
		
		$TitleHeader = ""."\t"."\t"."\t"."Goods Issue LIST"."\t";
		$DateHeader = ""."\t"."\t"."\t".$DateRange."\t".""."\t"."\t"."Date Printed: ".date("m/d/Y h:i A")."\t";
								
		$columnHeader = "G.I. Doc No."."\t".
						"Date"."\t".
						"Ref. No."."\t".
						"Remarks"."\t";
							
		if(odbc_num_rows($qry) == 0)
		{
			
			$htmldetails .= '<tr>
											<td colspan="8" align="left">No records found.</td>
										</tr>';	
		}
			
		while (odbc_fetch_row($qry)) 
			{
				$setData .= trim(utf8_encode(odbc_result($qry, 'DocNum')))."\t".
									trim(date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))))."\t".
									trim(utf8_encode(odbc_result($qry, 'Ref2')))."\t".
									trim(utf8_encode(odbc_result($qry, 'Comments')))."\t".
					"\n";
				$no++;
			} 
			
			$setData .= trim('')."\t".
										trim('')."\t".
										trim('')."\t".
										trim('')."\t"."\n";

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Goods Issue List_".$date.".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($TitleHeader)."\n". $DateHeader."\n".ucwords($columnHeader)."\n".$setData."\n";

?>