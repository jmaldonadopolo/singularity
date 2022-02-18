<?php

function obtener_todo($query){
$conn = db_connect();
   $result = @$conn->query($query);
   if (!$result)
     return false;
   $num_cats = @$result->num_rows;
   if ($num_cats ==0)
      return false;  
   $result = db_result_to_array($result);
    return $result;
}

function obtener_linea($query) {
$conn = db_connect();
$result = @$conn->query( $query );
	if ( !$result )
		return false;
	$result = @$result->fetch_assoc();
	return $result;
}

function actualizar_registro($query){
$conn = db_connect();
$result = $conn->query($query);
	if (!$result)
		return false;
	else
		return true;
}

function get_estatico() {
	$conn = db_connect();
	$query = "SELECT * FROM config_estatico";
	$result = @$conn->query( $query );
	if ( !$result )
		return false;
	$result = @$result->fetch_assoc();
	return $result;
}

function falso_pedido(){
if ($_SESSION['singularity_email']){
$cursos = $_SESSION['sing_prod_x_comp'];
$email = $_SESSION['singularity_email'];
date_default_timezone_set('America/Lima');
$hoy = date( "Y-m-d" );

$descripcion = "";

if ($cursos){	
$r = 1;
foreach ($cursos as $row){
$id_curso = $row['curso'];
$query = "SELECT * FROM cursos WHERE id_curso = '$id_curso'";
$curso = obtener_linea($query);
$nombre_curso = $curso['nombre_curso'];
	
$precio_producto = $row['precio_producto'];
$cantidad = $row['cantidad'];
$total = $row['total'];
	
$descripcion .= $r." - ".$nombre_curso." - Precio_".$precio_producto." - Cant.: ".$cantidad." - S/." .$total." <br> ";
$r++;
}
}
	
$query_agregar_falso_pedido = "INSERT INTO falso_pedido (descripcion_falso_pedido, fecha_falso_pedido,  email_falso_pedido) VALUE ('$descripcion', '$hoy', '$email')";
actualizar_registro($query_agregar_falso_pedido);
}
}

?>