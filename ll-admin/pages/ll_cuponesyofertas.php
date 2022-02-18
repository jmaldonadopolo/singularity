<?php 
date_default_timezone_set( 'America/Lima' );
$sec = "";

if(isset($_GET["sec"])){
$sec = $_GET["sec"]; 
}

menu_lateral();

?>
<div id="contenido">
	<div id="titulo">Cupones y Ofertas</div>
	<div id="cuadro">
<?php

if (!$sec){ 
cupones();
}

if ($sec == "cupones"){ 
cupones();
}

if ($sec == "notasdecredito"){ 
notas_credito();
}
?>
	</div>
</div>

<?php

function menu_lateral(){
?>
<div id="lateralizq">
    <nav>
	<ul>
        <li class="item <?php if (!$sec){ echo 'seleccionado';} ?>"><a href="cuponesyofertas.php">Cupones</a></li>
        <li class="item <?php if ($sec == "notasdecredito"){ echo 'seleccionado';} ?>"><a href="cuponesyofertas.php?sec=notasdecredito">Notas de Crédito</a></li>
    </ul>
    </nav>
</div>
<?php
}

/*-----Cuipones----*/

function cupones(){
$accion = "";
if(isset($_POST['accion'])){	
$accion = $_POST['accion'];
}

$query_cupones = "SELECT * FROM cupones_descuento WHERE id_cupon = 1";
$cupon = obtener_linea($query_cupones);

if ($accion == "vamos"){
	modificar_cupones();
}else{
	cupones_inicio($cupon);
}
}

function cupones_inicio($cupon){
$hoy = date( "Y-m-d" );
$hora = date ("H:i");
//print_r($cupon);
?>
<div id="titulo">Cupones</div>
<div id="formulario">
<form enctype="multipart/form-data" action="cuponesyofertas.php?sec=cupones" method="post">
	<div class="activar">
    	<div id="activo"> Cupón Activo 
			<input type="checkbox" value="1" name="activar" 
			<?php
            if ($cupon['activo_cupon'] == 1){
            echo " checked ";
            }
            ?>
        	/>
		</div>
    </div>
	<div id="cupon">Código: <input class="codigo" name="nombre_cupon" type="text" value="<?php echo $cupon['codigo_cupon'] ?>" required /></div>
	<hr />
	<div class="fecha"> Fecha y Hora de inicio: 
		<input name="inicio_dt" type="date" min="<?php echo $hoy; ?>" value="<?php echo $cupon['fecha_inicio_cupon']; ?>" required>
		<input name="inicio_hr" type="time" value="<?php echo $cupon['hora_inicio_cupon']; ?>" required>
	</div>
	<div class="fecha"> Fecha y Hora de cierre: 
		<input name="final_dt" type="date" min="<?php echo $hoy; ?>" value="<?php echo $cupon['fecha_cierre_cupon']; ?>" required >
		<input name="final_hr" type="time" value="<?php echo $cupon['hora_cierre_cupon']; ?>" required>
	</div>
    <hr />
	<div class="funcion"> Aplica a: <br>
	<?php
	$lista_array = explode(',',$cupon['secciones_cupon']);
	$query_subcategorias = "SELECT * FROM secciones";
	$subcategorias = obtener_todo($query_subcategorias);

	if($subcategorias){
	foreach ( $subcategorias as $row ) {
	?>
	<div class="datos_inline">
		<p><span class="bold"><?php echo $row['nombre_seccion'] ?>: </span>
			<input name="secciones_chk[]" type="checkbox" value="<?php echo $row['id_seccion'] ?>"
			<?php
			if (in_array($row['id_seccion'], $lista_array)) {
				echo " checked ";
			}
			?>
			>
			<br>
		</p>
	</div>
	<?php
	}
	}else{
	?>
	<div>No existen subsecciones creadas	</div>
	<?php
	}	
	?>
	<hr />
	<div id="datos">
		<p>
		<span class="bold">Porcentaje de descuento: </span><input name="porcentaje_nmr" type="number" min="1" max="99" value="<?php echo $cupon['porcentaje_cupon']; ?>" > %<br>
		</p>
	</div>
	</div>
	<div id="envio">
		<input type="hidden" name="accion" value="vamos"  />
		<input class="boton" type="submit" value="Actualizar Cupón" />
	</div>
</form>
</div>
<?php
if ($cupon['activo_cupon'] == 1){	
?>	
<div id="promoactiva">
<?php
$ahora = new DateTime();
$datetime1 = new DateTime($cupon['fecha_inicio_cupon'].$cupon['hora_inicio_cupon']); 
 	$datetime2   = new DateTime($cupon['fecha_cierre_cupon'].$cupon['hora_cierre_cupon']); 
$interval = date_diff($datetime1, $datetime2);
	
if($ahora > $datetime2){
	$titulo = "Cupón Finalizado";
}else{
	$inicio = date_diff($ahora, $datetime1);
	$fin = date_diff($ahora, $datetime2);
	if ($ahora > $datetime1){
	if ($cupon['activo_cupon'] == 0){
       $titulo = "(apagada) ";
       }
	$titulo = "";	
	$titulo = $titulo."El cupón esta activo hace: ".$inicio->format('%R%a días y %H:%I horas'). " y termina dentro de: ".$fin->format('%R%a días y %H:%I horas');
	}elseif($ahora < $datetime1){
	$titulo = "El cupón se activará dentro de:".$inicio->format('%R%a días y %H:%I horas'); 
	}
}
?>
	<div id="estado"><?php echo $titulo; ?></div>
	<div class="detalle">Desde: <?php echo $cupon['fecha_inicio_cupon']; ?> <?php echo $cupon['hora_inicio_cupon']; ?></div>
	<div class="detalle">Hasta: <?php echo $cupon['fecha_cierre_cupon']; ?> <?php echo $cupon['hora_cierre_cupon']; ?></div>
	<div class="detalle">Duración del Cupón: <?php echo $interval->format('%a días y %H:%I horas'); ?></div>
	<div class="detalle"></div>
</div>
<?php
}else{
?>
<div id="promoactiva">
	<div class="nota">Cupón Apagado</div>
</div>
<?php
}
}

