<?php
	//Start session
	session_start();

	//Unset the variables stored in session
	unset($_SESSION['SESS_USER_ID']);
	unset($_SESSION['SESS_USER_FIRST_NAME']);
	unset($_SESSION['SESS_USER_LAST_NAME']);
	unset($_SESSION['LAST_ACTIVITY']);
	
	//redirect to login
	
	// remove all session variables
	session_unset(); 

	// destroy the session 
	session_destroy();

	header('location: index.php');
	exit();
?>