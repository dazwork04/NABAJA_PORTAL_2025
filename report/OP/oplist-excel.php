<?php
session_start();
include_once('../../config/config.php');

date_default_timezone_set('Asia/Manila');

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$txtDateFrom = $_GET['txtDateFrom'];
$txtDateTo = $_GET['txtDateTo'];
$txtDisbursementListFrom = $_GET['txtDisbursementListFrom'];
$txtDisbursementListTo = $_GET['txtDisbursementListTo'];

if($txtDisbursementListFrom != '' && $txtDisbursementListTo != '')
{
	$APVRange = " AND T2.CounterRef BETWEEN '$txtDisbursementListFrom' AND '$txtDisbursementListTo'";
	$APVDateRange = "";
	$HeaderTitle = $txtDisbursementListFrom . ' to ' . $txtDisbursementListTo;
}
else
{
	if($txtDateFrom != '' && $txtDateTo != '')
	{
		$APVDateRange = " AND T2.DocDate BETWEEN '$txtDateFrom' AND '$txtDateTo'";
		
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
$Debit = 0;
$Credit = 0;

$cmpDetails = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT UPPER(T0.CompnyName) as CompnyName, 
																			T0.CompnyAddr, 
																			T1.Street, 
																			T1.City
																	FROM OADM T0, ADM1 T1");
odbc_fetch_row($cmpDetails);

$qry = odbc_exec($MSSQL_CONN, " 
						SELECT T2.DocEntry,
							T2.DocDate, 
							T2.CardName, 
							T2.CounterRef, 
							T0.Account,
							T4.AcctName, 
							T0.Debit, 
							T0.Credit, 
							T3.Descrip, 
							T2.PrjCode, 
							T5.PrjName,
							CASE WHEN T2.CANCELED = 'Y' 
									THEN 'Cancelled'
								ELSE 'Closed' 
								END AS DocStatus
						FROM JDT1 T0
						LEFT JOIN OJDT T1 ON T0.TransId = T1.TransId
						LEFT JOIN OVPM T2 ON T0.TransId = T2.TransId
						LEFT JOIN VPM4 T3 ON T2.DocEntry = T3.DocNum AND T0.Account = T3.AcctCode AND (T0.BalDueDeb = T3.SumApplied OR T0.BalDueCred = T3.SumApplied)
						LEFT JOIN OACT T4 ON T0.Account = T4.AcctCode
						LEFT JOIN OPRJ T5 ON T2.PrjCode = T5.PrjCode
						WHERE (T0.Debit != 0 OR T0.Credit != 0)
						".$APVDateRange."
						".$APVRange."
						ORDER BY T2.CounterRef ASC, ISNULL(T3.LineId, 999) ASC");
		
		$no = 1;
		
		$DateRange = $HeaderTitle;
		
		$TitleHeader = ""."\t"."\t"."\t"."Disbursement LIST"."\t";
		$DateHeader = ""."\t"."\t"."\t".$DateRange."\t".""."\t"."\t"."Date Printed: ".date("m/d/Y h:i A")."\t";
								
		$columnHeader = "Date"."\t".
						"Vendor Name"."\t".
						"Ref. No."."\t".
						"Account Description"."\t".
						"Line Description"."\t".
						"Debit Amount"."\t".
						"Credit Amount"."\t".
						"Job Id"."\t".
						"Status"."\t";
							
		if(odbc_num_rows($qry) == 0)
		{
			
			$htmldetails .= '<tr>
								<td colspan="7" align="left">No records found.</td>
							</tr>';	
		}
			
		while (odbc_fetch_row($qry)) 
			{
				$setData .= trim(date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))))."\t".
										trim(utf8_encode(odbc_result($qry, 'CardName')))."\t".
										trim(odbc_result($qry, 'CounterRef'))."\t".
										trim(odbc_result($qry, 'AcctName'))."\t".
										trim(odbc_result($qry, 'Descrip'))."\t".
										trim(number_format(odbc_result($qry, 'Debit'),2))."\t".
										trim(number_format(odbc_result($qry, 'Credit'),2))."\t".
										trim(odbc_result($qry, 'PrjCode'),2). '-' . odbc_result($qry, 'PrjName') ."\t".
										trim(odbc_result($qry, 'DocStatus'))."\t".
					"\n";
				$no++;
				
				$Debit += odbc_result($qry, 'Debit');
				$Credit += odbc_result($qry, 'Credit');
			} 
			
			$setData .= trim('')."\t".
										trim('')."\t".
										trim('')."\t".
										trim('')."\t".
										trim('')."\t".
										trim('')."\t".
										trim(number_format($Debit,2))."\t".
										trim(number_format($Credit,2))."\t".
										trim('')."\t".
										trim('')."\t"."\n";

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Disbursement List_".$date.".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($TitleHeader)."\n". $DateHeader."\n".ucwords($columnHeader)."\n".$setData."\n";

?>