function modificar_cupones(){
$activar = "";
if(isset($_POST['activar'])){	
$activar = $_POST['activar'];
}
if (!$activar){
$activar = "0";
}
$codigo = $_POST['nombre_cupon'];
$codigo = strtoupper($codigo);
$fecha_inicio = $_POST['inicio_dt'];
$fecha_cierre = $_POST['final_dt'];
$hora_inicio = $_POST['inicio_hr'];
$hora_cierre = $_POST['final_hr'];
$secciones = "";
if(isset($_POST['secciones_chk'])){	
$secciones = $_POST['secciones_chk'];
}
$porcentaje = $_POST['porcentaje_nmr'];

if ($secciones){
$secciones_string = implode(',', $secciones);
}

if($secciones){	
$query_cupones = "UPDATE cupones_descuento SET activo_cupon = '$activar', codigo_cupon = '$codigo',	fecha_inicio_cupon = '$fecha_inicio', fecha_cierre_cupon = '$fecha_cierre', hora_inicio_cupon = '$hora_inicio',	hora_cierre_cupon = '$hora_cierre',	secciones_cupon = '$secciones_string', porcentaje_cupon = '$porcentaje' WHERE id_cupon = '1'";

if (actualizar_registro($query_cupones)){
$msj = "Cupón Actualizado";
?>
<div id="msj">
	<?php echo $msj; ?><br><br>
	<a href="cuponesyofertas.php?sec=cupones" class="boton">Regresar</a>
</div>
<?php
}else{
$msj = "Error en el Servidor, por favor intente de nuevo";
?>
<div id="msj">
	<?php echo $msj; ?><br><br>
	<a href="cuponesyofertas.php?sec=cupones" class="boton">Regresar</a>
</div>
<?php
}
}else{
?>
<div id="msj">
	<?php
	$msj = "No se han seleccionado secciones que aplica";
	echo $msj; 
	?>
	<br><br>
	<a href="cuponesyofertas.php?sec=cupones" class="boton">Regresar</a>
</div>
<?php	
}
	
}

/*--------Notas-----*/

function notas_credito(){
$accion = "";
if (isset($_GET['accion'])){
$accion = $_GET['accion'];
}
$editar = "";
if(isset($_GET['editar'])){
$editar = $_GET['editar'];	
}
$eliminar = "";
if (isset($_GET['eliminar'])){
$eliminar = $_GET['eliminar'];	
}	

if ($accion == "nuevo" ){
	nueva_nota();
}elseif($editar){
	editar_nota($editar);
}elseif($eliminar){
	eliminar_nota($eliminar);
}else{
	notas_inicio();
}

}

