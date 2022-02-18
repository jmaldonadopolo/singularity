<?php
session_start();
include_once("includes/generales_ll.php");
$pagina = "Servicio al Cliente";
doctype($pagina);
cabecera();
include_once("pages/internas_ll.php");
footer();
?>