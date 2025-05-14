<?php
include_once('../../../config/config.php');

$docentry = $_GET['docentry'];
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
		SELECT T0.DocEntry,T0.DocNum,T0.DocType,T0.CardCode,T0.CardName,T1.Name,T0.NumAtCard,T0.GroupNum,T0.Series,T2.SeriesName,'' AS BPLId,T0.TaxDate,T0.DocDueDate,T0.DocDate,T0.Comments,T0.DocStatus,T0.DocTotal,T0.DiscPrcnt,T0.DiscSum,T0.VatSum,(T0.DocTotal - T0.VatSum + T0.DiscSum) AS TotBefDisc 
		FROM OPOR T0
		LEFT JOIN OCPR T1
		ON T0.CntctCode = T1.CntctCode
		LEFT JOIN NNM1 T2
		ON T0.Series = T2.Series
		WHERE T0.DocEntry = '$docentry'
		ORDER BY T0.DocEntry");

$arr = array();

while (odbc_fetch_row($qry)) {
	$arr[] = array("DocNum" => odbc_result($qry, 'DocNum'),
				"DocType" => odbc_result($qry, 'DocType'),
				"CardCode" => odbc_result($qry, 'CardCode'),
				"CardName" => odbc_result($qry, 'CardName'),
				"Name" => odbc_result($qry, 'Name'),
				"NumAtCard" => odbc_result($qry, 'NumAtCard'),
				"GroupNum" => odbc_result($qry, 'GroupNum'),
				"Series" => odbc_result($qry, 'Series'),
				"SeriesName" => odbc_result($qry, 'SeriesName'),
				"BPLId" => odbc_result($qry, 'BPLId'),
				"TaxDate" => date('m/d/Y' ,strtotime(odbc_result($qry, 'TaxDate'))),
				"DocDueDate" => date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDueDate'))),
				"DocDate" => date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))),
				"Comments" => odbc_result($qry, 'Comments'),
				"DocStatus" => odbc_result($qry, 'DocStatus'),
				"DocTotal" => number_format(odbc_result($qry, 'DocTotal'),2,'.',','),
				"DiscPrcnt" => number_format(odbc_result($qry, 'DiscPrcnt'),2,'.',','),
				"DiscSum" => number_format(odbc_result($qry, 'DiscSum'),2,'.',','),
				"VatSum" => number_format(odbc_result($qry, 'VatSum'),2,'.',','),
				"TotBefDisc" => number_format(odbc_result($qry, 'TotBefDisc'),2,'.',',')
				);
            
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);


echo json_encode($arr);
?>