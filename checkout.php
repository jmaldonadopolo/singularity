<?php
session_start();
include_once ("includes/generales_ll.php");
$pagina = "Checkout";
doctype($pagina);
cabecera();
include_once ("pages/checkout_ll.php");
footer();
?>
