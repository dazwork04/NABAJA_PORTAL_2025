<?php include_once('../../../config/config.php');
$udt = $_GET['udt'];

if($udt == 'REALTY')
{ 
  echo '<option value=""></option>';
  $qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT Code,Name FROM [@REALTY] ORDER BY Name");
  
  while (odbc_fetch_row($qry)) {
    echo '<option value="'.odbc_result($qry, 'Code').'" >'.odbc_result($qry, 'Name').'</option>';
  }
  odbc_free_result($qry);
  odbc_close($MSSQL_CONN);

}

if($udt == 'SALESCOORDINATOR')
{
  echo '<option value=""></option>';
  $qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT Code,Name FROM [@SALESCOORDINATOR] ORDER BY Name");
  
  while (odbc_fetch_row($qry)) {
    echo '<option value="'.odbc_result($qry, 'Code').'" >'.odbc_result($qry, 'Name').'</option>';
  }
  odbc_free_result($qry);
  odbc_close($MSSQL_CONN);
}




?>
