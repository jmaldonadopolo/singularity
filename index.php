<?php
session_start();
include_once("includes/generales_ll.php");
$pagina = "Inicio";
doctype($pagina);
cabecera();
include_once("pages/inicio_ll.php");
footer();
?>