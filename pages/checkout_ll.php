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
	//pasodos_emp($cliente);
	//pasotres_emp();
	//pasocuatro_emp();
} elseif ( $paso == "dos" ) {
	pago();
	//pasouno_fll($cliente);
	//pasodos($cliente);
	//pasotres_emp();
	//pasocuatro_emp();
} elseif ( $paso == "tres" ) {
	//pasouno_fll($cliente);
	//pasodos_fll($cliente);
	//pasotres();
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
$id = $cliente['id_cliente'];		
$nombres_cliente = $cliente['nombres_cliente'];
$apellidos_cliente = $cliente['apellidos_cliente'];
$dni_cliente = $cliente['dni_cliente'];
$celular_cliente = $cliente['celular_cliente'];
$email_cliente = $cliente['email_cliente'];
	
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
		<div class="icono"><img src="images/svg/check.svg"></div>
		<div class="texto">Detalles de Facturación</div>
	</div>
	<div class="datos">
		<form action="checkout.php" enctype="multipart/form-data" method="post">
		<div class="linea">
			<input type="text" name="nombre_txt" placeholder="Nombre" value="<?php echo $nombres_cliente ?>" required>
			<input type="text" name="apellidos_txt" placeholder="Apellidos" value="<?php echo $apellidos_cliente ?>" required>
		</div>
		<div class="linea">
			<input type="text" name="dni_txt" placeholder="DNI/CE/Pasaporte" value="<?php echo $dni_cliente ?>" required>
			<input type="number" name="celular_nmb" placeholder="Número de teléfono" value="<?php echo $celular_cliente ?>" required>
		</div>
		<div class="linea">
			<input class="large" type="text" name="empresa_txt" placeholder="Nombre de la Empresa (opcional)" value="" >
		</div>
		<div class="linea">
			<input type="email" placeholder="Email" value="<?php echo $email_cliente ?>" disabled>
			<input type="hidden" name="email_ml" value="<?php echo $email_cliente ?>">
		</div>
		<div class="linea">
			<input class="large" type="text" name="direccion_txt" placeholder="Dirección de Envío" required>
		</div>
		<div class="linea">	
			<input class="large" type="text" name="referencia_txt" placeholder="Referencia" required>
		</div>
		
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
			<input name="paso" value="dos" type="hidden">
			<button type="submit">Continuar</button>
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
$paso = "";
if(isset($_POST['paso'])){
	$paso = $_POST['paso'];
}

date_default_timezone_set('America/Lima');
$productos = $_SESSION['sing_prod_x_comp'];

//print_r($productos);

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
						<div class="nombre"><?php echo $nombre_curso ." x ".$row['cantidad'] ?></div>
						<div class="precio_uni"><?php echo $precio_final_curso ?></div>
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
		<div class="sumariomonto">
			<?php
			if($paso == "dos" ){
			formulario_izipay();
			}else{
			echo "Total: S/. ".number_format ($total, 2, '.', '');
			}
			?>
		</div>
	</div>
<?php
}

