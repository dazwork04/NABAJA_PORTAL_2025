<?php
include_once('../../../config/configmd.php');

$docentry = $_GET['docentry'];
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['MDdb']."]; 
		SELECT T0.DocNum,CONVERT(VARCHAR(10),T0.DeliveryDate,101) AS DeliveryDate,CONVERT(VARCHAR(1000),T0.Remarks) AS Remarks,T0.DocStatus,T0.ServiceType
		FROM [@OPRQ] T0
		WHERE T0.DocEntry ='{$_GET['docentry']}'");

$arr = array();

while (odbc_fetch_row($qry)) {
	$arr[] = array("DocNum" => odbc_result($qry, 'DocNum'),
				"DeliveryDate" => odbc_result($qry, 'DeliveryDate'),
				"Remarks" => odbc_result($qry, 'Remarks'),
				"DocStatus" => odbc_result($qry, 'DocStatus'),
				"ServiceType" => odbc_result($qry, 'ServiceType')
				);
            
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);


echo json_encode($arr);
?>