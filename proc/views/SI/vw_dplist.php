<?php include_once('../../../config/config.php');
$CardType = $_GET['CardType'];
$vendor = isset($_GET['vendor']) ? $_GET['vendor'] : '';
$selDocCur = isset($_GET['selDocCur']) ? $_GET['selDocCur'] : '';

?>
<div class="table-responsive" style="height: 350px; width:100%; border: solid lightblue 1px;">
	<table width="100%" class="table table-condensed table-hover table-bordered table-striped" id="tblDP">
	  <thead>
	    <tr>
        <th class='hidden'>DocEntry</th>
	      <th>Doc. No.</th>
	      <th>Ref No.</th>
	      <th>Remarks</th>
	      <th>Net Amt</th>
	      <th>Tax Amt</th>
        <th>Gross Amt</th>
        <th>Open Net Amt</th>
	      <th>Open Tax Amt</th>
        <th>Open Gross Amt</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
        if($vendor != '' && $selDocCur != '')
        {
          if($selDocCur == 'PHP')
          {
            $qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20
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
              ORDER BY T0.DocEntry");
          } 
          else
          {
            $qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT TOP 20
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
              ORDER BY T0.DocEntry");
          }

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
                <td class="item-5"><input '.$disabled.' class="form-control input-sm DpmAmntBal required numeric numericvalidate" value="'.number_format(odbc_result($qry, 'DpmAmntBal'),2,'.',',').'"></td>
                <td class="item-6"><input '.$disabled1.' class="form-control input-sm price DpmAppVatBal numeric numericvalidate" value="'.number_format(odbc_result($qry, 'DpmAppVatBal'),2,'.',',').'"></td>
                <td class="item-7"><input '.$disabled1.' class="form-control input-sm price DpmGrossBal numeric numericvalidate" value="'.number_format(odbc_result($qry, 'DpmGrossBal'),2,'.',',').'"></td>
                <td class="item-8">'.number_format(odbc_result($qry, 'DpmAmntBal'),2,'.',',').'</td>
                <td class="item-9">'.number_format(odbc_result($qry, 'DpmAppVatBal'),2,'.',',').'</td>
                <td class="item-10">'.number_format(odbc_result($qry, 'DpmGrossBal'),2,'.',',').'</td>
                  </tr>';
          }
          odbc_free_result($qry);
          odbc_close($MSSQL_CONN);

        }


	  		
        
	  	?>
	  </tbody>
    </table>
</div>
