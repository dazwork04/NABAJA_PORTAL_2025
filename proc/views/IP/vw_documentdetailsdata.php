<?php
include_once('../../../config/config.php');
include_once('../../../sbo-common/Common.php');

$taxcode2 = '';

$qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "]; SELECT Code,Name,Rate FROM OVTG WHERE Inactive = 'N' AND Category='O'");

while (odbc_fetch_row($qry)) 
{
    $taxcode2 .= '<option val' . odbc_result($qry, "Code") . ' val-rate="' . number_format(odbc_result($qry, "Rate"), 4, '.', '.') . '" value="' . odbc_result($qry, "Code") . '">' . odbc_result($qry, "Code") . ' - ' . utf8_encode(odbc_result($qry, "Name")) . '</option>';
}

$docentry = $_GET['docentry'];
$qry = odbc_exec($MSSQL_CONN, " USE [" . $_SESSION['mssqldb'] . "];
						SELECT DISTINCT
              CASE WHEN B.InvType = 13 THEN C.DocNum
                WHEN B.InvType = 14 THEN D.DocNum
                WHEN B.InvType = 203 THEN E.DocNum
              END  AS InvDocNum,
              B.DocEntry,
              CASE WHEN A.DocType = 'A' THEN 'Account' ELSE 'Vendor' END AS DocType,
              B.SumApplied,
              CASE WHEN B.InvType = 13 THEN C.DocDate
                WHEN B.InvType = 14 THEN D.DocDate
                WHEN B.InvType = 203 THEN E.DocDate
              END  AS DocDate,
              CASE WHEN B.InvType = 13 THEN F.DueDate
                WHEN B.InvType = 14 THEN F1.DueDate
                WHEN B.InvType = 203 THEN F2.DueDate
              END AS DueDate, 
              CASE WHEN B.InvType = 13 THEN F.InsTotal
                WHEN B.InvType = 14 THEN F1.InsTotal
                WHEN B.InvType = 203 THEN F2.InsTotal
              END AS InsTotal,  
              A.DocDate AS PaidDate,
              CASE WHEN B.InvType = 13 THEN F.PaidToDate
                WHEN B.InvType = 14 THEN F1.PaidToDate
                WHEN B.InvType = 203 THEN F2.PaidToDate
              END AS PaidToDate,  
              CASE WHEN B.InvType = 13 THEN C.InstallMnt
                WHEN B.InvType = 14 THEN D.InstallMnt
                WHEN B.InvType = 203 THEN E.InstallMnt
              END  AS InstallMnt, 
              CASE WHEN B.InvType = 13 THEN C.NumAtCard
                WHEN B.InvType = 14 THEN D.NumAtCard
                WHEN B.InvType = 203 THEN E.NumAtCard
              END  AS NumAtCard,  
              B.OcrCode AS DepartmentCode,
              B.OcrCode3 AS EmployeeCode,
              B.OcrCode4 AS EquipmentCode,
              G.PrcName AS DepartmentName,
              H.PrcName AS EmployeeName,
              I.PrcName AS EquipmentName,
              Y.AcctCode,
              Y.AcctName,
              Y.Descrip,
              Y.SumApplied AS Amount,
              Y.VatGroup,
              Y.OcrCode AS DepartmentCode1,
              Y.OcrCode3 AS EmployeeCode1,
              Y.OcrCode4 AS EquipmentCode1,
              J.PrcName AS DepartmentName1,
              K.PrcName AS EmployeeName1,
              L.PrcName AS EquipmentName1
          
            FROM ORCT A 
            LEFT JOIN RCT2 B ON A.DocEntry = B.DocNum
            LEFT JOIN RCT1 X ON A.DocEntry = X.DocNum
            LEFT JOIN RCT4 Y ON A.DocEntry = Y.DocNum
            LEFT JOIN OINV C ON B.DocEntry = C.DocNum AND B.InvType = 13
            LEFT JOIN ORIN D ON D.DocNum = B.DocEntry AND B.InvType = 14
            LEFT JOIN ODPI E ON B.DocEntry = E.DocNum AND B.InvType = 203
            LEFT JOIN INV6 F ON F.DocEntry = C.DocEntry  AND B.InvType = 13 
            LEFT JOIN RIN6 F1 ON F1.DocEntry = D.DocEntry  AND B.InvType = 14 
            LEFT JOIN DPI6 F2 ON F2.DocEntry = E.DocEntry  AND B.InvType = 203
            LEFT JOIN OPRC G ON G.PrcCode = B.OcrCode
            LEFT JOIN OPRC H ON H.PrcCode = B.OcrCode3
            LEFT JOIN OPRC I ON I.PrcCode = B.OcrCode4
            LEFT JOIN OPRC J ON J.PrcCode = Y.OcrCode
            LEFT JOIN OPRC K ON K.PrcCode = Y.OcrCode3
            LEFT JOIN OPRC L ON L.PrcCode = Y.OcrCode4
						WHERE A.DocEntry = '$docentry'");
$ctr = 1;
?>

<?php
while (odbc_fetch_row($qry)):

    $documentNumber = odbc_result($qry, "InvDocNum");
	$DocType = odbc_result($qry, "DocType");
    $installment = odbc_result($qry, "InstallMnt");
    $NumAtCard = odbc_result($qry, "NumAtCard");
    $invoicePostingDay = date('m/d/Y', strtotime(odbc_result($qry, "DocDate"))); //format date

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
	$DepartmentName = utf8_encode(odbc_result($qry, "DepartmentName"));
    $EmployeeName = utf8_encode(odbc_result($qry, "EmployeeName"));
    $EquipmentName = utf8_encode(odbc_result($qry, "EquipmentName"));
	$DepartmentName1 = utf8_encode(odbc_result($qry, "DepartmentName1"));
	$EmployeeName1 = utf8_encode(odbc_result($qry, "EmployeeName1"));
	$EquipmentName1 = utf8_encode(odbc_result($qry, "EquipmentName1"));
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
			<td class="hidden">
					<div class="input-group departmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
						<input type="hidden" class="form-control input-sm departmentcode" readonly/>
						<input class="form-control input-sm departmentname" value="<?php echo $DepartmentName; ?>" readonly/>
						<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#DepartmentModal"><span class="glyphicon glyphicon-list"></span></span>
					</div>
				</td>
				<td class="hidden">
					<div class="input-group employeeCont" style="height: 18px; padding: 0 4px; margin: 0;">
						<input type="hidden" class="form-control input-sm employeecode" readonly/>
						<input class="form-control input-sm employeename" value="<?php echo $EmployeeName; ?>" readonly/>
						<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EmployeeModal"><span class="glyphicon glyphicon-list"></span></span>
					</div>
				</td>
				<td class="hidden">
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
							<input class="form-control input-sm docremarks"  value="'.$Descrip.'" maxlength="254" readonly/>
						</td>
						<td style="padding-top: 2px;  padding-bottom: 2px;">
							<select class="form-control input-sm taxgroup" disabled>
								'.$taxcode.'
							</select>
						</td>
						<td style="padding-top: 2px;  padding-bottom: 2px;">
							<input onkeypress="return isNumberKey(event)" class="form-control input-sm price" value="'.$Amount.'" maxlength="13" readonly/>
						</td>
						<td class="hidden">
							<div class="input-group departmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
								<input type="hidden" class="form-control input-sm departmentcode" readonly/>
								<input class="form-control input-sm departmentname" value="'.$DepartmentName1.'" readonly/>
								<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#DepartmentModal"><span class="glyphicon glyphicon-list"></span></span>
							</div>
						</td>
						<td class="hidden">
							<div class="input-group employeeCont" style="height: 18px; padding: 0 4px; margin: 0;">
								<input type="hidden" class="form-control input-sm employeecode" readonly/>
								<input class="form-control input-sm employeename" value="'.$EmployeeName1.'" readonly/>
								<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EmployeeModal"><span class="glyphicon glyphicon-list"></span></span>
							</div>
						</td>
						<td class="hidden">
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
    
endwhile;
?>

<?php
odbc_free_result($qry);
odbc_close($MSSQL_CONN);
?>
