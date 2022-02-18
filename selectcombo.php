<?php 
include_once ("ll-admin/funciones/lla_db_fns.php");
include_once ("ll-admin/funciones/admin_lla_fnc.php");


$prov =  $_POST['selprov'];
$dis = $_POST['seldist'];
$costo = $_POST['costo'];
$monto = $_POST['actmonto'];
$costolimlim = $_POST['costolimlim'];
if ($prov) {
	$iddepa =  $_POST['id_depa'];
	$query_sel = "select * from provincias where idDepa = $iddepa";
	$obtener = obtener_todo($query_sel);
	foreach ($obtener as $itemprov) {
		
		echo "<option value='$itemprov[idProv]'>$itemprov[provincia]</option>";
	}

	
}
if ($dis) {
	$query_prv = "select * from provincias where estado_prov = '1'";
	$obtn_prv = obtener_linea($query_prv);
	$idprv = $obtn_prv['idProv'];
	
	$iddist = $_POST['id_dist'];
	if($iddist == $idprv){
		$query_sel = "select * from distritos where idProv = '$iddist' and estado_dist = 1";
	}else{
		$query_sel = "select * from distritos where idProv = '$iddist' ";
	}
	
	$obtener = obtener_todo($query_sel);
	foreach ($obtener as $itemdist) {
		
		echo "<option value='$itemdist[idDist]'>$itemdist[distrito]</option>";
	}
	
}
if ($costo) {
	$iddepa =  $_POST['id_depar'];
	$query_sel = "select * from departamentos where id_departamentos = $iddepa";
	$obtener = obtener_linea($query_sel);
	$costo_envio = $obtener['costo_envio'];
		//echo "$obtener[costo_envio]";
		echo "S/. ". number_format($costo_envio, 2, '.', '');
	
}
if ($monto) {
	$costoe = json_decode($_POST['costoe']);
$iddep = json_decode($_POST['iddep']);

	$cont = count($costoe);

	if (is_array($costoe)) {
			for ($i=0; $i < $cont; $i++) { 
			 $query = "UPDATE departamentos set costo_envio = $costoe[$i] where id_departamentos = '$iddep[$i]'";
			 actualizar_registro($query);
					
			 }
		echo "bien";
	}else{
		echo "error";	
	}	

}
if ($costolimlim) {
	$prov = $_POST['prov'];
	$query_prov = "UPDATE provincias set costo_envio_prov = '$prov' where idProv = 127";
	if (actualizar_registro($query_prov)) {
		echo "bien";
	}else{
		echo "mal";
	}
}