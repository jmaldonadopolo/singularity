<?php 
include_once ("../ll-admin/funciones/lla_db_fns.php");
include_once ("../ll-admin/funciones/admin_lla_fnc.php");
$seleccionado = $_POST['seleccionado'];
$nrosql = $_POST['nrosql'];

$query_producto = "UPDATE productos set id_inicio = '$nrosql' where id_producto = '$seleccionado'";
actualizar_registro($query_producto);