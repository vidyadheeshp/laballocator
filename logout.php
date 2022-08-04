<?php 
	session_start();
	 unset($_SESSION['logged_in']); 
	session_destroy();
	//echo 'session destroyed';
	header('location:login.php');
?>