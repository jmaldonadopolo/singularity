<?php
$sec = "pedidos";
if (!$sec){
	$sec = $_GET["sec"];	
}
$estatico = get_estatico();
$usuario = $_SESSION[$estatico['sesion_ll']];
$query_usuario = "SELECT * FROM usuarios WHERE user_usuario = '$usuario'";
$datos_usuario = obtener_linea($query_usuario);

menu_lateral()
?>

<div id="contenido">
	<div id="titulo">Pedidos</div>
	<div id="cuadro">
		<?php
		if ( $sec == "pedidos" ) {
			pedidos();
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
			<li class="item <?php if ($sec=="pedidos"){ echo 'seleccionado';} ?>"><a href="carrito_abandonado.php?sec=pedidos">Pedidos</a></li>
		</ul>
	</nav>
</div>
<?php
}

function pedidospendiente(){
$edit = $_GET['edit'];
$del = $_GET['del'];
$conf = $_GET['confirmar'];
	
if($edit){
edit_pedidopendiente($edit);
}elseif($del){
del_pedidopendiente($del);
}elseif($conf){
confirmar_pedidopendiente($conf);
}else{
todos_pedidospendiente();
}
}

function edit_pedidopendiente($edit){
if (isset($_POST['estado_txt'])){
$estado = $_POST['estado_txt'];
$email_desc = $_POST['textdesc'];
$tracking = $_POST['nrotra'];
if (isset($email_desc) & isset($tracking)) {
	$query_estado_new = "UPDATE pedidos SET id_estado_pedido ='$estado',nro_tracking = '$tracking',email_tracking = '$email_desc' WHERE id_pedido = '$edit'";
	if (actualizar_registro($query_estado_new)) {
		$query_select = "SELECT * from pedidos where id_pedido = '$edit'";
		$obtener_query = obtener_linea($query_select);
		$nombre = $obtener_query['nombre_pedido'];
		$email = $obtener_query['email_pedido'];
		$codigo_pedido = $obtener_query['codigo_pedido'];
		enviar_correo_compra($nombre,$email, $email_desc, $tracking, $codigo_pedido);	
	}
}else{
	$query_estado_new = "UPDATE pedidos SET id_estado_pedido ='$estado' WHERE id_pedido = '$edit'";
actualizar_registro($query_estado_new);
}

//print_r($edit);
//enviar_correo_compra();	
}
$query_pedido = "SELECT * FROM pedidos WHERE id_pedido = '$edit'";
$pedido = obtener_linea($query_pedido);
	$query_dist = "select * from distritos_eco where id_distritos_eco = $pedido[dist_pedido]";
	$obtener_dis = obtener_linea($query_dist);
?>
<div class="titulo">Pedido: <?php echo $pedido['codigo_pedido'] ?></div>
<div id="pedido">
	<div class="datos">
		<div class="nombre">Código:</div> <div class="linea"><?php echo $pedido['codigo_pedido'] ?></div>
	</div>
	<div class="datos">
		<div class="nombre">Descripción:</div> <div class="linea"><?php echo $pedido['descripcion_pedido'] ?></div>
	</div>
	<div class="datos">
		<div class="nombre">Monto:</div> <div class="linea"><?php echo $pedido['moneda_pedido']." ".$pedido['total_pedido'] ?></div>
	</div>
	<div class="datos">
		<div class="nombre">Fecha de Pedido:</div> <div class="linea"><?php echo $pedido['fecha_pedido'] ?></div>
	</div>
	<div class="datos">
		<div class="nombre">Datos de Cliente:</div> <div class="linea">
		Nombre: <?php echo $pedido['nombre_pedido'] ?><br>
		eMail: <?php echo $pedido['email_pedido'] ?><br>
		Teléfono: <?php echo $pedido['celular_pedido'] ?><br>
		Fijo: <?php echo $pedido['fijo_pedido'] ?><br>
		</div>
	</div>
	<div class="datos">
		<div class="nombre">Datos de Envío:</div> <div class="linea">
		<?php 
			$query_turno = "SELECT * FROM turno where id_turno = '$pedido[id_turno]'";
			$obtn_turno = obtener_linea($query_turno);
			$query_ocasion = "SELECT * FROM ocasion where id_ocasion = '$pedido[id_ocasion]'";
			$obtn_ocasion = obtener_linea($query_ocasion);
		?>
		Recibe : <?php echo $pedido['nombre_envio'] ?><br>

		Fecha Entrega: <?php echo $pedido['fecha_entrega'] ?><br>
		Turno: <?php echo $obtn_turno['descripcion_turno'] ?><br>
		Distrito: <?php echo $obtener_dis['nombre_distrito'] ?><br>
		Ocasion: <?php echo $obtn_ocasion['nombre_ocasion'] ?><br>

		Dedicatoria: <?php echo $pedido['dedicatoria'] ?><br>
		Direccion: <?php echo $pedido['direccionentrega_pedido'] ?><br>
		</div>
	</div>
	<div class="datos">
		<div class="nombre">Uso de Cupón:</div> <div class="linea"><?php echo $pedido['cupon_pedido'] ?></div>
	</div>
	<div class="datos">
		<div class="nombre">Uso de Nota de Crédito:</div> <div class="linea"><?php echo $pedido['nota_pedido'] ?></div>
	</div>

	<?php 
			if ($pedido['estado_compra'] == "envio" ) {	
			?>
	<script>
		$(document).ready(function(){
			
			var estadoco = $(".estadop").val();
			if (estadoco == 3) {
				$(".tracking").show();
				$('#textdesc').prop("required", true);
				$('#nrotra').prop("required", true);
			}else{
				$(".tracking").hide();
				$('#textdesc').prop("required", false);
				$('#nrotra').prop("required", false);
				$('#textdesc').val("");
				$('#nrotra').val("");
			}
			$(".estadop").on("change",function(){
				var estado = $(this).val();
				if (estado == 3) {
					$(".tracking").show();
					$('#textdesc').prop("required", true);
					$('#nrotra').prop("required", true);
				}else{
					$(".tracking").hide();
					$('#textdesc').prop("required", false);

					$('#nrotra').prop("required", false);
					$('#textdesc').val("");
					$('#nrotra').val("");
				}
			})
		})
	</script>
	<?php 
				}
			?>
	<div class="datos">
		<div class="nombre">Confirmar Pedido:</div> <div class="linea"><a href="carrito_abandonado.php?sec=pedidospendiente&confirmar=<?php echo $_GET['edit'] ?>" class="boton">Confirmar</a></div>
	</div>
</div>


<?php
}

