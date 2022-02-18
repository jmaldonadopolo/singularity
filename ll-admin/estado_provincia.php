<?php 
include_once ("funciones/lla_db_fns.php");
include_once ("funciones/admin_lla_fnc.php");


$idprov = $_POST['idprov'];


$query = "select * from provincias where idProv = '$idprov'";
$obtn = obtener_linea($query);

	$query_actcer = "update provincias set estado_prov = '0'";
	if(actualizar_registro($query_actcer)){
		$query_act = "update provincias set estado_prov = '1' where idProv = '$idprov'";
		if(actualizar_registro($query_act)){
			echo "bien";
		}else{
			echo "mal";
		}
	}else{
		echo "mal";
	}
	
