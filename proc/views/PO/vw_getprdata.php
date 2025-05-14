<?php
include_once('../../../config/config.php');

$docentry = $_GET['docentry'];

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
		SELECT T0.DocNum,T0.DocDueDate AS DeliveryDate, CASE WHEN T0.Comments IS NULL THEN '' ELSE T0.Comments END AS Remarks, T0.DocStatus, T0.DocType
		FROM [OPRQ] T0
		WHERE T0.DocEntry ='$docentry'");

$arr = array();

while (odbc_fetch_row($qry)) {
	$arr[] = array("DocNum" => odbc_result($qry, 'DocNum'),
				"DeliveryDate" => date('m/d/Y' ,strtotime(odbc_result($qry, 'DeliveryDate'))),
				"Remarks" => odbc_result($qry, 'Remarks'),
				"DocStatus" => odbc_result($qry, 'DocStatus'),
				"ServiceType" => odbc_result($qry, 'DocType')
				);
            
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

echo json_encode($arr);
?>