<div class="sesion">
<?php
$sesion_email = "";	
if (isset($_SESSION['singularity_email'])) {
	$sesion_email = $_SESSION['singularity_email'];
	$comp = substr($_SESSION['singularity_email'], 0, 9);
	if ($comp == "Invitado:"){

	}else{
		logueado($sesion_email);
	}
}else{
nologueado();
}
?>
</div>	
<?php
	
//*------------------Funciones  noLogueado----------------*/

function nologueado(){
$crear = "";
if (isset($_GET['crear'])){
$crear = $_GET['crear'];
}
$accion = "";	
if (isset($_POST['formulario'])){
$accion = $_POST['formulario'];
}
$error = "";	
if (isset($_GET['err'])){
$error = $_GET['err'];
}
if ($crear){
	if ($crear == 'invitado'){
		invitado($crear);
	}
}else{
?>
<div class="titulo_sesion">Identifícate</div>
<div class="secciones">
	<div class="cliente">
	<?php ingresar($accion, $error) ?>
	</div>
	<div class="cliente">
	<?php registrarse($accion, $error) ?>
	</div>
</div>	
<?php
}
}

function ingresar($accion, $error){
date_default_timezone_set('America/Lima');
$hoy = date( "Y-m-d" );		
if ($accion == "ingresar"){
	if (isset($_POST['email'])){
		$email = $_POST['email'];
	};
	if (isset($_POST['pass'])){
		$pass = $_POST['pass'];
	};
	
	if($email){
		if ($pass){
			$verificar = get_pass_cliente($email);
			if (tep_validate_password($pass, $verificar)){
				$_SESSION['singularity_email'] = $email;
				$query_ultima_sesion = "UPDATE clientes SET ult_fecha_ingreso = '$hoy' WHERE email_cliente = '$email'";
				actualizar_registro($query_ultima_sesion);
			echo "<script>location.href = 'sesion.php';</script>";
		}else{
			echo "<script>location.href = 'sesion.php?err=3';</script>";
		}
		}else{
			echo "<script>location.href = 'sesion.php?err=2';</script>";
		}
	}else{
		echo "<script>location.href = 'sesion.php?err=1';</script>";
	}
	
}else{
?>
<div class="registro">
	<form action="sesion.php" enctype="multipart/form-data" method="post">
	<div class="titulo_form">Soy Usuario</div>
	<div class="anuncio">Inicie Sesión</div>
	<input type="email" name="email" placeholder="Email*:" required>
	<input type="password" name="pass" placeholder="Clave*:" required>
	<input type="hidden" name="formulario" value="ingresar">
	<div><button type="submit">Iniciar Sesión</button></div>
	<?php
	if ($error == 1) {
		echo '<div id="error">Por favor Ingrese su Email</div>';
	} elseif ( $error == 2 ) {
		echo '<div id="error">Por favor Ingrese su contraseña</div>';
	} elseif ( $error == 3 ) {
		echo '<div id="error">Error en usuario o contraseña</div>';
	}
	?>
	</form>
</div>
<?php
}

}

