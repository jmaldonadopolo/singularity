<div class="sesion">
<div class="titulo_sesion">Proceso de compra</div>
<?php

if (isset($_SESSION['singularity_email'])) {
	falso_pedido();
	checkout();
} else {
	alerta();
	//checkout();
}
?>
</div>
<?php
/*---- funciones--------------*/

function alerta() {
?>
<div class="alerta">
		<div class="btn"><a href="sesion.php">Para continuar el proceso Inicia Sesión</a></div>
</div>
<?php
}

function checkout() {
if (!isset($_SESSION['sing_prod_x_comp'])) {
?>
<div class="vacio">
	<p>No tiene cursos seleccionados</p>
	<div class="btn"><a href="index.php">Ver cursos</a></div>
</div>
<?php
}else{
	
//print_r($_SESSION['sing_prod_x_comp']);
	
$paso = "";
if(isset($_POST['paso'])){	
$paso = $_POST['paso'];
}

$cliente = "";	
if(isset($_SESSION['singularity_email'])){	
$email = $_SESSION['singularity_email'];
$query_cliente = "SELECT * FROM clientes WHERE email_cliente = '$email'";
$cliente = obtener_linea($query_cliente);	
}

?>
<div class="pedido">
<div class="proceso">
<?php
if (!$paso) {
	pasouno($cliente);
	pasodos_emp($cliente);
	pasotres_emp();
	pasocuatro_emp();
} elseif ( $paso == "dos" ) {
	pasouno_fll($cliente);
	pasodos($cliente);
	pasotres_emp();
	pasocuatro_emp();
} elseif ( $paso == "tres" ) {
	pasouno_fll($cliente);
	pasodos_fll($cliente);
	pasotres();
	//pasocuatro();
} elseif($paso == "pago"){
	//pago();
}
?>
</div>
<div class="orden">
<?php	
orden();
?>
</div>	
</div>
<?php
}	
}

/*-----Pasos----*/

function pasouno($cliente) {
$nombres_cliente = $cliente['nombres_cliente'];
$apellidos_cliente = $cliente['apellidos_cliente'];
$dni_cliente = $cliente['dni_cliente'];
$celular_cliente = $cliente['celular_cliente'];
$email_cliente = $cliente['email_cliente'];
?>
<div class="paso">
	<div class="barra_titulo">
		<div class="icono"><img src="images/svg/uno.svg"></div>
		<div class="texto">Información del Usuario</div>
	</div>
	<div class="datos">
		<form action="checkout.php" enctype="multipart/form-data" method="post">
		<div class="linea">
			<input type="text" name="nombre_txt" placeholder="Nombre" value="<?php echo $nombres_cliente ?>" required>
			<input type="text" name="apellidos_txt" placeholder="Apellidos" value="<?php echo $apellidos_cliente ?>" required>
		</div>
		<div class="linea">
			<input type="text" name="dni_txt" placeholder="DNI/CE/Pasaporte" value="<?php echo $dni_cliente ?>" required>
		</div>
		<div class="linea">
			<input type="number" name="celular_nmb" placeholder="Número de teléfono" value="<?php echo $celular_cliente ?>" required>
		</div>
		<div class="linea">
			<input type="email" placeholder="Email" value="<?php echo $email_cliente ?>" disabled>
			<input type="hidden" name="email_ml" value="<?php echo $email_cliente ?>">
		</div>
		<div class="linea">
			<input name="paso" value="dos" type="hidden">
			<button type="submit">Continuar</button>
		</div>
		</form>	
	</div>	
</div>
<?php
}

function pasouno_fll(){
include("includes/datos_proceso.php");
?>
<div class="paso">
	<div class="barra_titulo">
		<div class="icono"><img src="images/svg/uno.svg"></div>
		<div class="texto">Información del Usuario</div>
	</div>
	<div class="datos">
		<div class="linea">
			<div class="dato">Nombre: <?php echo $nombre_txt ?></div>
			<div class="dato">Apellidos: <?php echo $apellidos_txt ?></div>
			<div class="dato">DNI: <?php echo $dni_txt ?></div>
			<div class="dato">Celular: <?php echo $celular_nmb ?></div>
			<div class="dato">email: <?php echo $email_ml ?></div>
		</div>
		<div class="linea">
			<div class="cambio"><a href="checkout.php" id="button_cambio">Cambiar Datos de Usuario</a></div>
		</div>	
	</div>	
</div>	
<?php	
}

function pasodos_emp(){
?>
<div class="paso">
	<div class="barra_titulo">
		<div class="icono"><img src="images/svg/dos.svg"></div>
		<div class="texto">Información para Envío / Recojo</div>
	</div>
</div>
<?php
}

