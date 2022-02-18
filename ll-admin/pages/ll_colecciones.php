<?php 
$usuario = $_SESSION[$estatico['sesion_ll']];
$query_usuario = "SELECT * FROM usuarios WHERE user_usuario = '$usuario'";
$datos_usuario = obtener_linea($query_usuario);

menu_lateral();

?>
<div id="contenido">
	<div id="titulo">Colecciones</div>
	<div id="cuadro">
	<?php
	colecciones($datos_usuario);
	?>
	</div>
</div>
<?php

function menu_lateral(){
?>
<div id="lateralizq">
<nav>
<ul>
<li class="item_tit">Colecciones</li>	
</ul>	
</nav>	
</div>
<?php
}

function colecciones($datos_usuario){
?>
<div class="titulo_prod"> 
<?php
if ( $datos_usuario['id_nivel_usuario'] == 1 || $datos_usuario['id_nivel_usuario'] == 2 ) {
	echo '<a href="colecciones.php?acc=nuevo" class="nuevo_btn">Agregar Coleccion</a>';
}
?>
</div>
<?php
$accion = "";
if(isset($_GET['acc'])){	
$accion = $_GET['acc'];
}
if ($accion){
	accion_coleccion($accion);
}else{
	coleccion_inicio($datos_usuario);
}
}

function coleccion_inicio($datos_usuario){
$query_colecciones = "SELECT * FROM colecciones";
$colecciones = obtener_todo($query_colecciones);
?>
<div class="productos">
	<div id="colecciones">
		<div id="titulo">Lista de Colecciones</div>
		<?php
		if($colecciones){
		foreach($colecciones as $row){
		?>
		<div class="coleccion">
		<div class="enlinea"><?php  echo $row['nombre_coleccion']; ?></div>
		<div class="enlinea"><a href="colecciones.php?acc=editar&id=<?php echo $row['id_coleccion'] ?>">Editar</a></div>
		<?php
		if ($row['estado_coleccion'] == 1){
		?>
		<div class="enlinea"><a href="colecciones.php?acc=suspender&id=<?php echo $row['id_coleccion'] ?>">Suspender</a></div>
		<?php
		}else{
		?>
		<div class="enlinea"><a href="colecciones.php?acc=activar&id=<?php echo $row['id_coleccion'] ?>">Activar</a></div>
		<?php	
		}
		?>
		<div class="enlinea"><a href="colecciones.php?acc=eliminar&id=<?php echo $row['id_coleccion'] ?>">Eliminar</a></div>
		</div>	
		<?php
		}
		}else{
		?>
		<div class="sidatos">No se registran Colecciones</div>
		<?php
		}	
		?>
	</div>
</div>
<?php

}

function accion_coleccion($accion){
//echo $accion;
if ($accion == 'nuevo'){
	agregar_coleccion();
}
if ($accion == 'editar'){
	editar_coleccion();
}	
if ($accion == 'suspender'){
	suspender_coleccion();
}
if ($accion == 'activar'){
	activar_coleccion();
}
if ($accion == 'eliminar'){
	eliminar_coleccion();
}	
}