function registrarse($accion, $error){
if ($accion == "registrar"){
	if (isset($_POST['nombre_txt'])){
		$nombre = $_POST['nombre_txt'];
	};
	if (isset($_POST['apellidos_txt'])){
		$apellidos = $_POST['apellidos_txt'];
	};
	if (isset($_POST['email'])){
		$email = $_POST['email'];
	};
	if (isset($_POST['pass'])){
		$pass = $_POST['pass'];
	};
	if (isset($_POST['pass_rep'])){
		$pass_rep = $_POST['pass_rep'];
	};
	
	if($pass == $pass_rep){
		$query_cliente_repetido = "SELECT * FROM clientes WHERE email_cliente = '$email'";
		$cliente_repetido = obtener_linea($query_cliente_repetido);
		if (!$cliente_repetido){
			$password = tep_encrypt_password($pass);
			$query_cliente = "INSERT INTO clientes (nombres_cliente, apellidos_cliente, email_cliente, password_cliente) VALUE ('$nombre', '$apellidos', '$email', '$password')";
			if (actualizar_registro($query_cliente)) {
				enviar_correo_confirmacion($nombre, $apellidos, $email);
				?>
				<div id="registro">
				<p>Muchas gracias por registrarte.</p>
				<p>Te llegará un correo de confirmación, en caso de que no lo encuentres, busca en el spam.</p>
				<p>Para continuar con tu compra <a href="sesion.php">inicia sesión</a>.</p>
				</div>
				<?php
			}else{
				echo "<script>location.href = 'sesion.php?err=6';</script>";
			}
		}else{
			echo "<script>location.href = 'sesion.php?err=5';</script>";
		}
	}else{
		echo "<script>location.href = 'sesion.php?err=4';</script>";
	}
	
}else{
?>
<div class="registro">
	<form action="sesion.php" enctype="multipart/form-data" method="post">
		<div class="titulo_form">Nuevo Usuario</div>
		<div class="anuncio">Crear una cuenta</div>
		<input type="text" name="nombre_txt" placeholder="Nombres*:" required>
		<input type="text" name="apellidos_txt" placeholder="Apellidos*:" required>
		<input type="email" name="email" placeholder="Usuario/Email*:" required>
		<input type="password" name="pass" placeholder="Clave*:" required>
		<input type="password" name="pass_rep" placeholder="Repetir Clave*:" required>
		<input type="hidden" name="formulario" value="registrar">
		<div><button type="submit">Registrarse</button></div>
		<?php
		if ($error == 4) {
			echo '<div class"error">Las claves ingresadas no coinciden</div>';
		}elseif($error == 5) {
			echo '<div class="error">El usuario/correo ingresado ya se encuentra registrado</div>';
		}elseif($error == 6) {
			echo '<div class="error">Disculpe los inconvenientes, por favor intente nuevamente</div>';
		}
		?>
	</form>
</div>
<?php
}

}

function enviar_correo_confirmacion($nombre, $apellidos, $email) {
	
$destinatario = "rbreva@gmail.com";
$asunto = "Bienvenido a Singularity";
$asunto_fly = "Nuevo Registro en Singularity Peru";
$cuerpo = ' 
<html>
<head>
<title>Registro Singularity Peru</title>
</head>
<body>
<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
<tr>
<td><img alt="Singularity Peru" src="https://www.limalocal.com/images/rep/logo_singularity_mail.jpg" style="display:block; border:0px;" width="600" /></td>
</tr>
<tr>
<td>
<p style="font-size: 22px; color: #555454; font-family: Open-sans, sans-serif; text-decoration: none; text-transform: uppercase; text-align: center; margin-top: 10px;">Hola '.$nombre.' '.$apellidos.',</p>
<p style="font-size:16px; color: #555454; font-family: Open-sans, sans-serif; text-decoration: none; text-transform: uppercase; text-align: center; margin: 10px;">Gracias por crear tu cuenta en Singularity Peru.</p>
</td>
</tr>
<tr>
<td style="background-color: #f7f7f7;">
<p style="font-size: 16px; color: #555454; font-family: Open-sans, sans-serif; text-decoration: none; text-transform: uppercase; text-align: left; margin: 10px;">Detalles de su cuenta</p>
<p style="font-size: 13px; color: #555454; font-family: Open-sans, sans-serif; text-decoration: none; text-align: left; margin: 10px;">Datos de acceso:</p>
<p style="font-size: 13px; color: #555454; font-family: Open-sans, sans-serif; text-decoration: none; text-align: left; margin: 10px;">Usuario: '.$email.'</p>
<p style="font-size: 13px; color: #555454; font-family: Open-sans, sans-serif; text-decoration: none; text-align: left; margin: 10px;">Contraseña: ******* </p>
</td>
</tr>
<tr>
<td style="font-size:8px; color:#FFF; font-family: Open-sans, sans-serif; text-decoration:none; line-height:5px; text-align: center;">&nbsp;</td>
</tr>
<tr>
<td style="background-color: #f7f7f7;">
<ul>
<li style="font-size: 12px; color: #555454; font-family: Open-sans, sans-serif; text-decoration: none; text-align: left; margin: 10px;">CONSEJOS DE SEGURIDAD:</li>
<li style="font-size: 12px; color: #555454; font-family: Open-sans, sans-serif; text-decoration: none; text-align: left; margin: 10px;">Mantenga los datos de su cuenta en un lugar seguro.</li>
<li style="font-size: 12px; color: #555454; font-family: Open-sans, sans-serif; text-decoration: none; text-align: left; margin: 10px;">No comparta los detalles de su cuenta con otras personas.</li>
<li style="font-size: 12px; color: #555454; font-family: Open-sans, sans-serif; text-decoration: none; text-align: left; margin: 10px;">Cambie su clave regularmente.</li>
<li style="font-size: 12px; color: #555454; font-family: Open-sans, sans-serif; text-decoration: none; text-align: left; margin: 10px;">Si sospecha que alguien está usando ilegalmente su cuenta, avísenos inmediatamente.</li>
</ul>	
</td>
</tr>
<tr bgcolor="#000000">
<td style="font-size:8px; color:#68214f; font-family: Open-sans, sans-serif; text-decoration:none; line-height:5px; text-align: center; ">-</td>
</tr>
<tr>
<td>
<p style="font-size: 14px; color: #555454; font-family: Open-sans, sans-serif; text-decoration: none; text-align: left; margin: 10px; text-align: center;">Ingresa ahora a tu cuenta: <a href="https://www.limalocal.com/singularity/sesion.php">Singularity Peru</a></p>
</td>
<tr bgcolor="#000000">
<td style="font-size:8px; color:#68214f; font-family: Open-sans, sans-serif; text-decoration:none; line-height:5px; text-align: center; ">-</td>
</tr>	
</tr>
</table>
</body>
</html>';

//para el envío en formato HTML 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

//dirección del remitente 
$headers .= "From: Registro Singularity Peru <registros@limalocal.com>\r\n";

//dirección de respuesta, si queremos que sea distinta que la del remitente 
$headers .= "Reply-To: registros@limalocal.com\r\n";

//ruta del mensaje desde origen a destino 
$headers .= "Return-path: registros@limalocal.com\r\n";

//direcciones que recibián copia 
//	$headers .= "Cc: " . $email . "\r\n";

//direcciones que recibirán copia oculta  se $headers .= "Bcc: rbreva@gmail.com\r\n";

mail($destinatario, $asunto_fly, $cuerpo, $headers);
mail($email, $asunto, $cuerpo, $headers);

//echo $cuerpo;	
	
}

