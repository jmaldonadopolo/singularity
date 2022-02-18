<?php
session_start();
include_once("includes/generales_ll.php");
$pagina = "Compra";
doctype($pagina);
cabecera();
include_once("pages/compra_ll.php");
footer();
?>

