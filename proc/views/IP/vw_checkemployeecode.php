<?php 
include_once('../../../config/config.php');


$employeename = $_POST['employeename'];

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT firstName, lastName, empID, branch, dept ".
        "FROM OHEM WHERE (lastName + ', ' + firstName) = '".$employeename."'");

odbc_fetch_row($qry);

$empId = odbc_result($qry, 'empID');

if($empId && $empId != '')
{
    echo odbc_result($qry, 'lastName') . ', ' . odbc_result($qry, 'firstName') . ';' . $empId;
}
 else {
     echo ';';
}

odbc_free_result($qry);

odbc_close($MSSQL_CONN);

?>