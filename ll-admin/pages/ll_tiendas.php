<?php 
$menu_sel = "recojo";	
if (isset($_GET['sec'])){
	if(isset($_GET['sec'])){	
		$menu_sel = $_GET['sec'];
	}
}

menu_lateral($menu_sel);
contenido_inicio($menu_sel);

function contenido_inicio($menu_sel){
$estatico = get_estatico();	
$usuario = $_SESSION[$estatico['sesion_ll']];
$query_usuario = "SELECT * FROM usuarios WHERE user_usuario = '$usuario'";
$datos_usuario = obtener_linea($query_usuario);
$nivel = $datos_usuario['id_nivel_usuario'];
$query_nivel = "SELECT * FROM nivel_usuario WHERE id_nivel_usuario = $nivel";
$nivel_usuario = obtener_linea($query_nivel);
?>
<div id="contenido">
	<div id="titulo">Configurar Tienda</div>
	<div id="cuadro">
	<?php
	if ($menu_sel == "recojo") {
		recojo($estatico, $datos_usuario, $nivel_usuario);
	}
	if ($menu_sel == "tiendas") {
		tiendas($estatico, $datos_usuario, $nivel_usuario);
	}
	?>
	</div>
</div>
<?php
}

function menu_lateral($menu_sel){
?>
<div id="lateralizq">
<nav>
<ul>
	<li class="item <?php if ($menu_sel=="recojo"){ echo 'seleccionado';} ?>"><a href="tiendas.php?sec=recojo" >Activar Recojo</a></li>
	<li class="item <?php if ($menu_sel=="tiendas"){ echo 'seleccionado';} ?>"><a href="tiendas.php?sec=tiendas">Tiendas</a></li>
</ul>	
</nav>	

</div>
<?php
}

function tiendas($estatico, $datos_usuario, $nivel_usuario){

if (isset($_GET['acc'])){
	$accion = $_GET['acc'];	
	if ($accion == 'nuevopro'){
		agregar_menu();
	}
	if ($accion == 'suspendermenu'){
		suspender_menu();
	}
	if ($accion == 'activarmenu'){
		activar_menu();
	}
	if ($accion == 'borrarmenu'){
		borrar_menu();
	}
	if ($accion == 'editarmenu'){
		editar_menu();
	}		
}else{
	menu_inicio($datos_usuario);
}

}

function menu_inicio($datos_usuario){
$query_menu = "SELECT * FROM tiendas";
$menus = obtener_todo($query_menu);
?>
<div class="titulo_prod">Lista de Tiendas</div>
	<div class="boton_nuevo">
	<?php
	if ( $datos_usuario['id_nivel_usuario'] == 1 || $datos_usuario['id_nivel_usuario'] == 2 ) {
	boton_nuevo_menu();
	}
	?>
	</div>	
<?php
	if($menus){
	foreach($menus as $row){
	?>
	<div class='tarjeta'>
		<div class="partar"><?php echo $row['nombre_tienda']; ?></div>
		<?php
			if ($row['estado_tienda'] == 1){
				?>
				<div class="partar"><a class="azuldos" href="tiendas.php?sec=tiendas&acc=suspendermenu&id=<?php echo $row['id_tienda'] ?>">Suspender</a></div>
				<?php
			}else{
				?>
				<div class="partar"><a class="rojo" href="tiendas.php?sec=tiendas&acc=activarmenu&id=<?php echo $row['id_tienda'] ?>">Activar</a></div>
				<?php
			}
		?>
		<div class="partar"><a href='tiendas.php?sec=tiendas&acc=editarmenu&id=<?php echo $row['id_tienda'] ?>'>Editar</a></div>
		<div class="partar"><a href='tiendas.php?sec=tiendas&acc=borrarmenu&id=<?php echo $row['id_tienda'] ?>'>Eliminar</a></div>
		
	</div>
	<?php
	}
	}else{
	?>
	<div class="sindatos">No se registran Tiendas</div>
	<?php
	}	
	
}

function boton_nuevo_menu(){
?>
<div class="item_botones"><a href="tiendas.php?sec=tiendas&acc=nuevopro" class="nuevo_btn">Agregar Nueva Tienda</a></div>
<?php
}

function suspender_menu(){
$id_sec = $_GET['id'];
if (isset($_GET['conf'])){
	$query_suspender_subcategoria = "UPDATE tiendas SET estado_tienda = '2' WHERE id_tienda = '$id_sec'";
	if (actualizar_registro($query_suspender_subcategoria)) {
		$ruta = "tiendas.php?sec=tiendas";
		$msj = "<p>Tienda suspendida</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
		$ruta = "tiendas.php?sec=tiendas";
		$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}	
}else{
?>
<div class="msj">
	<p>¿Desea Suspender esta Tienda?</p>
	<a href="tiendas.php?sec=tiendas" class="boton">Regresar</a>
	<a href="tiendas.php?sec=tiendas&acc=suspendermenu&id=<?php echo $id_sec ?>&conf=suspendelo" class="boton">Si, Suspender Tienda</a>
</div>
<?php
}
}

function activar_menu(){
$id_sec = $_GET['id'];
if(isset($_GET['conf'])){
$query_activar_subcategoria = "UPDATE tiendas SET estado_tienda = '1' WHERE id_tienda = '$id_sec'";
	if (actualizar_registro($query_activar_subcategoria)) {
		$ruta = "tiendas.php?sec=tiendas";
		$msj = "<p>Tienda activada</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
		$ruta = "tiendas.php?sec=tiendas";
		$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}	
}else{
?>
<div class="msj">
<p>¿Desea Suspender esta Tienda?</p>
<a href="tiendas.php?sec=tiendas" class="boton">Regresar</a>
<a href="tiendas.php?sec=tiendas&acc=activarmenu&id=<?php echo $id_sec ?>&conf=activalo" class="boton">Si, Activar Tienda</a>
</div>
<?php
}
}

