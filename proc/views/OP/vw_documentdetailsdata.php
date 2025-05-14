<?php
include_once('../../../config/config.php');
include_once('../../../sbo-common/Common.php');

$taxcode2 = '';

$qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "]; SELECT Code,Name,Rate FROM OVTG WHERE Inactive = 'N' AND Category='I'");

while (odbc_fetch_row($qry)) {
    $taxcode2 .= '<option val' . odbc_result($qry, "Code") . ' val-rate="' . number_format(odbc_result($qry, "Rate"), 4, '.', '.') . '" value="' . odbc_result($qry, "Code") . '">' . odbc_result($qry, "Code") . ' - ' . utf8_encode(odbc_result($qry, "Name")) . '</option>';
}

$docentry = $_GET['docentry'];
$qry = odbc_exec($MSSQL_CONN, " USE [" . $_SESSION['mssqldb'] . "];
				SELECT DISTINCT
					CASE WHEN B.InvType = 18 THEN C.DocNum ELSE M.TransId END AS DocNum,
					B.DocEntry,
					CASE WHEN A.DocType = 'A' THEN 'Account' ELSE 'Vendor' END AS DocType,
					B.SumApplied,
					C.DocDate,
					CASE WHEN B.InvType = 18 THEN F.DueDate ELSE M.DueDate END AS DueDate,
					F.InsTotal,
					A.DocDate AS PaidDate,
					F.PaidToDate, 
					C.InstallMnt, 
					CASE WHEN B.InvType = 18 THEN C.NumAtCard ELSE M.Ref2 END AS NumAtCard,
					B.OcrCode AS DepartmentCode,
					B.OcrCode2 AS EmployeeCode,
					B.OcrCode3 AS EquipmentCode,
					G.PrcName AS DepartmentName,
					H.PrcName AS EmployeeName,
					I.PrcName AS EquipmentName,
					Y.AcctCode,
					Y.AcctName,
					Y.Descrip,
					Y.SumApplied AS Amount,
					Y.VatGroup,
					Y.VatAmnt,
					Y.OcrCode AS DepartmentCode1,
					Y.OcrCode2 AS EmployeeCode1,
					Y.OcrCode3 AS EquipmentCode1,
					J.PrcName AS DepartmentName1,
					K.PrcName AS EmployeeName1,
					L.PrcName AS EquipmentName1

				FROM OVPM A 
				LEFT JOIN VPM2 B ON A.DocEntry = B.DocNum
				LEFT JOIN VPM1 X ON A.DocEntry = X.DocNum
				LEFT JOIN VPM4 Y ON A.DocEntry = Y.DocNum
				LEFT JOIN OPCH C ON B.DocEntry = C.DocEntry
				LEFT JOIN ORPC D ON D.DocNum = B.DocEntry
				LEFT JOIN ODPO E ON B.DocEntry = E.DocNum
				LEFT JOIN PCH6 F ON F.DocEntry = C.DocEntry
				LEFT JOIN OPRC G ON G.PrcCode = B.OcrCode
				LEFT JOIN OPRC H ON H.PrcCode = B.OcrCode2
				LEFT JOIN OPRC I ON I.PrcCode = B.OcrCode3
				LEFT JOIN OPRC J ON J.PrcCode = Y.OcrCode
				LEFT JOIN OPRC K ON K.PrcCode = Y.OcrCode2
				LEFT JOIN OPRC L ON L.PrcCode = Y.OcrCode3
				LEFT JOIN JDT1 M ON B.DocEntry = M.TransId AND B.DocLine = M.Line_ID AND B.InvType = M.TransType
                WHERE A.DocEntry = '$docentry'");
$ctr = 1;

while (odbc_fetch_row($qry)):

    $documentNumber = odbc_result($qry, "DocNum");
    $DocType = odbc_result($qry, "DocType");
    $installment = odbc_result($qry, "InstallMnt");
    $NumAtCard = odbc_result($qry, "NumAtCard");
    $DepartmentName = odbc_result($qry, "DepartmentName");
    $EmployeeName = odbc_result($qry, "EmployeeName");
    $EquipmentName = odbc_result($qry, "EquipmentName");
    $invoicePostingDay = date('m/d/Y', strtotime(odbc_result($qry, "DueDate"))); //format date
    $arrears = "*";
    $overdueDays = Common::getDiffInDays(odbc_result($qry, "DueDate"), odbc_result($qry, "PaidDate")); //date diff due date and current date +1???
    $outstandingAmount = number_format(odbc_result($qry, "InsTotal"), 2, '.', ','); //verify sources
    $balanceDue = number_format((odbc_result($qry, "InsTotal") - odbc_result($qry, "PaidToDate")), 2, '.', ','); //verify sources
	$blocked = "";
	$documentType = "";
    $cashDiscountPercent = 0; //number_format(odbc_result($qry, "DiscPrcnt"), 0, '.', ','); //default is zero
    $totalRoundingAmount = ''; //number_format(odbc_result($qry, "DiscPrcnt"), 0, '.', ','); 
    $totalPayment = number_format(odbc_result($qry, "SumApplied"), 2, '.', ','); //number_format(odbc_result($qry, "DiscPrcnt"), 0, '.', ','); //default is equal to balance due
    $paymentOrderRun = ''; //odbc_result($qry, "DocNum");//WHAT?
	
	$AcctCode = odbc_result($qry, "AcctCode");
	$AcctName = odbc_result($qry, "AcctName");
	$Descrip = odbc_result($qry, "Descrip");
	$Amount = number_format(odbc_result($qry, "Amount"),2);
	$TaxCode = odbc_result($qry, "VatGroup");
	$taxcode = str_replace('val'.$TaxCode, 'selected', $taxcode2);
	$VatGroup = odbc_result($qry, "VatGroup");
	$VatAmnt = number_format(odbc_result($qry, "VatAmnt"),2);
	$DepartmentName1 = odbc_result($qry, "DepartmentName1");
	$EmployeeName1 = odbc_result($qry, "EmployeeName1");
	$EquipmentName1 = odbc_result($qry, "EquipmentName1");

    $disabled = 'readonly';
	if($DocType == 'Vendor')
	{
		?>
		<tr>
			<td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center hidden">
				<a class="invdata" data-toggle="modal" data-target="#InvDataModal"><span class="glyphicon glyphicon-arrow-right"></span></a> <?php echo $ctr; ?>
			</td>
			<td style="padding-top: 2px;  padding-bottom: 2px;" ><input <?php echo $disabled; ?> type="checkbox" disabled class="form-control input-sm checkbox-inline itemselected" checked=""/></td>
			<td><input readonly class="form-control input-sm documentno" value="<?php echo $documentNumber; ?>" /></td>
			<td><input readonly class="form-control input-sm" value="<?php echo $NumAtCard; ?>" /></td>
			<td class="hidden"><input readonly class="form-control input-sm installment" value="<?php echo $installment; ?>" /></td>
			<td><input readonly class="form-control input-sm invoicepostingday" value="<?php echo $invoicePostingDay; ?>" /></td>
			<td  class="hidden"><input readonly class="form-control input-sm arrears" value="<?php echo $arrears; ?>" /></td>
			<td><input readonly class="form-control input-sm overduedays" value="<?php echo $overdueDays; ?>" /></td>
			<td><input readonly class="form-control input-sm outstandingamount text-right" value="<?php echo $outstandingAmount; ?>" /></td>
			<td><input readonly class="form-control input-sm balancedue text-right" value="<?php echo $balanceDue; ?>" /></td>
			<td class="hidden"><input readonly class="form-control input-sm blocked" value="<?php echo $blocked; ?>" /></td>
			<td class="hidden"><input <?php echo $disabled; ?> class="form-control input-sm cashdiscountpercent text-right" value="<?php echo $cashDiscountPercent; ?>" /></td>
			<td class="hidden"><input readonly class="form-control input-sm documenttype" value="<?php echo $documentType; ?>" /></td>
			<td class="hidden"><input readonly class="form-control input-sm totalroundingamount text-right" value="<?php echo $totalRoundingAmount; ?>" /></td>
			<td><input <?php echo $disabled; ?> class="form-control input-sm totalpayment" value="<?php echo $totalPayment; ?>" /></td>
			<td class="hidden"><input readonly type="checkbox" class="form-control checkbox-inline paymentorderrun" /></td>
			<td>
				<div class="input-group departmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input type="hidden" class="form-control input-sm departmentcode" readonly/>
					<input class="form-control input-sm departmentname" value="<?php echo $DepartmentName; ?>" readonly/>
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#DepartmentModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
			<td>
				<div class="input-group employeeCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input type="hidden" class="form-control input-sm employeecode" readonly/>
					<input class="form-control input-sm employeename" value="<?php echo $EmployeeName; ?>" readonly/>
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EmployeeModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
			<td>
				<div class="input-group equipmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input type="hidden" class="form-control input-sm equipmentcode" readonly/>
					<input class="form-control input-sm equipmentname" value="<?php echo $EquipmentName; ?>" readonly/>
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EquipmentModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
		</tr>
		<?php
	}
	else
	{
		echo '<tr>
						<td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center">'.$ctr.'</td>
						<td style="padding-top: 2px;  padding-bottom: 2px;">
							<div class="input-group acctcodeCont">
								<input class="form-control input-sm acctcode required" value="'.$AcctCode.'" disabled/>
								<span style="height: 18px; padding: 0 4px; margin: 0;" class="input-group-addon" data-toggle="modal" data-target="#AcctModal1"><span class="glyphicon glyphicon-list"></span></span>
							</div>
						</td>
						<td style="padding-top: 2px;  padding-bottom: 2px;">
							<input class="form-control input-sm acctname required" value="'.$AcctName.'" disabled/>
						</td>
							<td style="padding-top: 2px;  padding-bottom: 2px;">
							<input class="form-control input-sm docremarks"  value="'.$Descrip.'" maxlength="254"/>
						</td>
						<td style="padding-top: 2px;  padding-bottom: 2px;">
							<select class="form-control input-sm taxgroup" disabled>
								'.$taxcode.'
							</select>
						</td>
						<td style="padding-top: 2px;  padding-bottom: 2px;">
							<input onkeypress="return isNumberKey(event)" class="form-control input-sm price" value="'.$Amount.'" maxlength="13"/>
						</td>
						<td style="padding-top: 2px;  padding-bottom: 2px;">
							<input onkeypress="return isNumberKey(event)" class="form-control input-sm taxamount" value="'.$VatAmnt.'" maxlength="13"/>
						</td>
						<td>
							<div class="input-group departmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
								<input type="hidden" class="form-control input-sm departmentcode" readonly/>
								<input class="form-control input-sm departmentname" value="'.$DepartmentName1.'" readonly/>
								<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#DepartmentModal"><span class="glyphicon glyphicon-list"></span></span>
							</div>
						</td>
						<td>
							<div class="input-group employeeCont" style="height: 18px; padding: 0 4px; margin: 0;">
								<input type="hidden" class="form-control input-sm employeecode" readonly/>
								<input class="form-control input-sm employeename" value="'.$EmployeeName1.'" readonly/>
								<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EmployeeModal"><span class="glyphicon glyphicon-list"></span></span>
							</div>
						</td>
						<td>
							<div class="input-group equipmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
								<input type="hidden" class="form-control input-sm equipmentcode" readonly/>
								<input class="form-control input-sm equipmentname" value="'.$EquipmentName1.'"  readonly/>
								<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EquipmentModal"><span class="glyphicon glyphicon-list"></span></span>
							</div>
						</td>
						<td class="hidden">
							<input type="hidden" class="form-control input-sm lineno" value="0" readonly/>
							<input type="hidden" class="form-control input-sm item_index" readonly/>
							<input type="hidden" class="form-control input-sm wtaxindex" value="0" readonly/>
						</td>
					</tr>';
	}
    $ctr += 1;
    ?>
    <?php
endwhile;
?>

<?php
odbc_free_result($qry);
odbc_close($MSSQL_CONN);
?>
