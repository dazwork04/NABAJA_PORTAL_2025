<?php
include_once('../../../config/configmd.php');

$docentry = $_GET['docentry'];
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."];
		SELECT T0.UserCode,T0.Name,T0.Department,T0.sapuser,T0.sappass,T0.UserType,T0.Roles 
		FROM [@OUSR] T0
		WHERE T0.UserId = '$docentry'
		ORDER BY T0.UserId");

$arr = array();

while (odbc_fetch_row($qry)) {
	$arr[] = array("UserCode" => odbc_result($qry, 'UserCode'),
				"Name" => odbc_result($qry, 'Name'),
				"Department" => odbc_result($qry, 'Department'),
				"sapuser" => odbc_result($qry, 'sapuser'),
				"sappass" => odbc_result($qry, 'sappass'),
				"UserType" => odbc_result($qry, 'UserType'),
				"Roles" => odbc_result($qry, 'Roles')
				);
            
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);


echo json_encode($arr);
?>