function borrar_menu(){
$id_sec = $_GET['id'];
$query_ver = "SELECT id_menu FROM secciones";
$menu_ver = obtener_todo($query_ver);
$verifica = "0";

if($verifica == "0"){

	$query_ver = "SELECT id_menu FROM productos";
	$secciones_ver = obtener_todo($query_ver);
	$verifica_sec = "0";
	
	if($verifica_sec == "0"){
	if (isset($_GET['conf'])){
		$query_borrar_menu = "DELETE FROM tiendas WHERE id_tienda = $id_sec";
		if (actualizar_registro($query_borrar_menu)) {
			$ruta = "tiendas.php?sec=tiendas";
			$msj = "<p>Tienda eliminada con exito</p>";
			$boton = "Regresar";
			mensaje_generico($msj, $ruta, $boton);
		}else{
			$ruta = "tiendas.php?sec=tiendas";
			$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
			$boton = "Regresar";
			mensaje_generico($msj, $ruta, $boton);
		}	
	}else{
	?>
	<div class="msj">
	<p>¿Desea eliminar Esta Tienda?</p>
	<a href="tiendas.php?sec=tiendas" class="boton">Regresar</a>
	<a href="tiendas.php?sec=tiendas&acc=borrarmenu&id=<?php echo $id_sec ?>&conf=borralo" class="boton">Si, ELIMINAR Tienda</a>
	</div>
	<?php	
	}
	}else{
	?>
	<div class="msj">
		<p>Esta Tienda no se puede eliminar porque tiene Productos</p>
		<a href="tiendas.php?sec=tiendas" class="boton">Regresar</a>
	</div>
	<?php	
	}
		
}else{
?>
<div class="msj">
	<p>Este Tienda no se puede eliminar porque tiene Secciones</p>
	<a href="tiendas.php?sec=tiendas" class="boton">Regresar</a>
</div>
<?php
}
}

function agregar_menu(){

if(isset($_POST['nombre_menu'])){
$menu = $_POST['nombre_menu'];
$query_agregar_seccion = "INSERT INTO tiendas (nombre_tienda) VALUES ('$menu')";
if (actualizar_registro($query_agregar_seccion)) {
	$ruta = "tiendas.php?sec=tiendas";
	$msj = "<p>Tienda Agregada</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
}else{
	$ruta = "tiendas.php?sec=tiendas";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
}
}else{
?>
<div class="titulo_prod">Agregar Tienda</div>
<form action="tiendas.php?sec=tiendas&acc=nuevopro" method="post" enctype="multipart/form-data">
<div id="edit_producto">
	<div class="datos bold">Nombre de la Tienda: </span><input name="nombre_menu" type="text" value="" maxlength="100" required></div>
	<div class="datos">
	<div class="botonera"><input class="crear" type="submit" value="Agregar Tienda"></div>
	</div>
</div>
</form>
<?php
	}
}

function editar_menu(){
$id_menu = $_GET['id'];	
if(isset($_POST['nombre_menu'])){
$nuevo_menu = $_POST['nombre_menu'];
$query_agregar_seccion = "UPDATE tiendas SET nombre_tienda ='$nuevo_menu' WHERE id_tienda = '$id_menu'";
if (actualizar_registro($query_agregar_seccion)) {
	$ruta = "tiendas.php?sec=tiendas";
	$msj = "<p>Tienda Editada</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
}else{
	$ruta = "tiendas.php?sec=tiendas";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
}
}else{
$query_menu = "SELECT * FROM tiendas WHERE id_tienda = '$id_menu'";
$menu = obtener_linea($query_menu);	
?>
<div class="titulo_prod">Editar Tienda</div>
<form action="tiendas.php?sec=tiendas&acc=editarmenu&id=<?php echo $id_menu; ?>" method="post" enctype="multipart/form-data">
<div id="edit_producto">
	<div class="datos bold">Nombre de la tienda: </span><input name="nombre_menu" type="text" value="<?php echo $menu['nombre_tienda'] ?>" maxlength="100" required></div>
	<div class="datos">
		<div class="botonera">
		<input class="crear" type="submit" value="Editar Tienda">
		<a href="tiendas.php?sec=tiendas" class="boton">Regresar</a>
		</div>	
	</div>
</div>
</form>
<?php
	}
}

function recojo(){
$mensaje = "";
if(isset($_POST['editar'])){
	$check = 0;
	$mensaje = "Se desactivó";
	if(isset($_POST['chk_insta'])){
		$activo = $_POST['chk_insta'];
		if($activo == "on"){
			$check = 1;	
			$mensaje = "Se Activó";
		}
	}
	$query_instagram_update = "UPDATE config_estatico SET estado_delivery = '$check' WHERE id_conf_gen = '1'";
	actualizar_registro($query_instagram_update);

}
	
$query_instagram ="SELECT * FROM config_estatico WHERE id_conf_gen = '1'";
$instagram = obtener_linea($query_instagram);
$activo = $instagram['estado_delivery'];
$check = "";
if($activo == 1){
	$check = "checked";
}	
?>
<div class="titulo_prod">Activar Recojo en tiendas</div>
<form action="tiendas.php?sec=recojo" method="post" enctype="multipart/form-data">
<div class="item">
	<span class="subtitulo">Activo:</span><input name="chk_insta" type="checkbox"  <?php echo $check ?>>
</div>
	<div class="msj_tienda"><?php echo $mensaje; ?></div>
<div class="botonera">
<input name="editar" value="actualizar" type="hidden">
<input class="crear" type="submit" value="Modificar configuracion">

</div>	
</form>
</div>

<?php
}

?>