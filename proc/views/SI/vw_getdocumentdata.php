<?php
include_once('../../../config/config.php');

$docentry = $_GET['docentry'];

//$_SESSION['mssqldb'] = 'HIRAM_LIVE';
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."];
		SELECT T0.DocEntry,T0.DocNum,T0.DocType,T0.CardCode,T0.CardName,T1.Name,T0.NumAtCard,T0.GroupNum,T0.Series,T2.SeriesName,'' AS BPLId,T0.TaxDate,T0.DocDueDate,T0.DocDate,T0.Comments,T0.DocStatus,T0.DiscPrcnt,
			CASE WHEN T0.DocCur = 'PHP' THEN T0.DocTotal ELSE T0.DocTotalFC END AS DocTotal,
			CASE WHEN T0.DocCur = 'PHP' THEN T0.DiscSum ELSE T0.DiscSumFC END AS DiscSum,
			CASE WHEN T0.DocCur = 'PHP' THEN T0.VatSum ELSE T0.VatSumFC END AS VatSum,
			CASE WHEN T0.DocCur = 'PHP' THEN (T0.DocTotal - T0.VatSum + T0.DiscSum + T0.DpmAmnt) 
				ELSE
				(T0.DocTotalFC - T0.VatSumFC + T0.DiscSumFC + T0.DpmAmntFC) 
			END AS TotBefDisc,
      CASE WHEN T0.DocCur = 'PHP' THEN T0.DpmAmnt ELSE T0.DpmAmntFC END AS DpmAmnt,
      CASE WHEN T0.DocCur = 'PHP' THEN T0.DpmVat ELSE T0.DpmVatFC END AS DpmVat,
		T0.SlpCode, T0.OwnerCode, T3.lastname + ', ' + T3.firstName AS employeename, T4.Currency, T0.CurSource, T0.DocCur, T0.DocRate, T0.ShipToCode, T0.PayToCode, T0.Address, T0.Address2,

    T0.U_ContrctPrice,
    T0.U_Downpaymnt,
    T0.U_MiscFee,
    T0.U_ResrvationFee,
    
    T0.U_Realty,
    T0.U_SalesCoordinator
		
		FROM OINV T0
		LEFT JOIN OCPR T1
		ON T0.CntctCode = T1.CntctCode
		LEFT JOIN NNM1 T2
		ON T0.Series = T2.Series
		LEFT JOIN OHEM T3
		ON T0.OwnerCode = T3.empID
		LEFT JOIN OCRD T4
		ON T0.CardCode = T4.CardCode
		WHERE T0.DocEntry = '$docentry'
		ORDER BY T0.DocEntry");

$arr = array();

while (odbc_fetch_row($qry)) {
	$arr[] = array("DocNum" => odbc_result($qry, 'DocNum'),
				"DocType" => odbc_result($qry, 'DocType'),
				"CardCode" => odbc_result($qry, 'CardCode'),
				"CardName" => utf8_encode(odbc_result($qry, 'CardName')),
				"Name" => utf8_encode(odbc_result($qry, 'Name')),
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
				"TotBefDisc" => number_format(odbc_result($qry, 'TotBefDisc'),2,'.',','),
				"SlpCode" => odbc_result($qry, 'SlpCode'),
				"employeename" => odbc_result($qry, 'employeename'),
				"OwnerCode" => odbc_result($qry, 'OwnerCode'),
				"Currency" => odbc_result($qry, 'Currency'),
				"CurSource" => odbc_result($qry, 'CurSource'),
				"DocCur" => odbc_result($qry, 'DocCur'),
				"DocRate" => odbc_result($qry, 'DocRate'),
				"ShipToCode" => odbc_result($qry, 'ShipToCode'),
				"PayToCode" => odbc_result($qry, 'PayToCode'),
        "DpmAmnt" => number_format(odbc_result($qry, 'DpmAmnt'),2,'.',','),
        "DpmVat" => number_format(odbc_result($qry, 'DpmVat'),2,'.',','),

        // udfs

        "U_ContrctPrice" => number_format(odbc_result($qry, 'U_ContrctPrice'),2,'.',','),
        "U_Downpaymnt" => number_format(odbc_result($qry, 'U_Downpaymnt'),2,'.',','),
        "U_MiscFee" => number_format(odbc_result($qry, 'U_MiscFee'),2,'.',','),
        "U_ResrvationFee" => number_format(odbc_result($qry, 'U_ResrvationFee'),2,'.',','),
				
				"U_Realty" => odbc_result($qry, 'U_Realty'),
				"U_SalesCoordinator" => odbc_result($qry, 'U_SalesCoordinator'),
        
				);
            
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);


echo json_encode($arr);
?>