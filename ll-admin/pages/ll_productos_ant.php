<?php
$estatico = get_estatico();
$usuario = $_SESSION[$estatico['sesion_ll']];
$query_usuario = "SELECT * FROM usuarios WHERE user_usuario = '$usuario'";
$datos_usuario = obtener_linea($query_usuario);

menu_lateral();

?>
<div id="contenido">
	<div id="titulo">Productos</div>
	<div id="botones">
	<?php
	if ( $datos_usuario['id_nivel_usuario'] == 1 || $datos_usuario['id_nivel_usuario'] == 2 ) {
	boton_nuevo_producto();
	}
	busqueda_sub();
	?>
	</div>	
	<div id="cuadro">
		<?php productos($datos_usuario, $estatico);	?>
	</div>
</div>
<?php

/*-------------------------*/

function menu_lateral(){
$query_menus = "SELECT * FROM menus";
$menus = obtener_todo($query_menus);
?>
<div id="lateralizq">
<nav>
<ul>	
<?php
foreach ($menus as $row){
	if (($row['id_menu'] == 2) or ($row['id_menu'] == 3)){
	?>
	<li class="item_tit"><?php echo $row['nombre_menu'] ?></li>
		<?php
		$id_menu = $row['id_menu'];
		$query_secciones = "SELECT * FROM secciones WHERE id_menu = '$id_menu' AND estado_seccion = '1'";
		$secciones = obtener_todo($query_secciones);
		if($secciones){
			foreach ($secciones as $subrow){
			?>
			<li class="item <?php if ($seccion==$subrow['id_seccion']){ echo 'seleccionado';} ?>"><a href="productos_ant.php?seccion=<?php echo $subrow['id_seccion']?>"><?php echo $subrow['nombre_seccion']?></a></li>
			<?php
			}
		}else{
			?>
			<li class="item <?php if ($menu==$row['id_menu']){ echo 'seleccionado';} ?>"><a href="productos_ant.php?menu=<?php echo $row ['id_menu']?>"><?php echo $row['nombre_menu']?></a></li>
			<?php
		}
	}
}
?>
</ul>	
</nav>
</div>
<?php

}

function boton_nuevo_producto(){
?>
<div class="item_botones"><a href="productos_ant.php?acc=newprod" class="nuevo_btn">Agregar Nuevo Producto</a></div>
<?php
}

function busqueda_sub(){
$search = "";
if(isset($_POST['search_btn'])){
$search = $_POST['search_btn'];
}	
?>
<div class="item_botones" id="formulario_busqueda">
	<form action="productos_ant.php" method="post">
		<input class="busqueda" type="text" name="search" value="<?php echo $search ?>" required/>
		<button class="search_btn" type="submit">Buscar Producto</button>
	</form>
</div>
<?php
}

//--------------productos

function productos($datos_usuario, $estatico) {

$search = "";
if(isset($_POST['search_btn'])){
$search = $_POST['search_btn'];
}
$id_menu = "";
if(isset($_POST['menu'])){
$id_menu = $_POST['menu'];
}
$id_seccion = "";
if(isset($_POST['seccion'])){
$id_seccion = $_POST['seccion'];
}		
	
if ($search){
	$titulo = "Búsqueda: ".$search;
	$query = "SELECT * FROM productos_ant WHERE nombre_producto LIKE '%" .$search. "%' OR descripcion_producto LIKE '%" .$search. "%'";
}elseif($id_menu){
	$query_menu = "SELECT * FROM menus WHERE id_menu = '$id_menu'";
	$menu = obtener_linea($query_menu);
	$nombre_menu = $menu['nombre_menu'];
	
	$titulo = "Menú: <strong>".$nombre_menu."</strong>";
	$query = "SELECT * FROM productos_ant  WHERE id_menu = '$id_menu'";
}elseif($id_seccion){
	$query_seccion = "SELECT * FROM secciones WHERE id_seccion = '$id_seccion'";
	$seccion = obtener_linea($query_seccion);
	$nombre_seccion = $seccion['nombre_seccion'];
	
	$titulo = "Sección: <strong>".$nombre_seccion."</strong>";
	$query = "SELECT * FROM productos_ant WHERE id_seccion = '$id_seccion'";
}else{
	$query = "SELECT * FROM productos_ant ORDER BY id_producto DESC";
	$titulo = "Todos Los productos";
}	

$productos = obtener_todo($query);	
$acc = "";
if(isset($_GET['acc'])){	
$acc = $_GET['acc'];
}
$edit = "";
if(isset($_GET['edit'])){	
$edit = $_GET['edit'];
}
$sus = "";
if(isset($_GET['sus'])){	
$sus = $_GET['sus'];
}
$act = "";
if(isset($_GET['act'])){	
$act = $_GET['act'];
}
$del = "";
if(isset($_GET['del'])){	
$del = $_GET['del'];
}	
$prod = "";
if(isset($_GET['prod'])){	
$prod = $_GET['prod'];
}
$fotos = "";
if(isset($_GET['fotos'])){	
$fotos = $_GET['fotos'];
}
$edit_codigo_stock = "";
if(isset($_GET['editcs'])){	
$edit_codigo_stock = $_GET['editcs'];
}	
$delc = "";
if(isset($_GET['delc'])){	
$delc = $_GET['delc'];
}
$accolor = "";
if(isset($_GET['accolor'])){	
$accolor = $_GET['accolor'];
}	

	
if ($edit){
$query_producto = "SELECT * FROM productos_ant WHERE id_producto = '$edit'";
$producto_edit = obtener_linea($query_producto);
$titulo = $producto_edit['nombre_producto'];
}
	
if ($edit_codigo_stock){
$query_producto = "SELECT * FROM productos_ant WHERE id_producto = '$edit_codigo_stock'";
$producto_edit = obtener_linea($query_producto);
$titulo = $producto_edit['nombre_producto'];
}

?>
<div class="titulo_prod">Productos - <?php echo $titulo; ?></div>
<?php
if ($acc == "newprod") {
	nuevo_prod();
} elseif ($fotos) {
	agregar_fotos($fotos);
} elseif ($prod) {
	show_prod($prod);
} elseif ($edit) {
	edit_prod($edit);
} elseif ($sus) {
	sus_prod($sus);
} elseif ($act) {
	act_prod($act);
} elseif ($del) {
	del_prod($del);
} elseif ($delc) {
	delc_prod($delc);
} elseif ($accolor) {
	color_prod($accolor);
} elseif ($edit_codigo_stock) {
	editcs($edit_codigo_stock);
} else {
if (!$productos) {
echo "<span id='vacio'>Aun no se han ingresado Productos</span>";
} else {
todos_los_productos($productos);
		}
	}
}

