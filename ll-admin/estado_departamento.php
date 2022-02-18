<?php 
include_once ("funciones/lla_db_fns.php");
include_once ("funciones/admin_lla_fnc.php");


$id_depa = $_POST['id_depa'];


$query = "select * from departamentos where id_departamentos = '$id_depa'";
$obtn = obtener_linea($query);

	$query_actcer = "update departamentos set estado_departamento = '0'";
	if(actualizar_registro($query_actcer)){
		$query_act = "update departamentos set estado_departamento = '1' where id_departamentos = '$id_depa'";
		if(actualizar_registro($query_act)){
			$query_prov = "update provincias set estado_prov = '0'";
			actualizar_registro($query_prov);
			$query_provv = "update provincias set estado_prov = '1' where idProv = '".$id_depa."01'";
			actualizar_registro($query_provv);
			echo "bien";
		}else{
			echo "mal";
		}
	}else{
		echo "mal";
	}
	
