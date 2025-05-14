<?php
include_once('../../../config/config.php');
include_once('../../../sbo-common/Common.php');

$customer = $_GET['customer'];

$qry = odbc_exec($MSSQL_CONN, "select T0.[DocEntry]
	,T0.[DocNum]
	,T1.[InstlmntID]
	,T0.[DocType]
	,T0.[CANCELED]
	,T1.[Status]
	,T0.[DocDate]
	,T1.[DueDate]
	,T0.[CardCode]
	,T0.[CardName]
	,T0.[NumAtCard]
	,T0.[DocCur]
	,T0.[DocRate]
	,T1.[InsTotal]
	,T1.[PaidToDate]
	,T1.[VatSum]
	,T1.[TotalBlck]
	,T0.[Reserve]
	,T0.[Installmnt]
	,T0.[DpmStatus]
	
from [" . $_SESSION['mssqldb'] . "].[dbo].[OPCH] T0
inner join [" . $_SESSION['mssqldb'] . "].[dbo].[PCH6] T1 on T1.[DocEntry] = T0.[DocEntry]
where T0.[CardCode] = ('$customer')
	and (
		T1.[TotalBlck] <> T1.[InsTotal]
		or T1.[TotalBlck] = (0)
		)
	and T1.[Status] = ('O')
order by T0.[DocDate] desc
");
$ctr = 1;
?>
<?php
while (odbc_fetch_row($qry)):

    $documentNumber = odbc_result($qry, "DocEntry");
    $documentNumber1 = odbc_result($qry, "DocNum");
	$installment = odbc_result($qry, "InstallMnt");
    $invoicePostingDay = date('m/d/Y',strtotime(odbc_result($qry, "DueDate"))); 
    $arrears = "*";
    $overdueDays = Common::getDiffInDays(odbc_result($qry, "DueDate"), date('M j, Y')); 
    $outstandingAmount = number_format(odbc_result($qry, "InsTotal"), 2, '.', ','); //verify sources
    $balanceDue = number_format((odbc_result($qry, "InsTotal") - odbc_result($qry, "PaidToDate")), 2, '.', ','); 
    $blocked = odbc_result($qry, "Reserve") == 'N' ? '' : 'Y'; 
    $cashDiscountPercent = 0; 
    $documentType = odbc_result($qry, "DocType") == 'I' ? 'IN' : 'Unknown'; 
    $totalRoundingAmount = ''; 
    $totalPayment = $balanceDue; 
    $paymentOrderRun = ''; 

    $LineStatus = odbc_result($qry, "Status");
    $DocStatus = odbc_result($qry, "DpmStatus");
    $NumAtCard = odbc_result($qry, "NumAtCard"); 

    $disabled = '';
    if ($DocStatus == 'C') 
	{
        $disabled = 'readonly';
    }
    ?>

    <tr>
        <td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center hidden">
            <a class="invdata" data-toggle="modal" data-target="#InvDataModal"><span class="glyphicon glyphicon-arrow-right"></span></a> <?php echo $ctr; ?>
        </td>
        <td style="padding-top: 2px;  padding-bottom: 2px;"><input <?php echo $disabled; ?> type="checkbox" class="form-control input-sm checkbox-inline itemselected"/></td>
        <td><input readonly class="form-control input-sm documentno hidden" value="<?php echo $documentNumber; ?>" />
		<input readonly class="form-control input-sm " value="<?php echo $documentNumber1; ?>" /></td>
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
        <td><input <?php echo $disabled; ?> onkeypress="return isNumberKey(event)"  class="form-control input-sm totalpayment" value="<?php echo $totalPayment; ?>" /></td>
		<td>
			<div class="input-group departmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
				<input type="hidden" class="form-control input-sm departmentcode" readonly/>
				<input class="form-control input-sm departmentname" readonly/>
				<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#DepartmentModal"><span class="glyphicon glyphicon-list"></span></span>
			</div>
		</td>
		<td>
			<div class="input-group employeeCont" style="height: 18px; padding: 0 4px; margin: 0;">
				<input type="hidden" class="form-control input-sm employeecode" readonly/>
				<input class="form-control input-sm employeename" readonly/>
				<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EmployeeModal"><span class="glyphicon glyphicon-list"></span></span>
			</div>
		</td>
		<td>
			<div class="input-group equipmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
				<input type="hidden" class="form-control input-sm equipmentcode" readonly/>
				<input class="form-control input-sm equipmentname" readonly/>
				<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EquipmentModal"><span class="glyphicon glyphicon-list"></span></span>
			</div>
		</td>
        <td class="hidden"><input readonly type="checkbox" class="form-control checkbox-inline paymentorderrun" /></td>
    </tr>
    <?php
    $ctr += 1;
    ?>
    <?php
endwhile;
?>

<?php
odbc_free_result($qry);
odbc_close($MSSQL_CONN);
?>

<script>
function isNumberKey(event)
{
	var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46) 
	{
        return true;
    } 
	else if ( key < 48 || key > 57 ) 
	{
        return false;
    }
	else 
	{
    	return true;
    }
}
</script>

