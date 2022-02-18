<?php 
menu_lateral();
cuerpo_secciones();

function menu_lateral(){
$menu_sel = "";	
if (isset($_GET['acc'])){
	if(isset($_GET['id'])){	
		$menu_sel = $_GET['id'];
	}
}	
$query_menus = "SELECT * FROM menus";
$menus = obtener_todo($query_menus);
?>
<div id="lateralizq">
<nav>
<ul>
	<li class="item <?php if ($menu_sel== ""){ echo 'seleccionado';} ?>"><a href="secciones.php">Todo Menú</a></li>
</ul>	
<ul>
<li class="item_tit">Menú</li>	
<?php
if($menus){	
foreach ($menus as $row){
?>
<li class="item <?php if ($menu_sel==$row['id_menu']){ echo 'seleccionado';} ?>"><a href="secciones.php?acc=submenu&id=<?php echo $row ['id_menu']?>"><?php echo $row['nombre_menu']?></a></li>
<?php
}
}
?>
</ul>

</nav>	

</div>
<?php
}

function cuerpo_secciones(){
$estatico = get_estatico();
$usuario = $_SESSION[$estatico['sesion_ll']];
$query_usuario = "SELECT * FROM usuarios WHERE user_usuario = '$usuario'";
$datos_usuario = obtener_linea($query_usuario);	
?>
<div id="contenido">
	<div id="titulo">Configurar Menú</div>
	<div id="botones">
	<?php
	if ( $datos_usuario['id_nivel_usuario'] == 1 || $datos_usuario['id_nivel_usuario'] == 2 ) {
	boton_nuevo_menu();
	}
	?>
	</div>	
	<div id="cuadro">
	<?php
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
		if ($accion == 'submenu'){
			submenu_inicio($datos_usuario);
		}
		if ($accion == 'nuevasub'){
			agregar_seccion();
		}
		if ($accion == 'borrarsubmenu'){
			borrar_seccion();
		}
		if ($accion == 'suspendersubmenu'){
			suspender_seccion();
		}
		if ($accion == 'activarsubmenu'){
			activar_seccion();
		}
		if ($accion == 'editarsubmenu'){
			editar_seccion();
		}
	}else{
		menu_inicio($datos_usuario);
	}
	?>
	</div>
</div>
<?php
}
	
function boton_nuevo_menu(){
?>
<div class="item_botones"><a href="secciones.php?acc=nuevopro" class="nuevo_btn">Agregar Nuevo Menú</a></div>
<?php
}

function menu_inicio($datos_usuario){
$query_menu = "SELECT * FROM menus";
$menus = obtener_todo($query_menu);
		
?>
<div class="productos">
	<div id="color">
		<div id="titulo">Lista de Menus</div>
		<?php
		if($menus){
		foreach($menus as $row){
		?>
		<div class='nombre_color'>
		<?php 
		echo $row['nombre_menu'];
		if ($row['estado_menu'] == 1){
			echo "<a href='secciones.php?acc=suspendermenu&id=".$row['id_menu']."'>Suspender</a>";
		}else{
			echo "<a href='secciones.php?acc=activarmenu&id=".$row['id_menu']."'>Activar</a>";
		}
		?>
		<a href='secciones.php?acc=editarmenu&id=<?php echo $row['id_menu'] ?>'>Editar</a>
		<a href='secciones.php?acc=borrarmenu&id=<?php echo $row['id_menu'] ?>'>Eliminar</a>
		</div>
		<?php
		}
		}else{
		?>
		<div class="sindatos">No se registran Elementos de Menú</div>
		<?php
		}	
		?>
	</div>
</div>
<?php
}