function todos_los_productos($productos){
?>
<div class="productos mobile">
	<div class="codigo"><span class="titulo_tabla">Código</span></div>
	<div class="nombre"><span class="titulo_tabla">Nombre</span></div>
	<div class="imagen"><span class="titulo_tabla">Imagen</span></div>
	<div class="precio"><span class="titulo_tabla">Precio</span></div>
	<div class="stock"><span class="titulo_tabla">Stock</span></div>
	<div class="estado"><span class="titulo_tabla">Estado</span></div>
	<div class="editar"><span class="titulo_tabla">Editar</span></div>
	<div class="eliminar"><span class="titulo_tabla">Eliminar</span></div>
</div>
<?php

foreach ( $productos as $row ) {
$query_fotos = "SELECT * FROM fotos_ant WHERE id_producto = ' $row[id_producto] ' AND activo_foto= '1'";
$fotos = obtener_todo($query_fotos);
$primera = $fotos[0]['nombre_foto'];
$query_codigos = "SELECT * FROM codigos_ant WHERE id_producto = $row[id_producto]";
$codigos = obtener_todo($query_codigos);
//print_r ($fotos);
?>
<div class="productos">
	<div class="codigo">
		<div class="titmob">Códigos:</div>
	<?php
	if ($codigos){
	foreach ($codigos as $rowcod){
		echo "<p>".$rowcod['nombre_codigo']."</p>";
	}
	echo "<p><a class='boton' href='productos_ant.php?editcs=".$row['id_producto']."'>Editar Códigos</a></p>";
	}else{
	echo "<p><a class='boton' href='productos_ant.php?editcs=".$row['id_producto']."'>Códigos</a></p>";
	}
	?>
    </div>
	<div class="nombre">
	<div class="titmob">Nombre:</div>
	<a href="productos_ant.php?edit=<?php echo $row['id_producto'] ?>"><?php echo $row['nombre_producto'] ?></a></div>
	<div class="imagen">
	<div class="titmob">Imagen:</div>
	<?php
	if (!$primera){
		echo "<p><a class='boton' href='productos_ant.php?fotos=".$row['id_producto']."'>Fotos</a></p>";
	}else{
		?>	
		<a href="productos_ant.php?fotos=<?php echo $row['id_producto'] ?>"><img src="../images/productos_ant/thumb/<?php echo $primera ?>"></a>
		<?php
	}
	?>
	</div>
	<div class="precio">
	<div class="titmob">Precio:</div>
	<?php
		if ($codigos){
	foreach ($codigos as $rowstock){
		echo "<p>".$rowstock['precio_codigo']."</p>";
	}
	}else{
	echo "<p><a class='boton' href='productos_ant.php?editcs=".$row['id_producto']."'>Precio</a></p>";
	}
	?>
	</div>
	<div class="stock">
	<div class="titmob">Stock:</div>
	<?php
	if ($codigos){
	foreach ($codigos as $rowstock){
		echo "<p>".$rowstock['stock_codigo']."</p>";
	}
	}else{
	echo "<p><a class='boton' href='productos_ant.php?editcs=".$row['id_producto']."'>Stock</a></p>";
	}
	?>
	<div class="titmob">Opciones:</div>
	</div>
	<div class="estado">
	<?php 
    if($row['activo_producto'] == "1"){ 
	echo '<img src="images/svg/activo.svg" width="12"><a href="productos_ant.php?sus='. $row['id_producto'] .'" class="boton">Desactivar</a>'; 
    }else{
		if(!$primera){
			echo '<a href="#" class="boton">Bloqueado</a>';
		}else{
    echo '<img src="images/svg/inactivo.svg" width="12"><a href="productos_ant.php?act='. $row['id_producto'] .'" class="boton">Activar</a>'; 
    }
	}	
    ?>
	</div>
	<div class="editar"><a href="productos_ant.php?edit=<?php echo $row['id_producto'] ?>" class="boton">Editar</a></div>
	<div class="eliminar"><a href="productos_ant.php?del=<?php echo $row['id_producto'] ?>" class="boton">Eliminar</a></div>
</div>
<div class="titmob azul"></div>
<?php
	}	
}

//-------------Crear producto

