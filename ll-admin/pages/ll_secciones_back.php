<?php 
$sec = "";
if(isset($_GET['sec'])){
$sec = $_GET['sec'];
}

$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : '2' ;
$usuario = $_SESSION[$estatico['sesion_ll']];
$query_usuario = "SELECT * FROM usuarios WHERE user_usuario = '$usuario'";
$datos_usuario = obtener_linea($query_usuario);
?>
<div id="lateralizq">
    <nav>
	<ul>
		<li class="item <?php if (!$sec){ echo 'seleccionado';} ?>"><a href="secciones.php" >Secciones</a></li>
		<li class="item <?php if ($sec=="subsecciones" ){ echo 'seleccionado';} ?>"><a href="secciones.php?sec=subsecciones" >Subsecciones</a></li>
    </ul>
    </nav>
</div>
<div id="contenido">
	<div id="titulo">Menú Principal</div>
	<div id="cuadro">
			<?php
			if (!$sec) {
			seccion($seccion, $datos_usuario);
			}
			if ( $sec == "subsecciones" ) {
			subsecciones($seccion, $datos_usuario);
			}
			?>
	</div>
</div>

<?php

function seccion($seccion, $datos_usuario){
$query_secciones = "SELECT * FROM secciones";
$secciones = obtener_todo($query_secciones);	
	
?>
<div class="titulo_prod">Secciones 
<?php
if ( $datos_usuario['id_nivel_usuario'] == 1 || $datos_usuario['id_nivel_usuario'] == 2 ) {
	echo '<a href="secciones.php?acc=nuevo" class="nuevo_btn">Agregar Seccion</a>';
}
?>
</div>
<?php
$accion = "";
if(isset($_GET['accion'])){
$accion = $_GET['accion'];
}	
if ($accion){
	accion_seccion($accion, $seccion);
}else{
	seccion_inicio($secciones, $datos_usuario);
}
}

function seccion_inicio($secciones, $datos_usuario){
?>
<div class="productos">
	<div class="seccion">
	<?php
	if($secciones){
		foreach($secciones as $row){
			$id_menu = $row['id_menu'];
			$query_menu = "SELECT * FROM menus WHERE id_menu = '$id_menu'";
			$nombre_menu = obtener_linea($query_menu);
			?>
			<div class='nombre_seccion'>
			<span class="nombre_menu">(<?php echo $nombre_menu['nombre_menu'] ?>)</span>	
			<?php echo $row['nombre_seccion'] ?>
			<a href='secciones.php?seccion=<?php echo $row['id_seccion']?>&acc=editar'>Editar</a>
			<?php
			if ($row['estado_seccion'] == 1){
			echo "<a href='secciones.php?seccion=".$row['id_seccion']."&acc=suspender'>Suspender</a>";
			}else{
			echo "<a href='secciones.php?seccion=".$row['id_seccion']."&acc=activar'>Activar</a>";
			}
			?>
			<a href='secciones.php?seccion=<?php echo $row['id_seccion']?>&acc=borrar'>Eliminar</a></div>
			<?php
		}
	}else{
	?>
	<div id="vacio">No se registran secciones</div>
	<?php
	}	
	?>
	</div>
</div>
<?php

}

function accion_seccion($accion){
//echo $accion;
if ($accion == 'nuevo'){
	agregar_seccion();
}
if ($accion == 'editar'){
	editar_seccion();
}	
if ($accion == 'borrar'){
	borrar_seccion();
}
if ($accion == 'suspender'){
	suspender_seccion();
}
if ($accion == 'activar'){
	activar_seccion();
}
}