function suspender_menu(){
$id_sec = $_GET['id'];
if (isset($_GET['conf'])){
	$query_suspender_subcategoria = "UPDATE menus SET estado_menu = '2' WHERE id_menu = '$id_sec'";
	if (actualizar_registro($query_suspender_subcategoria)) {
		$ruta = "secciones.php";
		$msj = "<p>Menu suspendido</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
		$ruta = "secciones.php";
		$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}	
}else{
?>
<div class="msj">
	<p>¿Desea Suspender este Menu?</p>
	<a href="secciones.php" class="boton">Regresar</a>
	<a href="secciones.php?acc=suspendermenu&id=<?php echo $id_sec ?>&conf=suspendelo" class="boton">Si, Suspender Menu</a>
</div>
<?php
}
}

function activar_menu(){
$id_sec = $_GET['id'];
if(isset($_GET['conf'])){
$query_activar_subcategoria = "UPDATE menus SET estado_menu = '1' WHERE id_menu = '$id_sec'";
	if (actualizar_registro($query_activar_subcategoria)) {
		$ruta = "secciones.php";
		$msj = "<p>Menú activado</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
		$ruta = "secciones.php";
		$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}	
}else{
?>
<div class="msj">
<p>¿Desea Suspender este Menu?</p>
<a href="secciones.php" class="boton">Regresar</a>
<a href="secciones.php?acc=activarmenu&id=<?php echo $id_sec ?>&conf=activalo" class="boton">Si, Activar Sección</a>
</div>
<?php
}
}

function borrar_menu(){
$id_sec = $_GET['id'];
$query_ver = "SELECT id_menu FROM secciones";
$menu_ver = obtener_todo($query_ver);
$verifica = "0";
if($menu_ver){	
foreach ($menu_ver as $row){
	if ($row['id_menu'] == $id_sec){
	$verifica++;
	}
}
}
if($verifica == "0"){

	$query_ver = "SELECT id_menu FROM productos";
	$secciones_ver = obtener_todo($query_ver);
	$verifica_sec = "0";
	if($secciones_ver){
	foreach ($secciones_ver as $row){
		if ($row['id_menu'] == $id_sec){
		$verifica_sec++;
		}
	}	
	}
	if($verifica_sec == "0"){
	if (isset($_GET['conf'])){
		$query_borrar_menu = "DELETE FROM menus WHERE id_menu = $id_sec";
		if (actualizar_registro($query_borrar_menu)) {
			$ruta = "secciones.php";
			$msj = "<p>Menu eliminado con exito</p>";
			$boton = "Regresar";
			mensaje_generico($msj, $ruta, $boton);
		}else{
			$ruta = "secciones.php";
			$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
			$boton = "Regresar";
			mensaje_generico($msj, $ruta, $boton);
		}	
	}else{
	?>
	<div class="msj">
	<p>¿Desea eliminar Este Menu?</p>
	<a href="secciones.php" class="boton">Regresar</a>
	<a href="secciones.php?acc=borrarmenu&id=<?php echo $id_sec ?>&conf=borralo" class="boton">Si, ELIMINAR Menu</a>
	</div>
	<?php	
	}
	}else{
	?>
	<div class="msj">
		<p>Este Menú no se puede eliminar porque tiene Productos</p>
		<a href="secciones.php" class="boton">Regresar</a>
	</div>
	<?php	
	}
		
}else{
?>
<div class="msj">
	<p>Este Menú no se puede eliminar porque tiene Secciones</p>
	<a href="secciones.php" class="boton">Regresar</a>
</div>
<?php
}
}