function nuevo_prod(){
$crear = "";
if(isset($_POST['crear'])){	
$crear = $_POST['crear'];
}

if (!$crear){
?>
<form action="productos_ant.php?acc=newprod" method="post" enctype="multipart/form-data">
<div id="edit_producto">
	<div class="datos bold">Nombre del Producto: </span><input id="nombre" name="nombre_txt" type="text" value="" maxlength="100" required></div>
	<script src='js/tinymce/tinymce.min.js'></script>
	<script>
		tinymce.init({
			selector: '#mytextarea',
			language: "es",
		toolbar: [
			'undo redo | bold italic | bullist numlist ',
		],
			menu: {
			}
		});
	</script>	
	<div class="datos">
	<div class="bold">Descripción:</div>
	<textarea id="mytextarea" name="desc_txt_area" cols="" rows=""></textarea>
	</div>
	<!-----menu categorias-------------->
	<div class="datos">
	<div class="categorias">
	<div class="titulo_crear">Menú:</div>
	<?php
	$query_menus = "SELECT * FROM menus WHERE estado_menu = '1'";
	$menus = obtener_todo($query_menus);
	?>
	<select name="menu_slc" id="categoria_pri">
		<?php
		foreach ($menus as $row) {
			if(($row['id_menu'] == 2) or ($row['id_menu'] == 3)){
				echo '<option value="' . $row['id_menu'] . '" >' . $row['nombre_menu'] . '</option>';
			}
		}
		?>
	</select>
	</div>
	<div class="categorias" >
		<?php
		$query_secciones = "SELECT * FROM secciones WHERE id_menu = '2' AND estado_seccion = '1'";
		$secciones = obtener_todo($query_secciones);
		if ($secciones){
		?>
		<div class="titulo_crear">Sección:</div>
		<select name="seccion_slc" id="subcat">
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

		<div class="categorias" >
		<?php
		
		$query_subsecciones = "SELECT * FROM subsecciones WHERE id_seccion = '1' AND activo_subseccion = '1'";
	//echo $query_subsecciones;
		$subsecciones = obtener_todo($query_subsecciones);
		if ($secciones){
		?>
		<div class="titulo_crear">Subseccion:</div>
		<select name="subseccion_slc" id="subsec">
		<?php
		foreach ($subsecciones as $row) {
			if($producto['id_subseccion'] == $row['id_subseccion']){
				$selected = "selected";
			}else{
				$selected = "";   
			}	
		echo '<option value="' . $row['id_subseccion']. '" '.$selected.'>' . $row['nombre_subseccion'] . '</option>';
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
				$("#subcat").load('secciones_prod.php?id='+id);
				setTimeout(function(){
					var idsub = $("#subcat").find(':selected').val();
				
					$("#subsec").load('subsecciones_prod.php?id='+idsub);
				}, 500);
				
			});
			$("#subcat").change(function(event){
				var id = $("#subcat").find(':selected').val();
				$("#subsec").load('subsecciones_prod.php?id='+id);
			});
		});
		

	</script>		
	</div>

	<div class="datos">
		<p><span class="bold">Oferta: </span><input name="oferta_chk" type="checkbox" value="1" ><br></p>
	</div>

	<div class="datos">
	<div class="colecciones" >
		<?php
		
		$query_colecciones = "SELECT * FROM colecciones WHERE estado_coleccion = 1";
	//echo $query_subsecciones;
		$colecciones = obtener_todo($query_colecciones);
		if ($colecciones){
		?>
		<div class="titulo_crear">Colecciones:</div>
		<select name="coleccion_slc" id="subsec">
		<option value="0">--Seleccione--</option>
		<?php
		foreach ($colecciones as $row) {
			if($producto['id_coleccion'] == $row['id_coleccion']){
				$selected = "selected";
			}else{
				$selected = "";   
			}	
		echo '<option value="' . $row['id_coleccion']. '" '.$selected.'>' . $row['nombre_coleccion'] . '</option>';
		}
		?>
		</select>
		
		<?php
		}
		?>
	</div>	
	</div>
	
	
	<div class="datos">
	<input type="hidden" value="proceder" name="crear">
	<br><br>
		<div class="botonera">
		<input class="crear" type="submit" value="Crear Producto">
		<a href="productos_ant.php" class="boton">Regresar</a>
		</div>
	</div>
</div>
</form>

<?php
}else{

date_default_timezone_set('America/Lima');
$hoy = date("Y-m-d");
$nombre = $_POST['nombre_txt'];
$descripcion = addslashes( $_POST['desc_txt_area'] );
$menu = $_POST['menu_slc'];
$seccion = $_POST['seccion_slc'];
$subseccion = $_POST['subseccion_slc'];
$coleccion = $_POST['coleccion_slc'];
$oferta = $_POST['oferta_chk'];
if(!$oferta){
$oferta = 0;
}

$query_producto = "INSERT INTO productos_ant (nombre_producto, descripcion_producto, id_menu, id_seccion, id_subseccion, menu_oferta, fecha_creado_producto,id_coleccion) VALUE ('$nombre', '$descripcion', '$menu', '$seccion', '$subseccion', '$oferta', '$hoy','$coleccion')";
	//echo $query_producto;

if (actualizar_registro($query_producto)){
	$query_ultimo_id ="SELECT MAX(id_producto) AS id FROM productos_ant";
	$ultimo_id = obtener_linea($query_ultimo_id);  
	$id = $ultimo_id['id'];
	
	$msj = "Producto agregado con éxito";
	$ruta = "productos_ant.php?editcs=".$id;
	$boton = "Agregar Códigos y Stock";
	mensaje_generico($msj, $ruta, $boton);

}else{
	$msj = "Error en el Servidor, por favor intente de nuevo";
	$ruta = "productos_ant.php?acc=newprod";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
}

}

}

//----------------Editar Producto

function edit_prod($edit){
$editar = "";
if(isset($_POST['editar'])){	
$editar = $_POST['editar'];
}
if (!$editar){
	$accolor = "";
	if(isset($_GET['accolor'])){	
	$accolor = $_GET['accolor'];
	}
	if($accolor){
		agregar_color_a_producto($edit, $accolor);
	}else{
		edit_prod_inicio($edit);
	}
}elseif($editar == proceder){
	edit_prod_editar($edit);
}

}

