<?php
include_once ("funciones/lla_db_fns.php");
include_once ("funciones/admin_lla_fnc.php"); 
$idfoto = $_POST['idfoto'];
$idpro = $_POST['idpro'];
$query_sel = "SELECT * FROM fotos WHERE id_producto = $idpro";
$onbt = obtener_todo($query_sel);
foreach($onbt as $itemf){
	if($itemf['id_foto'] == $idfoto ){
		$query = "UPDATE fotos SET id_inicio = 1 WHERE id_foto = $itemf[id_foto]";
		actualizar_registro($query);
	}else{
		$query = "UPDATE fotos SET id_inicio = 0 WHERE id_foto = $itemf[id_foto]";
		actualizar_registro($query);
	}
}

//$query = "update fotos set id_inicio = 1 where id_foto = $idfoto";
//actualizar_registro($query);