function del_pedidopendiente($del){

$producto = $_GET['del'];
$conf = $_GET['conf'];

if (!$conf) {
?>
<div class="msj">
	<p>¿Realmente desea eliminar el Pedido?, NO se va a poder recuperar datos relacionados.</p>
	<a href="carrito_abandonado.php?sec=pedidospendiente" class="boton">Regresar</a>
	<a href="carrito_abandonado.php?sec=pedidospendiente&conf=borralo&del=<?php echo $del?>" class="boton">Si, Eliminar Pedido</a>
</div>
<?php
}else{
		$query_producto = "DELETE FROM pedidos WHERE id_pedido='$del'";
	if (actualizar_registro($query_producto)) {
		$ruta = "carrito_abandonado.php?sec=pedidospendiente";
		$msj = "<p>Se elimino el Pedido</p>";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
	$ruta = "carrito_abandonado.php?sec=pedidospendiente";
	$msj = "<p>Error en el servidor intente nuevamente por favor</p>";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
	}

}

}

function confirmar_pedidopendiente($conf){
	$conf = $_GET['conf'];
	if($conf){
		$idconfirmar =  $_GET['confirmar'];
		$query_confirmar = "UPDATE pedidos set estado_pagado = 'pagado' where id_pedido = '$idconfirmar'";
		$obtener_conf = actualizar_registro($query_confirmar);
		if($obtener_conf){
			?>
		<div class="msj">
			<p>Pedido Confirmado</p>
			<a href="carrito_abandonado.php?sec=pedidospendiente" class="boton">Regresar</a>
		</div>
			<?php
		}else{
			?>
		<div class="msj">
			<p>Ocurrio un error al confirmar pedido</p>
			<a href="carrito_abandonado.php?sec=pedidospendiente" class="boton">Regresar</a>
		</div>
			<?php
		}
	}else{
		
	
	?>
<div class="msj">
	<p>¿Confirmar el Pedido?</p>
	<a href="carrito_abandonado.php?sec=pedidospendiente" class="boton">Regresar</a>
	<a href="carrito_abandonado.php?sec=pedidospendiente&confirmar=<?php echo $_GET['confirmar'] ?>&conf=conf" class="boton">Si, Confirmar</a>
</div>
<?php
	}
}

