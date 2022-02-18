<?php
$sec = "pedidos";
if(isset($_GET["sec"])){
$sec = $_GET["sec"];
}
$estatico = get_estatico();
$usuario = $_SESSION[$estatico['sesion_ll']];
$query_usuario = "SELECT * FROM usuarios WHERE user_usuario = '$usuario'";
$datos_usuario = obtener_linea($query_usuario);
$query_departamento = "select * from departamentos where estado_departamento = '1'";
$obtener_depa = obtener_linea($query_departamento);
?>
<div id="lateralizq">
	<nav>
		<ul>
			<li class="item <?php if ($sec=="pedidos"){ echo 'seleccionado';} ?>"><a href="pedidos.php?sec=pedidos">Pedidos</a></li>
			<li class="item <?php if ($sec=="montominimo"){ echo 'seleccionado';} ?>"><a href="pedidos.php?sec=montominimo">Costo envio Dep.</a></li>
			<li class="item <?php if ($sec=="montominimolima"){ echo 'seleccionado';} ?>"><a href="pedidos.php?sec=montominimolima">Costo envio Dist.(<?php echo $obtener_depa['nombre_departamento'] ?>)</a></li>
			<li class="item <?php if ($sec=="minimomonto"){ echo 'seleccionado';} ?>"><a href="pedidos.php?sec=minimomonto">Monto Mínimo</a></li>
		</ul>
	</nav>
</div>
<div id="contenido">
	<div id="titulo">Pedidos</div>
	<div id="cuadro">
		<?php
		if ( $sec == "pedidos" ) {
			pedidos();
		}
		if ( $sec == "montominimo" ) {
			montominimo();
		}
		if ( $sec == "montominimolima" ) {
			montominimolima();
		}
		if ( $sec == "minimomonto" ) {
			minimomonto();
		}
		?>
	</div>
</div>

<?php

//------------- pedidos


