<?php
include_once('../../../config/config.php');
include_once('../../../sbo-common/Common.php');

$taxcode2 = '';

$qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "]; SELECT Code,Name,Rate FROM OVTG WHERE Inactive = 'N' AND Category='I'");

while (odbc_fetch_row($qry)) {
    $taxcode2 .= '<option val' . odbc_result($qry, "Code") . ' val-rate="' . number_format(odbc_result($qry, "Rate"), 4, '.', '.') . '" value="' . odbc_result($qry, "Code") . '">' . odbc_result($qry, "Code") . ' - ' . utf8_encode(odbc_result($qry, "Name")) . '</option>';
}

$docentry = $_GET['docentry'];
$qry = odbc_exec($MSSQL_CONN, " 
			SELECT T0.DocType,'N' AS LineType,T1.ItemCode,T1.Dscription,T2.InvntryUom
                                ,(T1.Quantity) AS Quantity,T2.InvntryUom,T1.StockPrice,T1.WhsCode
                                ,T1.VatGroup,T1.DiscPrcnt,T1.PriceAfVAT,T1.VatSum,T1.LineTotal
                                ,T1.GTotal,'' AS LineText,T3.AcctCode,T3.FormatCode,T3.AcctName
                                ,T1.LineNum,T1.LineStatus,'A' AS SubSeq,T1.LineNum AS AftLineNum
                                , T1.CodeBars AS BarCode, T1.OcrCode AS OcrCode
                                , T1.OcrCode5 AS OcrCode5
				FROM [" . $_SESSION['mssqldb'] . "].[dbo].[OIGE] T0
				INNER JOIN [" . $_SESSION['mssqldb'] . "].[dbo].IGE1 T1
				ON T0.DocEntry = T1.DocEntry
				LEFT JOIN [" . $_SESSION['mssqldb'] . "].[dbo].OITM T2
				ON T1.ItemCode = T2.ItemCode
				LEFT JOIN [" . $_SESSION['mssqldb'] . "].[dbo].[OACT] T3
				ON T1.AcctCode = T3.AcctCode
				WHERE T0.DocEntry ='$docentry' AND T1.LineStatus != 'C'
			UNION ALL
			SELECT T0.DocType,'Y' AS LineType,'' AS ItemCode,'' AS Dscription,'' AS InvntryUom
                        ,0 AS Quantity,'' AS InvntryUom,0 AS PriceBefDi,'' AS WhsCode,'' AS VatGroup
                        ,0 AS DiscPrcnt,0 AS PriceAfVAT,0 AS VatSum,0 AS LineTotal,0 AS GTotal,T1.LineText
                        ,'' AS AcctCode,'' AS FormatCode,'' AS AcctName,T1.LineSeq AS LineNum,'' AS LineStatus
                        ,'B' AS SubSeq,T1.AftLineNum, '' AS BarCode, '' AS OcrCode
                        , '' AS OcrCode5
				FROM [" . $_SESSION['mssqldb'] . "].[dbo].OIGE T0
				INNER JOIN [" . $_SESSION['mssqldb'] . "].[dbo].IGE10 T1
				ON T0.DocEntry = T1.DocEntry
				
				WHERE T0.DocEntry ='$docentry'
				ORDER BY AftLineNum,SubSeq");
$ctr = 1;

while (odbc_fetch_row($qry)) {
    $ItemCode = odbc_result($qry, "ItemCode");
    $ItemName = odbc_result($qry, "Dscription");
    $InvntryUom = odbc_result($qry, "InvntryUom");
    $Quantity = number_format(odbc_result($qry, "Quantity"), 2, '.', ',');
    $Price = number_format(odbc_result($qry, "StockPrice"), 2, '.', ',');
    $Whse = odbc_result($qry, "WhsCode");
    $TaxCode = odbc_result($qry, "VatGroup");
    $Discount = number_format(odbc_result($qry, "DiscPrcnt"), 0, '.', ',');
    $GrossPrice = number_format(odbc_result($qry, "PriceAfVAT"), 2, '.', ',');
    $TaxAmt = number_format(odbc_result($qry, "VatSum"), 2, '.', ',');
    $GrossTotal = number_format(odbc_result($qry, "GTotal"), 2, '.', ',');
    $LineNum = odbc_result($qry, "LineNum");
    $LineStatus = odbc_result($qry, "LineStatus");
    $Ftext = odbc_result($qry, "LineType");
    $FtextRemarks = odbc_result($qry, "LineText");
	$LineTotal = odbc_result($qry, "Quantity") * odbc_result($qry, "StockPrice");

    $ServiceRemarks = odbc_result($qry, "Dscription");
    $Account = odbc_result($qry, "AcctCode");
    $ServiceType = odbc_result($qry, "DocType");
    $FormatCode = odbc_result($qry, "FormatCode");
    $AcctName = odbc_result($qry, "AcctName");

    $taxcode = str_replace('val' . $TaxCode, 'selected', $taxcode2);

    $disabled = 'readonly';
    if ($LineStatus == 'C') {
        $disabled = 'readonly';
    }
	
	if ($ServiceType == 'I') {
        if ($Ftext == 'N') {
            echo '
				<tr>
					<td style="padding-top: 2px;  padding-bottom: 2px;"  class="rowno text-center">
						<a class="invdata" data-toggle="modal" data-target="#InvDataModal"><span class="glyphicon glyphicon-arrow-right"></span></a> ' . $ctr . '
					</td>
					<td style="padding-top: 2px;  padding-bottom: 2px;" >
                                                <input ' . $disabled . ' class="form-control input-sm itemcode required" value="' . $ItemCode . '" />
					</td>
					<td><input ' . $disabled . ' class="form-control input-sm itemname" value="' . $ItemName . '" /></td>
					
					<td><input class="form-control input-sm qty required numericvalidate" value="' . $Quantity . '"></td>

					<td class="hidden"><input readonly class="form-control input-sm uom" value="' . $InvntryUom . '"></td>
					<td class="hidden"><input readonly class="form-control input-sm uomname" value="' . $InvntryUom . '"></td>
					<td><input class="form-control input-sm price numeric numericvalidate" value="' . $Price . '">
						</td>
					<td>
						<div class="input-group warehouseCont">
							<input ' . $disabled . ' class="form-control input-sm warehouse required" value="' . $Whse . '" />
							<span style="height: 18px; padding: 0 4px; margin: 0;" class="input-group-addon" data-toggle="modal" data-target="#WhsModal"><span class="glyphicon glyphicon-list"></span></span>
						</div>
					</td>
					<!--<td class="hidden"><select ' . $disabled . ' class="form-control input-sm taxcode">' . $taxcode . '</select></td>-->
					<td class="hidden"><input ' . $disabled . ' class="form-control input-sm discount numeric" value="' . $Discount . '"></td>
					<td class="hidden"><input ' . $disabled . ' class="form-control input-sm grossprice numeric" value="' . $GrossPrice . '"></td>
					<td class="hidden"><input readonly class="form-control input-sm taxamount numeric" value="' . $TaxAmt . '"></td>
					<td><input ' . $disabled . ' aria-acctcode="' . $Account . '" class="form-control input-sm acctcode required" value="' . $FormatCode . '" /></td>
					<td><input readonly class="form-control input-sm linetotal numeric" value="' . $LineTotal . '"></td>
					<td class="hidden"><input readonly class="form-control input-sm grosstotal numeric" value="' . $GrossTotal . '"></td>
                    
					<td class="hidden"><input readonly class="form-control input-sm lineno" value="' . $LineNum . '"></td>
					<td class="ftext text-center hidden">N</td>
					
				</tr>';
        } else {

            echo '
				<tr>
					<td class="rowno text-center">
						' . $ctr . '
					</td>
					<td colspan="12"><textarea ' . $disabled . ' class="form-control input-sm remarks">' . $FtextRemarks . '</textarea></td>
					<td class="hidden"><input readonly class="form-control input-sm lineno" value="' . $LineNum . '"></td>
					<td class="ftext text-center hidden">Y</td>
				</tr>';
        }
    } else {
        echo '
			<tr>
				<td class="rowno text-center">
					' . $ctr . '
				</td>
				<td><textarea ' . $disabled . ' class="form-control input-sm remarks required">' . $ServiceRemarks . '</textarea></td>
				<td>
					<div class="input-group acctcodeCont">
						<input ' . $disabled . ' aria-acctcode="' . $Account . '" class="form-control input-sm acctcode required" value="' . $FormatCode . '" />
						<span class="input-group-addon" data-toggle="modal" data-target="#AcctModal"><span class="glyphicon glyphicon-list"></span></span>
					</div>
				</td>
				<td><input ' . $disabled . ' class="form-control input-sm acctname" value="' . $AcctName . '"></td>
				<td><input ' . $disabled . ' class="form-control input-sm price numeric" value="' . $Price . '"></td>
				<td><select ' . $disabled . ' class="form-control input-sm taxcode">' . $taxcode . '</select></td>
				<td><input ' . $disabled . ' class="form-control input-sm grossprice numeric" value="' . $GrossPrice . '"></td>
				<td><input readonly class="form-control input-sm taxamount numeric" value="' . $TaxAmt . '"></td>	
				<td class="hidden"><input readonly class="form-control input-sm lineno" value="' . $LineNum . '"></td>	
			</tr>';
    } // END Service Type
    $ctr += 1;
} // End For


odbc_free_result($qry);
odbc_close($MSSQL_CONN);


