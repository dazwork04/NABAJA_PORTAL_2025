<?php 
include_once('../../config/config.php');
$MSSQL_DB2 = 'HIRAM-COMMON';

$qry = odbc_exec($MSSQL_CONN, "USE [".$MSSQL_DB2."]; SELECT DISTINCT VoidPass FROM [dbo].[@OUSR]");

while (odbc_fetch_row($qry)) 
{
	echo odbc_result($qry, 'VoidPass');
}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