function eliminar_nota($eliminar){
$confirmacion = $_GET[confima];
if($confirmacion == "ok"){
	$query_eliminar = "DELETE FROM notas_credito WHERE id_nota='$eliminar'";
	if(actualizar_registro($query_eliminar)){
		$msj = "Nota de Crédito Eliminada con éxito";
		?>
		<div id="msj">
		<?php echo $msj; ?><br><br>
		<a href="cuponesyofertas.php?sec=notasdecredito" class="boton">Regresar</a>
		</div>
		<?php
		} else {
		$msj = "Error en el Servidor, por favor intente de nuevo";
		?>
		<div id="msj">
		<?php echo $msj; ?><br><br>
		<a href="cuponesyofertas.php?sec=notasdecredito" class="boton">Regresar</a>
		</div>
		<?php
		}
}else{
$msj = "¿Confirma Eliminar la Nota de crédito?";
	?>
	<div id="msj">
	<?php echo $msj; ?><br><br>
	<a href="cuponesyofertas.php?sec=notasdecredito" class="boton">Regresar</a>
	<a href="cuponesyofertas.php?sec=notasdecredito&eliminar=<?php echo $eliminar; ?>&confima=ok" class="boton">Confirmar</a>
	</div>
	<?php
}

}

function editar_nota($editar){
$actualizar = $_POST[actualizar];

if ($actualizar == "vamos"){
$codigo = $_POST['nombre_codigo'];
$codigo = strtoupper($codigo);
$caduca = $_POST['caduca_dt'];
$monto = $_POST['monto_nmr'];
$usado = $_POST['usado_nmr'];
$fecha_uso = $_POST['uso_dt'];

if(!$fecha_uso){
$fecha_uso ="0000-00-00";
}

	$query_edit_nota = "UPDATE notas_credito SET codigo_nota = '$codigo',	fecha_caducidad_nota = '$caduca', monto_soles = '$monto', usado_nota = '$usado', fecha_uso_nota = '$fecha_uso' WHERE id_nota = '$editar'";
	if(actualizar_registro($query_edit_nota)){
		$msj = "Nota de Crédito Actualizada con éxito";
		?>
		<div id="msj">
		<?php echo $msj; ?><br><br>
		<a href="cuponesyofertas.php?sec=notasdecredito" class="boton">Regresar</a>
		</div>
		<?php
	} else {
		$msj = "Error en el Servidor, por favor intente de nuevo";
		?>
		<div id="msj">
		<?php echo $msj; ?><br><br>
		<a href="cuponesyofertas.php?sec=notasdecredito" class="boton">Regresar</a>
		</div>
		<?php
	}
	
}else{
$query_nota = "SELECT * FROM notas_credito WHERE id_nota = '$editar'";
$nota = obtener_linea($query_nota);
?>
<div id="titulo">Editar Nota de Crédito</div>
<div id="formulario">
<form enctype="multipart/form-data" action="cuponesyofertas.php?sec=notasdecredito&editar=<?php echo $editar ?>" method="post">
	<div id="cupon">
		Código: <input class="codigo" name="nombre_codigo" type="text" value="<?php echo $nota['codigo_nota']; ?>" required />
    </div>
	<hr />
	<div class="fecha"> Fecha de Caducidad: 
		<input name="caduca_dt" type="date" value="<?php echo $nota[fecha_caducidad_nota]; ?>" required>
	</div>
	<hr />
	<div class="fecha">
		<p>Monto en soles: S/. <input name="monto_nmr" type="number" value="<?php echo $nota[monto_soles]; ?>" required > <br></p>
	</div>
	<hr />
	<div class="fecha">
		<p>¿Se ha usado?: 
		Si <input name="usado_nmr" type="radio" value="1" 
		<?php
		if ($nota[usado_nota] == 1){
		echo " checked ";
		}
		?>
		> 
		 | No <input name="usado_nmr" type="radio" value="2" 
		<?php
		if ($nota[usado_nota] != 1){
		echo " checked ";
		}
		?>
		>
		</p>
	</div>
	<hr />
	<div class="fecha"> Fecha de Uso: 
		<input name="uso_dt" type="date" value="<?php echo $nota[fecha_uso_nota]; ?>" >
	</div>
	<hr />
	<div id="envio">
		<input type="hidden" name="actualizar" value="vamos"  />
		<input class="boton" type="submit" value="Actualizar nota de Crédito" />
	</div>
</form>
</div>
<?php
}

}

