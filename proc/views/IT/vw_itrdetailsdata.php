<?php
include_once('../../../config/config.php');
include_once('../../../sbo-common/Common.php');


$docentry = $_GET['docentry'];
$qry = odbc_exec($MSSQL_CONN, " USE [" . $_SESSION['mssqldb'] . "];
			SELECT
                        T0.ItemCode, T0.Dscription, T0.FromWhsCod, T2.WhsName AS [FromWarehouseName]
                        , T0.WhsCode, T3.WhsName AS [ToWarehouseName], T0.Quantity, T0.UomCode, T0.UomCode2, T0.LineNum, T0.DocEntry
                        FROM WTQ1 T0
                        LEFT JOIN OWHS T2
                        ON T0.FromWhsCod = T2.WhsCode
                        LEFT JOIN OWHS T3
                        ON T0.WhsCode = T3.WhsCode
				WHERE T0.DocEntry ='$docentry'
                                    AND T0.LineStatus <> 'C'
				ORDER BY T0.LineNum");
$ctr = 1;
?>
<?php
while (odbc_fetch_row($qry)):

    $ItemCode = odbc_result($qry, "ItemCode");
    $ItemName = odbc_result($qry, "Dscription");
    $UomCode = odbc_result($qry, "UomCode");
    $UomCode2 = odbc_result($qry, "UomCode2");
    $FromWhsCod = odbc_result($qry, "FromWhsCod");
    $FromWarehouseName = odbc_result($qry, "FromWarehouseName");
    $WhsCode = odbc_result($qry, "WhsCode");
    $ToWarehouseName = odbc_result($qry, "ToWarehouseName");
    $Quantity = number_format(odbc_result($qry, "Quantity"), 2, '.', ',');
    $LineNum = odbc_result($qry, "LineNum");
    ?>
    <tr>
        <td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center">
            <a class="invdata" data-toggle="modal" data-target="#InvDataModal"><span class="glyphicon glyphicon-arrow-right"></span></a> <?php echo $ctr; ?>
        </td>
        <td style="padding-top: 2px;  padding-bottom: 2px;">
            <div class="input-group itemcodeCont">
                <input class="form-control input-sm itemcode required" value="<?php echo $ItemCode; ?>"/>
                <span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#ItemModal"><span class="glyphicon glyphicon-list"></span></span>
            </div>

        </td>
        <td><input class="form-control input-sm itemname" value="<?php echo $ItemName; ?>"/></td>
        <td class="">
            <div class="input-group warehouseCont">
                <input class="form-control input-sm linefromwarehouse required" name="txtLineFromWarehouse" value="<?php echo $FromWarehouseName; ?>" aria-whscode="<?php echo $FromWhsCod; ?>"/>
                <span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#WhsModal"><span class="glyphicon glyphicon-list"></span></span>
            </div>
        </td>
        <td class="">
            <div class="input-group warehouseCont">
                <input class="form-control input-sm linetowarehouse required"  name="txtLineToWarehouse"  value="<?php echo $ToWarehouseName; ?>" aria-whscode="<?php echo $WhsCode; ?>"/>
                <span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#WhsModal"><span class="glyphicon glyphicon-list"></span></span>
            </div>
        </td>
        <td class="hidden">
            <div class="input-group barcodeCont">
                <input class="form-control input-sm barcode" />
                <span class="input-group-addon" data-toggle="modal" data-target="#BarcodeModal"><span class="glyphicon glyphicon-list"></span></span>
            </div>
        </td>
        <td><input class="form-control input-sm qty required numericvalidate" value="<?php echo $Quantity; ?>">
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
        <td class="hidden"><input class="form-control input-sm price numeric"></td>
        <td class="hidden">
            <div class="input-group warehouseCont">
                <input class="form-control input-sm warehouse" />
                <span class="input-group-addon" data-toggle="modal" data-target="#WhsModal"><span class="glyphicon glyphicon-list"></span></span>
            </div>
        </td>
        <td class="hidden"><select class="form-control input-sm taxcode"></select></td>
        <td class="hidden"><input class="form-control input-sm discount numeric"></td>
        <td class="hidden"><input class="form-control input-sm grossprice numeric"></td>
        <td class="hidden"><input readonly class="form-control input-sm taxamount numeric" ></td>
        <td class="hidden"><input readonly class="form-control input-sm linetotal numeric"></td>
        <td class="hidden"><input readonly class="form-control input-sm grosstotal numeric"></td>
        <td class="hidden"><select class="form-control input-sm branchesoutlets required"><?php echo $branchesoutlets ?></select></td>
        <td class="hidden"><select class="form-control input-sm truckplatenumber"><?php echo $truckplatenumber ?></select></td>
        <td class="hidden"><input readonly class="form-control input-sm lineno" value="<?php echo $LineNum; ?>"></td>
        <td class="ftext text-center hidden">N</td>
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