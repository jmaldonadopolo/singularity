<?php 
include_once("includes/lla_generales.php");

$id = $_GET['id'];

?>
<?php
$query_secciones = "SELECT * FROM subsecciones WHERE id_seccion = '$id' AND activo_subseccion = '1'";
$secciones = obtener_todo($query_secciones);
if ($secciones){
?>
<div class="titulo_crear">Subseccion:</div>
<select name="subseccion_slc">
<?php
foreach ($secciones as $row) {
echo '<option value="' . $row['id_subseccion'] . '" >' . $row['nombre_subseccion'] . '</option>';
}
?>
</select>
<?php
}
?>