function nueva_nota(){
$nuevo = $_POST[nuevo];
if($nuevo == "vamos"){
	$codigo = $_POST['nombre_codigo'];
	$codigo = strtoupper($codigo);
	$caduca = $_POST['caduca_dt'];
	$monto = $_POST['monto_nmr'];
	$query_nota = "INSERT INTO notas_credito (codigo_nota, fecha_caducidad_nota, monto_soles) VALUE ('$codigo', '$caduca', '$monto')";
	if(actualizar_registro($query_nota)){
		$msj = "Nota de Crédito creada con éxito";
		?>
		<div id="msj">
		<?php echo $msj; ?><br><br>
		<a href="cuponesyofertas.php?sec=notasdecredito" class="boton">Regresar</a>
		</div>
		<?php
	}else{
		$msj = "Error en el Servidor, por favor intente de nuevo";
		?>
		<div id="msj">
		<?php echo $msj; ?><br><br>
		<a href="cuponesyofertas.php?sec=notasdecredito" class="boton">Regresar</a>
		</div>
		<?php
	}
}else{
?>
<div id="titulo">Nueva Nota de Crédito</div>
<div id="formulario">
<form enctype="multipart/form-data" action="cuponesyofertas.php?sec=notasdecredito&accion=nuevo" method="post">
	<div id="cupon">
		Código: <input class="codigo" name="nombre_codigo" type="text" required />
    </div>
	<hr />
	<div class="fecha"> Fecha de Caducidad: 
		<input name="caduca_dt" type="date" required>
	</div>
	<hr />
	<div class="fecha">
		<p>Monto en soles: S/. <input name="monto_nmr" type="number" required > <br></p>
	</div>
	<hr />
	<div id="envio">
		<input type="hidden" name="nuevo" value="vamos"  />
		<input class="boton" type="submit" value="Crear nota de Crédito" />
	</div>
</form>
</div>
<?php
}
}

function notas_inicio(){
$query_notas = "SELECT * FROM notas_credito";
$notas = obtener_todo($query_notas);
?>
<div id="titulo">Notas de Crédito</div>
	<div id="nuevo"> <a href="cuponesyofertas.php?sec=notasdecredito&accion=nuevo">Crear nueva Nota de Crédito</a></div>
		<div id="notas_credito">
			<div class="nombre_nota titulo_nota">Código</div>
			<div class="fecha_nota titulo_nota">Fecha Caducidad</div>
			<div class="monto_nota titulo_nota">Monto Soles</div>
			<div class="usado_nota titulo_nota">Usado</div>
			<div class="fecha_uso_nota titulo_nota">Fecha de uso</div>
			<div class="acciones_nota titulo_nota">Acciones</div>
		</div>
		<?php
	if ($notas){
		foreach ($notas as $row){
			if ($row['usado_nota'] == 1){
				$usado = "SI";
			}else{
				$usado = "NO";
			}
			if (!$row['fecha_uso_nota']){
				$usado_fecha = "--";
			}else{
				$usado_fecha = $row['fecha_uso_nota'];
			}
		?>
		<div id="notas_credito">
			<div class="nombre_nota cont_nota"><?php echo $row['codigo_nota']; ?></div>
			<div class="fecha_nota cont_nota"><?php echo $row['fecha_caducidad_nota']; ?></div>
			<div class="monto_nota cont_nota">S/. <?php echo $row['monto_soles']; ?></div>
			<div class="usado_nota cont_nota"><?php echo $usado; ?></div>
			<div class="fecha_uso_nota cont_nota"><?php echo $usado_fecha; ?></div>
			<div class="acciones_nota cont_nota">
				<a href="cuponesyofertas.php?sec=notasdecredito&editar=<?php echo $row['id_nota']; ?>">Editar</a>&nbsp;&nbsp;&nbsp; 
				<a href="cuponesyofertas.php?sec=notasdecredito&eliminar=<?php echo $row['id_nota']; ?>">Eliminar</a>
			</div>
		</div>
		<?php
		}
	}else{
		?>
		<div id="notas_credito"> Sin notas </div>
		<?php
	}

}

function otros(){
?>

 <!--   todo nada--->
 
<div class="datos">
		<p><span class="bold">Todos los productos: </span><input name="all_chk" type="checkbox" value="1" 
        <?php
            if ($cupon[todos_cupon] == 1){
            echo " checked ";
            }
            ?>
         ><br></p>
</div>
 <hr />
 <!--   todo nada fin--->   


<!-----otros-------------->
<?php
$especiales_array = explode(',',$cupon[especiales_cupon]);
?>
	<div class="datos_inline">
		<p><span class="bold">New: </span><input name="especiales_chk[]" type="checkbox" value="new" 
<?php
if (in_array("new", $especiales_array)) {
    echo " checked ";
}
?>
        ><br></p>
	</div>
	<div class="datos_inline">
		<p><span class="bold">Sale: </span><input name="especiales_chk[]" type="checkbox" value="sale" 
<?php
if (in_array("sale", $especiales_array)) {
    echo " checked ";
}
?>        
        ><br></p>
	</div>
	<div class="datos_inline">
		<p><span class="bold">Back: </span><input name="especiales_chk[]" type="checkbox" value="back" 
<?php
if (in_array("back", $especiales_array)) {
    echo " checked ";
}
?>        
        ><br></p>
	</div>
    <hr />
<!-----otros final-------------->
<?php
}


?>
