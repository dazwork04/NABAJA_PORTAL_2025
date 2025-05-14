<?php

include_once('../../../config/config.php');
if (isset($_GET['weightlive']) && strlen($_GET['weightlive']) > 0 && is_numeric($_GET['weightlive'])) {
    $vendor = isset($_GET['vendor']) ? $_GET['vendor'] : '';
    $weightlive = $_GET['weightlive'];
    try {
        $qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb']
                . "]; SELECT TOP 1 U_Price as [U_Price] FROM [dbo].[@SUPPL] WHERE [U_CardCode] = '" . $vendor . "'"
                . " AND " . $weightlive . " BETWEEN [U_WeightFrom] AND [U_WeightTo]");
        if (odbc_fetch_row($qry)) {
            echo number_format(odbc_result($qry, 'U_Price'), 2);
        } else {
            echo 0;
        }
        odbc_free_result($qry);
        odbc_close($MSSQL_CONN);
    } catch (Exception $ex) {
        echo print_r($ex, true);
    }
} else {

    echo number_format(0, 2);
}
?>
