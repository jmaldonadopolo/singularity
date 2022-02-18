<?php 
include_once("includes/lla_generales.php");

$id = $_GET['id'];

?>
<?php
$query_secciones = "SELECT * FROM secciones WHERE id_menu = '$id' AND estado_seccion = '1'";
$secciones = obtener_todo($query_secciones);
if ($secciones){
?>
<div class="titulo_crear">Sección:</div>
<select name="seccion_slc">
<?php
foreach ($secciones as $row) {
echo '<option value="' . $row['id_seccion'] . '" >' . $row['nombre_seccion'] . '</option>';
}
?>
</select>
<?php
}
?>