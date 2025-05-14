<?php include_once('../../../config/config.php'); ?>

<div class='text-error'> 
	
	  	<?php

      if(isset($_GET['CardCode']))
      {
        $CardCode = str_replace("'", "''", $_GET['CardCode']);

        $qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."];  SELECT 
                CASE 
                  WHEN ISNULL(DpmClear,'') = '' THEN CONCAT('Downpayment Clearing Account is Empty - ', CardName)
                  ELSE ''
                END AS CON
                FROM OCRD WHERE CardCode =  '".$CardCode."' ");

        while (odbc_fetch_row($qry)) {
          echo odbc_result($qry, 'CON');
        }
        odbc_free_result($qry);
        odbc_close($MSSQL_CONN);
      
      }
      else
      {
          
          echo ' ';
      } 

			
	  	?> 
</div>