function agregar_menu(){

if(isset($_POST['nombre_menu'])){
$menu = $_POST['nombre_menu'];
$query_agregar_seccion = "INSERT INTO menus (nombre_menu) VALUES ('$menu')";
if (actualizar_registro($query_agregar_seccion)) {
	$ruta = "secciones.php";
	$msj = "<p>Menú Agregado</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
}else{
	$ruta = "secciones.php";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
}
}else{
?>
<div class="titulo_prod">Agregar Menú</div>
<form action="secciones.php?menu&acc=nuevopro" method="post" enctype="multipart/form-data">
<div id="edit_producto">
	<div class="datos bold">Nombre del Menu: </span><input name="nombre_menu" type="text" value="" maxlength="100" required></div>
	<div class="datos">
	<div class="botonera"><input class="crear" type="submit" value="Agregar Menu"></div>
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
$query_agregar_seccion = "UPDATE menus SET nombre_menu ='$nuevo_menu' WHERE id_menu = '$id_menu'";
if (actualizar_registro($query_agregar_seccion)) {
	$ruta = "secciones.php";
	$msj = "<p>Menú Editado</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
}else{
	$ruta = "secciones.php";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
}
}else{
$query_menu = "SELECT * FROM menus WHERE id_menu = '$id_menu'";
$menu = obtener_linea($query_menu);	
?>
<div class="titulo_prod">Editar Menú</div>
<form action="secciones.php?menu&acc=editarmenu&id=<?php echo $id_menu; ?>" method="post" enctype="multipart/form-data">
<div id="edit_producto">
	<div class="datos bold">Nombre del Menu: </span><input name="nombre_menu" type="text" value="<?php echo $menu['nombre_menu'] ?>" maxlength="100" required></div>
	<div class="datos">
		<div class="botonera">
		<input class="crear" type="submit" value="Editar Menu">
		<a href="secciones.php" class="boton">Regresar</a>
		</div>	
	</div>
</div>
</form>
<?php
	}
}

function submenu_inicio($datos_usuario){
$id_menu = $_GET['id'];
$query_secciones = "SELECT * FROM secciones WHERE id_menu = '$id_menu'";
$secciones = obtener_todo($query_secciones);
$query_menu = "SELECT * FROM menus WHERE id_menu = '$id_menu'";
$datos_menu = obtener_linea($query_menu);	
?>
<div class="productos">
	<div id="color">
		<div id="titulo">Lista de Secciones - <?php echo $datos_menu['nombre_menu']?></div>
		<div id="botones">
		<?php
		if ( $datos_usuario['id_nivel_usuario'] == 1 || $datos_usuario['id_nivel_usuario'] == 2 ) {
		boton_nuevo_submenu($datos_menu);
		}
		?>
		</div>
		<?php
		if($secciones){
		foreach($secciones as $row){
			echo "<div class='nombre_color'>". $row['nombre_seccion'];
			if ($row['estado_seccion'] == 1){
			echo "<a href='secciones.php?menu=".$id_menu."&acc=suspendersubmenu&id=".$row['id_seccion']."'>Suspender</a>";
			}else{
			echo "<a href='secciones.php?menu=".$id_menu."&acc=activarsubmenu&id=".$row['id_seccion']."'>Activar</a>";
			}
			echo "<a href='secciones.php?menu=".$id_menu."&acc=editarsubmenu&id=".$row['id_seccion']."'>Editar</a>";
			echo "<a href='secciones.php?menu=".$id_menu."&acc=borrarsubmenu&id=".$row['id_seccion']."'>Eliminar</a></div>";
		}
		}else{
		?>
		<div class="sidatos">No se registran Secciones</div>
		<?php
		}	
		?>
	</div>
</div>
<?php

}

function boton_nuevo_submenu($datos_menu){
?>
<div class="item_botones"><a href="secciones.php?acc=nuevasub&id=<?php echo $datos_menu['id_menu']?>" class="nuevo_btn">Agregar Nueva Sección a:  <?php echo $datos_menu['nombre_menu']?> </a></div>
<?php
}

