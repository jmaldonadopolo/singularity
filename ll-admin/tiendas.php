<?php
session_start();
include_once ("includes/lla_generales.php");
$estatico = get_estatico();
if(isset($_SESSION[$estatico['sesion_ll']])){
	$page = "Tiendas";
	doctype($page);
	ll_header($page);
	include_once ("pages/ll_tiendas.php");
	ll_footer($estatico);	
}else{
	header("Location: login.php");
}
?>