function todos_pedidospendiente(){
$query_pedidos = "SELECT * FROM pedidos where estado_pagado = 'pendiente' ORDER BY id_pedido DESC";
$pedidos = obtener_todo($query_pedidos);

?>
<div class="titulo">Pedidos</div>
<div class="pedidos">
<div class="item num">#</div>
<div class="item codigo">Código</div>
<div class="item monto">Monto</div>
<div class="item fecha">Fecha del Pedido</div>
<div class="item cliente">Cliente</div>
<div class="item envio">Zona de Envío</div>
<div class="item cupon">Estado</div>
<div class="item acciones">Acciones</div>
</div>
<?php
if ($pedidos){
$i=1;
foreach ($pedidos as $row){
$id_estado_pedido = $row['id_estado_pedido'];
$query_estado_pedido = "SELECT * FROM estado_pedido WHERE id_estado_pedido = '$id_estado_pedido'";
$estado_pedido = obtener_linea($query_estado_pedido);
$nombre_estado_pedido =	$estado_pedido['nombre_estado_pedido'];
?>
<div class="pedidos">
<div class="item num"><?php echo $i ?></div>
<div class="item codigo"><?php echo $row['codigo_pedido'] ?></div>
<div class="item monto"><?php echo $row['moneda_pedido']." ".$row['total_pedido'] ?></div>
<div class="item fecha"><?php echo $row['fecha_pedido'] ?></div>
<div class="item cliente"><?php echo $row['nombre_pedido'] ?></div>
<div class="item envio"><?php echo $row['envio_pedido'] ?></div>
<div class="item credito"><?php echo $nombre_estado_pedido ?></div>
<div class="item acciones"><a href="carrito_abandonado.php?sec=pedidospendiente&edit=<?php echo $row['id_pedido']; ?>">Ver</a>&nbsp;<a href="carrito_abandonado.php?sec=pedidospendiente&del=<?php echo $row['id_pedido']; ?>">Eliminar</a>&nbsp;<a href="carrito_abandonado.php?sec=pedidospendiente&confirmar=<?php echo $row['id_pedido']; ?>">Confirmar</a></div>
	
</div>
<?php
$i++;
}
}else{
?>
<div class="pedidos">Aun no se encuentran pedidos</div>
<?php
}
}

//------------- pedidos

function pedidos(){

$edit = "";	
if(isset($_GET['edit'])){	
$edit = $_GET['edit'];
}

$del = "";	
if(isset($_GET['del'])){	
$del = $_GET['del'];
}

if($edit){
edit_pedido($edit);
}elseif($del){
del_pedido($del);
}else{
todos_pedidos();
}
}

function todos_pedidos(){
$query_pedidos = "SELECT * FROM carros_abandonados ORDER BY id_pedido DESC";
$pedidos = obtener_todo($query_pedidos);

?>
<div class="titulo">Pedidos</div>
<div class="pedidos">
<div class="item num">#</div>
<div class="item codigo">Código</div>
<div class="item monto">Monto</div>
<div class="item fecha">Fecha del Pedido</div>
<div class="item cliente">Cliente</div>
<div class="item envio">Zona de Envío</div>
<div class="item cupon">Estado</div>
<div class="item acciones">Acciones</div>
</div>
<?php
if ($pedidos){
$i=1;
foreach ($pedidos as $row){
$id_estado_pedido = $row['id_estado_pedido'];
$query_estado_pedido = "SELECT * FROM estado_pedido WHERE id_estado_pedido = '$id_estado_pedido'";
$estado_pedido = obtener_linea($query_estado_pedido);
$nombre_estado_pedido =	$estado_pedido['nombre_estado_pedido'];
?>
<div class="pedidos">
<div class="item num"><?php echo $i ?></div>
<div class="item codigo"><?php echo $row['codigo_pedido'] ?></div>
<div class="item monto"><?php echo $row['moneda_pedido']." ".$row['total_pedido'] ?></div>
<div class="item fecha"><?php echo $row['fecha_pedido'] ?></div>
<div class="item cliente"><?php echo $row['nombre_pedido'] ?></div>
<div class="item envio"><?php echo $row['envio_pedido'] ?></div>
<div class="item credito"><?php echo $nombre_estado_pedido ?></div>
<div class="item acciones"><a href="carrito_abandonado.php?sec=pedidos&edit=<?php echo $row['id_pedido']; ?>">Ver</a></div>
</div>
<?php
$i++;
}
}else{
?>
<div class="pedidos">Aun no se encuentran pedidos</div>
<?php
}
}

