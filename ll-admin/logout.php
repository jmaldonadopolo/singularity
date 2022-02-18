<?php
session_start (); 
include_once ("includes/lla_generales.php");
$estatico = get_estatico();
unset($_SESSION[$estatico['sesion_ll']]); 
header("Location: login.php");
?>