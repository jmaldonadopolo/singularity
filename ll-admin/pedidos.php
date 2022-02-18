<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once ("includes/lla_generales.php");
$estatico = get_estatico();
if(isset($_SESSION[$estatico['sesion_ll']])){
	$page = "Pedidos";
	doctype($page);
	ll_header($page);
	include_once ("pages/ll_pedidos.php");
	ll_footer($estatico);	
}else{
	header("Location: login.php");
}
?>