function pasodos($cliente) {
include("includes/datos_proceso.php");
if ($paso == 'dos'){
$query_act_cliente = "UPDATE clientes SET nombres_cliente = '$nombre_txt', apellidos_cliente = '$apellidos_txt', celular_cliente = '$celular_nmb', dni_cliente = '$dni_txt', ult_fecha_ingreso = '$hoy' WHERE email_cliente = '$email_ml'";
actualizar_registro($query_act_cliente);	
}
	
$query_delivery = "SELECT * FROM config_estatico WHERE id_conf_gen = 1";
$obtn_delivery = obtener_linea($query_delivery);
$id = $cliente['id_cliente'];	
	
$query_dpt = "SELECT * FROM departamentos WHERE estado_departamento = '1'";
$obtener_dpt = obtener_linea($query_dpt);
$iddpt = $obtener_dpt['id_departamentos'];
	
$query_prv = "SELECT * FROM provincias WHERE estado_prov = '1'";
$obtn_prv = obtener_linea($query_prv);
$idprv = $obtn_prv['idProv'];
	
$query_dep = "SELECT * FROM departamentos";
$obtener = obtener_todo($query_dep);
	
$query_dir = "SELECT * FROM direccion_cliente WHERE id_cliente = $id";
$obtener_dir = obtener_linea($query_dir);
$dep_cliente = $obtener_dir['id_departamento'];
$prov_cliente = $obtener_dir['id_provincia'];
	
$query_p = "SELECT * FROM provincias WHERE idDepa = '$iddpt'";
$obtener_p = obtener_todo($query_p);
	
$query_di = "SELECT * FROM distritos WHERE idProv = '$idprv' AND estado_dist = 1";
$obtener_di = obtener_todo($query_di);

$query_provincia = "SELECT * FROM provincias where idDepa = $dep_cliente";
$obtener_provincia = obtener_todo($query_provincia);
$query_distrito= "SELECT * FROM distritos where idProv = '$prov_cliente' and estado_dist = 1";
$obtener_distrito  = obtener_todo($query_distrito);

$envio = $iddpt;
$pro = $idprv;
$dis = $idprv."01";	
?>
<div class="paso">
	<div class="barra_titulo">
		<div class="icono"><img src="images/svg/dos.svg"></div>
		<div class="texto">Información Adicional</div>
	</div>
	<div class="datos">
		<form action="checkout.php" enctype="multipart/form-data" method="post">
		<div class="linea">
			<div class="place">
			<select name="departamento_txt" class="jdepa">
			<?php
			foreach ($obtener as $itemdep) {
				if ($obtener_dir) {
				?>
				<option value="<?php echo $itemdep['id_departamentos'] ?>" <?php 
					if ($obtener_dir['id_departamento'] == $itemdep['id_departamentos']) {
									 	echo "selected";
									 } ?>> <?php echo $itemdep['nombre_departamento'] ?></option>
							<?php
							}else{
								?>
								<option value="<?php echo $itemdep['id_departamentos'] ?>" <?php 
										if ($itemdep['id_departamentos'] == $envio) {
										 	echo "selected";
										 } ?>> <?php echo $itemdep['nombre_departamento'] ?></option>
								<?php
							}
						
						}
						?>
					</select>

			<select class="jprov" name="provincia_txt">
			<?php 
			if ($obtener_dir) {
				foreach ($obtener_provincia as $itemdis) {
				?>
				<option value="<?php echo $itemdis['idProv'] ?>" <?php 
					if ($obtener_dir['id_provincia'] == $itemdis['idProv']) {
						echo "selected";
					} ?>> <?php echo $itemdis['provincia'] ?></option>
				<?php
				}
			}else{
				foreach($obtener_p as $itemp){
				?>
				<option value="<?php echo $itemp['idProv'] ?>" <?php 
					if ($itemp['idProv'] == $pro) {
						echo "selected";
					} ?>> <?php echo $itemp['provincia'] ?></option>
				<?php
				}
			}
			 ?>
			</select>

			<select class="jdist" name="distrito_txt">
						<?php 
						if ($obtener_dir) {
							foreach ($obtener_distrito as $itemdis) {
									?>
							<option value="<?php echo $itemdis['idDist'] ?>" <?php 
						if ($obtener_dir['id_distrito'] == $itemdis['idDist']) {
						 	echo "selected";
						 } ?>> <?php echo $itemdis['distrito'] ?></option>
						<?php
							}
							}else{
								foreach($obtener_di as $itemd){
								?>
								<option value="<?php echo $itemd['idDist'] ?>" <?php 
						if ($itemd['idDist'] == $dis) {
						 	echo "selected";
						 } ?>> <?php echo $itemd['distrito'] ?></option>
								<?php
								}
								}
						 ?>
					</select>
			</div>	
		</div>
		<div class="linea">
			<input class="large" type="text" name="direccion_txt" placeholder="Dirección de Envío" required>
		</div>
		<div class="linea">	
			<input class="large" type="text" name="referencia_txt" placeholder="Referencia" required>
		</div>
		<div class="linea">
			<input name="nombre_txt" value="<?php echo $nombre_txt ?>" type="hidden">
			<input name="apellidos_txt" value="<?php echo $apellidos_txt ?>" type="hidden">
			<input name="dni_txt" value="<?php echo $dni_txt ?>" type="hidden">
			<input name="celular_nmb" value="<?php echo $celular_nmb ?>" type="hidden">
			<input name="email_ml" value="<?php echo $email_ml ?>" type="hidden">
			<input name="paso" value="tres" type="hidden">
			<button>Continuar</button>
		</div>
		</form>	
	</div>	
</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$(".jdepa").on("change",function(){
					var iddepa = $(this).val();
						$.ajax({
						url: 'selectcombo.php',
						type: 'post',
						data: {'id_depa':iddepa,'selprov':'provincia'},
						success:function(res){
							
							$(".jprov").html(res);
							
							var pro = $(".jprov").val();
							//console.log(pro);
							$.ajax({
								url: 'selectcombo.php',
								type: 'post',
								data: {'id_dist': pro,'seldist':'distrito'},
								success:function(res){

									$(".jdist").html(res);
									
								},
								error:function(res){
									console.log(res);
								}
							})
						},
							error: function(res){
								console.log(res);
							}
							})
					
					
				});
				var iddist = $(".jprov").val();

					$.ajax({
						url: 'selectcombo.php',
						type: 'post',
						data: {'id_dist': iddist,'seldist':'distrito'},
						success:function(res){
//							console.log(res);
							$(".jdist").html(res);
							
						},
						error:function(res){
							console.log(res);
						}
					})
				$(".jprov").on("change",function(){
					var iddist = $(this).val();

					$.ajax({
						url: 'selectcombo.php',
						type: 'post',
						data: {'id_dist': iddist,'seldist':'distrito'},
						success:function(res){
							console.log(res);
							$(".jdist").html(res);
							
						},
						error:function(res){
							console.log(res);
						}
					})
				})
				/**/
				var idd= $(".jdepa").val();
				$.ajax({
						url: 'selectcombo.php',
						type: 'post',
						data: {'id_depar':idd,'costo':'costo'},
						success:function(res){
						},
						error: function(res){
							console.log(res);
						}
						})
				$(".jdepa").on("change",function(){
					var iddepa = $(this).val();
					$.ajax({
						url: 'selectcombo.php',
						type: 'post',
						data: {'id_depar':iddepa,'costo':'costo'},
						success:function(res){
							//console.log(res);
							$(".montodep").html(res);
						},
						error: function(res){
							console.log(res);
						}
						})
				})
				/***/
			})
		</script>
<?php
}

function pasotres_emp(){
?>
<div class="paso">
	<div class="barra_titulo">
		<div class="icono"><img src="images/svg/tres.svg"></div>
		<div class="texto">Cupones</div>
	</div>
</div>
<?php
}

function pasotres(){
?>
<div class="paso">
	<div class="barra_titulo">
		<div class="icono"><img src="images/svg/tres.svg"></div>
		<div class="texto">Cupones</div>
	</div>
	<div class="datos">
		<div class="linea">¡Si tienes un <span class="resalte">cupón</span> o una <span class="resalte">nota de crédito</span>, este es el momento!</div>
		<div class="linea">
			<div class="cupon">
			<input name="cupon_nota" type="text" placeholder="acá tu cupón" required>
			<button class="validar">Validar Cupón</button>
			</div>
		</div>
	</div>	
</div>
<?php
}

function pasocuatro_emp(){
?>
<div class="paso">
	<div class="barra_titulo">
		<div class="icono"><img src="images/svg/cuatro.svg"></div>
		<div class="texto">Medio de Pago</div>
	</div>
</div>
<?php
}

