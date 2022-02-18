<?php
include_once ("funciones/lla_db_fns.php");
include_once ("funciones/admin_lla_fnc.php"); 

$id_prod = $_POST['id_prod'];
$select = $_POST['select'];
if ($select) {
    //$query_contar = "SELECT COUNT(orden_foto) AS contar FROM productos";
    //$obtn = obtener_linea($query_contar);
    //$contador =  $obtn['contar'];
    //if($contador == 0){
    //    $conta = $contador + 1;
    //}else{
    //    $conta = $contador + 1;
    //}
    if($select == "check"){
        $query = "update productos set orden_foto = '1' where id_producto = '$id_prod'";
        actualizar_registro($query);
    }
    if($select == "nocheck"){
        $query = "update productos set orden_foto = '0' where id_producto = '$id_prod'";
        actualizar_registro($query);
    }
    
}else{
    $id_foto =  $_POST['actualizarOrdenSlide'];
$ordenfoto =  $_POST['actualizarOrdenItem'];

$query_update = "update productos set ordenar_foto = '$ordenfoto' where id_producto = '$id_foto'";
actualizar_registro($query_update);

}