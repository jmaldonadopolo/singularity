<?php
session_start();
include_once ("includes/lla_generales.php");
$estatico = get_estatico();
if(isset($_SESSION[$estatico['sesion_ll']])){
	header("Location: index.php");
}else{ 
	$page = 'Login';
	doctype($page);
	include ("pages/ll_login.php"); 
}
?>
