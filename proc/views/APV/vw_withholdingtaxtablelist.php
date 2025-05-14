<?php  include_once('../../../config/config.php'); 

$docentry = $_GET['docentry'];
$qry = odbc_exec($MSSQL_CONN, " USE [".$_SESSION['mssqldb']."];
												SELECT T0.WTCode, T1.WTName, T0.Rate, T0.TaxbleAmnt
												FROM PCH5 T0
												INNER JOIN OWHT T1 ON T0.WTCode = T1.WTCode
												WHERE T0.AbsEntry = $docentry ");
$ctr = 1;
while (odbc_fetch_row($qry)) 
{
	echo '<tr><td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center">' .$ctr . '</td>
				<td style="padding-top: 2px;  padding-bottom: 2px;"><input type="hidden" class="lineid" value=""><input type="hidden" class="wtcode" value="' .odbc_result($qry, 'WTCode').'">&nbsp;' .odbc_result($qry, 'WTCode'). '</td>
				<td style="padding-top: 2px;  padding-bottom: 2px;"><input type="hidden" class="wtname" value="' .odbc_result($qry, 'WTName').'">&nbsp;' .odbc_result($qry, 'WTName').'</td>
				<td style="padding-top: 2px;  padding-bottom: 2px;"><input type="hidden" class="rate" value="'.odbc_result($qry, 'Rate').'">&nbsp;' .odbc_result($qry, 'Rate').'</td>
				<td style="padding-top: 2px;  padding-bottom: 2px;"><input type="type" class="taxableamount" value="' .number_format(odbc_result($qry, 'TaxbleAmnt'),2).'"></td>
				<td style="padding: 0px;" valign="middle"></td></tr>';
$ctr += 1;
}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>