function edit_prod_inicio($edit){
$query_producto = "SELECT * FROM productos_ant WHERE id_producto = $edit";
$producto = obtener_linea($query_producto);

?>
<form action="productos_ant.php?edit=<?php echo $edit ?>" method="post" enctype="multipart/form-data">
<div id="edit_producto">
	<div class="datos bold">Nombre del Producto: </span><input id="nombre" name="nombre_txt" type="text" value="<?php echo $producto['nombre_producto'] ?>" maxlength="100" required></div>
	<script src='js/tinymce/tinymce.min.js'></script>
	<script>
	tinymce.init({
	selector: '#mytextarea',
	language: "es",
	 toolbar: [
		'undo redo | bold italic | bullist numlist ',
	  ],
	menu: {
	  }
	});
	</script>
	<div class="datos">
	<p><span class="bold">Descripción:</span><br></p>
	<textarea id="mytextarea" name="desc_txt_area" cols="" rows=""><?php echo $producto['descripcion_producto'] ?></textarea>
	</div>
	<!-----menu categorias-------------->
	<div class="datos">
	<div class="categorias">
	<div class="titulo_crear">Menú:</div>
	<?php
	$query_menus = "SELECT * FROM menus WHERE estado_menu = '1'";
	$menus = obtener_todo($query_menus);
	?>
	<select name="menu_slc" id="categoria_pri">
		<?php
		foreach ($menus as $row) {
			if(($row['id_menu'] == 2) or ($row['id_menu'] == 3)){
				if($producto['id_menu'] == $row['id_menu']){
					$selected = "selected";
				}else{
					$selected = "";   
				}
				echo '<option value="' . $row['id_menu'] . '" '.$selected.'>' . $row['nombre_menu'] . '</option>';
			}
		}
		?>
	</select>
	</div>
	<div class="categorias" id="subcat">
		<?php
		$id_menu = $producto['id_menu'];
		$query_secciones = "SELECT * FROM secciones WHERE id_menu = '$id_menu' AND estado_seccion = '1'";
		$secciones = obtener_todo($query_secciones);
		if ($secciones){
		?>
		<div class="titulo_crear">Sección:</div>
		<select name="seccion_slc">
		<?php
		foreach ($secciones as $row) {
			if($producto['id_seccion'] == $row['id_seccion']){
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

		<div class="categorias" id="subsec">
		<?php
		$id_seccion = $producto['id_seccion'];
		$query_subsecciones = "SELECT * FROM subsecciones WHERE id_seccion = '$id_seccion' AND activo_subseccion = '1'";
	//echo $query_subsecciones;
		$subsecciones = obtener_todo($query_subsecciones);
		if ($secciones){
		?>
		<div class="titulo_crear">Subseccion:</div>
		<select name="subseccion_slc">
		<?php
		foreach ($subsecciones as $row) {
			if($producto['id_subseccion'] == $row['id_subseccion']){
				$selected = "selected";
			}else{
				$selected = "";   
			}	
		echo '<option value="' . $row['id_subseccion']. '" '.$selected.'>' . $row['nombre_subseccion'] . '</option>';
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
				$("#subcat").load('secciones_prod.php?id='+id);
				setTimeout(function(){
					var idsub = $("#subcat").find(':selected').val();
				
					$("#subsec").load('subsecciones_prod.php?id='+idsub);
				}, 500);
			});
			$("#subcat").change(function(event){
				var id = $("#subcat").find(':selected').val();
				$("#subsec").load('subsecciones_prod.php?id='+id);
			});
		});
	</script>		
	</div>	
		
		
		
	<!-----otros-------------->
    <?php
    if ($producto['menu_oferta'] == 1){
        $sel_oferta = "checked";
    }
    ?>
	<div class="datos">
		<p><span class="bold">Oferta: </span><input name="oferta_chk" type="checkbox" value="1" <?php echo $sel_oferta; ?> ><br></p>
	</div>
	
	<div class="datos">
	
	<div class="colecciones">
		<?php
		$id_seccion = $producto['id_seccion'];
		$query_subsecciones = "SELECT * FROM colecciones WHERE estado_coleccion = 1";
	//echo $query_subsecciones;
		$subsecciones = obtener_todo($query_subsecciones);
		if ($secciones){
		?>
		<div class="titulo_crear">Colecciones:</div>
		<select name="coleccion_slc">
			<option value="0">--Seleccione--</option>
		<?php
		foreach ($subsecciones as $row) {
			if($producto['id_coleccion'] == $row['id_coleccion']){
				$selected = "selected";
			}else{
				$selected = "";   
			}	
		echo '<option value="' . $row['id_coleccion']. '" '.$selected.'>' . $row['nombre_coleccion'] . '</option>';
		}
		?>
		</select>
		
		<?php
		}
		?>
	</div>	
	</div>
	
	<!-----otros final-------------->
	<div class="datos">
	<input type="hidden" value="proceder" name="editar">
	<br><br>
		<div class="botonera">
		<input class="crear" type="submit" value="Editar Producto">
		<a href="productos_ant.php" class="boton">Regresar</a>
		</div>
	</div>
    
</div>
</form>

<?php
}

function edit_prod_editar($edit){

date_default_timezone_set('America/Lima');
$hoy = date("Y-m-d");

$nombre = $_POST['nombre_txt'];
$descripcion = addslashes( $_POST['desc_txt_area'] );

$menu = $_POST['menu_slc'];
$seccion = $_POST['seccion_slc'];
$subseccion = $_POST['subseccion_slc'];
$coleccion = $_POST['coleccion_slc'];
$oferta = $_POST['oferta_chk'];
if(!$oferta){
$oferta = 0;
}

//echo $nombre;
//echo $descripcion;
//echo $menu;	
//echo $seccion;
//echo $subseccion."<br>";
	
$query_actualizar_producto = "UPDATE productos_ant SET nombre_producto = '$nombre', id_coleccion='$coleccion', descripcion_producto = '$descripcion', id_menu = '$menu', id_seccion = '$seccion', id_subseccion = '$subseccion', menu_oferta = '$oferta', fecha_editado_producto = '$hoy'  WHERE id_producto = '$edit'";

//echo $query_actualizar_producto;
	
if (actualizar_registro($query_actualizar_producto)){
	$msj = "Producto Actualizado con exito";
	?>
	<div id="msj">
		<?php echo $msj; ?><br><br>
		<a href="productos_ant.php?edit=<?php echo $edit ?>" class="boton">Regresar</a>
	</div>
	<?php
}else{
	$msj = "Error en el Servidor, por favor intente de nuevo";
	?>
	<div id="msj">
		<?php echo $msj; ?><br><br>
		<a href="productos_ant.php?edit=<?php echo $edit ?>" class="boton">Regresar</a>
	</div>
	<?php
}

}

function editcs($edit_codigo_stock){
$accolor = $_POST['accolor'];
$editarcs = $_POST['editar_codigo'];

if($editarcs == "codigo_nuevo"){
codigo_nuevo($edit_codigo_stock);
}

if($editarcs == "editar_codigo_producto"){
editar_codigo_producto($edit_codigo_stock);
}
	
$query_codigos = "SELECT * FROM codigos_ant WHERE id_producto = $edit_codigo_stock";
$codigos = obtener_todo($query_codigos);

?>
<div class="subtitulo_prod">Códigos y Colores</div>
<?php

if (!$codigos){
codigo_nuevo_form($edit_codigo_stock);
}else{	
editar_codigo_form($codigos, $edit_codigo_stock);
codigo_nuevo_form($edit_codigo_stock);
}
?>
<div class="botonera">
<a href="productos_ant.php" class="boton">Regresar</a>
</div>
<?php	
}

function codigo_nuevo($edit_codigo_stock){

$codigo = $_POST['codigo00'];
$talla = $_POST['talla00'];
$precio = $_POST['precio00'];
$stock = $_POST['stock00'];
$oferta = $_POST['oferta00'];
if (!$oferta){
$oferta = "0";
}
$precio_oferta = $_POST['precio_oferta00'];

$query_codigos_comp ="SELECT * FROM codigos_ant WHERE id_producto = '$edit_codigo_stock'";
$codigos_comp = obtener_todo($query_codigos_comp);

$cd = 1;
if ($codigos_comp){
	foreach($codigos_comp as $row){
		if ($row['nombre_codigo'] == $codigo){
			$cd++;
		}
	}
}

//&echo $cd;	
	
if ($cd == 1){	
$query_codigo_nuevo = "INSERT INTO cursos (nombre_curso, id_tipo_curso, codigo_curso, skills_curso, syllabus_curso, imagen_curso, inicio_curso, fin_curso, ini_insc_curso, fin_insc_curso, precio_curso, id_docente) VALUE ('$nombre', '$tipo', '$codigo', '$skills', '$sillabus', '$inicio_curso', '$fin_curso', '$ini_insc', '$fin_insc', '$precio', '$docente')";
actualizar_registro($query_codigo_nuevo);
}else{
?>
<div id="msj"> El codigo ingresado ya existe </div>
<?php
}
	
}

function editar_codigo_producto($edit_codigo_stock){

$idcodigo = $_POST['idcodigo00'];
$codigo = $_POST['codigo00'];
$talla = $_POST['talla00'];
$precio = $_POST['precio00'];
$stock = $_POST['stock00'];
$oferta = $_POST['oferta00'];
if (!$oferta){
$oferta = "0";
}
$precio_oferta = $_POST['precio_oferta00'];


$query_codigo_editar = "UPDATE codigos_ant SET nombre_codigo = '$codigo', talla_codigo = '$talla', precio_codigo = '$precio', oferta_codigo = '$oferta ', precio_oferta_codigo = '$precio_oferta', stock_codigo = '$stock' WHERE id_codigo='$idcodigo'";
actualizar_registro($query_codigo_editar);
	
}

function codigo_nuevo_form($edit_codigo_stock){
?>
<div class="detalle">
	<form action="productos_ant.php?editcs=<?php echo $edit_codigo_stock ?>" method="post" enctype="multipart/form-data">
	<div class="data">Código: <input name="codigo00" type="text" required></div>
	<div class="data">Talla: <input name="talla00" type="text" required></div>
	<div class="data">Precio: <input name="precio00" type="number" step="0.01" value="" required></div>
	<div class="data">Stock: <input name="stock00" type="number" required></div>
	<div class="data">Oferta: <input name="oferta00" type="checkbox" value="1"></div>
	<div class="data">Precio Oferta: <input name="precio_oferta00" type="number" step="0.01" value=""></div>
	<div class="data">
	<input type="hidden" value="codigo_nuevo" name="editar_codigo">
	<input type="submit" value="Agregar Código">
	</div>
	</form>
</div>
<?php
}

function editar_codigo_form($codigos, $edit_codigo_stock){
?>

<div class="detalle">
	<?php
	foreach ($codigos as $row){
	?>
	<form action="productos_ant.php?editcs=<?php echo $edit_codigo_stock ?>" method="post" enctype="multipart/form-data">
	<div class="data">Código: <input name="codigo00" type="txt" value="<?php echo $row['nombre_codigo'] ?>" required></div>
	<div class="data">Talla: <input name="talla00" type="txt" value="<?php echo $row['talla_codigo'] ?>" required></div>
	<div class="data">Precio: <input name="precio00" type="number" step="0.01" value="<?php echo $row['precio_codigo'] ?>" required></div>
	<div class="data">Stock: <input name="stock00" type="number" value="<?php echo $row['stock_codigo'] ?>" required></div>
	<div class="data">Oferta: <input name="oferta00" type="checkbox" value="1" 
	<?php
	if ($row['oferta_codigo'] == 1){
		echo " checked ";
	} ?>
	></div>
	<div class="data">Precio Oferta: <input name="precio_oferta00" type="number" step="0.01" value="<?php echo $row['precio_oferta_codigo'] ?>"></div>
	<div class="data">
	<input type="hidden" value="<?php echo $row['id_codigo'] ?>" name="idcodigo00">
	<input type="hidden" value="editar_codigo_producto" name="editar_codigo">
	<input type="submit" value="Actualizar">
	</div>
	<div class="data"><a href="productos_ant.php?editcs=<?php echo $edit_codigo_stock ?>&accolor=<?php echo $row['id_codigo'] ?>" class="boton">Colores</a></div>
	<div class="data"><a href="productos_ant.php?editcs=<?php echo $edit_codigo_stock ?>&delc=<?php echo $row['id_codigo'] ?>" class="boton">Eliminar</a></div>
	<br><br>
	</form>
	<?php
	}
	
	?>
</div>
<?php
}

function act_prod($id) {
	$query = "UPDATE productos_ant SET activo_producto='1' WHERE id_producto='$id'";
	if (actualizar_registro($query) ) {
		$msj = "Producto Activado";
		$ruta = "productos_ant.php";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	} else {
		$msj = "Error en el Servidor, por favor intente de nuevo";
		$ruta = "productos_ant.php";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}
}

function sus_prod($id) {
	$query = "UPDATE productos_ant SET activo_producto='2' WHERE id_producto='$id'";
	if (actualizar_registro($query) ) {
		$msj = "Producto Suspendido";
		$ruta = "productos_ant.php";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	} else {
		$msj = "Error en el Servidor, por favor intente de nuevo";
		$ruta = "productos_ant.php";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}
}

function del_prod() {
$producto = $_GET['del'];
$conf = "";
if(isset($_GET['conf'])){
$conf = $_GET['conf'];	
}	


if (!$conf) {
?>
<div class="msj">
	<p>¿Realmente desea eliminar el Producto?, NO se va a poder recuperar datos relacionados.</p>
	<a href="productos_ant.php" class="boton">Regresar</a>
	<a href="productos_ant.php?del=<?php echo $producto; ?>&conf=borralo" class="boton">Si, Eliminar Producto</a>
</div>
<?php
}else{
		$query_producto = "DELETE FROM productos_ant WHERE id_producto='$producto'";
		$query_codigo = "DELETE FROM codigos_ant WHERE id_producto = '$producto'";
	if (actualizar_registro($query_producto)) {
		if (actualizar_registro($query_codigo)) {
			$ruta = "productos_ant.php";
			$msj = "<p>Producto Eliminado con éxito</p>";
			$boton = "Regresar";
			mensaje_generico($msj, $ruta, $boton);
		}else{
		$ruta = "productos_ant.php";
		$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
		}
	}else{
	$ruta = "productos_ant.php";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}

}
}

function delc_prod() {
$producto = $_GET['editcs'];
$codigo = $_GET['delc'];
$conf = $_GET['conf'];

if (!$conf) {
?>
<div class="msj">
	<p>¿Realmente desea eliminar el Código?, NO se va a poder recuperar datos relacionados.</p>
	<a href="productos_ant.php?editcs=<?php echo $producto; ?>" class="boton">Regresar</a>
	<a href="productos_ant.php?editcs=<?php echo $producto; ?>&delc=<?php echo $codigo; ?>&conf=borralo" class="boton">Si, Eliminar Codigo</a>
</div>
<?php
}else{
		$query_codigo = "DELETE FROM codigos_ant WHERE id_codigo = '$codigo' AND id_producto = '$producto'";
	if (actualizar_registro($query_codigo)) {
		$ruta = "productos_ant.php?editcs=".$producto;
		$msj = "<p>Código Eliminado con éxito</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
		}else{
		$ruta = "productos_ant.php?editcs=".$producto;
		$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}

}
}

function color_prod($accolor){
$accion_color = $_POST['acciones_color'];
	
if ($accion_color){
$id_color = $_POST['id_color'];
	
	if($accion_color == "desactivar_color"){
		//echo $id_color;
		$query_desactivar = "UPDATE colores_ant SET activo_color = '0' WHERE id_color = '$id_color'";
		actualizar_registro($query_desactivar);  
	}
	
	if($accion_color == "activar_color"){
		//echo $id_color;
		$query_activar = "UPDATE colores_ant SET activo_color = '1' WHERE id_color = '$id_color'";
		actualizar_registro($query_activar); 
	}

	if($accion_color == "eliminar_color"){
		//echo $id_color;
		$query_eliminar = "DELETE FROM colores_ant WHERE id_color = '$id_color'";
		actualizar_registro($query_eliminar); 
	}
	
	if($accion_color == "nuevo_color"){
		$id_codigo = $_POST['id_codigo'];
		$nombre_color = $_POST['nombre_color'];
		$imagen = trim($_FILES['imagen_color']['name']);
		$temporal = $_FILES['imagen_color']['tmp_name'];
		$imagen_size = $_FILES['imagen_color']['size'];
		$imagen_type = $_FILES['imagen_color']['type'];
		
		if ($imagen_type == 'image/jpeg'){
		$nombre_tipo = '.jpg';
		}elseif ($imagen_type == 'image/png'){
		$nombre_tipo = '.png';
		}

		$nombrerand = time(). rand(0, 9) . rand(100, 9999) . rand(100, 9999) . rand(1000, 99999). $nombre_tipo;
		
		if ($imagen_type == 'image/jpeg' || $imagen_type == 'image/png') {
			if ($imagen_size < 2000000) {
				//echo $id_codigo."<br>";
				//echo $nombre_color."<br>";
				//echo $imagen."<br>";
				//echo $temporal."<br>";
				//echo $imagen_size."<br>";
				//echo $imagen_type."<br>";
				//echo $nombrerand."<br>";
				agregar_imagen_color($temporal, $imagen_type, $nombrerand);
				$query_nuevo_color = "INSERT INTO colores_ant (id_codigo, nombre_color, imagen_color, activo_color) VALUE ('$id_codigo', '$nombre_color', '$nombrerand', '1')";
				//actualizar_registro($query_nuevo_color); 
				//$query_foto = "INSERT INTO fotos (id_producto, nombre_foto, activo_foto) VALUE ('$id', '$nombrerand', '1')";
				if(actualizar_registro($query_nuevo_color)){
				$msj .= "<p>Imagen: ".$imagen. ", agregada con éxito</p>";
				}else{
				$msj .= "<p>Error al subir la imagen: ".$imagen. ", por favor intentar nuevamente.</p>";
				}
			}else{
				$msj .= "<p>La Imagen: ".$imagen. ", tiene un peso mayor al permitido: 2 megas</p>";
			}
		}else{
			$msj .= "<p>El Archivo: ".$imagen. ", no es de un formato permitido: jpg o png.<p>";
		}

		$ruta = "productos_ant.php?editcs=".$_GET['editcs']."&accolor=".$_GET['accolor'];
		$boton = "Cerrar";
		mensaje_generico($msj, $ruta, $boton);
	}
	
}

$query_codigo = "SELECT * FROM codigos_ant WHERE id_codigo = '$accolor'";
$codigo = obtener_linea($query_codigo);
	
$query_producto = "SELECT * FROM productos_ant WHERE id_producto = '$codigo[id_producto]'";
$producto = obtener_linea($query_producto);
	
$query_colores = "SELECT * FROM colores_ant WHERE id_codigo = '$accolor'";
$colores = obtener_todo($query_colores);
	
?>
<div id="colores">
<div id="descripcion"> Colores de producto: <span class="resalte"><?php echo $producto['nombre_producto'] ?></span>, Código: <span class="resalte"><?php echo $codigo['nombre_codigo'] ?></span></div>
<?php
//echo $accolor;
if ($colores){
	foreach($colores as $row){
	?>
	<div class="detalle">
	
	<div class="data"><img src="../images/colores/<?php echo $row['imagen_color'] ?>"></div>
	<div class="data"><span class="nombre_color"><?php echo $row['nombre_color'] ?></span></div>
	<div class="data">
		<form action="productos_ant.php?editcs=<?php echo $producto['id_producto'] ?>&accolor=<?php echo $accolor ?>" method="post" enctype="multipart/form-data">	
		<?php
		if ($row['activo_color'] == 1){
		?>
		<input type="hidden" value="desactivar_color" name="acciones_color">
		<input type="hidden" value="<?php echo $row['id_color'] ?>" name="id_color">
		<input type="submit" value="Desactivar">	
		<?php	
		}else{
		?>
		<input type="hidden" value="activar_color" name="acciones_color">
		<input type="hidden" value="<?php echo $row['id_color'] ?>" name="id_color">
		<input type="submit" value="Activar">	
		<?php
		}
		?>
		</form>
	</div>
	<div class="data">
		<form action="productos_ant.php?editcs=<?php echo $producto['id_producto'] ?>&accolor=<?php echo $accolor ?>" method="post" enctype="multipart/form-data">	
		<input type="hidden" value="eliminar_color" name="acciones_color">
		<input type="hidden" value="<?php echo $row['id_color'] ?>" name="id_color">	
		<input type="submit" value="Eliminar">	
		</form>
	</div>
	</div>
<?php	
	}
}else{
?>
	<div id="sincolor">No hay colores agregados</div>
<?php
}
?>
<div id="color">
	<div class="titulo_color">Agregar color</div>
	<div id="form">
       	<form action="productos_ant.php?editcs=<?php echo $producto['id_producto'] ?>&accolor=<?php echo $accolor ?>" method="post" enctype="multipart/form-data">
			<div id="agregar_color">
			<p>La imagen debe de ser cuadrada con un tamaño mínimo de 240px de ancho por 240px de alto.</p>
			</div>
            <div class="dato">Nombre del color: <input type="text" name="nombre_color" required></div>
			<div class="dato"><input type="file" name="imagen_color" required></div>
			<div class="dato">
			<input type="hidden" value="nuevo_color" name="acciones_color">
			<input type="hidden" value="<?php echo $accolor ?>" name="id_codigo">	
			<input type="submit" value="Agregar Nuevo Color">	
			</div>
		</form>
	</div>
</div>

<div class="botonera">
	<a href="productos_ant.php?editcs=<?php echo $producto['id_producto'] ?>" class="boton">Regresar</a>
</div>
	
</div>
<?php

	  
}

//--------------Fotos

function agregar_fotos($id){

$des = $_GET['des'];
$act = $_GET['act'];

if ($des){
$query = "UPDATE fotos_ant SET activo_foto='2' WHERE id_foto='$des'";

	if (actualizar_registro($query)) {
		$msj = "Foto Desactivada";
		?>
		<div id="msj">
			<?php echo $msj; ?><br><br>
			<a href="productos_ant.php?fotos=<?php echo $id; ?>" class="boton">Regresar</a>
		</div>
		<?php
	} else {
		$msj = "Error en el Servidor, por favor intente de nuevo";
		?>
		<div id="msj">
			<?php echo $msj; ?><br><br>
			<a href="productos_ant.php?fotos=<?php echo $id; ?>" class="boton">Regresar</a>
		</div>
		<?php
	}

}elseif($act){
$query = "UPDATE fotos_ant SET activo_foto='1' WHERE id_foto='$act'";

	if (actualizar_registro($query)) {
		$msj = "Foto Activa";
		?>
		<div id="msj">
			<?php echo $msj; ?><br><br>
			<a href="productos_ant.php?fotos=<?php echo $id; ?>" class="boton">Regresar</a>
		</div>
		<?php
	} else {
		$msj = "Error en el Servidor, por favor intente de nuevo";
		?>
		<div id="msj">
			<?php echo $msj; ?><br><br>
			<a href="productos_ant.php?fotos=<?php echo $id; ?>" class="boton">Regresar</a>
		</div>
		<?php
	}

}else{

$addphoto = $_POST['addphoto'];

if(!$addphoto){
	
$query = "SELECT * FROM productos_ant WHERE id_producto = '$id'";
$producto = obtener_linea($query);
	
$query_fotos = "SELECT * FROM fotos_ant WHERE id_producto = '$id'";
$fotos = obtener_todo($query_fotos);

?>

<div class="producto">
	<div class="datos">
        <div class="desc">
			<div class="tit_prod">
			<p><span class="bold">Nombre del Producto: <?php echo $producto['nombre_producto'] ?></span><br></p>
			</div>
		</div>
		
        <div id="fotos">
			<div id="muestra">
				<?php
				if (!$fotos){	
				?>
				<p>Aún no hay fotos relacionadas</p>
				<p>Mientras el producto no tenga fotografías relacionadas no puede ser activado.</p>
				<?php
				//print_r($producto);
				}else{
				//print_r($fotos);
				$cont = 1;
				$conta = 1;
				$contad = 1;
				foreach ($fotos as $row){
				?>
				<div class="fotoedit">
				
					<div class="foto">
					<img src="../images/productos/small/<?php echo $row['nombre_foto'] ?>" />
					<input type="hidden" value="<?php echo $row['id_foto']?>" class="<?php echo $contad++ ?>">
					<input type="hidden" value="<?php echo $row['nombre_foto'] ?>" class="xd" id="<?php echo $cont  ?>">
					</div>
					<div class="actdes">
					<?php 
					if($row['orden_foto'] == 1){
						$checked = "checked";		
					}else{
						$checked = "";
					}
    				if($row[activo_foto] == "1"){ 
						
						echo '<img src="svg/activo.svg" width="12"><a href="productos_ant.php?fotos='. $id .'&des='. $row['id_foto'] .'" class="boton">Desactivar</a>'; 
						echo '<img src="svg/inactivo.svg" width="12"><button class="boton nom'.$conta++.'">Eliminar</button><input type="radio" name="pescprimero" id="'. $row[id_foto] .'" class="obtcheck" '.$checked.' attr-foto="'. $id .'">';
					}else{
    					echo '<img src="svg/inactivo.svg" width="12"><a href="productos_ant.php?fotos='. $id .'&act='. $row['id_foto'] .'" class="boton">Activar</a>'; 
						echo '<img src="svg/inactivo.svg" width="12"><button class="boton nom'.$conta++.'">Eliminar</button><input type="radio" name="pescprimero" id="'. $row[id_foto] .'" '.$checked.' class="obtcheck" attr-foto="'. $id .'">';
					}	
					?>
					</div>
				
				</div>

				<script>
					$(document).ready(function(){
						var arraynom = [];
				
						$(".xd").each(function(){
							var s = $(this).attr('id');
							$(".nom" + s).click(function(){
								var e = $("#"+ s).val();
								var idfoto = $("." + s).val();
								$.ajax({
									url: 'elimina_foto.php',
									type: 'post',
									data: {'nomfoto': e,'idfoto': idfoto,'eliminaf':'eliminaf'},
									success: function(res){
										console.log("asd");
										location.reload();
									},
									error: function(resp){
										console.log(resp);
									}
								})

							})
						})
						////////////////
						$(".obtcheck").on("change",function(){
							var idfo = $(this).attr("id");
							var foto = $(this).attr("attr-foto");
							$.ajax({
									url: 'elimina_foto.php',
									type: 'post',
									data: {'idfoto': idfo,'checkf':'checkf','foto':foto},
									success: function(res){
										console.log(res);
									},
									error: function(resp){
										console.log(resp);
									}
								})
						})
													
					});
					
				
				
				</script>
				<?php
				$cont++;
				}
				}
				?>
			</div>
            <div id="form">
            <form action="productos_ant.php?fotos=<?php echo $producto['id_producto'] ?>" method="post" enctype="multipart/form-data">
				<div id="agregar_fotos">
					<p>Puedes agregar hasta 5 fotos en grupo o una por una.</p>
					<p>El tamaño máximo por fotograía de es de 1.5 Megas.</p>
					<p>Se recomienda usar fondos blancos o con buena distribución de los elementos.</p>
					<p>Se recomienda un Mínimo de tamaño de 800px de ancho por 1200px de Alto o Proporcional</p>
				</div>
                <input id="file_url" type="file" name="nueva_foto[]" multiple="multiple" required>
				<br><br>
				<div class="botonera">
                <input type="hidden" value="agregar_fotos" name="addphoto" />
					<input class="crear" type="submit" value="Agregar Fotografías">
					<a href="productos_ant.php" class="boton">Regresar</a>
				</div>
			</form>
			</div>
        </div>    
    </div>        
</div>

<?php
}else{

//echo '<pre>';
//print_r ($_FILES['nueva_foto']);
//echo '</pre><br>';

$cantidad = count($_FILES['nueva_foto']['tmp_name']);
//echo "Cantidad: ".$cantidad."<br>";

if ($cantidad > '5'){
	$ruta = "productos_ant.php?fotos=".$id;
	$msj = "<p>Por favor seleccionar máximo 5 Fotografias, con extenciones .jpg o .png, no se permiten otras extensiones</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
}else{

$msj = "";

foreach($_FILES["nueva_foto"]['tmp_name'] as $key => $tmp_name){

$temporal = $_FILES['nueva_foto']['tmp_name'][$key];
$nombre = $_FILES['nueva_foto']['name'][$key];
$imagen_type = $_FILES['nueva_foto']['type'][$key];
$imagen_size = $_FILES['nueva_foto']['size'][$key];
if ($imagen_type == 'image/jpeg'){
$nombre_tipo = '.jpg';
}elseif ($imagen_type == 'image/png'){
$nombre_tipo = '.png';
}

$nombrerand = time(). rand(0, 9) . rand(100, 9999) . rand(100, 9999) . rand(1000, 99999). $nombre_tipo;
	
//echo "Nombre: ".$nombre."<br>";
//echo "Nombre Temporal: ".$temporal."<br>";
//echo "Tipo de archivo: ".$imagen_type."<br>";
//echo "Peso de archivo: ".$imagen_size."<br>";
//echo "Nombre_random: ".$rand."<br>";
//echo "<br><br>";

if ( $imagen_type == 'image/jpeg' || $imagen_type == 'image/png' ) {
	if ($imagen_size < 2000000) {
		
		agregar_imagenes($temporal, $imagen_type, $nombrerand);
		$query_foto = "INSERT INTO fotos_ant (id_producto, nombre_foto, activo_foto) VALUE ('$id', '$nombrerand', '1')";
		if(actualizar_registro($query_foto)){
		$msj .= "<p>Imagen: ".$nombre. ", agregada con éxito</p>";
		}else{
		$msj .= "<p>Error al subir la imagen: ".$nombre. ", por favor intentar nuevamente.</p>";
		}
		
	}else{
		$msj .= "<p>La Imagen: ".$nombre. ", tiene un peso mayor al permitido: 2 megas</p>";
	}
}else{
	$msj .= "<p>El Archivo: ".$nombre. ", no es de un formato permitido: jpg o png.<p>";
}

}
$ruta = "productos_ant.php";
$boton = "Regresar";
mensaje_generico($msj, $ruta, $boton);
}

}

}
}

function agregar_imagen_color($temporal, $imagen_type, $nombrerand){
$ancho_alto = 240;

//abrir la foto original

if ($imagen_type == 'image/jpeg'){
$original = imagecreatefromjpeg($temporal);
}elseif($imagen_type == 'image/png'){
$original = imagecreatefrompng($temporal);
}

$ancho_original = imagesx($original);
$alto_original = imagesy($original);

$copia = imagecreatetruecolor($ancho_alto, $ancho_alto);
imagecopyresampled($copia, $original, 0, 0, 0, 0, $ancho_alto, $ancho_alto, $ancho_original, $alto_original);

if ($imagen_type == 'image/jpeg'){
imagejpeg($copia, '../images/colores/'.$nombrerand, 90);
}elseif($imagen_type == 'image/png'){
imagepng($copia, '../images/colores/'.$nombrerand, 8);
}	
	
}

function agregar_imagenes($temporal, $imagen_type, $nombrerand){
$thumb = 100;
$small = 500;
$max = 1200;

//abrir la foto original

if ($imagen_type == 'image/jpeg'){
$original = imagecreatefromjpeg($temporal);
}elseif($imagen_type == 'image/png'){
$original = imagecreatefrompng($temporal);
}


$ancho_original = imagesx($original);
$alto_original = imagesy($original);

if ($ancho_original == $alto_original){
	$manda = "iguales";
	//nuevo ancho 
	$ancho_nuevo_small = $small;
	$ancho_nuevo_thumb = $thumb;
	//nuevo alto 
	$alto_nuevo_small = $small;
	$alto_nuevo_thumb = $thumb;
	
	if($ancho_original > $max){
		muygrande($manda, $ancho_original, $alto_original, $max, $original, $nombrerand, $imagen_type);
	}else{
		estabien($ancho_original, $alto_original, $original, $nombrerand, $imagen_type);
	}
	
}elseif($ancho_original < $alto_original){
	$manda = "alto";
	//nuevo alto 
	$alto_nuevo_small = $small;
	$alto_nuevo_thumb = $thumb;
	//nuevo ancho 
	$ancho_nuevo_small = round($alto_nuevo_small * $ancho_original /$alto_original);
	$ancho_nuevo_thumb = round($alto_nuevo_thumb * $ancho_original /$alto_original);
	
	if($alto_original > $max){
		muygrande($manda, $ancho_original, $alto_original, $max, $original, $nombrerand, $imagen_type);
	}else{
		estabien($ancho_original, $alto_original, $original, $nombrerand, $imagen_type);
	}
	
}elseif($ancho_original > $alto_original){
	$manda = "ancho";
	//nuevo ancho
	$ancho_nuevo_small = $small;
	$ancho_nuevo_thumb = $thumb;
	//nuevo alto 
	$alto_nuevo_small = round($ancho_nuevo_small * $alto_original / $ancho_original);
	$alto_nuevo_thumb = round($ancho_nuevo_thumb * $alto_original / $ancho_original);

	if($ancho_original > $max){
		muygrande($manda, $ancho_original, $alto_original, $max, $original, $nombrerand, $imagen_type);
	}else{
		estabien($ancho_original, $alto_original, $original, $nombrerand, $imagen_type);
	}
}

//crear lienzo vacio ( foto destino tamaño variable)
$copia_small = imagecreatetruecolor($ancho_nuevo_small, $alto_nuevo_small);
$copia_thumb = imagecreatetruecolor($ancho_nuevo_thumb, $alto_nuevo_thumb);

//copiar original -> copia
//1-2 destino y original
//3-4 x_y pegado
//5-6 x_y original
//7_8 ancho y alto detino
//7_8 ancho y alto original

imagecopyresampled($copia_small, $original, 0, 0, 0, 0, $ancho_nuevo_small, $alto_nuevo_small, $ancho_original, $alto_original);
imagecopyresampled($copia_thumb, $original, 0, 0, 0, 0, $ancho_nuevo_thumb, $alto_nuevo_thumb, $ancho_original, $alto_original);

if ($imagen_type == 'image/jpeg'){
//exportar/guardar imagen
imagejpeg($copia_small, '../images/productos/small/'.$nombrerand, 90);
imagejpeg($copia_thumb, '../images/productos/thumb/'.$nombrerand, 90);
}elseif($imagen_type == 'image/png'){
imagepng($copia_small, '../images/productos/small/'.$nombrerand, 8);
imagepng($copia_thumb, '../images/productos/thumb/'.$nombrerand, 8);
}

//final funcion agregar_imagenes
}

function muygrande($manda, $ancho_original, $alto_original, $max, $original, $nombrerand, $imagen_type){
	if ($manda == "iguales"){
	//echo "Iguales </br>";
	$ancho_nuevo = $max;
	$alto_nuevo = round($ancho_nuevo * $alto_original / $ancho_original);
	}elseif($manda == "alto"){
	//echo "Manda Alto </br>";
	$alto_nuevo = $max;
	$ancho_nuevo = round($alto_nuevo * $ancho_original /$alto_original);
	}elseif($manda == "ancho"){
	//echo "Manda Ancho </br>";
	$ancho_nuevo = $max;
	$alto_nuevo = round($ancho_nuevo * $alto_original / $ancho_original);
	}
	$copia = imagecreatetruecolor($ancho_nuevo, $alto_nuevo);
	imagecopyresampled($copia, $original, 0, 0, 0, 0, $ancho_nuevo, $alto_nuevo, $ancho_original, $alto_original);
	
		if ($imagen_type == 'image/jpeg'){
		imagejpeg($copia, '../images/productos/'.$nombrerand, 90);
		}elseif($imagen_type == 'image/png'){
		imagepng($copia, '../images/productos/'.$nombrerand, 8);
		}
}

function estabien($ancho_original, $alto_original, $original, $nombrerand, $imagen_type){
	//echo "se queda igual </br>";
	$copia = imagecreatetruecolor($ancho_original, $alto_original);
	imagecopyresampled($copia, $original, 0, 0, 0, 0, $ancho_original, $alto_original, $ancho_original, $alto_original);

		if ($imagen_type == 'image/jpeg'){
		//exportar/guardar imagen
		imagejpeg($copia, '../images/productos/'.$nombrerand, 90);
		}elseif($imagen_type == 'image/png'){
		imagepng($copia, '../images/productos/'.$nombrerand, 8);
		}
}

?>