function suspender_coleccion(){
$id_sec = $_GET['id'];

$conf = $_GET['conf'];

if (!$conf) {
?>
<div class="msj">
	<p>¿Desea Suspender esta coleccion?</p>
	<a href="colecciones.php" class="boton">Regresar</a>
	<a href="colecciones.php?acc=suspender&id=<?php echo $id_sec ?>&conf=suspendelo" class="boton">Si, Suspender Sección</a>
</div>
<?php
}else{
		$query_suspender_subcategoria = "UPDATE colecciones SET estado_coleccion = '2' WHERE id_coleccion = '$id_sec'";
	if (actualizar_registro($query_suspender_subcategoria)) {
		$ruta = "colecciones.php";
		$msj = "<p>Coleccion suspendida con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "colecciones.php";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}

}

}

function activar_coleccion(){

$id_sec = $_GET['id'];

$conf = $_GET['conf'];

if (!$conf) {
?>
<div class="msj">
	<p>¿Desea Suspender esta Sección?</p>
	<a href="colecciones.php" class="boton">Regresar</a>
	<a href="colecciones.php?acc=activar&id=<?php echo $id_sec ?>&conf=activalo" class="boton">Si, Activar Sección</a>
</div>
<?php
}else{
		$query_activar_subcategoria = "UPDATE colecciones SET estado_coleccion = '1' WHERE id_coleccion = '$id_sec'";
	if (actualizar_registro($query_activar_subcategoria)) {
		$ruta = "colecciones.php";
		$msj = "<p>Coleccion activada con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "colecciones.php";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}

}

}

function agregar_coleccion(){

$nombre_coleccion = $_POST['nombre_coleccion'];
if ($nombre_coleccion){
	$query_agregar_coleccion = "INSERT INTO colecciones (nombre_coleccion) VALUES ('$nombre_coleccion')";
	if (actualizar_registro($query_agregar_coleccion)) {
		$ruta = "colecciones.php";
		$msj = "<p>Colección Agregada con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "colecciones.php";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}
}else{
?>
<div class="titulo_prod">Agregar Colección</div>
<form action="colecciones.php?acc=nuevo" method="post" enctype="multipart/form-data">
<div id="edit_producto">
	<div class="datos bold">Nombre de Coleccion: </span><input name="nombre_coleccion" type="text" value="" maxlength="100" required></div>
	<div class="datos">
	<br><br>
		<div class="botonera">
		<input class="crear" type="submit" value="Agregar Coleccion">
		<a href="colecciones.php" class="boton">Regresar</a>
		</div>
	</div>
</div>
</form>
<?php
}
}

function editar_coleccion(){
$id_col = $_GET['id'];	
$query_coleccion = "SELECT * FROM colecciones WHERE id_coleccion = '$id_col'";
$coleccion = obtener_linea($query_coleccion);	

$nuevo_nombre_coleccion = $_POST['nuevo_nombre_coleccion'];
if ($nuevo_nombre_coleccion){
	$query_editar_coleccion = "UPDATE colecciones SET nombre_coleccion = '$nuevo_nombre_coleccion' WHERE id_coleccion = '$id_col'";
	if (actualizar_registro($query_editar_coleccion)) {
		$ruta = "colecciones.php";
		$msj = "<p>Coleccion Editada con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "colecciones.php";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}
}else{
?>
<div class="titulo_prod">Editar Coleccion - <?php echo $coleccion['nombre_coleccion']?> </div>
<form action="colecciones.php?acc=editar&id=<?php echo $id_col ?>" method="post" enctype="multipart/form-data">
<div id="edit_producto">
	<div class="datos bold">Nombre de Coleccion: </span><input name="nuevo_nombre_coleccion" type="text" value="<?php echo $coleccion['nombre_coleccion'] ?>" maxlength="100" required></div>
	<div class="datos">
	<br><br>
		<div class="botonera">
		<input class="crear" type="submit" value="Editar Coleccion">
		<a href="colecciones.php" class="boton">Regresar</a>
		</div>
	</div>
</div>
</form>
<?php
}
}

function eliminar_coleccion(){
$id_col = $_GET['id'];

$query_compcol = "SELECT * FROM productos WHERE id_coleccion = '$id_col'";
$compara = obtener_todo($query_compcol);

if (!$compara){
$conf = $_GET['conf'];

if (!$conf) {
?>
<div class="msj">
	<p>¿Desea eliminar Esta Colección?</p>
	<a href="colecciones.php" class="boton">Regresar</a>
	<a href="colecciones.php?acc=eliminar&id=<?php echo $id_col ?>&conf=borralo" class="boton">Si, ELIMINAR Colección</a>
</div>
<?php
}else{
		$query_borrar_subcategoria = "DELETE FROM colecciones WHERE id_coleccion = '$id_col'";
	if (actualizar_registro($query_borrar_subcategoria)) {
		$ruta = "colecciones.php";
		$msj = "<p>Colección eliminada con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "colecciones.php";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}

}
}else{
?>
<div class="msj">
	<p>Esta Colección no se puede eliminar porque esta siendo usada</p>
	<a href="colecciones.php" class="boton">Regresar</a>
</div>
<?php	
}	
	
}

?>