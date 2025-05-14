<?php
include_once('../../sbo-common/script.php');

//Variables
$server = str_replace("'", "''", $_POST['ServerName']);
$port = str_replace("'", "''", $_POST['Port']);
$dbuser = str_replace("'", "''", $_POST['DBUser']);
$dbpass = str_replace("'", "''", $_POST['DBPass']);
$dbversion = str_replace("'", "''", $_POST['DBVersion']);
//End Variables

//Create New Server
$retval = insertNewServer($server, $port, $dbuser, $dbpass, $dbversion);
$res = explode('*', $retval);
//End Create New Server

if ($res[0] == 'true'){
	echo 'true*'.$server;
}else{
	echo 'false*'.$res[1];
}



?>