function logueado($sesion_email){
$sec= "";
if(isset($_GET['sec'])){	
$sec = $_GET['sec'];
}
if (!$sec){
	$sec = "datospersonales";
}
$query_usuario = "SELECT * FROM clientes WHERE email_cliente = '$sesion_email'";
$usuario = obtener_linea($query_usuario);
$nombres = $usuario['nombres_cliente'];
$apellidos = $usuario['apellidos_cliente'];
?>
	<div class="titulo_sesion">Hola <?php echo $nombres." ".$apellidos;?></div>
	<div class="logueado">
		<aside>
			<div class="titulo_sesion">Mi Cuenta</div>
			<ul>
				
				<li <?php if ($sec == "datospersonales") { echo 'class="select"'; } ?>><a href="sesion.php?sec=datospersonales">Datos Personales</a></li>
				<li <?php if ($sec == "cursos") { echo 'class="select"'; } ?>><a href="sesion.php?sec=cursos">Mis Cursos</a></li>
				<li <?php if ($sec == "password") { echo 'class="select"'; } ?>><a href="sesion.php?sec=password">Contraseña</a></li>
				<li><a href="logout.php">Cerrar Sesión</a></li>
			</ul>
		</aside>
		<div class="primaria">
		<?php 
		if ($sec == "datospersonales"){
			datospersonales($usuario);	
		} 
		if ($sec == "direcciones"){
			direcciones($usuario);	
		} 
		if ($sec == "cursos"){
			cursos($usuario);	
		}
		if ($sec == "password"){
			password($usuario);	
		}
		?>
		</div>
	</div>
<?php
}

