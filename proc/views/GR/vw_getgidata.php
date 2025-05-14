<?php

include_once('../../../config/config.php');

$docentry = $_GET['docentry'];

$qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "];
		SELECT T0.DocEntry,T0.DocNum,T0.DocType
                ,T0.GroupNum,T0.Series,T2.SeriesName,'' AS BPLId,T0.TaxDate,T0.DocDueDate,T0.DocDate
                ,T0.Comments,T0.DocStatus,T0.DocTotal,T0.Ref2,T0.JrnlMemo
               
		FROM OIGE T0
		LEFT JOIN NNM1 T2
		ON T0.Series = T2.Series
		WHERE T0.DocEntry = '$docentry'
		ORDER BY T0.DocEntry");

$arr = array();

while (odbc_fetch_row($qry)) {
    $arr[] = array("DocNum" => odbc_result($qry, 'DocNum'),
        "DocType" => odbc_result($qry, 'DocType'),
        "GroupNum" => odbc_result($qry, 'GroupNum'),
        "Series" => odbc_result($qry, 'Series'),
        "SeriesName" => odbc_result($qry, 'SeriesName'),
        "BPLId" => odbc_result($qry, 'BPLId'),
        "TaxDate" => date('m/d/Y', strtotime(odbc_result($qry, 'TaxDate'))),
        "DocDueDate" => date('m/d/Y', strtotime(odbc_result($qry, 'DocDueDate'))),
        "DocDate" => date('m/d/Y', strtotime(odbc_result($qry, 'DocDate'))),
        "Ref2" => odbc_result($qry, 'Ref2'),
        "Comments" => odbc_result($qry, 'Comments'),
        "JrnlMemo" => odbc_result($qry, 'JrnlMemo'),
        "DocStatus" => odbc_result($qry, 'DocStatus')
        
    );
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);


echo json_encode($arr);
?>