function agregar_seccion(){
$id_menu = $_GET['id'];
if(isset($_POST['nombre_seccion'])){	
$nombre_seccion = $_POST['nombre_seccion'];
$query_agregar_seccion = "INSERT INTO secciones (nombre_seccion, id_menu) VALUES ('$nombre_seccion', '$id_menu')";
	if (actualizar_registro($query_agregar_seccion)) {
		$ruta = "secciones.php?acc=submenu&id=$id_menu";
		$msj = "<p>Sección Agregada</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "secciones.php?acc=submenu&id=$id_menu";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}
}else{
$query_menu = "SELECT * FROM menus WHERE id_menu = '$id_menu'";
$datos_menu = obtener_linea($query_menu);	
?>
<div class="titulo_prod">Agregar Sección a: <?php echo $datos_menu['nombre_menu']?></div>
<form action="secciones.php?acc=nuevasub&id=<?php echo $id_menu ?>" method="post" enctype="multipart/form-data">
<div id="edit_producto">
	<div class="datos bold">Nombre de Sección: </span><input name="nombre_seccion" type="text" value="" maxlength="100" required></div>
	<div class="datos">
	<br><br>
		<div class="botonera">
		<input class="crear" type="submit" value="Agregar Seccion">
		<a href="secciones.php?acc=submenu&id=<?php echo $id_menu ?>" class="boton">Regresar</a>
		</div>
	</div>
</div>
</form>
<?php
}
}

function borrar_seccion(){
$id_menu = $_GET['menu'];		
$id_sec = $_GET['id'];
$query_ver = "SELECT id_seccion FROM productos";
$secciones_ver = obtener_todo($query_ver);
$verifica = '0';
if($secciones_ver){	
foreach ($secciones_ver as $row){
	if ($row['id_seccion'] == $id_sec){
	$verifica++;
	}
}
}
	
if($verifica == '0'){
if(isset($_GET['conf'])){
	$query_borrar_subcategoria = "DELETE FROM secciones WHERE id_seccion = '$id_sec'";
	if (actualizar_registro($query_borrar_subcategoria)) {
		$ruta = "secciones.php?acc=submenu&id=$id_menu";
		$msj = "<p>Seccion eliminada con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
		$ruta = "secciones.php?acc=submenu&id=$id_menu";
		$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}	
}else{
?>
<div class="msj">
	<p>¿Desea eliminar Esta Sección?</p>
	<a href="secciones.php?acc=submenu&id=<?php echo $id_menu ?>" class="boton">Regresar</a>
	<a href="secciones.php?menu=<?php echo $id_menu ?>&acc=borrarsubmenu&id=<?php echo $id_sec ?>&conf=borralo" class="boton">Si, ELIMINAR Sección</a>
</div>
<?php	
}

}else{
?>
<div class="msj">
	<p>Esta Sección no se puede eliminar porque tiene productos</p>
	<a href="secciones.php?acc=submenu&id=<?php echo $id_menu ?>" class="boton">Regresar</a>
</div>
<?php
}

}

function suspender_seccion(){
$id_menu = $_GET['menu'];
$id_sec = $_GET['id'];

if (isset($_GET['conf'])){
	$query_suspender_submenu = "UPDATE secciones SET estado_seccion = '2' WHERE id_seccion = '$id_sec'";
	if (actualizar_registro($query_suspender_submenu)) {
		$ruta = "secciones.php?acc=submenu&id=$id_menu";
		$msj = "<p>Sección suspendida</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
		$ruta = "secciones.php?acc=submenu&id=$id_menu";
		$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}	
}else{
?>
<div class="msj">
	<p>¿Desea Suspender esta Sección?</p>
	<a href="secciones.php?acc=submenu&id=<?php echo $id_menu ?>" class="boton">Regresar</a>
	<a href="secciones.php?menu=<?php echo $id_menu ?>&acc=suspendersubmenu&id=<?php echo $id_sec ?>&conf=suspendelo" class="boton">Si, Suspender Sección</a>
</div>
<?php
}

}

function activar_seccion(){
$id_menu = $_GET['menu'];
$id_sec = $_GET['id'];

if (isset($_GET['conf'])){
	$query_suspender_submenu = "UPDATE secciones SET estado_seccion = '1' WHERE id_seccion = '$id_sec'";
	if (actualizar_registro($query_suspender_submenu)) {
		$ruta = "secciones.php?acc=submenu&id=$id_menu";
		$msj = "<p>Sección activada</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
		$ruta = "secciones.php?acc=submenu&id=$id_menu";
		$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}	
}else{
?>
<div class="msj">
	<p>¿Desea Activar esta Sección?</p>
	<a href="secciones.php?acc=submenu&id=<?php echo $id_menu ?>" class="boton">Regresar</a>
	<a href="secciones.php?menu=<?php echo $id_menu ?>&acc=activarsubmenu&id=<?php echo $id_sec ?>&conf=activar" class="boton">Si, Activar Sección</a>
</div>
<?php
}

}

function editar_seccion(){
$id_menu = $_GET['menu'];	
$id_submenu = $_GET['id'];	
if(isset($_POST['nombre_menu'])){
$nuevo_menu = $_POST['nombre_menu'];
$query_agregar_seccion = "UPDATE secciones SET nombre_seccion ='$nuevo_menu' WHERE id_seccion = '$id_submenu'";
if (actualizar_registro($query_agregar_seccion)) {
	$ruta = "secciones.php?acc=submenu&id=$id_menu";
	$msj = "<p>Sección Editada</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
}else{
	$ruta = "secciones.php?acc=submenu&id=$id_menu";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
}
}else{
$query_submenu = "SELECT * FROM secciones WHERE id_seccion = '$id_submenu'";
$submenu = obtener_linea($query_submenu);
$query_menu = "SELECT * FROM menus WHERE id_menu = '$id_menu'";
$datos_menu = obtener_linea($query_menu);		
?>
<div class="titulo_prod">Editar Nombre Sección en <?php echo $datos_menu['nombre_menu']; ?></div>
<form action="secciones.php?menu=<?php echo $id_menu; ?>&acc=editarsubmenu&id=<?php echo $id_submenu; ?>" method="post" enctype="multipart/form-data">
<div id="edit_producto">
	<div class="datos bold">Nombre de la Sección: </span><input name="nombre_menu" type="text" value="<?php echo $submenu['nombre_seccion'] ?>" maxlength="100" required></div>
	<div class="datos">
		<div class="botonera">
		<input class="crear" type="submit" value="Editar Menu">
		<a href="secciones.php" class="boton">Regresar</a>
		</div>	
	</div>
</div>
</form>
<?php
	}
}

//-------------------------


function secciones($menu, $datos_usuario){
$query_menu = "SELECT * FROM menus WHERE id_menu = '$menu'";
$datos_menu = obtener_linea($query_menu);
?>
<div class="titulo_prod">
	
<?php
if ( $datos_usuario['id_nivel_usuario'] == 1 || $datos_usuario['id_nivel_usuario'] == 2 ) {
	$var = $_GET['acc'];
	if($var != "suspendermenu" && $var != "activarmenu" && $var != "borrarmenu"){
	echo '<a href="secciones.php?menu='.$menu.'&acc=nuevo" class="nuevo_btn">Agregar Sección a '.$datos_menu['nombre_menu'].'</a>';
	}
}
?>
</div>
<?php
$accion = $_GET['acc'];
if ($accion){
	accion_submenu($accion, $datos_menu);
}else{
	submenu_inicio($datos_menu, $datos_usuario);
}
}

function accion_submenu($accion, $datos_menu){
//echo $accion;
if ($accion == 'nuevopro'){
	agregar_menu($datos_menu);
}
if ($accion == 'nuevo'){
	agregar_seccion($datos_menu);
}
if ($accion == 'borrar'){
	borrar_seccion($datos_menu);
}
if ($accion == 'suspender'){
	suspender_seccion($datos_menu);
}
if ($accion == 'activar'){
	activar_seccion($datos_menu);
}
if ($accion == 'suspendermenu'){
	suspender_menu($datos_menu);
	//echo "hola";
}
if ($accion == 'activarmenu'){
	activar_menu($datos_menu);
	//echo "hola2";
}
if ($accion == 'borrarmenu'){
	//activar_seccion($datos_menu);
	borrar_menu($datos_menu);
}

}







?>