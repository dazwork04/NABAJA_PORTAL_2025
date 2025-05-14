<?php
@session_start();


  
   
   function createDatabase(){
   //**************************************
   //MSSQL
   
   $MSSQL_CONN = odbc_connect("Driver={SQL Server Native Client 11.0};Server=$MSSQL_SERVER;", $MSSQL_USER, $MSSQl_PASSWORD) or die('Could not open database!');
   
   //Create Session
   $_SESSION['MDUser'] = $MSSQL_USER;
   $_SESSION['MDPass'] = $MSSQl_PASSWORD;
   $_SESSION['MDServer'] = $MSSQL_SERVER;
   $_SESSION['MDdb'] = '';
   //End Create Session

   //MSSQL
   //**************************************
   		//Create Database
         
   		$qry = odbc_exec($MSSQL_CONN, "DECLARE @QryStr NVARCHAR(MAX); SET @QryStr = 'IF NOT (EXISTS (SELECT name 
				FROM master.dbo.sysdatabases 
				WHERE name = ''WEB-COMMON''))
					BEGIN
						
						CREATE DATABASE [WEB-COMMON]

					END'; EXEC(@QryStr);");



   		odbc_free_result($qry);
         
   		//End Create Database
   		
   		//Create Table OSVR
   		$qry = odbc_exec($MSSQL_CONN, "DECLARE @QryStr NVARCHAR(MAX); SET @QryStr = 
            'IF NOT (EXISTS (SELECT * 
                 FROM INFORMATION_SCHEMA.TABLES 
                 WHERE TABLE_CATALOG = ''WEB-COMMON'' 
                 AND  TABLE_NAME = ''OSVR''))
            BEGIN
                USE [WEB-COMMON];
                  CREATE TABLE [dbo].[OSVR](
                     [ID] [bigint] IDENTITY(1,1) NOT NULL,
                     [Server] [varchar](150) NULL,
                     [SqlUser] [varchar](50) NULL,
                     [SqlPassword] [varchar](50) NULL,
                     [DBName] [varchar](200) NULL,
                     [SapUser] [varchar](50) NULL,
                     [SapPass] [varchar](50) NULL,
                     [DBVersion] [int] NULL,
                     [Port] [varchar](50) NULL
                  ) ON [PRIMARY]


                  DROP TABLE [dbo].[@OUSR];
                  CREATE TABLE [dbo].[@OUSR](
                     [UserId] [bigint] IDENTITY(1,1) NOT NULL,
                     [UserCode] [varchar](150) NULL,
                     [UserPass] [varchar](50) NULL,
                     [Name] [varchar](50) NULL,
                     [Department] [varchar](200) NULL,
                     [UserType] [varchar](50) NULL,
                     [Status] [varchar](50) NULL,
                     [Roles] [int] NULL,
                     [sapuser] [varchar](50) NULL,
                     [sappass] [varchar](50) NULL
                     
                  ) ON [PRIMARY]


                  DROP TABLE [dbo].[@OPRQ];
                  CREATE TABLE [dbo].[@OPRQ](
                     [DocEntry] [bigint] IDENTITY(1,1) NOT NULL,
                     [DocNum] [varchar](50) NULL,
                     [Requester] [varchar](50) NULL,
                     [RequesterName] [varchar](250) NULL,
                     [Remarks] [varchar](1000) NULL,
                     [DocStatus] [varchar](2) DEFAULT (''O''),
                     [DocDate] [date] DEFAULT(GETDATE()),
                     [DeliveryDate] [date] NULL,
                     [ServiceType] [varchar](2) DEFAULT(''I''),
                     [TotalBefDisc] [decimal](20,2) DEFAULT((0))

                  ) ON [PRIMARY]

                  
                  DROP TABLE [dbo].[@PRQ1];
                  CREATE TABLE [dbo].[@PRQ1](
                     [DocEntry] [bigint] NULL,
                     [ItemCode] [varchar](50) NULL,
                     [ItemName] [varchar](250) NULL,
                     [Quantity] [decimal](20, 2) NULL,
                     [Price] [decimal](20, 2) NULL,
                     [Whse] [varchar](50) NULL,
                     [Uom] [varchar](50) NULL,
                     [TaxCode] [varchar](50) NULL,
                     [Discount] [decimal](20, 0) DEFAULT ((0)),
                     [GrossPrice] [decimal](20, 2) DEFAULT ((0)),
                     [TaxAmt] [decimal](20, 2) DEFAULT ((0)),
                     [LineTotal] [decimal](20, 2) DEFAULT ((0)),
                     [GrossTotal] [decimal](20, 2) DEFAULT ((0)),
                     [ServiceRemarks] [varchar](1000) NULL,
                     [Account] [varchar](50) NULL,
                     [LineNum] [int] DEFAULT ((0)),
                     [OpenQty] [decimal](20, 2) DEFAULT ((0)),
                     [PostedQty] [decimal](20, 2) DEFAULT ((0)),
                     [LineStatus] [varchar] (2) DEFAULT(''O'')
                  ) ON [PRIMARY]

                  DROP TABLE [dbo].[@PRQ10];
                  CREATE TABLE [dbo].[@PRQ10](
                     [DocEntry] [bigint] NULL,
                     
                     [LineNum] [int] DEFAULT ((0)),
                     [AftLineNum] [int] DEFAULT ((0)),
                     [Remarks] [varchar](1000) NULL,
                     [LineStatus] [varchar] (2) DEFAULT(''O'')
                    
                  ) ON [PRIMARY]

                  DROP TABLE [dbo].[@ORLE];
                  CREATE TABLE [dbo].[@ORLE](
                     [DocEntry] [bigint] IDENTITY(1,1) NOT NULL,
                     [RoleCode] [varchar](50) NULL,
                     [RoleName] [varchar](200) NULL
                  ) ON [PRIMARY]


                  DROP TABLE [dbo].[@PRMN];
                  CREATE TABLE [dbo].[@PRMN](
                     [DocEntry] [bigint] IDENTITY(1,1) NOT NULL,
                     [RoleID] [nvarchar](50) NULL,
                     [Module] [varchar](50) NULL,
                     [SubModule] [varchar](100) NULL,
                     [Action] [varchar](100) NULL
                  ) ON [PRIMARY]
                  
            END'; EXEC(@QryStr);");
   		odbc_free_result($qry);
         
   		//End Create Table

         odbc_close($MSSQL_CONN);
								
   }// Enc Create Database



   function insertNewServer($server, $port, $dbuser, $dbpass, $dbversion){
      //**************************************
      
      //$MSSQL_DB = mysql_real_escape_string('SATURN_BRANCH_FINAL');
      $MSSQL_CONN = odbc_connect("Driver={SQL Server Native Client 11.0};Server=$MSSQL_SERVER;", $MSSQL_USER, $MSSQl_PASSWORD) or 
      die('Could not open database!');
      
     
      $errmsg = '';
      //MSSQL
      //**************************************

      odbc_autocommit($MSSQL_CONN,false);

      $qry = odbc_exec($MSSQL_CONN, "USE [WEB-COMMON]; SELECT COUNT(*) AS Res FROM OSVR WHERE Server = '$server'");
      odbc_fetch_row($qry);

      if(odbc_result($qry, 1) == 0){
         //Free Result
         odbc_free_result($qry);

         $qry = odbc_exec($MSSQL_CONN, "USE [WEB-COMMON]; INSERT 
                                             INTO OSVR(Server,SqlUser,SqlPassword,DBVersion,Port)
                                             VALUES('$server','$dbuser','$dbpass','$dbversion','$port')");
         if($qry){
            odbc_commit($MSSQL_CONN);
            $errmsg = 'true*';
         }else{
            odbc_rollback($MSSQL_CONN);

            $errmsg = 'false*'.odbc_errormsg();
         }
      }else{
         $errmsg = 'false*Server already exist!';
      }

      return $errmsg;

      odbc_close($MSSQL_CONN);
   }//End insertNewServer

			
   
?>