function orden() {
	$query_dpt = "select * from departamentos where estado_departamento = '1'";
$obtener_dpt = obtener_linea($query_dpt);
$iddpt = $obtener_dpt['id_departamentos'];
	
$query_prv = "select * from provincias where estado_prov = '1'";
$obtn_prv = obtener_linea($query_prv);
$idprv = $obtn_prv['idProv'];
	
	$query_monto_lima = "SELECT monto_peru FROM config_estatico WHERE id_conf_gen = '1'";
$monto_peru_gen = obtener_linea($query_monto_lima);	
$monto_peru = $monto_peru_gen['monto_peru'];
$tienda = "";
if(isset($_POST['tienda_txt'])){	
$tienda_txt = $_POST['tienda_txt'];
}
$tipoenv = "";
if(isset($_POST['tipoenv'])){	
$tipoenv = $_POST['tipoenv'];
}
$provincia = "";
if (isset($_POST['provincia_txt'])){	
$provincia = $_POST['provincia_txt'];
}
$distrito = "";
if (isset($_POST['distrito_txt'])){	
$distrito = $_POST['distrito_txt'];
}
$paso = "";
if (isset($_POST['paso'])){
$paso = $_POST['paso'];	
}	
date_default_timezone_set('America/Lima');
$productos = $_SESSION['sing_prod_x_comp'];
$estado_pedido = "envio";	
if (isset($_POST['pedido'])) {
	$estado_pedido = $_POST['pedido'];
}
$zona = "";
if (isset($_POST['envio_txt'])){
$zona = $_POST['envio_txt'];	
}	
$iddep = "";
if (isset($_POST['departamento_txt'])){
$iddep = $_POST['departamento_txt'];
}

if ($iddep == $iddpt && $provincia== $idprv) {
		$query_envio_peru = "SELECT * FROM distritos WHERE idDist = $distrito and estado_dist = 1";
	$envio_peru = obtener_linea($query_envio_peru);
	if($envio_peru){
		$costo_envio = $envio_peru['costo_envio'];
	}else{
		$query_envio_peru = "SELECT * FROM departamentos WHERE id_departamentos = '$iddep'";
		$envio_peru = obtener_linea($query_envio_peru);
		$costo_envio = $envio_peru['costo_envio'];
	}
	
	}else{
		$query_envio_peru = "SELECT * FROM departamentos WHERE id_departamentos = '$iddep'";
	$envio_peru = obtener_linea($query_envio_peru);
	$costo_envio = $envio_peru['costo_envio'];
	}

?>

	<div id="cuadro">
		<div id="prendas">
			<div id="cabecera_prendas">Sumario</div>
			<?php
			$i = 1;
			foreach ($productos as $row) {
				$id_curso = $row['curso'];
				$precio_final_curso = $row['precio_producto'];
				$qty = $row['cantidad'];
				$cantidad = $row['cantidad'];
				$total = $row['total'];

				$query_curso = "SELECT * FROM cursos WHERE id_curso = '$id_curso'";
				$curso = obtener_linea($query_curso);
				$imagen = $curso['imagen_curso'];
				$nombre_curso = $curso['nombre_curso'];
				?>
			<div class="detalle">
				<div class="descripcion">
					<div class="imagen">
						<img src="images/cursos/<?php echo $imagen ?>" >
					</div>
					<div class="datos">
						<div class="nombre"><?php echo $producto['nombre_producto']." x ".$row['cantidad'] ?></div>
						<div class="precio_uni">
						<?php
							if ($codigo['oferta_codigo'] == 0){
								echo " S/. ". $row['precio_producto'];
							}elseif($codigo['oferta_codigo'] == 1){
								echo " S/. <span class='tachado'>". $codigo['precio_codigo']." </span> ". $codigo['precio_oferta_codigo'];
							}	
						?>	
						</div>
<!--						<div class="nombre">Talla: <?php echo $codigo['talla_codigo'] ?></div>-->
						<?php 
							if(!empty($row['nombre_nino'])){
						?>
						<div class="nombre">Nombre del Niño(a): <?php echo $row['nombre_nino'] ?></div>
						<?php 

							}
						?>
						<div class="subtotal">
							<p>Subtotal: S/. <?php echo $row['total'] ?></p>
						</div>
					</div>
				</div>
			</div>
			<?php
			$i++;
			}
			$cantidad = "0";
			$total = "0";
			foreach ( $productos as $row ) {
				$cantidad = $cantidad + $row['cantidad'];
				$total = $total + $row['total'];
			}
			?>
		</div>
		
	<div id="pedido_resumen">

		<?php
		if($tipoenv == "envio"){
			if ($paso){
				if ($total < $monto_peru){
					?>
					<div id="envio">Costo de envío: S/. <?php echo number_format($costo_envio, 2, '.', '') ?></div>
					<?php
				}else{
					$costo_envio = 0;
				?>
				<div id="envio">Costo de envío: S/. <?php echo number_format($costo_envio, 2, ',', '') ?></div>
				<?php
				}
			
			}
		}elseif($tipoenv == "recojo"){
			?>
			<div id="envio">Costo de envío: S/. 0.00</div>
			<?php
		}
		?>
		
		
		<?php
			if(isset($_SESSION['smile_coupon'])){
				$id_tipo = "id='total_cupon'";
			}else{
				if(isset($_SESSION["smile_nota_credito"])){
					$id_tipo = "id='total_cupon'";
				}else{
					$id_tipo = "id='total'";
				}
			}
		?>	
		<div <?php echo $id_tipo ?> class="sumariomonto"> 
			<?php 
			if($tipoenv == "envio"){
				if ($paso){
					$query_peru = "SELECT * FROM config_estatico where id_conf_gen = 1";
					$obt_peru = obtener_linea($query_peru);
					$montoaingresar =  $obt_peru['monto_peru']; 
					if($total >= $montoaingresar){
						echo "Total: S/. ".number_format (($total), 2, '.', '');	
					}else{
						echo "Total: S/. ".number_format (($total+$costo_envio), 2, '.', '');	
					}
					}else{
					echo "Total (sin costo de envio): S/. ".number_format (($total), 2, '.', ''); 
					}	
			}elseif($tipoenv == "recojo"){
				echo "Total: S/. ".number_format ($total, 2, '.', '');	
			}
			?>
		</div>
		
		<input type="hidden" class="obtenermonto" value="<?php echo number_format (($total+$costo_envio), 2, '.', '') ?>">
		<?php
		if(isset($_SESSION['smile_coupon'])){
			//$porcentaje = ($_SESSION['smile_coupon']/100);
			//$descuento = $total*$porcentaje; 
			//$total_cupon = $total-$descuento;
			//$descuento_mod = "S/".$descuento; 
			$descuento_mod = "S/".$_SESSION['smile_coupon'];
			$total_cupon = $total-$_SESSION['smile_coupon'];
				if($tipoenv == "envio"){
					$costo_envio  = $costo_envio;
				}elseif($tipoenv == "recojo"){
					$costo_envio  = 0;
				}
		?>
		<div id="aclara">Cupón de descuento: S/. <?php echo $_SESSION['smile_coupon']; ?></div>
		<!--<div id="aclara">Descuento total: <?php echo $descuento_mod; ?></div>-->
		<div id="total">Total (con Cupón): S/. <?php echo number_format (($total_cupon+$costo_envio), 2, '.', '') ?></div>
		<?php
		}else{
			if(isset($_SESSION['smile_nota_credito'])){
				$credito = $_SESSION['smile_nota_credito'];
				$total_nota = $total-$credito;
				$credito_mod = "S/".$credito; 
				if($tipoenv == "envio"){
				$costo_envio  = $costo_envio;
				}elseif($tipoenv == "recojo"){
				$costo_envio  = 0;
				}
			$costo_final_nota =	number_format (($total_nota+$costo_envio), 2, '.', '');
			if ($costo_final_nota < 10){
			$costo_final_nota = "10.00";
			}	
			?>
			<div id="aclara">Nota de Crédito: <?php echo $credito_mod; ?></div>
			<div id="total">Total (con nc): S/. <?php echo $costo_final_nota; ?>
			</div>
			<?php
			}
		}
		?>

		</div>		
	</div>
<?php
}

function datos_envio($paso, $nombre_txt, $apellidos_txt, $dni_txt, $celular_nmb, $email_ml, $departamento_txt, $provincia_txt, $distrito_txt, $direccion_txt, $referencia_txt){
echo "paso: ".$paso."<br>";
echo "nombre_txt: ".$nombre_txt."<br>";
echo "apellidos_txt: ".$apellidos_txt."<br>";
echo "dni_txt: ".$dni_txt."<br>";
echo "celular_nmb: ".$celular_nmb."<br>";
echo "email_ml: ".$email_ml."<br>";
echo "departamento_txt: ".$departamento_txt."<br>";
echo "provincia_txt: ".$provincia_txt."<br>";
echo "distrito_txt: ".$distrito_txt."<br>";
echo "direccion_txt: ".$direccion_txt."<br>";
echo "referencia_txt: ".$referencia_txt."<br>";
}