function edit_pedido($edit){

$query_pedido = "SELECT * FROM carros_abandonados WHERE id_pedido = '$edit'";
$pedido = obtener_linea($query_pedido);
	
$dep = $pedido['depa_pedido'];	
$prov = $pedido['prov_pedido'];	
$dist = $pedido['dist_pedido'];	
$query_departamento = "SELECT * FROM departamentos WHERE id_departamentos = $dep";
$query_provincia = "SELECT * FROM provincias WHERE idProv = $prov";
$query_distrito = "SELECT * FROM distritos WHERE idDist = $dist";
$dep_dat = obtener_linea($query_departamento);	
$prov_dat = obtener_linea($query_provincia);	
$dist_dat = obtener_linea($query_distrito);
$nombre_dep = $dep_dat['nombre_departamento'];
$nombre_prov = $prov_dat['provincia'];
$nombre_dist = $dist_dat['distrito'];
	
?>
<div class="titulo">Pedido: <?php echo $pedido['codigo_pedido'] ?></div>
<div id="pedido">
	<div class="datos">
		<div class="nombre">Código:</div> <div class="linea"><?php echo $pedido['codigo_pedido'] ?></div>
	</div>
	<div class="datos">
		<div class="nombre">Descripción:</div> <div class="linea"><?php echo $pedido['descripcion_pedido'] ?></div>
	</div>
	<div class="datos">
		<div class="nombre">Monto:</div> <div class="linea"><?php echo $pedido['moneda_pedido']." ".$pedido['total_pedido'] ?></div>
	</div>
	<div class="datos">
		<div class="nombre">Fecha de Pedido:</div> <div class="linea"><?php echo $pedido['fecha_pedido'] ?></div>
	</div>
	<div class="datos">
		<div class="nombre">Datos de Cliente:</div> <div class="linea">
		Nombre: <?php echo $pedido['nombre_pedido'] ?><br>
		eMail: <?php echo $pedido['email_pedido'] ?><br>
		Teléfono: <?php echo $pedido['celular_pedido'] ?><br>
		Fijo: <?php echo $pedido['fijo_pedido'] ?><br>
		</div>
	</div>
	<div class="datos">
		<div class="nombre">Datos de Envío:</div> <div class="linea">
		<!-- Envio <?php  //echo $pedido['envio_pedido'] ?><br>-->
		<?php
		if ($pedido['estado_compra'] == "envio"){
		?>
		Departamento: <?php echo $nombre_dep ?><br>
		Provincia: <?php echo $nombre_prov ?><br>
		Distrito: <?php echo $nombre_dist ?><br>
		DNI: <?php echo $pedido['dni_pedido'] ?><br>
		<?php
		}
		?>
		Direccion: <?php echo $pedido['direccion_pedido'] ?><br>
		Referencias: <?php echo $pedido['referencia_pedido'] ?><br>
		</div>
	</div>
	<div class="datos">
		<div class="nombre">Uso de Cupón:</div> <div class="linea"><?php echo $pedido['cupon_pedido'] ?></div>
	</div>
	<div class="datos">
		<div class="nombre">Uso de Nota de Crédito:</div> <div class="linea"><?php echo $pedido['nota_pedido'] ?></div>
	</div>


</div>

<?php
}

function del_pedido($del){
?>
<div class="titulo">Eliminar Pedido</div>
<?php
}

//------------ Mnto Minimo

function montominimo(){
$accion = $_POST['accion'];
$cantidad = $_POST['cantidad'];

?>

<?php
if (!$accion) {
montos_peru();
}else{
		
	if ($accion == "montoperu"){
		//echo "montoperu";
		$query = "UPDATE config_estatico SET monto_peru='$cantidad' WHERE id_conf_gen='1'";
		$msj = "Monto Minimo Perú Modificado";
	}elseif($accion == "enviolima"){
		//echo "enviolima";
		$query = "UPDATE config_estatico SET monto_peru='$cantidad' WHERE id_conf_gen='2'";
		$msj = "Costo de envío Lima Modificado";
	}elseif($accion == "envioprov"){
		//echo "envioprov";
		$query = "UPDATE config_estatico SET monto_peru='$cantidad' WHERE id_conf_gen='3'";
		$msj = "Costo de envío Provincia Modificado";
	}
		

		if (actualizar_registro($query) ) {
		?>
			<div id="msj">
				<?php echo $msj; ?><br><br>
				<a href="carrito_abandonado.php?sec=montominimo" class="boton">Regresar</a>
			</div>
			<?php
		} else {
			$msj = "Error en el Servidor, por favor intente de nuevo";
			?>
			<div id="msj">
				<?php echo $msj; ?><br><br>
				<a href="carrito_abandonado.php?sec=montominimo" class="boton">Regresar</a>
			</div>
			<?php
		}
		
		
	}  
}

