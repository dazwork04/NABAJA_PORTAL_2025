<?php

include_once('../../../config/config.php');
$vendor = isset($_GET['vendor']) ? $_GET['vendor'] : '';
$CardType = isset($_GET['CardType']) ? $_GET['CardType'] : '';
$selDocCur = isset($_GET['selDocCur']) ? $_GET['selDocCur'] : '';

if($vendor != '')
{
  if($selDocCur == 'PHP')
  {
    if(isset($_GET['srchval']))
    {
      $srchval = str_replace("'", "''", $_GET['srchval']);
      
      $itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20
          T0.DocEntry,
          T0.DocNum,  
          T0.DpmStatus, 
          T0.NumAtCard,
          T0.Comments,
          T0.DocDate,
          T0.CardCode,
          T0.CardName,
          T0.DocType,
          T0.DocCur,
          ISNULL(T0.DpmAmnt,0) - ISNULL(T0.DpmAppl,0) as 'DpmAmntBal',
          ISNULL(T0.VatSum,0) - ISNULL(T0.DpmAppVat,0) as 'DpmAppVatBal',
          (ISNULL(T0.DpmAmnt,0) - ISNULL(T0.DpmAppl,0)) + (ISNULL(T0.VatSum,0) - ISNULL(T0.DpmAppVat,0)) as 'DpmGrossBal'
        FROM ODPI T0
        WHERE T0.DpmStatus = 'O'
        AND T0.DocCur = 'PHP'
        AND T0.CardCode ='". $vendor ."'
        AND (T0.DocNum LIKE '%$srchval%' OR T0.NumAtCard  LIKE '%$srchval%' OR T0.Comments  LIKE '%$srchval%')
        ORDER BY T0.DocEntry";
    }
    else
    {
      $itemcode = $_POST['itemcode'];
      
      $itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20
          T0.DocEntry,
          T0.DocNum,  
          T0.DpmStatus, 
          T0.NumAtCard,
          T0.Comments,
          T0.DocDate,
          T0.CardCode,
          T0.CardName,
          T0.DocType,
          T0.DocCur,
          ISNULL(T0.DpmAmnt,0) - ISNULL(T0.DpmAppl,0) as 'DpmAmntBal',
          ISNULL(T0.VatSum,0) - ISNULL(T0.DpmAppVat,0) as 'DpmAppVatBal',
          (ISNULL(T0.DpmAmnt,0) - ISNULL(T0.DpmAppl,0)) + (ISNULL(T0.VatSum,0) - ISNULL(T0.DpmAppVat,0)) as 'DpmGrossBal'
        FROM ODPI T0
        WHERE T0.DpmStatus = 'O'
        AND T0.DocCur = 'PHP'
        AND T0.CardCode ='". $vendor ."'
        AND T0.DocEntry > '".$itemcode."'
        ORDER BY T0.DocEntry";
    }
  }
  else {
    if(isset($_GET['srchval']))
    {
      $srchval = str_replace("'", "''", $_GET['srchval']);
      
      $itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20
          T0.DocEntry,
          T0.DocNum,  
          T0.DpmStatus, 
          T0.NumAtCard,
          T0.Comments,
          T0.DocDate,
          T0.CardCode,
          T0.CardName,
          T0.DocType,
          T0.DocCur,
          ISNULL(T0.DpmAmntFC,0) - ISNULL(T0.DpmApplFc,0) as 'DpmAmntBal',
          ISNULL(T0.VatSumFC,0) - ISNULL(T0.DpmAppVatF,0) as 'DpmAppVatBal',
          (ISNULL(T0.DpmAmntFC,0) - ISNULL(T0.DpmApplFc,0)) + (ISNULL(T0.VatSumFC,0) - ISNULL(T0.DpmAppVatF,0)) as 'DpmGrossBal'
        FROM ODPI T0
        WHERE T0.DpmStatus = 'O'
        AND T0.DocCur != 'PHP'
        AND T0.CardCode ='". $vendor ."'
        AND (T0.DocNum LIKE '%$srchval%' OR T0.NumAtCard  LIKE '%$srchval%' OR T0.Comments  LIKE '%$srchval%')
        ORDER BY T0.DocEntry";
    }
    else
    {
      $itemcode = $_POST['itemcode'];
      
      $itemqry = "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20
          T0.DocEntry,
          T0.DocNum,  
          T0.DpmStatus, 
          T0.NumAtCard,
          T0.Comments,
          T0.DocDate,
          T0.CardCode,
          T0.CardName,
          T0.DocType,
          T0.DocCur,
          ISNULL(T0.DpmAmntFC,0) - ISNULL(T0.DpmApplFc,0) as 'DpmAmntBal',
          ISNULL(T0.VatSumFC,0) - ISNULL(T0.DpmAppVatF,0) as 'DpmAppVatBal',
          (ISNULL(T0.DpmAmntFC,0) - ISNULL(T0.DpmApplFc,0)) + (ISNULL(T0.VatSumFC,0) - ISNULL(T0.DpmAppVatF,0)) as 'DpmGrossBal'
        FROM ODPI T0
        WHERE T0.DpmStatus = 'O'
        AND T0.DocCur != 'PHP'
        AND T0.CardCode ='". $vendor ."'
        AND T0.DocEntry > '".$itemcode."'
        ORDER BY T0.DocEntry";
    }
  }

	$qry = odbc_exec($MSSQL_CONN, $itemqry);
	while (odbc_fetch_row($qry)) {

    //Check if Closed
    $disabled = '';
    $disabled1 = 'readonly';
    // if($LineStatus == 'C'){
    // 	$disabled = 'readonly';
    // }
    //End Check if Closed
			 
				
		echo '<tr class="srch">
                <td class="item-1 hidden">'.odbc_result($qry, 'DocEntry').'</td>
                <td class="item-2">'.odbc_result($qry, 'DocNum').'</td>
                <td class="item-3">'.utf8_encode(odbc_result($qry, 'NumAtCard')).'</td>
                <td class="item-4">'.utf8_encode(odbc_result($qry, 'Comments')).'</td>
                <td class="item-5"><input '.$disabled.' class="form-control input-sm price required numeric numericvalidate" value="'.number_format(odbc_result($qry, 'DpmAmntBal'),2,'.',',').'"></td>
                <td class="item-6"><input '.$disabled1.' class="form-control input-sm price required numeric numericvalidate" value="'.number_format(odbc_result($qry, 'DpmAppVatBal'),2,'.',',').'"></td>
                <td class="item-7"><input '.$disabled1.' class="form-control input-sm price required numeric numericvalidate" value="'.number_format(odbc_result($qry, 'DpmGrossBal'),2,'.',',').'"></td>
                <td class="item-8">'.number_format(odbc_result($qry, 'DpmAmntBal'),2,'.',',').'</td>
                <td class="item-9">'.number_format(odbc_result($qry, 'DpmAppVatBal'),2,'.',',').'</td>
                <td class="item-10">'.number_format(odbc_result($qry, 'DpmGrossBal'),2,'.',',').'</td>
                  </tr>';
	}


  odbc_free_result($qry);
  odbc_close($MSSQL_CONN);

} 

?>
