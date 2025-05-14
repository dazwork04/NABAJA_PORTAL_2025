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
	$APVRange = " AND T0.Ref1 BETWEEN '$txtRefListFrom' AND '$txtRefListTo'";
	$APVDateRange = "";
	$HeaderTitle = $txtRefListFrom . ' to ' . $txtRefListTo;
}
else
{
	if($txtDateFrom != '' && $txtDateTo != '')
	{
		$APVDateRange = " AND T0.RefDate BETWEEN '$txtDateFrom' AND '$txtDateTo'";
		
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
		SELECT T0.TransId, 
				T0.RefDate, 
				T0.Ref1, 
				T0.Memo,
				T0.LocTotal
		FROM OJDT T0 
		WHERE T0.TransType = 30
		".$APVDateRange."
		".$APVRange."
		ORDER BY T0.Ref1 ASC");
		
		$no = 1;
		
		$DateRange = $HeaderTitle;
		
		$TitleHeader = ""."\t"."\t"."\t"."Journal Entry List"."\t";
		$DateHeader = ""."\t"."\t"."\t".$DateRange."\t".""."\t"."\t"."Date Printed: ".date("m/d/Y h:i A")."\t";
								
		$columnHeader = "Ref. No."."\t".
						"Date"."\t".
						"Debit"."\t".
						"Credit"."\t".
						"Remarks"."\t";
							
		if(odbc_num_rows($qry) == 0)
		{
			
			$htmldetails .= '<tr>
											<td colspan="8" align="left">No records found.</td>
										</tr>';	
		}
			
		while (odbc_fetch_row($qry)) 
			{
				$setData .= trim(utf8_encode(odbc_result($qry, 'Ref1')))."\t".
									trim(date('m/d/Y' ,strtotime(odbc_result($qry, 'RefDate'))))."\t".
									trim(number_format(odbc_result($qry, 'LocTotal'),2))."\t".
									trim(number_format(odbc_result($qry, 'LocTotal'),2))."\t".
									trim(utf8_encode(odbc_result($qry, 'Memo')))."\t".
					"\n";
				$no++;
			} 
			
			$setData .= trim('')."\t".
										trim('')."\t".
										trim('')."\t".
										trim('')."\t"."\n";

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Journal Entry List_".$date.".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($TitleHeader)."\n". $DateHeader."\n".ucwords($columnHeader)."\n".$setData."\n";

?>