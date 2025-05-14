<?php

include_once('../../../config/config.php');

if (isset($_GET['srchval'])) {
    $srchval = str_replace("'", "''", $_GET['srchval']);
	
    $itemqry = "USE [" . $_SESSION['mssqldb'] . "]; SELECT TOP 20 firstName, lastName, empID, branch FROM OHEM WHERE (firstName LIKE '%" . $srchval . "%' OR lastName LIKE '%" . $srchval . "%' OR empID LIKE '%" . $srchval . "%') ORDER BY firstName";
} else {
    $itemcode = $_POST['itemcode'];
    $itemqry = "USE [" . $_SESSION['mssqldb'] . "]; SELECT TOP 20 firstName, lastName, empID, branch FROM OHEM WHERE firstName > '" . $itemcode . "' ORDER BY firstName";
}
?>



<?php

$qry = odbc_exec($MSSQL_CONN, $itemqry);
while (odbc_fetch_row($qry)) {
    echo '<tr class="srch">
				<td class="item-1">' . odbc_result($qry, 'firstName') . '</td>
				<td class="item-2">' . odbc_result($qry, 'lastName') . '</td>
				<td class="item-3">' . odbc_result($qry, 'empID') . '</td>
                                <td class="hidden item-10">' . odbc_result($qry, 'branch') . '</td>
                                <td class="hidden item-11"></td>
		      </tr>';
}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);
?>
