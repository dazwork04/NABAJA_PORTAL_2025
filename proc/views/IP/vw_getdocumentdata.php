<?php
include_once('../../../config/config.php');

$docentry = $_GET['docentry'];

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."];
		SELECT T0.DocEntry, 
					T0.DocNum, 
					CASE WHEN T0.DocType = 'A' THEN 'Account' ELSE 'Vendor' END AS DocType,
					T0.Canceled,
					CONVERT(VARCHAR(10),T0.DocDate,101) AS DocDate,
					CONVERT(VARCHAR(10),T0.DocDueDate,101) AS DocDueDate,
					T0.CardCode, 
					T0.CardName, 
					T0.Address,
					T0.CntctCode, 
					CONVERT(VARCHAR(10),T0.TaxDate,101) AS TaxDate, 
					T0.CounterRef,
					T0.JrnlMemo, 
					T0.Comments, 
					T0.CashSum,
					T0.CashAcct, 
					T0.TrsfrSum, 
					T0.TrsfrDate,
					T0.TrsfrRef, 
					T0.TrsfrAcct, 
					T0.CheckAcct, 
					T0.CreditSum AS CreditSumT0,
					T1.[CheckSum], 
					T1.CheckNum, 
					CONVERT(VARCHAR(10),T1.DueDate,101) AS CheckDueDate,
					T1.Branch, 
					T1.AcctNum, 
					T1.BankCode,
					T0.NoDocSum,
					T1.CountryCod, 
					T0.DocTotal, 
					T0.OpenBal,
					T2.CreditCard, 
					T2.CrCardNum, 
					T2.CreditAcct, 
					T2.CardValid,
					T2.VoucherNum, 
					T2.OwnerIdNum, 
					T2.OwnerPhone,
					T2.CrTypeCode, 
					T2.CreditSum AS CreditSumT2, 
					T2.FirstSum,
					T3.PrjName
                FROM ORCT T0
				LEFT JOIN OPRJ T3 ON T0.PrjCode = T3.PrjCode
                OUTER APPLY
                (
                SELECT TOP 1 * FROM RCT1 T1
                WHERE T0.DocNum = T1.DocNum
                ) T1
				OUTER APPLY
                (
                SELECT TOP 1 * FROM RCT3 T2
                WHERE T0.DocNum = T2.DocNum
                ) T2
		WHERE T0.DocEntry = '$docentry'
		ORDER BY T0.DocDate DESC, T0.DocEntry");

$arr = array();

while (odbc_fetch_row($qry)) {
	$arr[] = array("DocNum" => odbc_result($qry, 'DocNum'),
				"DocType" => odbc_result($qry, 'DocType'),
				"CardCode" => odbc_result($qry, 'CardCode'),
				"CardName" => odbc_result($qry, 'CardName'),
				"Address" => utf8_encode(odbc_result($qry, 'Address')),
				"CntctCode" => odbc_result($qry, 'CntctCode'),
				"TaxDate" => date('m/d/Y' ,strtotime(odbc_result($qry, 'TaxDate'))),
				"DocDueDate" => date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDueDate'))),
				"DocDate" => date('m/d/Y' ,strtotime(odbc_result($qry, 'DocDate'))),
				"CounterRef" => odbc_result($qry, 'CounterRef'),
            
				"CashAcct" => odbc_result($qry, 'CashAcct'),
				"CashSum" => number_format(odbc_result($qry, 'CashSum'),2,'.',','),
            
				"TrsfrAcct" => odbc_result($qry, 'TrsfrAcct'),
				"TrsfrSum" => number_format(odbc_result($qry, 'TrsfrSum'),2,'.',','),
				"TrsfrDate" => date('m/d/Y' ,strtotime(odbc_result($qry, 'TrsfrDate'))),
				"TrsfrRef" => odbc_result($qry, 'TrsfrRef'),
            
				"CheckAcct" => odbc_result($qry, 'CheckAcct'),
				"CheckDueDate" => date('m/d/Y' ,strtotime(odbc_result($qry, 'CheckDueDate'))),
				"CountryCod" => odbc_result($qry, 'CountryCod'),
				"BankCode" => odbc_result($qry, 'BankCode'),
				"Branch" => odbc_result($qry, 'Branch'),
				"AcctNum" => odbc_result($qry, 'AcctNum'),
				"CheckNum" => odbc_result($qry, 'CheckNum'),
				"CheckSum" => number_format(odbc_result($qry, 'CheckSum'),2,'.',','),
				
				"creditcard" => odbc_result($qry, 'CreditCard'),
				"cardno" => '**********',
				"creditaccount" => odbc_result($qry, 'CreditAcct'),
				"cardvalid" => date('m/d/Y' ,strtotime(odbc_result($qry, 'CardValid'))),
				"voucherno" => odbc_result($qry, 'VoucherNum'),
				"owneridno" => odbc_result($qry, 'OwnerIdNum'),
				"ownerphone" => odbc_result($qry, 'OwnerPhone'),
				"paymentmethod" => odbc_result($qry, 'CrTypeCode'),
				"creditsum" => odbc_result($qry, 'CreditSumT0'),
				"creditsumt2" => number_format(odbc_result($qry, 'CreditSumT2'),2),
				"firstpayment" => number_format(odbc_result($qry, 'FirstSum'),2),
				
				"Comments" => odbc_result($qry, 'Comments'),
				"JrnlMemo" => odbc_result($qry, 'JrnlMemo'),
				"Canceled" => odbc_result($qry, 'Canceled'),
				"PrjName" => odbc_result($qry, 'PrjName'),
            
				"NoDocSum" => number_format(odbc_result($qry, 'NoDocSum'),2,'.',','),
				"DocTotal" => number_format(odbc_result($qry, 'DocTotal'),2,'.',','),
				"OpenBal" => number_format(odbc_result($qry, 'OpenBal'),2,'.',','),
				);
            
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);


echo json_encode($arr);
?>