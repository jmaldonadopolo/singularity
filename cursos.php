<?php
session_start();
include_once("includes/generales_ll.php");
$pagina = "Cursos";
doctype($pagina);
cabecera();
include_once("pages/cursos_ll.php");
footer();
?>