/*------------------------

function pasodos_fll($cliente){

$zona = $_POST['envio_txt'];
$nombre_zona = "Resto del Per&uacute;";
$tipoenv = $_POST['tipoenv'];
if($tipoenv == "envio"){
	$departamento = $_POST['departamento_txt'];
$provincia = $_POST['provincia_txt'];
$distrito = $_POST['distrito_txt'];
}elseif($tipoenv == "recojo"){
	$tienda = $_POST['tienda_txt'];
}

if(isset($tienda)){
$direccion = "-";
$referencia = "-";	
}else{
$direccion = $_POST['direccion_txt'];
$referencia = $_POST['referencia_txa'];
	
$query_ver_dir ="SELECT * FROM direccion_cliente WHERE id_cliente = '$cliente[id_cliente]'";
$ver_dir = obtener_linea($query_ver_dir);	

if ($ver_dir){	
$act_dir = "UPDATE direccion_cliente SET id_departamento = '$departamento', id_provincia='$provincia', id_distrito='$distrito', lineauno_direccion='$direccion', referencia_direccion='$referencia' WHERE id_cliente=$cliente[id_cliente]";
}else{
$act_dir = "INSERT INTO direccion_cliente (id_cliente, id_departamentos, id_provincia, id_distrito, lineauno_direccion, referencia_direccion) VALUE ('$cliente[id_cliente]', '$departamento', '$provincia', '$distrito', '$direccion', '$referencia')";	
}
actualizar_registro($act_dir);
}
	
$query_dep = "select * from departamentos where id_departamentos = $departamento";
$obtenerdep = obtener_linea($query_dep);
$query_pro = "select * from provincias where idProv = $provincia";
$obtenerpro = obtener_linea($query_pro);
$query_dis = "select * from distritos where idDist = $distrito";
$obtenerdis = obtener_linea($query_dis);

$departamento = $obtenerdep['nombre_departamento'];
$provincia = $obtenerpro['provincia'];
$distrito =$obtenerdis['distrito'] ;

$dni = $_POST['dni_txt'];

$nombre = $_POST['nombre_txt'];
$apellido = $_POST['apellido_txt'];
$mail = $_POST['mail_txt'];
$celular = $_POST['celular_txt'];
$fijo = $_POST['fijo_txt'];		


$act_cli = "UPDATE clientes SET nombres_cliente = '$nombre', apellidos_cliente = '$apellido', celular_cliente = '$celular', fijo_cliente='$fijo', dni_cliente='$dni' WHERE id_cliente=$cliente[id_cliente]";
actualizar_registro($act_cli);

?>
<div class="paso">
	<div class="titulo">
		<div class="icono"><img src="images/svg/check.svg"></div>
		<div class="texto">Envío/Recojo:</div>
	</div>	
	<div class="datos">
		<?php
		if ($tipoenv == "envio"){
		?>
		<div class="linea">
			<div class="texto">Departamento:</div>
			<div class="item"><?php echo $departamento ?></div>
		</div>
		<div class="linea">
			<div class="texto">Provincia:</div>
			<div class="item"><?php echo $provincia ?></div>
		</div>
		<div class="linea">
			<div class="texto">Distrito:</div>
			<div class="item"><?php echo $distrito ?></div>
		</div>
		<div class="linea">
			<div class="texto">Tipo:</div>
			<div class="item"><?php echo $tipoenv ?></div>
		</div>			
		<div class="linea">
			<div class="texto">Dirección:</div>
			<div class="item"><?php echo $direccion ?></div>
		</div>
		<div class="linea">
			<div class="texto">Referencia: </div>
			<div class="item"><?php echo $referencia ?></div>
		</div>
		<?php
		}
		if ($tipoenv == "recojo"){
			$query_tienda = "SELECT * FROM tiendas where id_tienda = '$tienda'";
			$obtn = obtener_linea($query_tienda);
		?>
		<div class="linea">
			<div class="texto">Tienda:</div>
			<div class="item"><?php echo $obtn['nombre_tienda'] ?></div>
		</div>
		<?php
		}
		?>
		<div class="linea">
			<div class="texto">DNI:</div>
			<div class="item"><?php echo $dni ?></div>
		</div>		
		<div class="linea">
			<div class="texto">Nombre:</div>
			<div class="item"><?php echo $nombre ?></div>
		</div>	
		<div class="linea">
			<div class="texto">Apellidos:</div>
			<div class="item"><?php echo $apellido ?></div>
		</div>					
		<div class="linea">
			<div class="texto">Email:</div>
			<div class="item"><?php echo $mail ?></div>
		</div>
						
		<div class="linea">
			<div class="texto">Celular:</div>
			<div class="item"><?php echo $celular ?></div>
		</div>	
		<div class="linea">
			<div class="texto">Fijo:</div>
			<div class="item"><?php echo $fijo ?></div>
		</div>
		<div class="linea">
			<div class="texto"></div>
			<div class="item">
				<div class="proceder_boton"><a href="checkout.php">Cambiar datos de envío</a></div>
			</div>
		</div>
		

	</div>
</div>


<?php
}



function pasotres(){
if(isset($_POST['cupon_nota'])){	
$cupon_nota = $_POST['cupon_nota'];
$cupon_nota = strtoupper($cupon_nota);
}
?>
<div class="paso">
	<div class="titulo">
		<div class="icono"><img src="images/svg/tres.svg"></div>
		<div class="texto">Cupón/Nota de Crédito:</div>
	</div>
	<div class="datos">
	<?php
	if(isset($cupon_nota)){
		cupon_nota();
		$boton_cont = "Continuar";
	}else{
		cupon_ingresar();
		$boton_cont = "Continuar sin Cupón";
	}
	$tienda_txt = "";
	if(isset($_POST['tienda_txt'])){
	$tienda_txt = $_POST['tienda_txt'];
	}
	$tipoenv = $_POST['tipoenv'];
	$zona = $_POST['envio_txt'];
	$departamento = $_POST['departamento_txt'];
	$provincia = $_POST['provincia_txt'];
	$distrito = $_POST['distrito_txt'];
	$dni = $_POST['dni_txt'];
	$direccion = $_POST['direccion_txt'];
	$referencia = $_POST['referencia_txa'];
	$nombre = $_POST['nombre_txt'];
	$apellido = $_POST['apellido_txt'];
	$mail = $_POST['mail_txt'];
	$celular = $_POST['celular_txt'];
	$fijo = $_POST['fijo_txt'];	
	$estado_pedido = "envio";
	?>
	<form action="checkout.php" enctype="multipart/form-data" method="post">
		<div class="linea">
			<div class="texto">
				<input name="tienda_txt" value="<?php echo $tienda_txt ?>" type="hidden">
				<input name="tipoenv" value="<?php echo $tipoenv ?>" type="hidden">
				<input name="envio_txt" value="<?php echo $zona ?>" type="hidden">
				<input name="departamento_txt" value="<?php echo $departamento ?>" type="hidden">
				<input name="provincia_txt" value="<?php echo $provincia ?>" type="hidden">
				<input name="distrito_txt" value="<?php echo $distrito ?>" type="hidden">
				<input name="dni_txt" value="<?php echo $dni ?>" type="hidden">
				<input name="direccion_txt" value="<?php echo $direccion ?>" type="hidden">
				<input name="referencia_txa" value="<?php echo $referencia ?>" type="hidden">
				<input name="nombre_txt" value="<?php echo $nombre ?>" type="hidden">
				<input name="apellido_txt" value="<?php echo $apellido ?>" type="hidden">
				<input name="mail_txt" value="<?php echo $mail ?>" type="hidden">
				<input name="celular_txt" value="<?php echo $celular ?>" type="hidden">
				<input name="fijo_txt" value="<?php echo $fijo ?>" type="hidden">
				<input name="pedido" value="<?php echo $estado_pedido ?>" type="hidden">
			</div>
			<div class="item">
			<input name="paso" value="tres" type="hidden">
				<br>
			<input type="submit" value="<?php 	echo $boton_cont; ?>" class="button_continua">	
			</div>
		</div>
	</form>
	</div>	
</div>
<?php

}

function pasotres_fll(){
?>
<div class="paso">
	<div class="titulo">
		<div class="icono"><img src="images/svg/check.svg"></div>
		<div class="texto">Cupón/Nota de Crédito:</div>
	</div>
	<div class="datos">
	<?php
	if (isset($_SESSION['smile_coupon'])){
	?>
	<div id="mensaje">Cupón Activo</div>
	<?php
	}elseif(isset($_SESSION['smile_nota_credito'])){
	?>
	<div id="mensaje">Nota de Crédito Activa</div>
	<?php
	}else{
	?>
	<div id="mensaje">No se ingresó ningún cupón</div>
	<?php
	}
	?>
	</div>	
</div>
<?php
}

function cupon_ingresar(){

if (isset($_SESSION['smile_coupon'])){
?>
<div class="linea">¡Tu cupón se encuentra validado!</div>
<?php
}elseif(isset($_SESSION['smile_nota_credito'])){
?>
<div class="linea">
	<p>¡Tu Nota de crédito se encuentra Activa!</p>
</div>
<?php
}else{
$tienda_txt = "";
if (isset($_POST['tienda_txt'])){
$tienda_txt = $_POST['tienda_txt'];	
}	
$tipoenv = $_POST['tipoenv'];
$zona = $_POST['envio_txt'];
$departamento = $_POST['departamento_txt'];
$provincia = $_POST['provincia_txt'];
$distrito = $_POST['distrito_txt'];
$dni = $_POST['dni_txt'];
if(isset($_POST['pais_txt'])){
	$pais = $_POST['pais_txt'];
}
if(isset($_POST['ciudad_txt'])){
	$ciudad = $_POST['ciudad_txt'];
}
if(isset($_POST['postal_txt'])){
	$postal = $_POST['postal_txt'];
}	
$direccion = $_POST['direccion_txt'];
$referencia = $_POST['referencia_txa'];
$nombre = $_POST['nombre_txt'];
$apellido = $_POST['apellido_txt'];
$mail = $_POST['mail_txt'];
$celular = $_POST['celular_txt'];
$fijo = $_POST['fijo_txt'];

?>
<div class="linea">¡Si tienes un <span class="resalte">cupón</span> o una <span class="resalte">nota de crédito</span>, este es el momento!</div>
<div class="cupon">
	<form action="checkout.php" enctype="multipart/form-data" method="post">
		<input class="cuponnota" name="cupon_nota" type="text" placeholder="acá tu cupón" required>
	<div id="proceder">
		<input name="paso" value="dos" type="hidden">
		<input name="tienda_txt" value="<?php echo $tienda_txt ?>" type="hidden">
			<input name="tipoenv" value="<?php echo $tipoenv ?>" type="hidden">
		<input name="envio_txt" value="<?php echo $zona ?>" type="hidden">
		<input name="departamento_txt" value="<?php echo $departamento ?>" type="hidden">
		<input name="provincia_txt" value="<?php echo $provincia ?>" type="hidden">
		<input name="distrito_txt" value="<?php echo $distrito ?>" type="hidden">
		<input name="dni_txt" value="<?php echo $dni ?>" type="hidden">
        
		<input name="pais_txt" value="<?php echo $pais ?>" type="hidden">
        <input name="ciudad_txt" value="<?php echo $ciudad ?>" type="hidden">
	    <input name="postal_txt" value="<?php echo $postal ?>" type="hidden">
        
		<input name="direccion_txt" value="<?php echo $direccion ?>" type="hidden">
		<input name="referencia_txa" value="<?php echo $referencia ?>" type="hidden">
		<input name="nombre_txt" value="<?php echo $nombre ?>" type="hidden">
		<input name="apellido_txt" value="<?php echo $apellido ?>" type="hidden">
		<input name="mail_txt" value="<?php echo $mail ?>" type="hidden">
		<input name="celular_txt" value="<?php echo $celular ?>" type="hidden">
		<input name="fijo_txt" value="<?php echo $fijo ?>" type="hidden">
		<input name="pedido" value="<?php echo $tipoenv ?>" type="hidden">

		<input type="submit" value="Validar Cupón" class="button">
	</div>
	</form>
</div>
<?php
}
}

function cupon_nota(){
$cupon_nota = $_POST['cupon_nota'];
$query_cupon = "SELECT * FROM cupones_descuento_soles WHERE codigo_cupon_soles = '$cupon_nota'";
$cupon_existe = obtener_linea($query_cupon);
if($cupon_existe){
	cupon_verificado($cupon_existe);
}else{
	$query_nota = "SELECT * FROM notas_credito WHERE codigo_nota = '$cupon_nota'";
	$nota_existe = obtener_linea($query_nota);
	if($nota_existe){
	nota_verificada($nota_existe);
	}else{
	?>
	<div id="mensaje">El cupón ingresado no Existe. </div>
	<?php
	cupon_ingresar();	
	}
}

}

function nota_verificada($nota_existe){
$_SESSION['smile_nota_credito'] = $nota_existe['monto_soles'];
$_SESSION['smile_nota_nombre'] = $nota_existe['codigo_nota'];
$signo = "S/.";
$nota = $nota_existe['monto_soles'];
?>
<div id="mensaje">Felicidades su nota de Crédito por <?php echo $signo." ".$nota; ?> se ha activado</div>
<?php
}

function cupon_verificado($cupon_existe){
date_default_timezone_set('America/Lima');
if($cupon_existe['activo_cupon_soles'] == 1){
	$ahora = new DateTime('now');
	$datetime1 = new DateTime($cupon_existe['fecha_inicio_cupon_soles'].$cupon_existe['hora_inicio_cupon_soles']); 
	$datetime2   = new DateTime($cupon_existe['fecha_cierre_cupon_soles'].$cupon_existe['hora_cierre_cupon_soles']); 
	$interval = date_diff($datetime1, $datetime2);
	if($ahora > $datetime2){
		?>
		<div id="mensaje">La vigencia del cupón ha finalizado.</div>
		<?php
		cupon_ingresar();	
	}else{
		$inicio = date_diff($ahora, $datetime1);
		$fin = date_diff($ahora, $datetime2);
		if ($ahora > $datetime1){
			$productos = $_SESSION['sing_prod_x_comp'];
			$lista_array = explode(',',$cupon_existe['secciones_cupon_soles']);
			$error = "";
			foreach ($productos as $row){
				$query_seccion = "SELECT * FROM productos WHERE id_producto = '$row[id_producto]'";
				$src_seccion_producto = obtener_linea($query_seccion);
				if (!in_array($src_seccion_producto['id_seccion'], $lista_array)){
					$error = "error";
				}
			}
			if (!$error){
				$_SESSION['smile_coupon'] = $cupon_existe['monto_cupon_soles'];
				?>
				<div id="mensaje">Felicidades su cupón se ha activado</div>
				<?php
			}else{
				$lista_array = explode(',',$cupon_existe['secciones_cupon_soles']);
				$query_seccion = "SELECT * FROM secciones WHERE estado_seccion = '1'";
				$secciones = obtener_todo($query_seccion);
				$secciones_nom = "";
				foreach ($secciones as $row){
					if (in_array($row['id_seccion'], $lista_array)){
						$secciones_nom = $secciones_nom." - ". $row['nombre_seccion'];
					}
				}
				?>
				<div id="mensaje">
				El cupón ingresado es válido para: <?php echo $secciones_nom ?><br>
				Si tu bolsa contiene productos de otras categorías el cupón no va a ser procesado.
				</div>
				<?php
				cupon_ingresar();	
			}
		}elseif($ahora < $datetime1){
			?>
			<div id="mensaje">El cupón no se encuentra vigente.</div>
			<?php
			cupon_ingresar();
		}
	}
}else{
?>
<div id="mensaje">El cupón ingresado no se encuentra Activo.</div>
<?php
cupon_ingresar();	
}

}

function pasocuatro_emp(){
?>
<div class="paso">
	<div class="titulo">
		<div class="icono"><img src="images/svg/cuatro.svg"></div>
		<div class="texto">Método de Pago:</div>
	</div>
</div>
<?php
}

function pasocuatro(){
	
$query_dpt = "select * from departamentos where estado_departamento = '1'";
$obtener_dpt = obtener_linea($query_dpt);
$iddpt = $obtener_dpt['id_departamentos'];
	
$query_prv = "select * from provincias where estado_prov = '1'";
$obtn_prv = obtener_linea($query_prv);
$idprv = $obtn_prv['idProv'];
	
	
$query_peru = "SELECT * FROM config_estatico where id_conf_gen = 1";
$obt_peru = obtener_linea($query_peru);
$query_pagos = "SELECT * FROM config_estatico WHERE id_conf_gen = 1";
$obtn_pagos = obtener_linea($query_pagos);
$estado_pedido = "envio";
date_default_timezone_set('America/Lima');
	$hoy = date( "Y-m-d" );	
$productos = $_SESSION['sing_prod_x_comp'];
	
//print_r($productos);	
$tienda_txt = "0";
if(isset($_POST['tienda_txt'])){	
$tienda_txt = $_POST['tienda_txt'];
}
// fin monto y envio
$tipoenv = $_POST['tipoenv'];
	//echo $tipoenv;
$zona = $_POST['envio_txt'];
$departamento = $_POST['departamento_txt'];
$provincia = $_POST['provincia_txt'];
$distrito = $_POST['distrito_txt'];
$dni = $_POST['dni_txt'];
$direccion = $_POST['direccion_txt'];
$referencia = $_POST['referencia_txa'];
$nombre = $_POST['nombre_txt'];
$mail = $_POST['mail_txt'];
$celular = $_POST['celular_txt'];
$fijo = $_POST['fijo_txt'];
	
$total = "0";
	if (isset($_SESSION["smile_coupon"])){
 $cupon_previo = $_SESSION["smile_coupon"];
}else{
$cupon_previo = "NO";
}

if (isset($_SESSION["smile_nota_credito"])){
 $nota_previo = $_SESSION["smile_nota_credito"];
}else{
$nota_previo = "NO";
}
foreach ($productos as $row) {
	$total = $total + $row['total'];
$totalprod = $row['total'];
}
	if(isset($_SESSION['smile_coupon'])){
		//$porcentaje = ($_SESSION['smile_coupon']/100);
		//$descuento = $total*$porcentaje; 
		$descuento = $_SESSION['smile_coupon']; 
		$total = $total-$descuento;
		}elseif(isset($_SESSION['smile_nota_credito'])){
		$credito = $_SESSION['smile_nota_credito'];
		$total = $total-$credito;
		if ($total < 0){
			$total = 0;
		}
		}
	if($tipoenv == "envio"){
		if ($departamento == $iddpt && $provincia== $idprv) {
		$query_envio_peru = "SELECT * FROM distritos WHERE idDist = $distrito and estado_dist = 1";
	$envio_peru = obtener_linea($query_envio_peru);
			if($envio_peru){
				$costo_envio = $envio_peru['costo_envio'];
			}else{
				$query_envio_peru = "SELECT * FROM departamentos WHERE id_departamentos = '$departamento'";
	$envio_peru = obtener_linea($query_envio_peru);
	$costo_envio = $envio_peru['costo_envio'];
			}
	
	}else{
		$query_envio_peru = "SELECT * FROM departamentos WHERE id_departamentos = '$departamento'";
	$envio_peru = obtener_linea($query_envio_peru);
	$costo_envio = $envio_peru['costo_envio'];
	}
	}elseif($tipoenv == "recojo"){
		$costo_envio = 0;
	}
$codigo = "SBT".date("ymdHis");;

	if ($total < 5){
		$total = 5;
	}
$simbolo = "S/.";
$descripcion = "";	
$r = 1;
foreach ($productos as $row){
	$id_producto = $row['id_producto'];
	$id_codigo = $row['id_codigo'];
	$id_color = $row['id_color'];
	$query = "SELECT * FROM productos WHERE id_producto = '$id_producto'";
	$producto = obtener_linea($query);
    $nombre_producto = $producto['nombre_producto'];
	$query_codigo = "SELECT * FROM codigos WHERE id_codigo = '$id_codigo'";
	$codigo_pr = obtener_linea($query_codigo);
	$nombre_codigo = $codigo_pr['talla_codigo'];
	if ($id_color == 0){
	$nombre_color = "--";
	}else{
	$query_color = "SELECT * FROM colores WHERE id_color = '$id_color'";
	$color = obtener_linea($query_color);
	$nombre_color = $color['nombre_color'];
	}
$descripcion .= $r." - ".$nombre_producto." - T:".$nombre_codigo." - C:".$nombre_color." - Cant.: ".$row['cantidad']." - ".$simbolo.$row['total']." <br> ";
$r++;
}	
	

?>

<div class="paso">
	<div class="titulo">
		<div class="icono"><img src="images/svg/cuatro.svg"></div>
		<div class="texto">Método de Pago:</div>
	</div>
	<div class="datos">

	<?php
	if($obtn_pagos['estado_tarjeta'] == 1){
		?>
		<div class="datos_tarjeta_transferencia">
		<input type="radio" name="mercadopago" value="formulario">
		Tarjeta de Crédito o Débito
		</div>
		<?php
	}
	if($obtn_pagos['estado_transferencia'] == 1){
		?>
		<div class="datos_tarjeta_transferencia">
		<input type="radio" name="mercadopago" value="transferencia">
		Transferencia Bancaria
		</div>
		<?php
	}
//	if($obtn_pagos['estado_yape'] == 1){
		?>
<!--
		<div class="datos_tarjeta_transferencia">
		<input type="radio" name="mercadopago" value="yape">
		Pago con Yape
		</div>
-->
		<?php
//	}
	?>
	
	<br><br>	
	
	<div class="datos">
		<div id="frmmp">
			<div class="linea">
			<div class="todas">Aceptamos las siguientes tarjetas:</div>
			<div class="tarjetas">
				<ul>
					<li><img src="images/tarjetas/visa.png" alt="Culqi visa"></li>
					<li><img src="images/tarjetas/mc.png" alt="Culqi Master Card"></li>
					<li><img src="images/tarjetas/diners.png" alt="Culqi Diners Club"></li>
					<li><img src="images/tarjetas/amex.png" alt="Culqi American Express"></li>
				</ul>
			</div>
		</div>
		
		<div class="pagculqi"><button  type="button" class="buyButton">Pagar Ahora</button></div>
		</div>
		<div id="frmtr">
			<?php
				formulario_tranferencia();
			?>
		</div>
		<div id="frmyape">
			<?php
				$query_qr = "select * from yape where id_yape = 1";
				$obtn_qr = obtener_linea($query_qr);
			?>
			<img src="images/yape/<?php echo $obtn_qr['foto_yape'] ?>" alt="">
		</div>
	
	</div>
	<script>
	$(document).ready(function(){
		$("#frmtr").hide();
		$("#frmmp").hide();
		$("#frmyape").hide();
		$("input[name=mercadopago]").on("change",function(){
			var des = $(this).val();
			if(des == "formulario"){
			   	$("#frmmp").show("slow");
				$("#frmtr").hide();
				$("#frmyape").hide();
			   }else if(des == "transferencia"){
				 $("#frmmp").hide();
				 $("#frmyape").hide(); 
				 $("#frmtr").show("slow"); 
			   }else if(des == "yape"){
				 $("#frmmp").hide();
				 $("#frmyape").show("slow");
				 $("#frmtr").hide();
			   }
		})
	})
	</script>


		

		<?php
		date_default_timezone_set('America/Lima');
		$codigo = "GVK".date("ymdHis");;
		$productos = $_SESSION['sing_prod_x_comp'];
		$montoaingresar =  $obt_peru['monto_peru'];
		$moneda = 'PEN';
		if($total >= $montoaingresar){
			$previo_total = $total;
		}else{
			$previo_total = $total+$costo_envio;
		}
	
		if($total >= $montoaingresar){
			$total = $total*100;
			
		}else{
			$total = ($total+$costo_envio)*100;
		}
			
	
		
		
	
		?>

		<script>
		//Culqi.publicKey = 'pk_test_011edcd9fe4e39dc';
		Culqi.publicKey = 'pk_live_a6a55c35479ac04f';
		Culqi.settings({
		title: 'Sianny Boutique',
		currency: 'PEN',
		description: 'Pedido N: <?php echo $codigo ?>',
		amount: '<?php echo $total ?>',
		email: '<?php echo $mail ?>'
		});
		Culqi.options({
			modal: true,
			style: {
			  logo: 'https://www.siannyboutique.com.pe/images/rep/logo_siannyboutique_cq.jpg',
			  maincolor: '#000000',
			  buttontext: '#ffffff',
			  maintext: '#000000',
			  desctext: '#4A4A4A'
			}
		});	
		</script>
	<?php	
			if($regalo){
				$regalo = 1;
			}else{
				$regalo = 0;
			}
			if($tarjeta_etiqueta){
				$tarjeta_etiqueta = 1;
			}else{
				$tarjeta_etiqueta = 0;
			}
				$query_carro_abandonado = "INSERT INTO carros_abandonados (codigo_pedido,descripcion_pedido,moneda_pedido,total_pedido,fecha_pedido,nombre_pedido,email_pedido,celular_pedido,fijo_pedido,envio_pedido,depa_pedido,prov_pedido,dist_pedido,dni_pedido,direccion_pedido,referencia_pedido,cupon_pedido,nota_pedido,estado_pagado,metodo_pedido,id_tienda,estado_regalo,estado_etiqueta,etiqueta_de,etiqueta_para,etiqueta_mensaje) VALUE ('$codigo','$descripcion','PEN S/. ','$previo_total','$hoy','$nombre','$mail','$celular','$fijo','$zona','$departamento','$provincia','$distrito','$dni','$direccion','$referencia','$cupon_previo','$nota_previo','-','$tipoenv','$tienda_txt','$regalo','$tarjeta_etiqueta','$de','$para','$mensaje')";	
//	echo $query_carro_abandonado;	
				actualizar_registro($query_carro_abandonado);
?>
			<script>
$('.buyButton').on('click', function (e) {
// Abre el formulario con las opciones de Culqi.settings
Culqi.open();
e.preventDefault();
});
</script>

<script>
	function culqi() {
    Culqi.close();
    };
</script>
<script>
function culqi() {

    if(Culqi.token) { // ¡Token creado exitosamente!
        // Get the token ID:
        var token = Culqi.token.id;
		var email = Culqi.token.email;
		
		var codigo = "<?php echo $codigo ?>";
		var total = "<?php echo $total ?>";
		var moneda = "<?php echo $moneda ?>";
		var datitos = {codigo:codigo,total:total,token:token,email:email,moneda:moneda};
		var data =datitos;
		var url = "proceso.php";
		$.post(url,data,function(res){
		//console.log(datitos);
		//if (res){
			//alert(res);
			
			var contiene = res.indexOf("charge_id");
			if (contiene >= 0){ // Es un error
				var datos = JSON.parse(eval(res));
				//alert(datos["user_message"]);
				//alert(datos["merchant_message"]);
				//response.redirect("paginaerror.php");
				var parametros = {
					"mensaje_culqi": datos["user_message"],
				};
				
				$.ajax({
					data: parametros,
					url: 'paginaerror.php',
					type: 'post',
					beforeSend: function (){
						$("#pedido").html("Procesando, espere por favor")
					},
					success: function(msg) {
						$("#pedido").html(msg)

					}
				});
			}
			else{
				var datos = JSON.parse(res);
				//alert(datos["outcome"]["user_message"]);
			
				var parametros = {
					"zona": "<?php echo $zona ?>",
					"departamento": "<?php echo $departamento ?>",
					"provincia": "<?php echo $provincia ?>",
					"distrito": "<?php echo $distrito ?>",
					"dni": "<?php echo $dni ?>",
					"direccion": "<?php echo $direccion ?>",
					"referencia": "<?php echo $referencia ?>",
					"nombre": "<?php echo $nombre ?>",
					"email": "<?php echo $mail ?>",
					"celular": "<?php echo $celular ?>",
					"tienda":"<?php echo $tienda_txt ?>",
					"fijo": "<?php echo $fijo ?>",
					"codigo": "<?php echo $codigo ?>",
					"total": "<?php echo $total ?>",
					"costo_envio": "<?php echo $costo_envio ?>",
					"estado_envio": "<?php echo $estado_pedido ?>",
					"moneda": "<?php echo $moneda ?>",
					"mensaje_culqi": datos["outcome"]["user_message"],
					"tipenv" : "<?php echo $tipoenv ?>"
				};
				//console.log(parametros);
				$.ajax({
					data: parametros,
					url: 'paginaexito.php',
					type: 'post',
					beforeSend: function (){
						$("#pedido").html("Procesando, espere por favor")
					},
					success: function(msg) {
						$("#pedido").html(msg)

					}
				});
			}
		
		});

    }else{ // ¡Hubo algún problema!
        // Mostramos JSON de objeto error en consola
        console.log(Culqi.error);
        alert(Culqi.error.mensaje);
    }
};
</script>
	</div>
</div>
<?php
}

function formulario_tranferencia(){
	$query_textotrans = "select * from texto_transferencia where id_texto = 1";
	$obtn_textotras = obtener_linea($query_textotrans);
	
	$query_dpt = "select * from departamentos where estado_departamento = '1'";
$obtener_dpt = obtener_linea($query_dpt);
$iddpt = $obtener_dpt['id_departamentos'];
	
$query_prv = "select * from provincias where estado_prov = '1'";
$obtn_prv = obtener_linea($query_prv);
$idprv = $obtn_prv['idProv'];
	
		$query_peru = "SELECT * FROM config_estatico where id_conf_gen = 1";
	$obt_peru = obtener_linea($query_peru);
$estado_pedido = "envio";	
$query_tarjeta = "SELECT * FROM detalle_tarjeta where estado_tarjeta = 1";
$tarjeta = obtener_todo($query_tarjeta);
date_default_timezone_set('America/Lima');
$productos = $_SESSION['sing_prod_x_comp'];
	
//print_r($productos);	
$tienda_txt = $_POST['tienda_txt'];

// fin monto y envio
$tipoenv = $_POST['tipoenv'];
	//echo $tipoenv;
$zona = $_POST['envio_txt'];
$departamento = $_POST['departamento_txt'];
$provincia = $_POST['provincia_txt'];
$distrito = $_POST['distrito_txt'];
$dni = $_POST['dni_txt'];
$direccion = $_POST['direccion_txt'];
$referencia = $_POST['referencia_txa'];
$nombre = $_POST['nombre_txt'];
$mail = $_POST['mail_txt'];
$celular = $_POST['celular_txt'];
$fijo = $_POST['fijo_txt'];
$total = "0";

foreach ($productos as $row) {
	$total = $total + $row['total'];
$totalprod = $row['total'];
}
	if(isset($_SESSION['smile_coupon'])){
		$porcentaje = ($_SESSION['smile_coupon']/100);
		$descuento = $total*$porcentaje; 
		$total = $total-$descuento;
		}elseif(isset($_SESSION['smile_nota_credito'])){
		$credito = $_SESSION['smile_nota_credito'];
		$total = $total-$credito;
		if ($total < 0){
			$total = 0;
		}
		}
	if($tipoenv == "envio"){
		if ($departamento == $iddpt && $provincia== $idprv) {
		$query_envio_peru = "SELECT * FROM distritos WHERE idDist = $distrito";
	$envio_peru = obtener_linea($query_envio_peru);
	if($envio_peru){
				$costo_envio = $envio_peru['costo_envio'];
			}else{
				$query_envio_peru = "SELECT * FROM departamentos WHERE id_departamentos = '$departamento'";
	$envio_peru = obtener_linea($query_envio_peru);
	$costo_envio = $envio_peru['costo_envio'];
			}
	}else{
		$query_envio_peru = "SELECT * FROM departamentos WHERE id_departamentos = '$departamento'";
	$envio_peru = obtener_linea($query_envio_peru);
	$costo_envio = $envio_peru['costo_envio'];
	}
	}elseif($tipoenv == "recojo"){
		$costo_envio = 0;
	}
$codigo = "SYT".date("ymdHis");;

	if ($total < 10){
		$total = 10;
	}
	$montoaingresar =  $obt_peru['monto_peru'];
		if($total >= $montoaingresar){
			$total = $total;
		}else{
			$total = ($total+$costo_envio);	
		}
	$moneda = 'PEN';
		
	if($regalo){
				$regalo = 1;
			}else{
				$regalo = 0;
			}
			if($tarjeta_etiqueta){
				$tarjeta_etiqueta = 1;
			}else{
				$tarjeta_etiqueta = 0;
			}
		
?>
<div id="check">
<form action="checkout.php" method="post" id="frmenviod">
	<div style="font-weight: bold; text-transform: uppercase;"><?php echo $obtn_textotras['texto'] ?></div>
	<br>
	<?php
	foreach ($tarjeta as $itemtarjeta) {
		?>
		<strong><?php echo $itemtarjeta['nombre_tarjeta'] ?></strong> <br>
    	Cuenta en soles:<br>
	 	N° de cuenta: <?php echo $itemtarjeta['numero_tarjeta'] ?><br>
		<?php
			if(!empty($itemtarjeta['numero_interbancaria'])){
			?>
		N° de Interbancaria: <?php echo $itemtarjeta['numero_interbancaria'] ?><br><br>
		<?php
			}
		if(!empty($itemtarjeta['numero_sistra'])){
		?>
	
		Numero de <?php echo $itemtarjeta['sistra_tarjeta'] ?>: <?php echo $itemtarjeta['numero_sistra'] ?><br><br>
		<?php if($itemtarjeta['logo_tarjeta'] != ""){
			?>
		<img src="images/qr/<?php echo $itemtarjeta['logo_tarjeta'] ?>" width="25%"><br><br>
		<?php
		} 
		}
	}
	?>
			
	<input type="hidden" value="<?php echo $zona ?>" name="zona">
	<input type="hidden" value="<?php echo $departamento ?>" name="departamento">
	<input type="hidden" value="<?php echo $provincia ?>" name="provincia">
	<input type="hidden" value="<?php echo $distrito ?>" name="distrito">
	<input type="hidden" value="<?php echo $dni ?>" name="dni">
	<input type="hidden" value="<?php echo $direccion ?>" name="direccion">
	<input type="hidden" value="<?php echo $referencia ?>" name="referencia">
	<input type="hidden" value="<?php echo $nombre ?>" name="nombre">
	<input type="hidden" value="<?php echo $mail ?>" name="email">
	<input type="hidden" value="<?php echo $celular ?>" name="celular">
	<input type="hidden" value="<?php echo $tienda_txt ?>" name="tienda">
	<input type="hidden" value="<?php echo $fijo ?>" name="fijo">
	<input type="hidden" value="<?php echo $codigo ?>" name="codigo">
	<input type="hidden" value="<?php echo $total ?>" name="total">
	<input type="hidden" value="<?php echo $costo_envio ?>" name="costo_envio">
	<input type="hidden" value="<?php echo $estado_pedido ?>" name="estado_envio">
	<input type="hidden" value="<?php echo $moneda?>" name="moneda">
	<input type="hidden" value="<?php echo $tipoenv ?>" name="metodo">

	<div class="pagotrns" style="display: none">
<!--		Numero de operacion Bancaria: <input type="text" class="nro_bank" name="nro_banca_opera" >-->
	<input class="btn_transferencia" type="submit" value="Confirmar Pedido" id="pagar2"/>
	</div>

	
</form>
	<label><input type="checkbox" id="checkbtn2"> Acepto los <a href="tyc.php" target="_blank">Términos y Condiciones</a> y la <a href="faq.php" target="_blank">Política de Protección de Datos Personales</a>. </label><br>
	<label class="rspterminos2" style="display: none">*Tienes que aceptar los Términos y Condiciones y la Política de Protección de Datos Personales para finalizar tu compra*</label>
</div>
<script>
	
$(document).ready(function(){
	$('#pagar2').removeAttr("type");
		$('#pagar2').attr("type","button");
		$("#checkbtn2").on("change",function(){
			if ($(this).is(':checked')) {
				$(".pagotrns").show();
				$('#pagar2').attr("type", "submit");
				$(".rspterminos2").hide();
			} else {
				$(".pagotrns").hide();
				$('#pagar2').removeAttr("type");
				$('#pagar2').attr("type","button");
				$(".rspterminos2").show();
			}
		})
		$("#pagar2").on("click",function(){
			if ($("#checkbtn2").is(':checked')) {
				$(".rspterminos2").hide();
			} else {
				$(".rspterminos2").show();
			}
		})

	
});
</script>

      <script>
		  var parametros = {
			  "zona": "<?php echo $zona ?>",
					"departamento": "<?php echo $departamento ?>",
					"provincia": "<?php echo $provincia ?>",
					"distrito": "<?php echo $distrito ?>",
					"dni": "<?php echo $dni ?>",
					"direccion": "<?php echo $direccion ?>",
					"referencia": "<?php echo $referencia ?>",
					"nombre": "<?php echo $nombre ?>",
					"email": "<?php echo $mail ?>",
					"celular": "<?php echo $celular ?>",
					"tienda":"<?php echo $tienda_txt ?>",
					"fijo": "<?php echo $fijo ?>",
					"codigo": "<?php echo $codigo ?>",
					"total": "<?php echo $total ?>",
					"costo_envio": "<?php echo $costo_envio ?>",
					"estado_envio": "<?php echo $estado_pedido ?>",
					"moneda": "<?php echo $moneda ?>",
					"tipenv" : "<?php echo $tipoenv ?>"
	};
		  //console.log(parametros);
		$("#frmenviod").on("submit",function(e){
			e.preventDefault();
			var nro_bank = $(".nro_bank").val();
			parametros.nro_banco = nro_bank;

		$.ajax({
			data: parametros,
			url: 'transferencia_correo.php',
			type: 'post',
			beforeSend:function(){
				$("#pedido").html("Procesando, espere por favor")
			},
			success: function(res) {
				//window.location.href = "pedido_aceptado.php";
				//console.log(res);
				//$("#clicksolo2").click();
				//$(".rpstventa").html(res);
				$("#pedido").html(res)
			},
			error: function(res){
			console.log(res);
			}
		});
		
		})

	</script>

<!---->
<div style="display: none">
  <button type="button" class="btn btn-info btn-lg" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#myModal2" id="clicksolo2">Open Modal</button>
</div>
<!-- Modal -->
  <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Pedido Enviado</h4>
        </div>
        <div class="modal-body">
			
		
          <img src="images/rep/logo_siannyboutique_mail.jpg" class="img-rounded" alt="Sianny Boutique" width="100%" height="auto" >
      
          <p class="rpstventa"></p>
        </div>

      </div>
    </div>
  </div>
<!---->
<?php
}




----------------*/
