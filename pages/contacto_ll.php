<?php
$accion = "";
if(isset($_POST['accion'])){
$accion = $_POST['accion'];
}
?>

	<div class="estaticas">
		<div class="titulo">Contacto</div>
		<?php
		if ( $accion == 'enviar' ) {
			enviar_mensaje();
		} else {
			formulario_inicial();
		}
		?>
	</div>

<?php

function formulario_inicial() {
?>
<div class="comunicate">
	<div class="formulario">
		<form action="contacto.php" enctype="multipart/form-data" method="post">
			<div class="doble">
				<div class="form_sec">
					<div class="nombre_form">Nombres y Apellidos</div>
					<div class="input_form"><input type="text" name="nombre_form" value="" required></div>
				</div>
				<div class="form_sec">
					<div class="nombre_form">Correo Electrónico</div>
					<div class="input_form"><input type="email" name="mail_form" value="" required></div>
				</div>
				<div class="form_sec">
					<div class="nombre_form">Celular / Teléfono</div>
					<div class="input_form"><input type="text" name="celular_form" value="" required></div>
				</div>
				<div class="form_sec">
					<div class="nombre_form">Empresa</div>
					<div class="input_form"><input type="text" name="empresa_form" value="" required></div>
				</div>
			</div>
			<div class="form_long">
					<div class="nombre_form_long">Consulta</div>
					<div class="textarea_form"><textarea name="comentario_txt_area" cols="" rows="" placeholder="Escríbelo aquí"></textarea></div>
				</div>
			<div class="">
			<input type="hidden" name="accion" value="enviar">
			<button class="btn_form" type="submit" value="enviar" >Enviar</button>
			</div>
		</form>
	</div>
</div>
<?php
}

function enviar_mensaje() {
	$nombre = $_POST['nombre_form'];
	$mail = $_POST['mail_form'];
	//$fono = $_POST[ fono_form ];
	$comentario = $_POST['comentario_txt_area'];

	$origenNombre = 'singularityperu.com';
	//$origenNombre = 'singularityperu.com';
	$origenEmail = 'no_reply@singularityperu.com';
	//$origenEmail = 'no_reply@singularityperu.com';
	$destinatarioEmail = 'rbreva@gmail.com';

	$asuntoEmail = 'Formulario Contacto - singularityperu.com';

	//cuerpo del email:
	$cuerpoMensaje = '
<html>
<head>
<title>Mensaje Contacto - Singularity</title>
</head>
<body>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="880">
<tr>
<td colspan="2"><strong>MENSAJE ENVIADO DESDE EL FORMULARIO DE CONTACTO Rogod</strong></td>
</tr>
<tr>
<td>Nombre:</td>
<td>' . $nombre . '</td>
</tr>
<tr>
<td>Email:</td>
<td>' . $mail . '</td>
</tr>
<tr>
<td>Comentario:</td>
<td>' . $comentario . '</td>
</tr>
</table>
</body>
</html>   
';
	//fin cuerpo del email.
	//<tr>
	//<td>Teléfono:</td>
	//<td>' . $fono . '</td>
	//</tr>


	//cabecera del email (forma correcta de codificarla)
	$header = "From: " . $origenNombre . " <" . $origenEmail . ">\r\n";
	$header .= "Reply-To: " . $origenEmail . "\r\n";
	$header .= "MIME-Version: 1.0\r\n";

	$header .= "Content-type: text/html; charset=iso-8859-1\r\n";

	//armado del mensaje y attachment
	$mensaje = "";
	$mensaje .= $cuerpoMensaje . "\r\n\r\n";

	//envio el email y verifico la respuesta de la función "email" (true o false)
	if ( mail( $destinatarioEmail, $asuntoEmail, $mensaje, $header ) ) {
		$msj = "Mensaje enviado correctamente";
		mensaje_error($msj);
	} else {
		$msj = "Error al enviar mensaje, intente nuevamente por favor";
		mensaje_error($msj);
	}

}

function mensaje_error($msj) {
	?>
	<div id="comunicate">
		<div id="formulario">
			<h3>Muchas gracias por su interés, intentaremos comunicarnos con usted dentro de las 24 horas.</h3>
			<div id="msj">
				<?php echo $msj; ?><br><br>
				<a href="contacto.php" class="boton">Regresar</a>
			</div>
		</div>
	</div>
	<?php
}

function enviado() {
	$msj = "Mensaje enviado correctamente";
	mensaje_error($msj);
}
?>