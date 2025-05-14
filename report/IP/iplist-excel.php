<?php
session_start();
include_once('../../config/config.php');

date_default_timezone_set('Asia/Manila');

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$txtDateFrom = $_GET['txtDateFrom'];
$txtDateTo = $_GET['txtDateTo'];
$txtReceiptListFrom = $_GET['txtReceiptListFrom'];
$txtReceiptListTo = $_GET['txtReceiptListTo'];

if($txtReceiptListFrom != '' && $txtReceiptListTo != '')
{
	$APVRange = " AND T0.CounterRef BETWEEN '$txtReceiptListFrom' AND '$txtReceiptListTo'";
	$APVDateRange = "";
	$HeaderTitle = $txtReceiptListFrom . ' to ' . $txtReceiptListTo;
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
		SELECT  T0.DocEntry, 
						T0.DocNum, 
						T0.DocDate, 
						T0.CardCode, 
						T0.CardName, 
						T0.CounterRef, 
						T0.DocTotal, 
						T4.AcctCode AS CashAccount,
						T5.AcctCode AS TransferAccount,
						T6.AcctCode AS CheckAccount,
						T7.AcctCode AS CreditCardAccount,
						T0.JrnlMemo,
						T1.CheckNum,
						CASE WHEN T0.CANCELED = 'Y' 
							THEN 'Cancelled'
						ELSE 'Closed' 
						END AS DocStatus
			FROM ORCT T0
			LEFT JOIN OACT T4 ON T0.CashAcct =T4.AcctCode AND T4.Finanse = 'Y'
			LEFT JOIN OACT T5 ON T0.TrsfrAcct =T5.AcctCode AND T5.Finanse = 'Y'
			OUTER APPLY
			(
				SELECT TOP 1 * 
				FROM RCT1 T1
				WHERE T1.DocNum = T0.DocEntry
			) T1
			OUTER APPLY
			(
					SELECT TOP 1 * 
					FROM RCT3 T2
				WHERE T2.DocNum = T0.DocEntry
			) T2
			LEFT JOIN OACT T6 ON T1.CheckAct =T6.AcctCode AND T6.Finanse = 'Y'
			LEFT JOIN OACT T7 ON T2.CreditAcct =T7.AcctCode AND T7.Finanse = 'Y'
		WHERE T0.DocEntry != '' 
		".$APVDateRange."
		".$APVRange."
		ORDER BY T0.CounterRef ASC");
		
		$no = 1;
		
		$DateRange = $HeaderTitle;
		
		$TitleHeader = ""."\t"."\t"."\t"."REEIPT LIST"."\t";
		$DateHeader = ""."\t"."\t"."\t".$DateRange."\t".""."\t"."\t"."Date Printed: ".date("m/d/Y h:i A")."\t";
								
		$columnHeader = "Date"."\t".
						"Vendor Code"."\t".
						"Vendor Name"."\t".
						"Check No."."\t".
						"Ref. No."."\t".
						"Amount"."\t".
						"Account"."\t".
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
										trim(utf8_encode(odbc_result($qry, 'CardCode')))."\t".
										trim(utf8_encode(odbc_result($qry, 'CardName')))."\t".
										trim(odbc_result($qry, 'CheckNum'))."\t".
										trim(odbc_result($qry, 'CounterRef'))."\t".
										trim(number_format(odbc_result($qry, 'DocTotal'),2))."\t".
										trim(odbc_result($qry, 'CheckAccount'),2)."\t".
										trim(odbc_result($qry, 'DocStatus'),2)."\t".
					"\n";
				$no++;
				
				$POTotal += odbc_result($qry, 'DocTotal');
			} 
			
			$setData .= trim('')."\t".
										trim('')."\t".
										trim('')."\t".
										trim('')."\t".
										trim('Total')."\t".
										trim(number_format($POTotal,2))."\t".
										trim('')."\t".
										trim('')."\t".
										"\n";

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Receipt List_".$date.".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($TitleHeader)."\n". $DateHeader."\n".ucwords($columnHeader)."\n".$setData."\n";

?>