function datospersonales($usuario){
?>
<div class="titulo_sesion">Datos Personales</div>
<?php
$accion = "";
if(isset($_POST['accion'])){
$accion = $_POST['accion'];
}
if ($accion){

	if ($accion == "guardar"){
		$nombre = $_POST['nombre_txt'];
		$apellidos = $_POST['apellidos_txt'];
		$celular = $_POST['celular_nmr'];
		$fijo = "";
		if(isset($_POST['fijo_nmr'])){
		$fijo = $_POST['fijo_nmr'];
		}
		$dni = $_POST['dni_cli'];
		$query_cliente = "UPDATE clientes SET nombres_cliente = '$nombre', apellidos_cliente = '$apellidos', celular_cliente = '$celular', fijo_cliente = '$fijo',dni_cliente = '$dni' WHERE id_cliente = '$usuario[id_cliente]'";
		if(actualizar_registro ($query_cliente)){
			$msj = "Datos Actualizados";
		}else{
			$msj = "No se actualizaron los datos, por favor vuelva a intentarlo";
		}
	}
	$ruta = "sesion.php?sec=datospersonales";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
}else{
?>
<form action="sesion.php?sec=datospersonales" enctype="multipart/form-data" method="post">
	<div class="dato"><label for="nombres">Nombres: </label><input id="nombres" type="text" name="nombre_txt" value="<?php echo $usuario['nombres_cliente'] ?>" required></div>
	<div class="dato"><label for="apellidos">Apellidos: </label><input id="apellidos" type="text" name="apellidos_txt" value="<?php echo $usuario['apellidos_cliente'] ?>" required></div>
	<div class="dato"><label for="email">Email: </label><input id="email" type="text" value="<?php echo $usuario['email_cliente'] ?>" disabled></div>
	<div class="dato"><label for="celular">Celular: </label><input id="celular" type="text" name="celular_nmr" value="<?php echo $usuario['celular_cliente'] ?>" required></div>
	<div class="dato"><label for="dni">DNI: </label><input id="dni" type="text" name="dni_cli" value="<?php echo $usuario['dni_cliente'] ?>" required></div>
	<div class="dato">
		<div><input type="hidden" name="accion" value="guardar"></div>
		<div><button type="submit">Guardar Datos Personales</button></div>
	</div>	
</form>
<?php
if (isset($_SESSION['sing_prod_x_comp'])) {	
?>
	<div class="ir_check"><a href="checkout.php">Continuar con el proceso de Compra</a></div>
<?php
}
	
}

}

function cursos(){
	
}

function password($usuario){
$accion = $_GET['sec'];
$email = $usuario['email_cliente'];
if(isset($_POST['passold']) && $_POST['passnew'] && $_POST['repass']){
	if ($accion == "password"){
		$oldpass = $_POST['passold'];
		$newpass = $_POST['passnew'];
		$repass = $_POST['repass'];
		$check = $usuario['password_cliente'];	
		if ($newpass){
			if(tep_validate_password($oldpass, $check)){
				if ($newpass === $repass) {
					$password = tep_encrypt_password($newpass);
					$query_editar = "UPDATE clientes SET password_cliente = '$password' WHERE email_cliente = '$email'";
					if($query_editar){
						if (actualizar_registro($query_editar)){
							$msj = "Contraseña actualizada con éxito";
						}else{
							$msj = "Error en el servidor, Por favor intente de nuevo";
						}
					}
				}else{
					$msj = "Los contrseñas ingresadas no coinciden";
				}
			}else{
				$msj = "La contraseña actual no coincide";
			}
		}
	}
	$ruta = "sesion.php?sec=password";
	$boton = "Regresar";
	mensaje_generico($msj, $ruta, $boton);
}else{
?>
<div class="titulo_sesion">Cambiar Contraseña</div>
<form action="sesion.php?sec=password" enctype="multipart/form-data" method="post">
	<div class="dato"><label for="oldpass">Contraseña Actual: </label><input id="oldpass" type="password" name="passold" value="" required></div>
	<div class="dato"><label for="newpass">Contraseña nueva: </label><input id="newpass" type="password" name="passnew" value="" required></div>
	<div class="dato"><label for="passre">Repetir Contraseña: </label><input id="passre" type="password" name="repass" value="" required></div>
	<div class="dato">
		<div><input type="hidden" name="accion" value="guardar"></div>
		<div><button type="submit">Cambiar Contraseña</button></div>
	</div>	
</form>
<?php
	}
}

