<?php

include_once('../../../config/config.php');

$docentry = $_GET['docentry'];

$qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "];
		SELECT T0.DocEntry,T0.DocNum,T0.DocType,T0.CardCode,T0.CardName
                , T0.CntctCode, T1.Name, T0.[Address], T0.DocDate, T0.DocDueDate
                , T0.TaxDate, T0.Filler, T2.WhsName AS [FromWarehouseName], T0.ToWhsCode, T3.WhsName AS [ToWarehouseName]
                , T0.GroupNum, T0.SlpCode, T0.JrnlMemo, T0.PickRmrk, T0.Comments
                , T0.Series, T4.SeriesName, T0.DocStatus
                FROM OWTR T0
                LEFT JOIN OCPR T1
                ON T0.CntctCode = T1.CntctCode
                LEFT JOIN OWHS T2
                ON T0.Filler = T2.WhsCode
                LEFT JOIN OWHS T3
                ON T0.ToWhsCode = T3.WhsCode
                LEFT JOIN NNM1 T4
                ON T0.Series = T4.Series
		WHERE T0.DocEntry = '$docentry'
		ORDER BY T0.DocEntry");

$arr = array();

while (odbc_fetch_row($qry)) {
    $arr[] = array("DocNum" => odbc_result($qry, 'DocNum'),
        "DocStatus" => odbc_result($qry, 'DocStatus'),
        "DocType" => odbc_result($qry, 'DocType'),
        "CardCode" => odbc_result($qry, 'CardCode'),
        "CardName" => odbc_result($qry, 'CardName'),
        "CntctCode" => odbc_result($qry, 'CntctCode'),
        "Name" => odbc_result($qry, 'Name'),
        "Address" => odbc_result($qry, 'Address'),
        "DocDate" => date('m/d/Y', strtotime(odbc_result($qry, 'DocDate'))),
        "DocDueDate" => date('m/d/Y', strtotime(odbc_result($qry, 'DocDueDate'))),
        "TaxDate" => date('m/d/Y', strtotime(odbc_result($qry, 'TaxDate'))),
        "GroupNum" => odbc_result($qry, 'GroupNum'),
        "Filler" => odbc_result($qry, 'Filler'),
        "ToWhsCode" => odbc_result($qry, 'ToWhsCode'),
        "FromWarehouseName" => odbc_result($qry, 'FromWarehouseName'),
        "ToWarehouseName" => odbc_result($qry, 'ToWarehouseName'),
        "SlpCode" => odbc_result($qry, 'SlpCode'),
        "JrnlMemo" => odbc_result($qry, 'JrnlMemo'),
        "PickRmrk" => odbc_result($qry, 'PickRmrk'),
        "Comments" => odbc_result($qry, 'Comments'),
        "Series" => odbc_result($qry, 'Series'),
        "SeriesName" => odbc_result($qry, 'SeriesName'),
    );
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);


echo json_encode($arr);
?>