function datos_envio($paso, $nombre_txt, $apellidos_txt, $dni_txt, $celular_nmb, $empresa_txt, $email_ml, $departamento_txt, $provincia_txt, $distrito_txt, $direccion_txt, $referencia_txt){
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

function pago(){
include("includes/datos_proceso.php");	
	
$query_dep = "SELECT * FROM departamentos WHERE id_departamentos = '$departamento_txt'";
$obtener_dep = obtener_linea($query_dep);
$nombre_dep = $obtener_dep['nombre_departamento'];
	
$query_prov = "SELECT * FROM provincias WHERE idProv = '$provincia_txt'";
$obtener_prov = obtener_linea($query_prov);
$nombre_prov = $obtener_prov['provincia'];
	
$query_dis = "SELECT * FROM distritos WHERE idDist = '$distrito_txt'";
$obtener_dis = obtener_linea($query_dis);	
$nombre_dis = $obtener_dis['distrito'];	
	
	
?>
<div class="paso">
	<div class="barra_titulo">
		<div class="icono"><img src="images/svg/check.svg"></div>
		<div class="texto">Detalles de Facturación</div>
	</div>
	<div class="datos">
		<div class="linea">
			<div class="dato">Nombres y Apellidos: <?php echo $nombre_txt." ".$apellidos_txt ?></div>
			<div class="dato">DNI/CE/Pasaporte: <?php echo $dni_txt ?></div>
			<div class="dato">Número de teléfono: <?php echo $celular_nmb ?></div>
			<div class="dato">Nombre de la Empresa (opcional): <?php echo $empresa_txt ?></div>
			<div class="dato">Email: <?php echo $email_ml ?></div>
			<div class="dato">Dirección: <?php echo $direccion_txt ?></div>
			<div class="dato">Referencia: <?php echo $referencia_txt ?></div>
			<div class="dato">Departamento: <?php echo $nombre_dep ?></div>
			<div class="dato">Provincia: <?php echo $nombre_prov ?></div>
			<div class="dato">Distrito: <?php echo $nombre_dis ?></div>
		</div>
	</div>	
</div>
<?php
}

function formulario_izipay(){
include("includes/datos_proceso.php");
date_default_timezone_set('America/Lima');
	
$productos = $_SESSION['sing_prod_x_comp'];
$cantidad = "0";
$total = "0";
	foreach ( $productos as $row ) {
		$cantidad = $cantidad + $row['cantidad'];
		$total = $total + $row['total'];
	}
	
$codigo = "SNGizi".date("ymdHis");	
$total_cq = $total*100; 	

require_once './izipay/vendor/autoload.php';
require_once './izipay/keys.php';
require_once './izipay/helpers.php';

/** 
 * Initialize the SDK 
 * see keys.php
 */
$client = new Lyra\Client();

/**
 * I create a formToken
 */
$store = array("amount" => $total_cq, 
"currency" => "PEN", 
"orderId" => uniqid($codigo),
"customer" => array(
  "email" => $email_ml
));
$response = $client->post("V4/Charge/CreatePayment", $store);

/* I check if there are some errors */
if ($response['status'] != 'SUCCESS') {
    /* an error occurs, I throw an exception */
    display_error($response);
    $error = $response['answer'];
    throw new Exception("error " . $error['errorCode'] . ": " . $error['errorMessage'] );
}

/* everything is fine, I extract the formToken */
$formToken = $response["answer"]["formToken"];

//echo "zona: ".$zona."<br>";
//echo "departamento: ".$departamento."<br>";
//echo "provincia: ".$provincia."<br>";
//echo "distrito: ".$distrito."<br>";
//echo "dni: ".$dni."<br>";
//echo "direccion_envio: ".$direccion_envio."<br>";
//echo "referencia_envio: ".$referencia_envio."<br>";
//echo "nombre_compra: ".$nombre_compra."<br>";
//echo "mail_compra: ".$mail_compra."<br>";
//echo "celular_compra: ".$celular_compra."<br>";
//echo "fijo_compra: ".$fijo_compra."<br>";
//echo "codigo: ".$codigo."<br>";
//echo "total: ".$total."<br>";
//echo "moneda: ".$moneda."<br>";
//echo "mensaje_culqi: ".$mensaje_culqi."<br><br>";
 $apellidos_txt
?>
<!-- Javascript library. Should be loaded in head section -->
  <script 
   src="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
   kr-public-key="<?php echo $client->getPublicKey();?>"
   kr-post-url-success="paginaexito_izi.php?departamento=<?php echo $departamento_txt ?>&provincia=<?php echo $provincia_txt ?>&distrito=<?php echo $distrito_txt ?>&dni=<?php echo $dni_txt ?>&direccion=<?php echo $direccion_txt ?>&referencia=<?php echo $referencia_txt ?>&nombre=<?php echo $nombre_txt ?>&apellidos=<?php echo $apellidos_txt ?>&email=<?php echo $email_ml ?>&celular=<?php echo $celular_nmb ?>&empresa=<?php echo $empresa_txt ?>&codigo=<?php echo $codigo ?>&total=<?php echo $total_cq ?>&moneda=PEN">
  </script>

  <!-- theme and plugins. should be loaded after the javascript library -->
  <!-- not mandatory but helps to have a nice payment form out of the box -->
  <link rel="stylesheet" 
   href="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/ext/classic-reset.css">
  <script 
   src="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/ext/classic.js">
  </script>

<!-----
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
		
	<div class="pagculqi"><button  type="button" class="buyButton">Pagar Ahora</button></div>-->
	<div class="kr-embedded"
	   kr-popin
	   kr-form-token="<?php echo $formToken;?>">
			<!-- payment form fields -->
		<div class="kr-pan"></div>
		<div class="kr-expiry"></div>
		<div class="kr-security-code"></div>  
			<!-- payment form submit button -->
		<button class="kr-payment-button"></button>
			<!-- error zone -->
		<div class="kr-form-error"></div>
	  </div> 
	
<?php	
}
