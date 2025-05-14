<?php
session_start();
include_once('../../config/config.php');

date_default_timezone_set('Asia/Manila');

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$txtDateFrom = $_GET['txtDateFrom'];
$txtDateTo = $_GET['txtDateTo'];
$txtAPVListFrom = $_GET['txtAPVListFrom'];
$txtAPVListTo = $_GET['txtAPVListTo'];

if($txtAPVListFrom != '' && $txtAPVListTo != '')
{
	$APVRange = " AND T0.NumAtCard BETWEEN '$txtAPVListFrom' AND '$txtAPVListTo'";
	$APVDateRange = "";
	$HeaderTitle = $txtAPVListFrom . ' to ' . $txtAPVListTo;
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
		SELECT T0.DocDate,
						T0.CardCode,
						T0.CardName,
						T0.NumAtCard,
						CASE WHEN T0.CANCELED IN ('C','Y') THEN -1 * T0.DocTotal ELSE T0.DocTotal END AS DocTotal,
						T0.Comments,
						CASE WHEN T0.U_SiDr IS NULL THEN T0.Comments ELSE T0.U_SiDr
						END AS CustInvNo,
						T0.DocTotal - T0.PaidToDate AS NetDue,
						CASE WHEN T0.PaidToDate = 0 THEN 'Unpaid' 
						WHEN T0.PaidToDate = T0.DocTotal THEN 'Paid'
						ELSE 'Partial' END AS DocStatus,
						CASE WHEN T0.DocStatus = 'O' 
							THEN 'Open' 
						ELSE CASE WHEN T0.CANCELED = 'Y' 
							THEN 'Cancelled'
						ELSE 'Closed' END
						END AS DocStatus1
		FROM OPCH T0
		WHERE T0.DocEntry != ''
		".$APVDateRange."
		".$APVRange."
		ORDER BY T0.NumAtCard ASC");
		
		$no = 1;
		
		$DateRange = $HeaderTitle;
		
		$TitleHeader = ""."\t"."\t"."\t"."AP Invoice LIST"."\t";
		$DateHeader = ""."\t"."\t"."\t".$DateRange."\t".""."\t"."\t"."Date Printed: ".date("m/d/Y h:i A")."\t";
								
		$columnHeader = "A.P. Date"."\t".
						"Vendor Code"."\t".
						"Vendor Name"."\t".
						"A.P. Ref. No."."\t".
						"Cust. Inv. No."."\t".
						"Net Due"."\t".
						"A.P. Total"."\t".
						"A.P. Status"."\t";
							
		if(odbc_num_rows($qry) == 0)
		{
			
			$htmldetails .= '<tr>
											<td colspan="8" align="left">No records found.</td>
										</tr>';	
		}
			
		while (odbc_fetch_row($qry)) 
			{
				$setData .= trim(date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))))."\t".
										trim(utf8_encode(odbc_result($qry, 'CardCode')))."\t".
										trim(utf8_encode(odbc_result($qry, 'CardName')))."\t".
										trim(odbc_result($qry, 'NumAtCard'))."\t".
										trim(str_replace(array("\r", "\n"), '', odbc_result($qry, 'CustInvNo')))."\t".
										trim(number_format(odbc_result($qry, 'NetDue'),2))."\t".
										trim(number_format(odbc_result($qry, 'DocTotal'),2))."\t".
										trim(odbc_result($qry, 'DocStatus1') . '/' . odbc_result($qry, 'DocStatus'))."\t".
					"\n";
				$no++;
				
				$TotalNetDue += odbc_result($qry, 'NetDue');
				
				$POTotal += odbc_result($qry, 'DocTotal');
			} 
			
			$setData .= trim('')."\t".
										trim('')."\t".
										trim('')."\t".
										trim('')."\t".
										trim('Total')."\t".
										trim(number_format($TotalNetDue,2))."\t".
										trim(number_format($POTotal,2))."\t".
										trim('')."\t"."\n";

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=AP Invoice List_".$date.".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($TitleHeader)."\n". $DateHeader."\n".ucwords($columnHeader)."\n".$setData."\n";

?>