<?php 
	
	require_once("../configglobal.php");
	require_once("User.class.php");
	
	$database = "if15_kadri";

	session_start();

	$mysqli = new mysqli($servername, $server_username, $server_password, $database);		

	//saadan ühenduse classi ja loon uue classi
	$User = new User($mysqli);
	
	//var_dump($User->connection);

	
?>