function pedidos(){
$edit = "";
if (isset($_GET['edit'])){	
$edit = $_GET['edit'];
}
$del = "";
if (isset($_GET['del'])){	
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

function minimomonto(){
	$accion = "";
if (isset($_POST['accion'])){
$accion = $_POST['accion'];
}
$cantidad = "";
if (isset($_POST['cantidad'])){	
$cantidad = $_POST['cantidad'];
}
?>
<div class="titulo">Montos Mínimos de compra</div>
<?php
if (!$accion) {
montos_peru_minimo();
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
				<a href="pedidos.php?sec=minimomonto" class="boton">Regresar</a>
			</div>
			<?php
		} else {
			$msj = "Error en el Servidor, por favor intente de nuevo";
			?>
			<div id="msj">
				<?php echo $msj; ?><br><br>
				<a href="pedidos.php?sec=minimomonto" class="boton">Regresar</a>
			</div>
			<?php
		}
		
		
	}  
}

function montos_peru_minimo(){
	$query_peru = "SELECT monto_peru FROM config_estatico WHERE id_conf_gen = 1";
$monto_peru = obtener_linea($query_peru);
   
$query_lima = "SELECT envio_lima FROM config_estatico WHERE id_conf_gen = 1";
$costo_lima = obtener_linea($query_lima);
  
$query_provincias = "SELECT envio_provincia FROM config_estatico WHERE id_conf_gen = 1";
$costo_provincia = obtener_linea($query_provincias);
?>
<div class="bloque_separa">
	<div class="montos">
        <div class="titulo_montos">Perú</div>
        <form action="pedidos.php?sec=minimomonto" method="post" enctype="multipart/form-data">
        Monto Mínimo para Perú: <input type="number" name="cantidad"  step="0.01" value="<?php echo $monto_peru['monto_peru'] ?>" required><br>
		<input type="hidden" name="accion" value="montoperu">
        <input type="submit" value="Modificar Monto Mínimo para Perú" id="enviar">
		</form>
	</div>
</div>

	<?php
}

function todos_pedidos(){
$query_pedidos = "SELECT * FROM pedidos";
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
<!--<div class="item cupon">Uso de Cupón</div>-->
<div class="item credito">Envio/Recojo</div>
<div class="item envio">Tipo de pago</div>	
<div class="item acciones">Acciones</div>
</div>
<?php
$i=1;
if ($pedidos){
foreach ($pedidos as $row){
?>
<div class="pedidos">
<div class="item num"><?php echo $i ?></div>
<div class="item codigo"><?php echo $row['codigo_pedido'] ?></div>
<div class="item monto"><?php echo $row['moneda_pedido']." ".$row['total_pedido'] ?></div>
<div class="item fecha"><?php echo $row['fecha_pedido'] ?></div>
<div class="item cliente"><?php echo $row['nombre_pedido']." ".$row['apellido_pedido'] ?></div>
<div class="item envio"><?php echo $row['envio_pedido'] ?></div>
<div class="item credito"><?php echo $row['estado_compra'] ?></div>
<div class="item envio"><?php echo $row['estado_pagado'] ?></div>	
<div class="item acciones"><a href="pedidos.php?sec=pedidos&edit=<?php echo $row['id_pedido']; ?>">Ver</a> |  <a href="pedidos.php?sec=pedidos&del=<?php echo $row['id_pedido']; ?>">Eliminar</a></div>
</div>
<?php
$i++;
}
}
}

function edit_pedido($edit){

$query_pedido = "SELECT * FROM pedidos WHERE id_pedido = '$edit'";
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
		<!-- Envio <?php  //echo $pedido['envio_pedido'] ?><br>-->
		<?php
		if ($pedido['estado_compra'] == "envio"){
		?>
		<div class="nombre">Datos de Envío:</div> <div class="linea">
		Departamento: <?php echo $nombre_dep ?><br>
		Provincia: <?php echo $nombre_prov ?><br>
		Distrito: <?php echo $nombre_dist ?><br>
		DNI: <?php echo $pedido['dni_pedido'] ?><br>
		Direccion: <?php echo $pedido['direccion_pedido'] ?><br>
		Referencias: <?php echo $pedido['referencia_pedido'] ?><br>
		<?php
		}else{
		$id_tienda = $pedido['id_tienda'];	
		$query_tienda = "SELECT * FROM tiendas WHERE id_tienda = $id_tienda";
			$tienda = obtener_linea($query_tienda);
			//echo $query_tienda;
		?>
		<div class="nombre">Tienda de Recojo:</div> <div class="linea">
		Tienda: <?php echo $tienda['nombre_tienda'] ?><br>
		<?php
		}
		?>
		</div>
	</div>
	<?php
		if($pedido['estado_pagado'] == "transferencia"){
			?>
		<div class="datos">
		<div class="nombre">Número de operación bancaria:</div> <div class="linea"><?php echo $pedido['numero_operacion'] ?></div>
	</div>
			<?php
		}
	?>

	<div class="datos">
		<div class="nombre">Uso de Cupón:</div> <div class="linea"><?php echo $pedido['cupon_pedido'] ?></div>
	</div>
	<div class="datos">
		<div class="nombre">Uso de Nota de Crédito:</div> <div class="linea"><?php echo $pedido['nota_pedido'] ?></div>
	</div>
		<div class="datos">
		<div class="nombre">Tipo de Pago:</div> <div class="linea"><?php echo $pedido['estado_pagado'] ?></div>
	</div>
</div>

<?php
}

function del_pedido($del){
?>
<div class="titulo">Eliminar Pedido</div>
<?php	
if (isset($_GET['delconf'])){
	$del = 	$_GET['del'];
	$eliminar = "DELETE FROM pedidos WHERE id_pedido ='$del'";
	if(actualizar_registro($eliminar)){
	?>
	<div class="msj">
	<p>Pedido Eliminado</p>
	<a href="pedidos.php?sec=pedidos" class="boton">Regresar</a>
	</div>
	<?php
	}else{
	?>
	<div class="msj">
	<p>No se pudo eliminar Pedido</p>
	<a href="pedidos.php?sec=pedidos" class="boton">Regresar</a>
	</div>
	<?php
	}
}else{
	$del = 	$_GET['del'];
	?>
<div class="msj">
	<p>¿Realmente desea eliminar el pedido?, NO se va a poder recuperar datos relacionados.</p>
	<a href="pedidos.php?sec=pedidos" class="boton">Regresar</a>
	<a href="pedidos.php?sec=pedidos&del=<?php echo $del ?>&delconf=borralo" class="boton">Si, Eliminar Pedido</a>
</div>
<?php
}	

}

//------------ Mnto Minimo

function montominimo(){
$accion = "";
if (isset($_POST['accion'])){	
$accion = $_POST['accion'];
$cantidad = $_POST['cantidad'];
}
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
				<a href="pedidos.php?sec=montominimo" class="boton">Regresar</a>
			</div>
			<?php
		} else {
			$msj = "Error en el Servidor, por favor intente de nuevo";
			?>
			<div id="msj">
				<?php echo $msj; ?><br><br>
				<a href="pedidos.php?sec=montominimo" class="boton">Regresar</a>
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
			<div class="cab_cell apellidos">Estado Costo Envio</div>
			<!--<div class="cab_cell">Ultimo ingreso</div>-->
		</div>
		<?php
		foreach ($obtener as $itemdep) {
			$estadoact = $itemdep['estado_departamento'];
			if($estadoact == 1){
				$checka = "checked";
			}else{
				$checka = "";
			}
?>
				<div class="tabla_row">	
			<div class="tabla_cell id">
			<?php echo $itemdep['id_departamentos'] ?>
			</div>
			<div class="tabla_cell apellidos">
				<?php echo $itemdep['nombre_departamento'] ?>
			</div>
			<div class="tabla_cell apellidos">
				<input type="text" name="costo[]" value="<?php echo $itemdep['costo_envio'] ?>">
				<input type="hidden" value="<?php echo $itemdep['id_departamentos'] ?>" name="iddep[]">
			</div>
					
			<div class="tabla_cell apellidos">
				<input type="radio" value="<?php echo $itemdep['id_departamentos'] ?>" name="checkradio" class="chkrd<?php echo $itemdep['id_departamentos'] ?>" <?php echo $checka ?>>
			</div>
			<script>
				$(".chkrd<?php echo $itemdep['id_departamentos'] ?>").on("click",function(){
//					console.log($(this).val());
					var ide = $(this).val();
					$.ajax({
						data: {"id_depa" : ide},
						type: "post",
						url: "estado_departamento.php",
					})
					 .done(function( data ) {
						 
							if(data == "bien"){
							   location.reload();
							   }
						 
					 })
					 .fail(function( data ) {
						 console.log( data );
					});
				})	
			</script>
		</div>

		
        
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

function montominimo_back(){
$accion = "";
if (isset($_POST['accion'])){
$accion = $_POST['accion'];
}
$cantidad = "";
if (isset($_POST['cantidad'])){	
$cantidad = $_POST['cantidad'];
}
?>
<div class="titulo">Montos Mínimos de compra</div>
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
				<a href="pedidos.php?sec=montominimo" class="boton">Regresar</a>
			</div>
			<?php
		} else {
			$msj = "Error en el Servidor, por favor intente de nuevo";
			?>
			<div id="msj">
				<?php echo $msj; ?><br><br>
				<a href="pedidos.php?sec=montominimo" class="boton">Regresar</a>
			</div>
			<?php
		}
		
		
	}  
}

function montos_peru_back(){
$query_peru = "SELECT monto_peru FROM config_estatico WHERE id_conf_gen = 1";
$monto_peru = obtener_linea($query_peru);
   
$query_lima = "SELECT envio_lima FROM config_estatico WHERE id_conf_gen = 1";
$costo_lima = obtener_linea($query_lima);
  
$query_provincias = "SELECT envio_provincia FROM config_estatico WHERE id_conf_gen = 1";
$costo_provincia = obtener_linea($query_provincias);
?>
<div class="bloque_separa">
	<div class="montos">
        <div class="titulo_montos">Perú</div>
        <form action="pedidos.php?sec=montominimo" method="post" enctype="multipart/form-data">
        Monto Mínimo para Perú: <input type="number" name="cantidad"  step="0.01" value="<?php echo $monto_peru['monto_peru'] ?>" required><br>
		<input type="hidden" name="accion" value="montoperu">
        <input type="submit" value="Modificar Monto Mínimo para Perú" id="enviar">
		</form>
	</div>
    <div class="envio">
        <div class="titulo_envios">Lima</div>
        <form action="pedidos.php?sec=montominimo" method="post" enctype="multipart/form-data">
        Costo de envío Lima: <input type="number" name="cantidad"  step="0.01" value="<?php echo $costo_lima['envio_lima'] ?>" required><br>
		<input type="hidden" name="accion" value="enviolima">
        <input type="submit" value="Modificar Costo de envío Lima" id="enviar">
		</form>
	</div>
    <div class="envio">
        <div class="titulo_envios">Provincias</div>
        <form action="pedidos.php?sec=montominimo" method="post" enctype="multipart/form-data">
        Costo de envío Provincia: <input type="number" name="cantidad"  step="0.01" value="<?php echo $costo_provincia['envio_provincia'] ?>" required><br>
		<input type="hidden" name="accion" value="envioprov">
        <input type="submit" value="Modificar Costo de envío Provincia" id="enviar">
		</form>
	</div>
</div>

	<?php
}

//------------ Mnto Minimo

function montominimolima(){
$accion = "";
if (isset($_POST['accion'])){	
$accion = $_POST['accion'];
$cantidad = $_POST['cantidad'];
}
?>

<?php
if (!$accion) {
montos_perulima();
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
				<a href="pedidos.php?sec=montominimo" class="boton">Regresar</a>
			</div>
			<?php
		} else {
			$msj = "Error en el Servidor, por favor intente de nuevo";
			?>
			<div id="msj">
				<?php echo $msj; ?><br><br>
				<a href="pedidos.php?sec=montominimo" class="boton">Regresar</a>
			</div>
			<?php
		}
		
		
	}  
}