function montos_peru(){
	$query_sel = "select * from departamentos";
	$obtener = obtener_todo($query_sel);
	?>

<div class="tabla_div">
	<div class="titulo_tabla_div">Costo de Envios</div>
		<div class="cabecera_tabla_div">
			<div class="cab_cell id">Id</div>
			<div class="cab_cell apellidos">Nombres</div>
			<div class="cab_cell apellidos">Costo Envio</div>
			
			<!--<div class="cab_cell">Ultimo ingreso</div>-->
		</div>
		<?php
		foreach ($obtener as $itemdep) {
			if ($itemdep['id_departamentos'] == 15) {
				$query_prov = "SELECT * FROM provincias where idProv = 127";
				$obtenerpro = obtener_linea($query_prov);
				?>
				<div class="tabla_row">	
			<div class="tabla_cell id">
			<?php echo $itemdep['id_departamentos'] ?>
			</div>
			<div class="tabla_cell apellidos">
				<?php echo $itemdep['nombre_departamento'] ?>
			</div>
			<div class="tabla_cell apellidos">
				<input type="text" name="costo[]" value="<?php echo $itemdep[costo_envio] ?>">
				<input type="hidden" value="<?php echo $itemdep[id_departamentos] ?>" name="iddep[]">
			</div>
			Lima
			<input type="text" name="costo_prov" value="<?php echo $obtenerpro['costo_envio_prov'] ?>">

			 <button class="boton actulimal">Actualizar</button>
			 <label class="respenv"></label>
			<!--<div class="tabla_cell"><?php echo $itemcli['ult_fecha_ingreso']?></div>-->
			<script type="text/javascript">
				$(".actulimal").on("click",function(){
					var prov = $("input:text[name=costo_prov]").val();

					$.ajax({
						url: '../selectcombo.php',
						type: 'post',
						data: {'costolimlim': 'costolimlim','prov':prov},
					})
					.done(function(res) {
						if (res== "bien") {
							$(".respenv").html("Se Actualizo Correctamente");
							setTimeout(function(){
								$(".respenv").html("");	
							},2000)
						}
					})
					.fail(function(res) {
						$(".respenv").html("Hubo un error intentelo de nuevo");
							setTimeout(function(){
								$(".respenv").html("");
							},2000)
					})
					
				})
			</script>
		</div>
				<?php
			}else{
				?>
				<div class="tabla_row">	
			<div class="tabla_cell id">
			<?php echo $itemdep['id_departamentos'] ?>
			</div>
			<div class="tabla_cell apellidos">
				<?php echo $itemdep['nombre_departamento'] ?>
			</div>
			<div class="tabla_cell apellidos">
				<input type="text" name="costo[]" value="<?php echo $itemdep[costo_envio] ?>">
				<input type="hidden" value="<?php echo $itemdep[id_departamentos] ?>" name="iddep[]">
			</div>
			
			<!--<div class="tabla_cell"><?php echo $itemcli['ult_fecha_ingreso']?></div>-->
		</div>
				<?php
			}
		?>
		
        
	<?php 
		}
	 ?>
	 <button class="boton acttabla">Actualizar</button>
	 <label id="respuesta"></label>
</div>
<script type="text/javascript">
	$(".acttabla").on("click",function(){
		var costo = [];
		var iddep = [];

		valores_array('input[name="costo[]"]',costo);
		valores_array('input[name="iddep[]"]',iddep);

		function valores_array(valor,det_array){
			$(valor).each(function() {
			//console.log($(this).val());
			det_array.push($(this).val());
			//console.log(det_array);   	
    	
		})
		}
		$.ajax({
			url: '../selectcombo.php',
			type: 'post',
			data: {'costoe': JSON.stringify(costo),'iddep':JSON.stringify(iddep),'actmonto':'monto'},
			success:function(res){
				
				if(res == "bien"){
				   $("#respuesta").html("Se actualizaron los datos"); 
						$("#respuesta").show();
						setTimeout(function() {
							$("#respuesta").fadeOut("slow");
							},2000);
				}
			},
			error:function(res){
				console.log(res);
			}
		})
		
		
	})
</script>
<?php
}

?>