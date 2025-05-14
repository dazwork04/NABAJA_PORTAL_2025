<?php
include_once('../../config/config.php');
include_once('../../sbo-common/Common.php');
$servicetype = $_GET['servicetype'];

$sample = '';

$taxcode = '';
$currency = '';

$qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "]; SELECT Code,Name,Rate FROM OVTG WHERE Inactive = 'N' AND Category='I' ORDER BY CASE WHEN Code = 'IVAT-N' THEN '1' ELSE Code END ASC");

while (odbc_fetch_row($qry)) {
	$taxcode .= '<option val-rate="' . number_format(odbc_result($qry, "Rate"), 4, '.', '.') . '" value="' . odbc_result($qry, "Code") . '">' . odbc_result($qry, "Code") . ' - ' . utf8_encode(odbc_result($qry, "Name")) . '</option>';
}
odbc_free_result($qry);

$qry1 = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT CurrCode FROM OCRN");
while (odbc_fetch_row($qry1)) {
	$currency .= '<option value="'.odbc_result($qry1, 'CurrCode').'" >'.odbc_result($qry1, 'CurrCode').'</option>';
}

odbc_free_result($qry1);

if ($servicetype == 'I') 
	{
    if (!isset($_GET['freetext'])) {
        ?>
        <!--Item Type-->
        <tr>
            <td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center"></td>
            <td style="padding-top: 2px;  padding-bottom: 2px;">
                <div class="input-group itemcodeCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input class="form-control input-sm itemcode required" />
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#ItemModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>

            </td>
            <td><input class="form-control input-sm itemname" /></td>
            <td class="hidden">
                <div class="input-group barcodeCont">
                    <input class="form-control input-sm barcode" />
                    <span class="input-group-addon" data-toggle="modal" data-target="#BarcodeModal"><span class="glyphicon glyphicon-list"></span></span>
                </div>
            </td>
            <td><input onkeypress="return isNumberKey(event);" class="form-control input-sm qty required numericvalidate"></td>
			<td>
                <div class="input-group warehouseCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input class="form-control input-sm warehouse" />
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#WhsModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
            </td>
            <td><input onkeypress="return isNumberKey(event);" class="form-control input-sm price numeric"></td>
			<td><input onkeypress="return isNumberKey(event);" class="form-control input-sm discount numeric"></td>
            <td class="hidden"><input readonly class="form-control input-sm uom"></td>
            <td class="hidden"><input readonly class="form-control input-sm uomname"></td>
            <td class="hidden"><select class="form-control input-sm currency"><?php echo $currency ?></select></td>
			<td><select class="form-control input-sm taxcode"><?php echo $taxcode ?></select></td>
			<td><input onkeypress="return isNumberKey(event);" class="form-control input-sm grossprice numeric"></td>
            <td class="hidden"><input readonly class="form-control input-sm taxamount numeric" ></td>
            <td><input readonly class="form-control input-sm linetotal numeric"></td>
			<td><input class="form-control input-sm itemdetails" maxlength="1000"></td>
            <td class="hidden"><input readonly class="form-control input-sm grosstotal numeric"></td>
            <td class="hidden"><input readonly class="form-control input-sm lineno"></td>
            <td class="ftext text-center hidden">N</td>

        </tr>
        <!--End Item Type-->
    <?php } else { ?>
        <tr>
            <td class="rowno text-center"></td>
            <td colspan="18"><textarea class="form-control input-sm remarks"></textarea></td>
            <td><input readonly class="form-control input-sm lineno hidden"></td>
            <td class="ftext text-center hidden">Y</td>
        </tr>
    <?php } ?>

<?php } else { ?>	
    <!--Service Type-->
    <tr>
        <td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center"></td>
        <td style="padding-top: 2px;  padding-bottom: 2px;"><textarea class="form-control input-sm remarks required" maxlength="100"></textarea></td>
        <td>
            <div class="input-group acctcodeCont" style="height: 18px; padding: 0 4px; margin: 0;">
                <input class="form-control input-sm acctcode required" />
                <span style="height: 18px; padding: 0 4px; margin: 0;" class="input-group-addon" data-toggle="modal" data-target="#AcctModal"><span class="glyphicon glyphicon-list"></span></span>
            </div>
        </td>
        <td><input class="form-control input-sm acctname"></td>
        <td><input onkeypress="return isNumberKey(event);" class="form-control input-sm price numeric"></td>
        <td><select class="form-control input-sm taxcode"><?php echo $taxcode ?></select></td>
        <td><input onkeypress="return isNumberKey(event);" class="form-control input-sm grossprice numeric"></td>
        <td><input readonly class="form-control input-sm taxamount numeric"></td>	
        <td class="hidden"><input readonly class="form-control input-sm lineno"></td>	
    </tr>
    <!--End Service Type-->
<?php } ?>
<script>
	function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : evt.keyCode;
	  if (charCode != 46 && charCode > 31 
		&& (charCode < 48 || charCode > 57))
			return false;

		return true;
		
	}
</script>