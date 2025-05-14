<?php
include_once('../../../config/config.php');

$docentry = $_GET['docentry'];
$rowno = 1;
$qry = odbc_exec($MSSQL_CONN, " USE [" . $_SESSION['mssqldb'] . "];
						SELECT 
								T0.CreditCard,
								T1.CardName, 
								T0.CreditAcct, 
								'**********' AS CrCardNum, 
								T0.CardValid, 
								T0.CreditSum, 
								T0.VoucherNum
						FROM VPM3 T0
						LEFT JOIN OCRC T1 ON T0.CreditCard = T1.CreditCard
						WHERE T0.DocNum = '$docentry' ");

while (odbc_fetch_row($qry))
{
echo '<tr>
			<td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center">' . $rowno .'</td>
			<td>'.utf8_encode(odbc_result($qry, "CardName")).'</td>
			<td class="hidden"><input type="hidden" class="lineid" value=""><input class="creditcardname hidden" value="'.utf8_encode(odbc_result($qry, "CreditCard")).'"></td>
			<td class="hidden"><input class="glaaccountcreditcard" value="'.utf8_encode(odbc_result($qry, "CreditAcct")).'"></td>
			<td class="hidden"><input class="creditcardno" value="'.odbc_result($qry, "CrCardNum").'"></td>
			<td class="hidden"><input class="validuntil" value="'.date('m/d/Y', strtotime(odbc_result($qry, "CardValid"))) .'"></td>
			<td class="hidden"><input class="amountdue" value="'.odbc_result($qry, "CreditSum").'"></td>
			<td class="hidden"><input class="voucherno" value="'.utf8_encode(odbc_result($qry, "VoucherNum")).'"></td>
			<td style="padding: 0px;" valign="middle"><center><button type="button" class="btn-danger" id="btnDelRow1" disabled><i class="fa fa-times"></i></button></center></td>
		</tr>';
$rowno++;
}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);
?>
