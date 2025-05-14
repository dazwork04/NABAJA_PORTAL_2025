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
$TotalServed = 0;
$TotalUnserved = 0;
$POTotal = 0;

$cmpDetails = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT UPPER(T0.CompnyName) as CompnyName, 
																			T0.CompnyAddr, 
																			T1.Street, 
																			T1.City
																	FROM OADM T0, ADM1 T1");
odbc_fetch_row($cmpDetails);

$qry = odbc_exec($MSSQL_CONN, " 
															SELECT T0.DocDate,
																	T0.CardName,
																	T0.NumAtCard,
																	T0.Comments,
																	T0.DocTotal,
																	CASE WHEN T0.DocStatus = 'O' THEN 'Open' ELSE
																		CASE WHEN T0.CANCELED = 'Y' THEN 'Canceled' ELSE 'Closed' END
																	END AS DocStatus
															FROM OPOR T0
															WHERE T0.DocEntry != '' 
															".$APVDateRange."
															".$APVRange."
															ORDER BY T0.DocDate ASC, T0.NumAtCard ASC");
		
		$no = 1;
		
		$DateRange = $HeaderTitle;
		
		$TitleHeader = ""."\t".""."\t"."PURCHASE ORDER LIST"."\t";
		$DateHeader = ""."\t".""."\t".$DateRange."\t".""."\t"."Date Printed: ".date("m/d/Y h:i A")."\t";
								
		$columnHeader = "P.O. Date"."\t".
						"Vendor Name"."\t".
						"P.O. Ref. No."."\t".
						"P.O. Remarks"."\t".
						"P.O. Total"."\t".
						"P.O. Status"."\t";
							
		if(odbc_num_rows($qry) == 0)
		{
			
			$htmldetails .= '<tr>
												<td colspan="15" align="left">No records found.</td>
											</tr>';	
		}
			
		while (odbc_fetch_row($qry)) 
			{
				$setData .= trim(date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))))."\t".
										trim(utf8_encode(odbc_result($qry, 'CardName')))."\t".
										trim(odbc_result($qry, 'NumAtCard'))."\t".
										trim(odbc_result($qry, 'Comments'))."\t".
										trim(number_format(odbc_result($qry, 'DocTotal'),2))."\t".
										trim(odbc_result($qry, 'DocStatus'))."\t".					
					"\n";
				$no++;
				
				if(odbc_result($qry, 'DocStatus') == 'Closed')
				{
					$TotalServed += odbc_result($qry, 'DocTotal');
				}	

				if(odbc_result($qry, 'DocStatus') == 'Open')
				{
					$TotalUnserved += odbc_result($qry, 'DocTotal');
				}	
				
				$POTotal += odbc_result($qry, 'DocTotal');
			} 
			$setData1 .= trim('Total Served :')."\t".
									trim(number_format($TotalServed,2))."\t";
			$setData2 .= trim('Total Unserved :')."\t".
									trim(number_format($TotalUnserved,2))."\t";
			
			$setData .= trim('')."\t".
										trim('')."\t".
										trim('')."\t".
										trim('Total')."\t".
										trim(number_format($POTotal,2))."\t".
										trim('')."\t"."\n";

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Purchase Order List_".$date.".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($TitleHeader)."\n". $DateHeader."\n".$setData1."\n".$setData2."\n".ucwords($columnHeader)."\n".$setData."\n";

?>