/*---------------

function invitado(){
?>
<div id="invitado">
	<div id="mensaje">Hola <?php echo $sesion_email; ?></div>
	<div id="regreso"><a href="checkout.php" class="button">Continúa con tu Compra</a></div>
	<div id="regreso"><a href="logout.php" class="button">Cerrar</a></div>
</div>
<?php
}

function direcciones($usuario){
$id = $usuario['id_cliente'];
$query_dep = "SELECT * from departamentos";
$obtener = obtener_todo($query_dep);
$query_p = "SELECT * from provincias where idDepa = 13";
$obtener_p = obtener_todo($query_p);
$query_di = "SELECT * from distritos where idProv = 1301";
$obtener_di = obtener_todo($query_di);
$query_dir = "SELECT * from direccion_cliente where id_cliente = $id";
$obtener_dir = obtener_linea($query_dir);

$query_provincia = "SELECT * FROM provincias where idDepa = $obtener_dir[id_departamento]";
$obtener_provincia = obtener_todo($query_provincia);
$query_distrito= "SELECT * FROM distritos where idProv = $obtener_dir[id_provincia]";
$obtener_distrito  = obtener_todo($query_distrito);
//print_r($obtener_provincia);

$de = 13;
$pro = 1301;
$dis = 130101;

$acc = "";
if(isset($_POST['accion'])){	
$acc = $_POST['accion'];
}
if ($acc == "guardar") {
	
	$departamento = $_POST['departamento_txt'];
	$provincia = $_POST['provincia_txt'];
	$distrito = $_POST['distrito_txt'];
	$lineauno = $_POST['lineauno_txt'];
	$lineados = $_POST['lineados_txt'];
	$referencia = $_POST['referencia_txt'];
	//echo $departamento . $provincia . $distrito . $lineauno .$lineados . $referencia;
	$query_update = "UPDATE direccion_cliente set id_departamento = $departamento,id_provincia =$provincia,id_distrito = $distrito,lineauno_direccion = '$lineauno',lineados_direccion = '$lineados',referencia_direccion= '$referencia' where id_cliente = $id";
		if (actualizar_registro($query_update)) {

			mensaje_exito_error_editpedido("Datos Actualizados");
		}else{
		
			mensaje_exito_error_editpedido("Hubo un error al Actualizar los datos");
		}
}else{
?>
<form id="formulario" action="sesion.php?sec=direcciones" enctype="multipart/form-data" method="post">
	<div class="dato">
		<div class="detalle">Departamento:</div>
		<div class="variable">
			<select id="selectdep" name="departamento_txt" class="jdepa">
				
				
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
						if ($itemdep['id_departamentos'] == $de) {
						 	echo "selected";
						 } ?>> <?php echo $itemdep['nombre_departamento'] ?></option>
				<?php
						}	
					}
				?>
			</select>
		</div>
	</div>
	
	<div id="dep_cambio">
	<div class="dato">
		<div class="detalle">Provincia:</div>
		<div class="variable">
			<select id="selectprov" name="provincia_txt" class="jprov">
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
		</div>
	</div>
		<div id="prov_cambio">	
			<div class="dato">
				<div class="detalle">Distrito:</div>
				<div class="variable">
					<select name="distrito_txt" class="jdist">
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
							$.ajax({
								url: 'selectcombo.php',
								type: 'post',
								data: {'id_dist': pro,'seldist':'distrito'},
								success:function(res){

									$(".jdist").html(res);
									$(".validar").prop("disabled",false);
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
				$(".jprov").on("change",function(){
					var iddist = $(this).val();
					//console.log(iddist);
					
					$.ajax({
						url: 'selectcombo.php',
						type: 'post',
						data: {'id_dist': iddist,'seldist':'distrito'},
						success:function(res){

							$(".jdist").html(res);
							
						},
						error:function(res){
							console.log(res);
						}
					})
				
					
					
				})
				$(".jdepa").on("change",function(){
					var iddepa = $(this).val();
					//console.log(iddepa);
					$.ajax({
						url: 'selectcombo.php',
						type: 'post',
						data: {'id_depar':iddepa,'costo':'costo'},
						success:function(res){
							
							var envio = $("#envio").html("Costo de envío: S/. " + res);
							
							
						},
						error: function(res){
							console.log(res);
						}
						})
				
					
					
				})
			})
	</script>
	<div class="dato"><div class="detalle">Dirección:</div>  <div class="variable"><input type="text" name="lineauno_txt" value="<?php echo $obtener_dir['lineauno_direccion'] ?>" ></div></div>
	<div class="dato"><div class="detalle">Adicional:</div>  <div class="variable"><input type="text" name="lineados_txt" value="<?php echo $obtener_dir['lineados_direccion'] ?>" ></div></div>
	<div class="dato"><div class="detalle">Referencia:</div>  <div class="variable"><textarea type="number" name="referencia_txt" ><?php echo $obtener_dir['referencia_direccion'] ?></textarea></div></div>
	<div class="boton">
		<input type="hidden" name="accion" value="guardar">
		<input type="submit" value="Modificar Dirección" class="validar">
	</div>
</form>
<?php
	}
}

function pedidos($usuario){
	$email = $usuario['email_cliente'];
	$query_listar = "SELECT * from pedidos where email_pedido = '$email'";
	$query_l = obtener_todo($query_listar);
?>
<div id="titulo">Pedidos</div>
<?php if($query_l){
	?>
<!--<div class="mispedidos">
	<div class="linea codigo"><span class="titulo_tabla">Código</span></div>
	<div class="linea desc"><span class="titulo_tabla">Descripcion</span></div>
	<div class="linea monto"><span class="titulo_tabla">Monto</span></div>
	<div class="linea fecha"><span class="titulo_tabla">Fecha Pedido</span></div>
	<div class="linea estado"><span class="titulo_tabla">Estado Pedido</span></div>
	<div class="linea estado"><span class="titulo_tabla">Estado Compra</span></div>
	<div class="linea estado"><span class="titulo_tabla">Tracking</span></div>
</div>-->
<?php 
	foreach($query_l as $rowpedido){
		$id_estado = $rowpedido['id_estado_pedido'];
		$query_estado = "SELECT * FROM estado_pedido WHERE id_estado_pedido = '$id_estado'";
		$estado_pedido = obtener_linea($query_estado);
?>
<div class="mispedidos">
	<div class="linea codigo"><span class="titulo_tabla"><strong>Código:</strong> <?php echo $rowpedido['codigo_pedido']?></span></div>
	<div class="linea desc"><span class="titulo_tabla"><strong>Descripción:</strong> <?php echo $rowpedido['descripcion_pedido']?></span></div>
	<div class="linea monto"><span class="titulo_tabla"><strong>Total:</strong> <?php echo $rowpedido['moneda_pedido']?> <?php echo $rowpedido['total_pedido']?></span></div>
	<div class="linea fecha"><span class="titulo_tabla"><strong>Fecha:</strong> <?php echo $rowpedido['fecha_pedido']?></span></div>
	<div class="linea estado"><span class="titulo_tabla"><strong>Estado:</strong> <?php echo $estado_pedido['nombre_estado_pedido'] ?></span></div>
	<div class="linea estado"><span class="titulo_tabla"><strong>Tipo de envío:</strong> <?php echo $rowpedido['estado_compra'] ?></span></div>
	<div class="linea estado"><span class="titulo_tabla"><strong>Trackinng:</strong> <?php echo $rowpedido['nro_tracking'] ?></span></div>
</div>
<?php }?>
<?php
}else{
	?>
	<div class="codigo"><span class="titulo_tabla">No tiene pedidos</span></div>
	<?php
}
}



function mensaje_exito_error_editusuario( $msj ) {
	?>
	<div id="msj">
		<div id="texto"><?php echo $msj; ?></div>
		<div id="retorno_btn"><a href="sesion.php?sec=password" class="boton">Regresar</a></div>
	</div>
	<?php
	}

function mensaje_exito_error_editpedido( $msj ) {
	?>
	<div id="msj">
		<div id="texto"><?php echo $msj; ?></div>
		<div id="retorno_btn"><a href="sesion.php?sec=direcciones" class="boton">Regresar</a></div>
	</div>
	<?php
	}

//Funciones  No logueudo



function invitado(){
	$invitado = $_POST['email_invitado'];
	$sesion_email = "Invitado:". $invitado;
	?>
	<div id="invitado">
	<div id="mensaje">Hola <?php echo $sesion_email; ?></div>
	<div id="regreso"><a href="checkout.php" class="button">Continúa con tu Compra</a></div>
	</div>
	<?php
}







-*-------------*/

?>