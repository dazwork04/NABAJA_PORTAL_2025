<?php
session_start();
include_once('../../config/config.php');

date_default_timezone_set('Asia/Manila');

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$txtDateFrom = $_GET['txtDateFrom'];
$txtDateTo = $_GET['txtDateTo'];
$txtAPDPListFrom = $_GET['txtAPDPListFrom'];
$txtAPDPListTo = $_GET['txtAPDPListTo'];

if($txtAPDPListFrom != '' && $txtAPDPListTo != '')
{
	$APDPRange = " AND T0.NumAtCard BETWEEN '$txtAPDPListFrom' AND '$txtAPDPListTo'";
	$APDPDateRange = "";
	$HeaderTitle = $txtAPDPListFrom . ' to ' . $txtAPDPListTo;
}
else
{
	if($txtDateFrom != '' && $txtDateTo != '')
	{
		$APDPDateRange = " AND T0.DocDate BETWEEN '$txtDateFrom' AND '$txtDateTo'";
		
		$HeaderTitle = date('m/d/Y' ,strtotime($txtDateFrom)) . ' to ' . date('m/d/Y' ,strtotime($txtDateTo));
	}
	else
	{
		$APDPDateRange = "";
		$HeaderTitle = "";
	}
	
	$APDPRange = "";
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
						T0.DocTotal,
						T0.Comments,
						CASE WHEN T0.U_SiDr IS NULL THEN T0.Comments ELSE T0.U_SiDr
						END AS CustInvNo,
						T0.DocTotal - T0.PaidToDate AS NetDue,
						CASE WHEN T0.PaidToDate = 0 THEN 'Unpaid' 
						WHEN T0.PaidToDate = T0.DocTotal THEN 'Paid'
						ELSE 'Partial' END AS DocStatus
		FROM ODPO T0
		WHERE T0.DocEntry != ''
		".$APDPDateRange."
		".$APDPRange."
		ORDER BY T0.NumAtCard ASC");
		
		$no = 1;
		
		$DateRange = $HeaderTitle;
		
		$TitleHeader = ""."\t"."\t"."\t"."AP Down Payment LIST"."\t";
		$DateHeader = ""."\t"."\t"."\t".$DateRange."\t".""."\t"."\t"."Date Printed: ".date("m/d/Y h:i A")."\t";
								
		$columnHeader = "A.P.D.P. Date"."\t".
						"Vendor Code"."\t".
						"Vendor Name"."\t".
						"A.P.D.P. Ref. No."."\t".
						"Cust. Inv. No."."\t".
						"Net Due"."\t".
						"A.P.D.P. Total"."\t".
						"A.P.D.P. Status"."\t";
							
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
										trim(odbc_result($qry, 'CustInvNo'))."\t".
										trim(number_format(odbc_result($qry, 'NetDue'),2))."\t".
										trim(number_format(odbc_result($qry, 'DocTotal'),2))."\t".
										trim(odbc_result($qry, 'DocStatus'),2)."\t".
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
header("Content-Disposition: attachment; filename=AP Down Payment List_".$date.".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($TitleHeader)."\n". $DateHeader."\n".ucwords($columnHeader)."\n".$setData."\n";

?>