function borrar_seccion(){
$seccion = $_GET['seccion'];
$query_ver = "SELECT id_seccion FROM productos";
$seccion_ver = obtener_todo($query_ver);
//print_r($seccion_ver);
$verifica = '0';
foreach ($seccion_ver as $row){
	//echo $row[submenu_producto];
	if ($row['id_seccion'] == $seccion){
	$verifica++;
	}
}
//echo $verifica;

if($verifica == '0'){
$conf = $_GET['conf'];

if (!$conf) {
?>
<div class="msj">
	<p>¿Desea eliminar Esta subcategoria?</p>
	<a href="secciones.php" class="boton">Regresar</a>
	<a href="secciones.php?seccion=<?php echo $seccion ?>&acc=borrar&conf=borralo" class="boton">Si, ELIMINAR Subcategoria</a>
</div>
<?php
}else{
		$query_borrar_seccion = "DELETE FROM secciones WHERE id_seccion = '$seccion'";
	if (actualizar_registro($query_borrar_seccion)) {
		$ruta = "secciones.php";
		$msj = "<p>Sección eliminada con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "secciones.php";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}

}
}else{
?>
<div class="msj">
	<p>Esta Subcategoria no se puede eliminar porque esta siendo usada (<?php echo $verifica ?>)</p>
	<a href="secciones.php" class="boton">Regresar</a>
</div>
<?php
}

}

function suspender_seccion(){
$seccion = $_GET['seccion'];

$conf = $_GET['conf'];

if (!$conf) {
?>
<div class="msj">
	<p>¿Desea Suspender esta sección?</p>
	<a href="secciones.php" class="boton">Regresar</a>
	<a href="secciones.php?seccion=<?php echo $seccion ?>&acc=suspender&conf=suspendelo" class="boton">Si, Suspender Sección</a>
</div>
<?php
}else{
	$query_suspender_seccion = "UPDATE secciones SET estado_seccion = '2' WHERE id_seccion = '$seccion'";
	if (actualizar_registro($query_suspender_seccion)) {
		$ruta = "secciones.php";
		$msj = "<p>Sección suspendida con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "secciones.php";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}

}

}

function activar_seccion(){
$seccion = $_GET['seccion'];

$conf = $_GET['conf'];

if (!$conf) {
?>
<div class="msj">
	<p>¿Desea Activar esta sección?</p>
	<a href="secciones.php" class="boton">Regresar</a>
	<a href="secciones.php?seccion=<?php echo $seccion ?>&acc=activar&conf=activalo" class="boton">Si, Activar Sección</a>
</div>
<?php
}else{
		$query_activar_seccion = "UPDATE secciones SET estado_seccion = '1' WHERE id_seccion = '$seccion'";
	if (actualizar_registro($query_activar_seccion)) {
		$ruta = "secciones.php";
		$msj = "<p>Seccion activada con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "secciones.php";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}

}

}

function agregar_seccion(){
$nombre_seccion = $_POST['nombre_seccion'];
if ($nombre_seccion){
	$id_menu = $_POST['txt_menu'];
	$query_agregar_seccion = "INSERT INTO secciones (nombre_seccion, id_menu) VALUES ('$nombre_seccion', '$id_menu')";
	if (actualizar_registro($query_agregar_seccion)) {
		$ruta = "secciones.php";
		$msj = "<p>Sección Agregada con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "secciones.php";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}
}else{
$query_menus = "SELECT * FROM menus WHERE estado_menu = '1'";
$menus = obtener_todo($query_menus);	
?>
<div class="titulo_prod">Agregar Sección</div>
<form action="secciones.php?acc=nuevo".$submenu."&acc=nuevo" method="post" enctype="multipart/form-data">
<div id="edit_producto">
	<div class="datos bold">Nombre de Sección: </span><input name="nombre_seccion" type="text" value="" maxlength="100" required></div>
	<div class="datos bold">Seleccionar Menú: 
		<select name="txt_menu">
			<?php
			foreach($menus as $row){
			if ($row['id_menu'] != 1 && $row['id_menu'] != 4&& $row['id_menu'] != 5){
			?>
			<option value="<?php echo $row['id_menu'] ?>"><?php echo $row['nombre_menu'] ?></option>
			<?php
			}
			}	
			?>
		</select>
	</div>
	<div class="datos">
	<br><br>
		<div class="botonera">
		<input class="crear" type="submit" value="Agregar Sección">
		<a href="secciones.php" class="boton">Regresar</a>
		</div>
	</div>
</div>
</form>
<?php
}
}

function editar_seccion(){
$nuevo_nombre_seccion = $_POST['nombre_seccion'];
$seccion = $_GET['seccion'];
$query_seccion = "SELECT * FROM secciones WHERE id_seccion = '$seccion'";
$nombre_seccion = obtener_linea($query_seccion);	
$id_seccion_menu = 	$nombre_seccion['id_menu'];
	
if ($nuevo_nombre_seccion){
	$id_menu = $_POST['txt_menu'];
	$query_agregar_seccion = "UPDATE secciones SET nombre_seccion = '$nuevo_nombre_seccion', id_menu = '$id_menu' WHERE id_seccion = '$seccion'";
	if (actualizar_registro($query_agregar_seccion)) {
		$ruta = "secciones.php";
		$msj = "<p>Sección Editada con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "secciones.php";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}
}else{
$query_menus = "SELECT * FROM menus WHERE estado_menu = '1'";
$menus = obtener_todo($query_menus);		
?>
<div class="titulo_prod">Editar Nombre de Sección</div>
<form action="secciones.php?seccion=<?php echo $seccion ?>&acc=editar".$submenu."&acc=nuevo" method="post" enctype="multipart/form-data">
<div id="edit_producto">
	<div class="datos bold">Nombre de Sección: </span><input name="nombre_seccion" type="text" value="<?php echo $nombre_seccion['nombre_seccion'] ?>" maxlength="100" required></div>
		<div class="datos bold">Seleccionar Menú: 
		<select name="txt_menu">
			<?php
			foreach($menus as $row){
			if ($row['id_menu'] != 1 && $row['id_menu'] != 4&& $row['id_menu'] != 5){
				$selected = "";
				if($row['id_menu'] == $id_seccion_menu ){
					$selected = "selected";
				}
			?>
			<option value="<?php echo $row['id_menu'] ?>" <?php echo $selected ?>><?php echo $row['nombre_menu'] ?></option>
			<?php
			}
			}	
			?>
			
		</select>
	</div>
	<div class="datos">
	<br><br>
		<div class="botonera">
		<input class="crear" type="submit" value="Cambiar Nombre">
		<a href="secciones.php" class="boton">Regresar</a>
		</div>
	</div>
</div>
</form>
<?php
}
}

//----subseccion

function subsecciones($seccion, $datos_usuario){
$query_secciones = "SELECT * FROM secciones";
$secciones = obtener_todo($query_secciones);	

$query_subsecciones = "SELECT * FROM subsecciones";
$subsecciones = obtener_todo($query_subsecciones);	
	
?>
<div class="titulo_prod">Subsecciones 
<?php
if ( $datos_usuario['id_nivel_usuario'] == 1 || $datos_usuario['id_nivel_usuario'] == 2 ) {
	echo '<a href="secciones.php?sec=subsecciones&acc=nuevo" class="nuevo_btn">Agregar SubSeccion</a>';
}
?>
</div>
<?php
$accion = "";
if(isset($_GET['acc'])){
$accion = $_GET['acc'];	
}	

if ($accion){
	accion_subseccion($accion, $seccion);
}else{
	subseccion_inicio($secciones, $subsecciones, $datos_usuario);
}
}

function subseccion_inicio($secciones, $subsecciones, $datos_usuario){
?>
<div class="productos">
	<div class="seccion">
	<?php
	if($subsecciones){
		foreach($subsecciones as $row){
			$id_seccion = $row['id_seccion'];
			$query_secciones = "SELECT * FROM secciones WHERE id_seccion = '$id_seccion'";
			$nombre_seccion = obtener_linea($query_secciones);
			
			$query_seccion = "SELECT * FROM secciones WHERE id_seccion = '$id_seccion'";
			$seccion = obtener_linea($query_seccion);	
			
			$id_menu = $seccion['id_menu'];
			$query_menu = "SELECT * FROM menus WHERE id_menu = '$id_menu'";
			$nombre_menu = obtener_linea($query_menu);
			
			?>
			<div class='nombre_seccion'>
			<span class="nombre_menu">(<?php echo $nombre_menu['nombre_menu'] ?>)</span>	
			<span class="nombre_menu">(<?php echo $nombre_seccion['nombre_seccion'] ?>)</span>	
			<?php
			echo $row['nombre_subseccion'];
			echo "<a href='secciones.php?sec=subsecciones&acc=editar&id=".$row['id_subseccion']."'>Editar</a>";
			if ($row['activo_subseccion'] == 1){
				echo "<a href='secciones.php?sec=subsecciones&acc=suspender&id=".$row['id_subseccion']."'>Suspender</a>";
			}else{
				echo "<a href='secciones.php?sec=subsecciones&acc=activar&id=".$row['id_subseccion']."'>Activar</a>";
			}
			echo "<a href='secciones.php?sec=subsecciones&acc=borrar&id=".$row['id_subseccion']."'>Eliminar</a>";
			?>
			</div>
			<?php
		}
	}else{
	?>
	<div id="vacio">No se registran subsecciones</div>
	<?php
	}	
	?>
	</div>
</div>
<?php

}

function accion_subseccion($accion){
//echo $accion;
if ($accion == 'nuevo'){
	agregar_subseccion();
}
if ($accion == 'editar'){
	editar_subseccion();
}	
if ($accion == 'borrar'){
	borrar_subseccion();
}
if ($accion == 'suspender'){
	suspender_subseccion();
}
if ($accion == 'activar'){
	activar_subseccion();
}
}

function agregar_subseccion(){
$nombre_subseccion = $_POST['nombre_subseccion'];
if ($nombre_subseccion){
	$id_seccion = $_POST['seccion_slc'];
	$query_agregar_subseccion = "INSERT INTO subsecciones (nombre_subseccion, id_seccion) VALUES ('$nombre_subseccion', '$id_seccion')";
	//echo $query_agregar_subseccion;
	if (actualizar_registro($query_agregar_subseccion)) {
		$ruta = "secciones.php?sec=subsecciones";
		$msj = "<p>SubSección Agregada con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "secciones.php?sec=subsecciones";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}
}else{
$query_menus = "SELECT * FROM menus WHERE estado_menu = '1'";
$menus = obtener_todo($query_menus);	
?>
<div class="titulo_prod">Agregar Sección</div>
<form action="secciones.php?sec=subsecciones&acc=nuevo".$submenu."&acc=nuevo" method="post" enctype="multipart/form-data">
<div id="edit_producto">
	
	<div class="datos bold">1.- Seleccionar Menú: </div>
	<div class="categorias">
		<?php
		$query_menus = "SELECT * FROM menus WHERE estado_menu = '1'";
		$menus = obtener_todo($query_menus);	
		?>
			<select name="menu_slc" id="categoria_pri">
			<?php
			foreach($menus as $row){
			if ($row['id_menu'] != 1 && $row['id_menu'] != 4&& $row['id_menu'] != 5){
			?>
			<option value="<?php echo $row['id_menu'] ?>"><?php echo $row['nombre_menu'] ?></option>
			<?php
			}
			}	
			?>
			</select>
		</div>
	<div class="datos bold">2. Seleccionar Sección: </div>
	<div class="categorias" id="subcat">
		<?php
		$query_secciones = "SELECT * FROM secciones WHERE id_menu = '2' AND estado_seccion = '1'";
		$secciones = obtener_todo($query_secciones);
		if($secciones){
			?>
			<select name="seccion_slc">
			<?php
			
				foreach ($secciones as $row) {
				echo '<option value="' . $row['id_seccion'] . '" >' . $row['nombre_seccion'] . '</option>';
				}
				?>
			</select>
			<?php
			}
			?>
		</div>
	<script language="JavaScript" type="text/JavaScript">
		$(document).ready(function(){
			$("#categoria_pri").change(function(event){
				var id = $("#categoria_pri").find(':selected').val();
				$("#subcat").load('subseccioncrear_load.php?id='+id);
			});
		});
	</script>
	
	<div class="datos bold">3.- Nombre de SubSección: </div>
	<div></span><input name="nombre_subseccion" type="text" value="" maxlength="100" required></div>
	<div class="datos">
	<br><br>
		<div class="botonera">
		<input class="crear" type="submit" value="Agregar SubSección">
		<a href="secciones.php" class="boton">Regresar</a>
		</div>
	</div>
</div>
</form>
<?php
}
}

function editar_subseccion(){
$id_sub = $_GET['id'];	
$query_subseccion = "SELECT * FROM subsecciones WHERE id_subseccion = '$id_sub'";
$subseccion = obtener_linea($query_subseccion);
$id_seccion = $subseccion['id_seccion'];	
$query_seccion = "SELECT *FROM secciones WHERE id_seccion = '$id_seccion'";
$seccion = obtener_linea($query_seccion);
	
$nuevo_nombre_subseccion = $_POST['nombre_subseccion'];
	
if ($nuevo_nombre_subseccion){
	$id_seccion_chg = $_POST['seccion_slc'];
	$query_editar_subseccion = "UPDATE subsecciones SET nombre_subseccion = '$nuevo_nombre_subseccion', id_seccion = '$id_seccion_chg' WHERE id_subseccion = '$id_sub'";
	if (actualizar_registro($query_editar_subseccion)) {
		$ruta = "secciones.php?sec=subsecciones";
		$msj = "<p>SubSección Editada con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "secciones.php?sec=subsecciones";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}
}else{
	
$query_menus = "SELECT * FROM menus WHERE estado_menu = '1'";
$menus = obtener_todo($query_menus);		
?>
<div class="titulo_prod">Editar Subsección</div>
<form action="secciones.php?sec=subsecciones&acc=editar&id=<?php echo $id_sub ?>".$submenu."&acc=nuevo" method="post" enctype="multipart/form-data">
<div id="edit_producto">
	<div class="datos bold">1.- Nombre de SubSección: </div>
	<div></span><input name="nombre_subseccion" type="text" value="<?php echo $subseccion['nombre_subseccion'] ?>" maxlength="100" required></div>
	<div class="datos bold">2.- Seleccionar Menú: </div>
	<div class="categorias">
		<?php
		$query_menus = "SELECT * FROM menus WHERE estado_menu = '1'";
		$menus = obtener_todo($query_menus);	
		?>
			<select name="menu_slc" id="categoria_pri">
			<?php
			foreach($menus as $row){
			if ($row['id_menu'] != 1 && $row['id_menu'] != 4&& $row['id_menu'] != 5){
				$selected = "";
				if($row['id_menu'] == $seccion['id_menu']){
					$selected = "selected";
				}
				?>
			<option value="<?php echo $row['id_menu'] ?>" <?php echo $selected ?>><?php echo $row['nombre_menu'] ?></option>
			<?php
			}
			}	
			?>
			</select>
		</div>
	<div class="datos bold">3. Seleccionar Sección: </div>
	<div class="categorias" id="subcat">
		<?php
		$query_secciones = "SELECT * FROM secciones WHERE id_menu = '$seccion[id_menu]' AND estado_seccion = '1'";
		$secciones = obtener_todo($query_secciones);
		if($secciones){
			?>
			<select name="seccion_slc">
			<?php
				foreach ($secciones as $row) {
					if($row['id_seccion'] == $seccion['id_seccion'] ){
					$selected = "selected";
					}else{
					$selected = "";   
					}
				echo '<option value="' . $row['id_seccion'] . '" '.$selected.'>' . $row['nombre_seccion'] . '</option>';
				}
				?>
			</select>
			<?php
			}
			?>
		</div>
	<script language="JavaScript" type="text/JavaScript">
		$(document).ready(function(){
			$("#categoria_pri").change(function(event){
				var id = $("#categoria_pri").find(':selected').val();
				$("#subcat").load('subseccioncrear_load.php?id='+id);
			});
		});
	</script>
	

	<div class="datos">
	<br><br>
		<div class="botonera">
		<input class="crear" type="submit" value="Editar SubSección">
		<a href="secciones.php" class="boton">Regresar</a>
		</div>
	</div>
</div>
</form>
<?php
}
}

function suspender_subseccion(){
$subseccion = $_GET['id'];

$conf = $_GET['conf'];

if (!$conf) {
?>
<div class="msj">
	<p>¿Desea Suspender esta subsección?</p>
	<a href="secciones.php?sec=subsecciones" class="boton">Regresar</a>
	<a href="secciones.php?sec=subsecciones&acc=suspender&id=<?php echo $subseccion ?>&conf=suspendelo" class="boton">Si, Suspender Subsección</a>
</div>
<?php
}else{
	$query_suspender_subseccion = "UPDATE subsecciones SET activo_subseccion = '2' WHERE id_subseccion = '$subseccion'";
	if (actualizar_registro($query_suspender_subseccion)) {
		$ruta = "secciones.php?sec=subsecciones";
		$msj = "<p>Subsección suspendida con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "secciones.php?sec=subsecciones";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}

}

}

function activar_subseccion(){
$subseccion = $_GET['id'];

$conf = $_GET['conf'];

if (!$conf) {
?>
<div class="msj">
	<p>¿Desea Activar esta subsección?</p>
	<a href="secciones.php?sec=subsecciones" class="boton">Regresar</a>
	<a href="secciones.php?sec=subsecciones&acc=activar&id=<?php echo $subseccion ?>&conf=activalo" class="boton">Si, Activar Subsección</a>
</div>
<?php
}else{
		$query_activar_seccion = "UPDATE subsecciones SET activo_subseccion = '1' WHERE id_subseccion = '$subseccion'";
	if (actualizar_registro($query_activar_seccion)) {
		$ruta = "secciones.php?sec=subsecciones";
		$msj = "<p>Subseccion activada con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "secciones.php?sec=subsecciones";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}

}

}

function borrar_subseccion(){
	
	$seccion = $_GET['id'];
$query_ver = "SELECT id_subseccion FROM productos";
$seccion_ver = obtener_todo($query_ver);
//print_r($seccion_ver);
$verifica = '0';
foreach ($seccion_ver as $row){
	//echo $row[submenu_producto];
	if ($row['id_subseccion'] == $seccion){
	$verifica++;
	}
}
//echo $verifica;

if($verifica == '0'){
$conf = $_GET['conf'];

if (!$conf) {
?>
<div class="msj">
	<p>¿Desea eliminar Esta Subseccion?</p>
	<a href="secciones.php" class="boton">Regresar</a>
	<a href="secciones.php?sec=subsecciones&acc=borrar&conf=borralo&id=<?php echo $_GET['id']; ?>" class="boton">Si, ELIMINAR Subseccion</a>

</div>
<?php
}else{
		$query_borrar_seccion = "DELETE FROM subsecciones WHERE id_subseccion = '$seccion'";
	//echo $query_borrar_seccion;
	if (actualizar_registro($query_borrar_seccion)) {
		$ruta = "secciones.php?sec=subsecciones";
		$msj = "<p>SubSección eliminada con exito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "secciones.php?sec=subsecciones";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}

}
}else{
?>
<div class="msj">
	<p>Esta Subcategoria no se puede eliminar porque esta siendo usada (<?php echo $verifica ?>)</p>
	<a href="secciones.php" class="boton">Regresar</a>
</div>
<?php
}
}

?>