function montos_perulima(){
	$query_departamento = "select * from departamentos where estado_departamento = '1'";
	$obtener_depa = obtener_linea($query_departamento);
	$idepa = $obtener_depa['id_departamentos'];
	
	$query_provs = "select * from provincias where estado_prov = '1'";
	$obtn_provs = obtener_linea($query_provs);
	$idprovs = $obtn_provs['idProv'];
	
	$query_sel = "select * from distritos where idProv = '$idprovs'";
	$obtener = obtener_todo($query_sel);
	$query_prov = "select * from provincias where idDepa = '$idepa'";
	$obtn_prov = obtener_todo($query_prov);
	?>

<div class="tabla_div">
	<div class="titulo_tabla_div">Costo de Envios</div>
		<div class="cabecera_tabla_div">
			Provincia: 
			<select name="" id="slcprov">
				<?php
					foreach($obtn_prov as $item){
						if($item['estado_prov'] == 1){
							$slcs = "selected";
						}else{
							$slcs = "";
						}
				?>
				<option value="<?php echo $item['idProv'] ?>" <?php echo $slcs ?>><?php echo $item['provincia'] ?></option>
				<?php
					}
				?>
			</select>
		</div><br>
		<script>
			$("#slcprov").on("change",function(){
//				console.log($(this).val());	
				var idprov = $(this).val();
				$.ajax({
					data: {"idprov" : idprov},
					type: "post",
					url: "estado_provincia.php",
				})
				 .done(function( data ) {
					 if(data == "bien"){
						location.reload();
						}
				 })
				 .fail(function( data ) {
					 console.log(data);
				});
			})
		</script>
		<div class="cabecera_tabla_div">
			<div class="cab_cell id">Id</div>
			<div class="cab_cell apellidos">Nombres</div>
			<div class="cab_cell apellidos">Costo Envio</div>
			<div class="cab_cell apellidos">Estado</div>
			<!--<div class="cab_cell">Ultimo ingreso</div>-->
		</div>
		<?php
	$contar = 1;
		foreach ($obtener as $itemdep) {
			if($itemdep['estado_dist'] == 1){
				$checked = "checked";
			}else{
				$checked = "";
			}
			
			
				?>
				<div class="tabla_row">	
			<div class="tabla_cell id">
			<?php echo $contar++ ?>
			</div>
			<div class="tabla_cell apellidos">
				<?php echo $itemdep['distrito'] ?>
			</div>
			<div class="tabla_cell apellidos">
				<input type="text" name="costo[]" value="<?php echo $itemdep['costo_envio'] ?>">
				<input type="hidden" value="<?php echo $itemdep['idDist'] ?>" name="iddep[]">
			</div>
			<div class="tabla_cell apellidos">
				<input type="checkbox" name="estado" <?php echo $checked ?> id="<?php echo $itemdep['idDist'] ?>">
			</div>
			
			<!--<div class="tabla_cell"><?php echo $itemcli['ult_fecha_ingreso']?></div>-->
		</div>
		
        
	<?php 
		}
	 ?>
	 <button class="boton acttabla">Actualizar</button>
	 <label id="respuesta"></label>
</div>
<script type="text/javascript">
	$("input[name=estado]").on("change",function(){
		var idd= $(this).attr("id");
		if( $(this).is(':checked') ) {
			$.ajax({
				url: '../selectcombolima.php',
				type: 'post',
				data: {'check':'1','iddis':idd,'estado':'estado'},
				success:function(res){

				},
				error:function(res){
					console.log(res);
				}
			})
		}else{
			$.ajax({
				url: '../selectcombolima.php',
				type: 'post',
				data: {'check':'0','iddis':idd,'estado':'estado'},
				success:function(res){

				},
				error:function(res){
					console.log(res);
				}
			})
		}
		
	})
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
			url: '../selectcombolima.php',
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