<?php

include_once('../../../config/config.php');
if(isset($_GET['CardCode']))
{
  $CardCode = str_replace("'", "''", $_GET['CardCode']);
  if(isset($_GET['srchval']))
  {
    $srchval = str_replace("'", "''", $_GET['srchval']);
    
    $itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 AcctName, FormatCode, AcctCode FROM OACT WHERE Postable = 'Y' AND frozenFor = 'N' 
    AND (AcctName LIKE '%".$srchval."%' OR FormatCode LIKE '%".$srchval."%')
    AND AcctCode = (SELECT ISNULL(DpmClear,'') FROM OCRD WHERE CardCode =  '".$CardCode."')
    ORDER BY FormatCode";
  }
  else
  {
    $itemcode = $_POST['itemcode'];
    
    $itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20 AcctName, FormatCode, AcctCode FROM OACT WHERE Postable = 'Y' AND frozenFor = 'N' 
    AND AcctCode = (SELECT ISNULL(DpmClear,'') FROM OCRD WHERE CardCode =  '".$CardCode."')
    AND FormatCode > '".$itemcode."' ORDER BY FormatCode";	
  }
}
else
{
  $itemcode = $_POST['itemcode'];
    
    $itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 1 '' AcctName, '' FormatCode, '' AcctCode FROM OACT WHERE Postable = 'Y' AND frozenFor = 'N'  
    AND FormatCode > '".$itemcode."' ORDER BY FormatCode";	
}

$qry = odbc_exec($MSSQL_CONN, $itemqry);
while (odbc_fetch_row($qry)) 
{
	echo '<tr class="srch">
			<td class="item-2">'.odbc_result($qry, 'FormatCode').'</td>
			<td class="item-1">'.odbc_result($qry, 'AcctName').'</td>
			
			<td class="item-3 hidden">'.odbc_result($qry, 'AcctCode').'</td>
		  </tr>';
}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
