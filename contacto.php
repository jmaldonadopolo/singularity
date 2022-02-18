<?php
session_start();
include_once("includes/generales_ll.php");
$pagina = "Contacto";
doctype($pagina);
cabecera();
include_once("pages/contacto_ll.php");
footer();
?>

