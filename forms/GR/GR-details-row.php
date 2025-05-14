<?php
include_once('../../config/config.php');
include_once('../../sbo-common/Common.php');
$servicetype = $_GET['servicetype'];
?>

<?php
$taxcode = '';

$qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "]; SELECT Code,Name,Rate FROM OVTG WHERE Inactive = 'N' AND Category='I'");

while (odbc_fetch_row($qry)) {
    $taxcode .= '<option val-rate="' . number_format(odbc_result($qry, "Rate"), 4, '.', '.') . '" value="' . odbc_result($qry, "Code") . '">' . odbc_result($qry, "Code") . ' - ' . utf8_encode(odbc_result($qry, "Name")) . '</option>';
}

//Free Result
odbc_free_result($qry);
//End Free Result

if ($servicetype == 'I') {
    if (!isset($_GET['freetext'])) {
        ?>
        <!--Item Type-->
        <tr>
            <td style="padding-top: 2px;  padding-bottom: 2px;"  class="rowno text-center"></td>
            <td style="padding-top: 2px;  padding-bottom: 2px;">
                <div class="input-group itemcodeCont">
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
            <td><input class="form-control input-sm qty required numericvalidate">
				<div class="input-group qtyCont hidden" style="height: 18px; padding: 0 4px; margin: 0;">
					<input type="hidden" class="form-control input-sm serialno" id="SerialNo[]" name="SerialNo[]">
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#SerialModal">
					<span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
            <td class="hidden"><input class="form-control input-sm weightlive" /></td>
            <td class="hidden"><input readonly class="form-control input-sm priceperkg" /></td>
            <td class="hidden"><input readonly class="form-control input-sm uom"></td>
            <td class="hidden"><input readonly class="form-control input-sm uomname"></td>
            <td><input class="form-control input-sm price numeric"></td>
            <td class="">
                <div class="input-group warehouseCont" style="height: 18px; padding: 0 4px; margin: 0;">
                    <input class="form-control input-sm warehouse required" />
                    <span  class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#WhsModal"><span class="glyphicon glyphicon-list"></span></span>
                </div>
            
			</td>
            <td>
                <div class="input-group acctcodeCont">
                    <input class="form-control input-sm acctcode " />
                    <span style="height: 18px; padding: 0 4px; margin: 0;" class="input-group-addon" data-toggle="modal" data-target="#AcctModal"><span class="glyphicon glyphicon-list"></span></span>
                </div>
            </td>
            
			<td class="hidden"><select class="form-control input-sm taxcode"><?php echo $taxcode ?></select></td>
            <td class="hidden"><input class="form-control input-sm discount numeric"></td>
            <td class="hidden"><input class="form-control input-sm grossprice numeric"></td>
            <td class="hidden"><input readonly class="form-control input-sm taxamount numeric" ></td>
            <td class=""><input readonly class="form-control input-sm linetotal numeric"></td>
            <td class="hidden"><input readonly class="form-control input-sm grosstotal numeric"></td>
            <td class="hidden"><input readonly class="form-control input-sm lineno"></td>
            <td class="ftext text-center hidden">N</td>

        </tr>
        <!--End Item Type-->
    <?php } else { ?>
        <tr>
            <td class="hidden"class="rowno text-center"></td>
            <td class="hidden"colspan="18"><textarea class="form-control input-sm remarks"></textarea></td>
            <td><input readonly class="form-control input-sm lineno hidden"></td>
            <td class="ftext text-center hidden">Y</td>
        </tr>
    <?php } ?>

<?php } else { ?>	
    <!--Service Type-->
    <tr>
        <td class="rowno text-center"></td>
        <td><textarea class="form-control input-sm remarks required"></textarea></td>
        <td class="hidden">
            <div class="input-group acctcodeCont">
                <input class="form-control input-sm acctcode " />
                <span class="input-group-addon" data-toggle="modal" data-target="#AcctModal"><span class="glyphicon glyphicon-list"></span></span>
            </div>
        </td>
        <td><input class="form-control input-sm acctname"></td>
        <td><input class="form-control input-sm price numeric"></td>
        <td><select class="form-control input-sm taxcode"><?php echo $taxcode ?></select></td>
        <td><input class="form-control input-sm grossprice numeric"></td>
        <td><input readonly class="form-control input-sm taxamount numeric"></td>	
        <td><input readonly class="form-control input-sm lineno"></td>	
    </tr>
    <!--End Service Type-->
<?php } ?>