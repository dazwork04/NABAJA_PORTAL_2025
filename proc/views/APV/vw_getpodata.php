<?php
include_once('../../../config/config.php');

$docentry = $_GET['docentry'];

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
		SELECT T0.DocEntry,T0.DocNum,T0.DocType,T0.CardCode,T0.CardName,T1.Name,T0.NumAtCard,T0.GroupNum,T0.Series,T2.SeriesName,'' AS BPLId,T0.TaxDate,T0.DocDueDate,T0.DocDate,T0.Comments,T0.DocStatus,T0.DiscPrcnt,
			CASE WHEN T0.DocCur = 'PHP' THEN T0.DocTotal ELSE T0.DocTotalFC END AS DocTotal,
			CASE WHEN T0.DocCur = 'PHP' THEN T0.DiscSum ELSE T0.DiscSumFC END AS DiscSum,
			CASE WHEN T0.DocCur = 'PHP' THEN T0.VatSum ELSE T0.VatSumFC END AS VatSum,
			CASE WHEN T0.DocCur = 'PHP' THEN (T0.DocTotal - T0.VatSum + T0.DiscSum) 
				ELSE
				(T0.DocTotalFC - T0.VatSumFC + T0.DiscSumFC) 
			END AS TotBefDisc, 
			T0.SlpCode, T0.OwnerCode, T3.lastname + ', ' + T3.firstName AS employeename, T4.Currency, T0.CurSource, T0.DocCur, T0.DocRate, T0.Address, T0.Address2, T4.ListNum,	
      T0.CtlAccount, 
      T5.AcctName AS CtrlAcctName			
		FROM OPDN T0
		LEFT JOIN OCPR T1
		ON T0.CntctCode = T1.CntctCode
		LEFT JOIN NNM1 T2
		ON T0.Series = T2.Series
		LEFT JOIN OHEM T3
		ON T0.OwnerCode = T3.empID
		LEFT JOIN OCRD T4
		ON T0.CardCode = T4.CardCode
		LEFT JOIN OACT T5 ON T0.CtlAccount = T5.AcctCode
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
				"U_GRPODeliveryDate" => date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDueDate'))),
				"Comments" => odbc_result($qry, 'Comments'),
				"DocStatus" => odbc_result($qry, 'DocStatus'),
				"DocTotal" => number_format(odbc_result($qry, 'DocTotal'),2,'.',','),
				"DiscPrcnt" => number_format(odbc_result($qry, 'DiscPrcnt'),2,'.',','),
				"DiscSum" => number_format(odbc_result($qry, 'DiscSum'),2,'.',','),
				"VatSum" => number_format(odbc_result($qry, 'VatSum'),2,'.',','),
				"TotBefDisc" => number_format(odbc_result($qry, 'TotBefDisc'),2,'.',','),
				"SlpCode" => odbc_result($qry, 'SlpCode'),
				"employeename" => odbc_result($qry, 'employeename'),
				"OwnerCode" => odbc_result($qry, 'OwnerCode'),
				"Currency" => odbc_result($qry, 'Currency'),
				"CurSource" => odbc_result($qry, 'CurSource'),
				"DocCur" => odbc_result($qry, 'DocCur'),
				"DocRate" => odbc_result($qry, 'DocRate'),
				"Address" => odbc_result($qry, 'Address'),
				"Address2" => odbc_result($qry, 'Address2'),
				"ListNum" => odbc_result($qry, 'ListNum'),
				"CtlAccount" => odbc_result($qry, 'CtlAccount'),
				"CtrlAcctName" => utf8_encode(odbc_result($qry, 'CtrlAcctName'))
				);
            
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);


echo json_encode($arr);
?>