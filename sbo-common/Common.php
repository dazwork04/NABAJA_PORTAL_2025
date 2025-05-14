<?php

Class Common {

    public static function auto_version($file) {
        if (strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file))
            return $file;

        $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
        return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
    }

    public static function isDevServer() {
        if (gethostname() == 'CE201706') {
            return false;
        }
        return false;
    }

    public static function getDiffInDays($date1, $date2) {
        $dStart = new DateTime($date1);
        $dEnd = new DateTime($date2);
        $dDiff = $dStart->diff($dEnd);
//        return $dDiff;
        return (int) $dDiff->format("%r%a"); // use for point out relation: smaller/greater
    }

    public static function getBranchesOutletsOptions() {
        return "SELECT OcrCode, OcrName FROM OOCR WHERE DimCode = 1 ORDER BY (CASE WHEN OcrCode = 'NA' THEN 0 ELSE 1 END), OcrCode";
    }

    public static function getBranchesOptions() {
        return "SELECT Code, Name, Remarks FROM OUBR";
    }

    public static function getDepartmentOptions() {
        return "SELECT Code, Name, Remarks FROM OUDP";
    }

    public static function getTruckPlateNumberOptions() {
        return "SELECT OcrCode, OcrName FROM OOCR WHERE DimCode = 5 ORDER BY (CASE WHEN OcrCode = 'N/A' THEN 0 ELSE 1 END), OcrCode";
    }

    public static function getTaxCodeOptions($selectedvalue) {
        include('/../config/config.php');
        $taxcode = '';
        $qry = odbc_exec($MSSQL_CONN, "USE [" . $_SESSION['mssqldb'] . "]; SELECT Code,Name,Rate FROM OVTG WHERE Inactive = 'N' AND Category='I'");

        while (odbc_fetch_row($qry)) {
            $taxcode .= '<option val-rate="' . number_format(odbc_result($qry, "Rate"), 4, '.', '.')
                    . '" value="' . odbc_result($qry, "Code") . '" ' . ($selectedvalue == odbc_result($qry, "Code") ? 'selected' : '') . '>'
                    . odbc_result($qry, "Code") . ' - ' . utf8_encode(odbc_result($qry, "Name")) . '</option>';
        }

        echo $taxcode;
    }

}

?>