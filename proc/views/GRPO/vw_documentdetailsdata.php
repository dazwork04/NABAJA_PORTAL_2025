<?php  include_once('../../../config/config.php'); 

$taxcode2 = '';

//Load Tax Code
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT Code,Name,Rate FROM OVTG WHERE Inactive = 'N' AND Category='I'");
//End Load Tax Code

while (odbc_fetch_row($qry)) {
	$taxcode2 .= '<option val'.odbc_result($qry, "Code").' val-rate="'.number_format(odbc_result($qry, "Rate"),4,'.','.').'" value="'.odbc_result($qry, "Code").'">'. odbc_result($qry, "Code") .' - '. utf8_encode(odbc_result($qry, "Name")) .'</option>';	
}

//Free Result
odbc_free_result($qry);
//End Free Result


$docentry = $_GET['docentry'];
$qry = odbc_exec($MSSQL_CONN, " 
			SELECT T0.DocType,'N' AS LineType,T1.ItemCode,T1.Dscription,T2.InvntryUom,(T1.Quantity) AS Quantity,
			T1.OpenSum,T2.InvntryUom,T1.PriceBefDi,T1.WhsCode,T1.VatGroup,T1.DiscPrcnt,T1.PriceAfVAT,
			CASE WHEN T1.Currency = 'PHP' THEN T1.VatSum ELSE T1.VatSumFrgn END as VatSum,
			CASE WHEN T1.Currency = 'PHP' THEN T1.LineTotal ELSE T1.TotalFrgn END as LineTotal,
			CASE WHEN T1.Currency = 'PHP' THEN T1.GTotal ELSE T1.GTotalFC END as GTotal,
			'' AS LineText, T3.AcctCode,T3.FormatCode,T3.AcctName,T1.LineNum,T1.LineStatus,'A' AS SubSeq,T1.LineNum AS AftLineNum, T1.Text,
				T1.OcrCode,
				T4.PrcName AS DeptName,
				T1.Project,
				T5.PrjName AS PrjName,
				T1.OcrCode2,
				T6.PrcName AS EmpName,
				T1.OcrCode3,
				T7.PrcName AS EquipName,
				T1.AcctCode AS POAcctCode,
				T8.AcctName AS POAcctName
				FROM [".$_SESSION['mssqldb']."].[dbo].[OPDN] T0
				INNER JOIN [".$_SESSION['mssqldb']."].[dbo].PDN1 T1
				ON T0.DocEntry = T1.DocEntry
				LEFT JOIN [".$_SESSION['mssqldb']."].[dbo].OITM T2
				ON T1.ItemCode = T2.ItemCode
				LEFT JOIN [".$_SESSION['mssqldb']."].[dbo].[OACT] T3
				ON T1.AcctCode = T3.AcctCode
				LEFT JOIN [".$_SESSION['mssqldb']."].[dbo].[OPRC] T4 
				ON T1.OcrCode = T4.PrcCode 
				LEFT JOIN [".$_SESSION['mssqldb']."].[dbo].[OPRJ] T5 
				ON T1.Project = T5.PrjCode
				LEFT JOIN [".$_SESSION['mssqldb']."].[dbo].[OPRC] T6 
				ON T1.OcrCode2 = T6.PrcCode
				LEFT JOIN [".$_SESSION['mssqldb']."].[dbo].[OPRC] T7
				ON T1.OcrCode3 = T7.PrcCode
				LEFT JOIN [dbo].[OACT] T8 ON T1.AcctCode = T8.AcctCode
				WHERE T0.DocEntry ='$docentry'
			UNION ALL
			SELECT T0.DocType,'Y' AS LineType,'' AS ItemCode,'' AS Dscription,'' AS InvntryUom,0 AS Quantity,0 AS OpenSum,'' AS InvntryUom,0 AS PriceBefDi,'' AS WhsCode,'' AS VatGroup,0 AS DiscPrcnt,0 AS PriceAfVAT,0 AS VatSum,0 AS LineTotal,0 AS GTotal,T1.LineText,'' AS AcctCode,'' AS FormatCode,'' AS AcctName,T1.LineSeq AS LineNum,'' AS LineStatus,'B' AS SubSeq,T1.AftLineNum AS AftLineNum, 
				'' AS Text,
				'' AS OcrCode,
				'' AS DeptName,
				'' AS Project,
				'' AS PrjName,
				'' AS OcrCode2,
				'' AS EmpName,
				'' AS OcrCode3,
				'' AS EquipName,
				'' AS POAcctCode,
				'' AS POAcctName
				FROM [".$_SESSION['mssqldb']."].[dbo].OPDN T0
				INNER JOIN [".$_SESSION['mssqldb']."].[dbo].PDN10 T1
				ON T0.DocEntry = T1.DocEntry
				WHERE T0.DocEntry ='$docentry'
				ORDER BY AftLineNum,SubSeq");
$ctr = 1;

while (odbc_fetch_row($qry)) {
	$ItemCode = odbc_result($qry, "ItemCode");
	$POAccount = odbc_result($qry, "POAcctCode") . '-' . odbc_result($qry, "POAcctName");
	$ItemName = odbc_result($qry, "Dscription");
	$InvntryUom = odbc_result($qry, "InvntryUom");
	$Quantity = number_format(odbc_result($qry, "Quantity"),2,'.',',');
	
	$Whse = odbc_result($qry, "WhsCode");
	$TaxCode = odbc_result($qry, "VatGroup");
	$LineNum = odbc_result($qry, "LineNum");
	$LineStatus = odbc_result($qry, "LineStatus");
	$Ftext = odbc_result($qry, "LineType");
	$FtextRemarks = odbc_result($qry, "LineText");
	$Text = odbc_result($qry, "Text");

	$ServiceRemarks = odbc_result($qry, "Dscription");
	$Account = odbc_result($qry, "AcctCode");
	$ServiceType = odbc_result($qry, "DocType");
	$FormatCode = odbc_result($qry, "FormatCode");
	$AcctName = odbc_result($qry, "AcctName");

	$taxcode = str_replace('val'.$TaxCode, 'selected', $taxcode2);
	$Price = number_format(odbc_result($qry, "PriceBefDi"),2,'.',',');
	$OpenPrice = number_format(odbc_result($qry, "OpenSum"),'2','.',',');
	$Discount = number_format(odbc_result($qry, "DiscPrcnt"),0,'.',',');
	$GrossPrice = number_format(odbc_result($qry, "PriceAfVAT"),2,'.',',');
	$LineTotal = number_format(odbc_result($qry, "LineTotal"),2,'.',',');
	$TaxAmt = number_format(odbc_result($qry, "VatSum"),2,'.',',');
	$GrossTotal = number_format(odbc_result($qry, "GTotal"),2,'.',',');

	$OcrCode = utf8_encode(odbc_result($qry, 'OcrCode'));
	$DeptName = utf8_encode(odbc_result($qry, 'DeptName'));
	$OcrCode2 = utf8_encode(odbc_result($qry, 'Project'));
	$PrjName = utf8_encode(odbc_result($qry, 'PrjName'));
	$OcrCode3 = utf8_encode(odbc_result($qry, 'OcrCode2'));
	$EmpName = utf8_encode(odbc_result($qry, 'EmpName'));
	$OcrCode4 = utf8_encode(odbc_result($qry, 'OcrCode3'));
	$EquipName = utf8_encode(odbc_result($qry, 'EquipName'));

	$disabled = '';
	if($LineStatus == 'C'){
		$disabled = 'readonly';
	}
	
	if($ServiceType == 'I'){
		if($Ftext == 'N'){
			echo '
				<tr>
					<td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center">
						<a class="invdata" data-toggle="modal" data-target="#InvDataModal"><span class="glyphicon glyphicon-arrow-right"></span></a> '.$ctr.'
					</td>
					<td style="padding-top: 2px;  padding-bottom: 2px;">
						<div class="input-group itemcodeCont" style="height: 18px; padding: 0 4px; margin: 0;">
							<input '.$disabled.' class="form-control input-sm itemcode required" value="'.$ItemCode.'" />
							<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#ItemModal"><span class="glyphicon glyphicon-list"></span></span>
						</div>

					</td>
					<td class="hidden"><input class="form-control input-sm glaccount" value="'.$POAccount.'" readonly/></td>
					<td style="padding-top: 2px;  padding-bottom: 2px;"><input '.$disabled.' class="form-control input-sm itemname" value="'.$ItemName.'" /></td>
					<td style="padding-top: 2px;  padding-bottom: 2px;">
						<div class="input-group qtyCont" style="height: 18px; padding: 0 4px; margin: 0;">
							<input '.$disabled.' class="form-control input-sm qty required numericvalidate" value="'.$Quantity.'">
							<input type="hidden" class="form-control input-sm serialno" id="SerialNo[]" name="SerialNo[]">
							<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#SerialModal">
							<span class="glyphicon glyphicon-list"></span></span>
						</div>
						
					</td>
					<td style="padding-top: 2px;  padding-bottom: 2px;"><input readonly class="form-control input-sm uom" value="'.$InvntryUom.'"></td>
					<td style="padding-top: 2px;  padding-bottom: 2px;"><input '.$disabled.' class="form-control input-sm price required numeric numericvalidate" value="'.$Price.'"></td>
					<td style="padding-top: 2px;  padding-bottom: 2px;">
						<div class="input-group warehouseCont" style="height: 18px; padding: 0 4px; margin: 0;">
							<input '.$disabled.' class="form-control input-sm warehouse required" value="'.$Whse.'" />
							<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#WhsModal"><span class="glyphicon glyphicon-list"></span></span>
						</div>
					</td>
					<td style="padding-top: 2px;  padding-bottom: 2px;"><select '.$disabled.' class="form-control input-sm taxcode">'.$taxcode.'</select></td>
					<td style="padding-top: 2px;  padding-bottom: 2px;"><input '.$disabled.' class="form-control input-sm discount numeric" value="'.$Discount.'"></td>
					<td style="padding-top: 2px;  padding-bottom: 2px;"><input '.$disabled.' class="form-control input-sm grossprice numeric" value="'.$GrossPrice.'"></td>
					<td style="padding-top: 2px;  padding-bottom: 2px;" class="hidden"><input readonly class="form-control input-sm taxamount numeric" value="'.$TaxAmt.'"></td>
					<td style="padding-top: 2px;  padding-bottom: 2px;"><input readonly class="form-control input-sm linetotal numeric" value="'.$LineTotal.'"></td>
					<td style="padding-top: 2px;  padding-bottom: 2px;"><input '.$disabled.' class="form-control input-sm itemdetails" maxlength="1000" value="'.$Text.'"></td>
					<td style="padding-top: 2px;  padding-bottom: 2px;" class="hidden"><input readonly class="form-control input-sm grosstotal numeric" value="'.$GrossTotal.'"></td>
					<td class="hidden">
						<div class="input-group departmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
							<input type="hidden" class="form-control input-sm departmentcode" value="'.$OcrCode.'" readonly/>
							<input class="form-control input-sm departmentname" value="'.$DeptName.'" readonly/>
							<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#DepartmentModal"><span class="glyphicon glyphicon-list"></span></span>
						</div>
					</td>
					<td>
						<div class="input-group projectCont" style="height: 18px; padding: 0 4px; margin: 0;">
							<input type="hidden" class="form-control input-sm projectcode" value="'.$OcrCode2.'" readonly/>
							<input class="form-control input-sm projectname" value="'.$PrjName.'" readonly/>
							<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#ProjectModal"><span class="glyphicon glyphicon-list"></span></span>
						</div>
					</td>
					<td class="hidden">
						<div class="input-group employeeCont" style="height: 18px; padding: 0 4px; margin: 0;">
							<input type="hidden" class="form-control input-sm employeecode" value="'.$OcrCode3.'" readonly/>
							<input class="form-control input-sm employeename" value="'.$EmpName.'" readonly/>
							<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EmployeeModal"><span class="glyphicon glyphicon-list"></span></span>
						</div>
					</td>
					<td class="hidden">
						<div class="input-group equipmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
							<input type="hidden" class="form-control input-sm equipmentcode" value="'.$OcrCode4.'" readonly/>
							<input class="form-control input-sm equipmentname" value="'.$EquipName.'" readonly/>
							<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EquipmentModal"><span class="glyphicon glyphicon-list"></span></span>
						</div>
					</td>
					<td style="padding-top: 2px;  padding-bottom: 2px;" class="hidden"><input readonly class="form-control input-sm lineno" value="'.$LineNum.'"></td>
					<td style="padding-top: 2px;  padding-bottom: 2px;" class="ftext text-center hidden">N</td>
					
				</tr>';
		}else{

			echo '
				<tr>
					<td class="rowno text-center">
						'.$ctr.'
					</td>
					<td colspan="12"><textarea '.$disabled.' class="form-control input-sm remarks">'.$FtextRemarks.'</textarea></td>
					<td><input readonly class="form-control input-sm lineno" value="'.$LineNum.'"></td>
					<td class="ftext text-center">Y</td>
				</tr>';

		}

	}else{
		echo '
			<tr>
				<td class="rowno text-center">
					'.$ctr.'
				</td>
				<td><textarea '.$disabled.' class="form-control input-sm remarks required">'.$ServiceRemarks.'</textarea></td>
				<td>
					<div class="input-group acctcodeCont" style="height: 18px; padding: 0 4px; margin: 0;">
						<input '.$disabled.' aria-acctcode="'.$Account.'" class="form-control input-sm acctcode required" value="'.$FormatCode.'" />
						<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#AcctModal"><span class="glyphicon glyphicon-list"></span></span>
					</div>
				</td>
				<td><input '.$disabled.' class="form-control input-sm acctname" value="'.$AcctName.'"></td>
				<td><input '.$disabled.' class="form-control input-sm price numeric" value="'.$Price.'"></td>
				<td><select '.$disabled.' class="form-control input-sm taxcode">'.$taxcode.'</select></td>
				<td><input '.$disabled.' class="form-control input-sm grossprice numeric" value="'.$GrossPrice.'"></td>
				<td><input readonly class="form-control input-sm taxamount numeric" value="'.$TaxAmt.'"></td>
				<td class="hidden">
					<div class="input-group departmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
						<input type="hidden" class="form-control input-sm departmentcode" value="'.$OcrCode.'" readonly/>
						<input class="form-control input-sm departmentname" value="'.$DeptName.'" readonly/>
						<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#DepartmentModal"><span class="glyphicon glyphicon-list"></span></span>
					</div>
				</td>
				<td>
					<div class="input-group projectCont" style="height: 18px; padding: 0 4px; margin: 0;">
						<input type="hidden" class="form-control input-sm projectcode" value="'.$OcrCode2.'" readonly/>
						<input class="form-control input-sm projectname" value="'.$PrjName.'" readonly/>
						<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#ProjectModal"><span class="glyphicon glyphicon-list"></span></span>
					</div>
				</td>
				<td class="hidden">
					<div class="input-group employeeCont" style="height: 18px; padding: 0 4px; margin: 0;">
						<input type="hidden" class="form-control input-sm employeecode" value="'.$OcrCode3.'" readonly/>
						<input class="form-control input-sm employeename" value="'.$EmpName.'" readonly/>
						<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EmployeeModal"><span class="glyphicon glyphicon-list"></span></span>
					</div>
				</td>
				<td class="hidden">
					<div class="input-group equipmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
						<input type="hidden" class="form-control input-sm equipmentcode" value="'.$OcrCode4.'" readonly/>
						<input class="form-control input-sm equipmentname" value="'.$EquipName.'" readonly/>
						<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EquipmentModal"><span class="glyphicon glyphicon-list"></span></span>
					</div>
				</td>	
				<td class="hidden"><input readonly class="form-control input-sm lineno" value="'.$LineNum.'"></td>	
			</tr>';

	} // END Service Type
$ctr += 1;
} // End For


odbc_free_result($qry);
odbc_close($MSSQL_CONN);


