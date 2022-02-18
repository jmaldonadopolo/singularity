<?php
session_start();
include_once("includes/generales_ll.php");

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Singularity - Pago IziPay</title>
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel="shortcut icon" href="images/singularity_icon.png" type="image/x-icon" />
<link rel="stylesheet" href="css/styles.css" />	
<script src="js/menu.js"></script>
</head>
<body>
<header>
<?php
cabecera();		
	
require_once './izipay/vendor/autoload.php';
require_once './izipay/keys.php';
require_once './izipay/helpers.php';


$client = new Lyra\Client();

/* No POST data ? paid page in not called after a payment form */
if (empty($_POST)) {
    throw new Exception("no post data received!");
}

/* Use client SDK helper to retrieve POST parameters */
$formAnswer = $client->getParsedFormAnswer();


/* Check the signature */
if (!$client->checkHash()) {
    //something wrong, probably a fraud ....
    signature_error($formAnswer['kr-answer']['transactions'][0]['uuid'], $hashKey, 
                    $client->getLastCalculatedHash(), $_POST['kr-hash']);
    throw new Exception("invalid signature");
}

/* I check if it's really paid */
if ($formAnswer['kr-answer']['orderStatus'] != 'PAID') {
	//error de compra
    $mensaje_exito = "<p>Hubo algunos problemas para validar su compra</p>";
	?>

	<div id="mensaje_de_culqi">

	<!--<div id="salida"><?php //echo $mensaje_culqi."<br>"; ?></div>-->

	<div id="salida"><?php echo $mensaje_exito."<br>"; ?></div>



	<div id="regreso"><a href="https://limalocal.com/singularity/">Volver a comprar</a></div>

	</div>
	<?php
} else {
	//compra correcta
    
date_default_timezone_set('America/Lima');
$hoy = date( "Y-m-d" );

$productos = $_SESSION['sing_prod_x_comp'];
$i = 1;

$departamento_txt = $_REQUEST['departamento'];
$provincia_txt = $_REQUEST['provincia'];
$distrito_txt = $_REQUEST['distrito'];
$dni_txt = $_REQUEST['dni'];
$direccion_txt = $_REQUEST['direccion'];
$referencia_txt = $_REQUEST['referencia'];
$nombre_txt = $_REQUEST['nombre'];
$apellidos_txt = $_REQUEST['apellidos'];
$email_ml = $_REQUEST['email'];
$celular_nmb = $_REQUEST['celular'];
$empresa_txt = $_REQUEST['empresa'];
$codigo = $_REQUEST['codigo'];
$total_cq = $_REQUEST['total'];

$total_mail = number_format(($total_cq/100),2);

$moneda = $_REQUEST['moneda'];

if ($moneda == "PEN"){
$simbolo = "PEN S/. ";
}elseif ($moneda == "USD"){
$simbolo = "USD $ ";
}

$mensaje_culqi = "COMPRA EXITOSO";

$query_nombre_distrito = "SELECT distrito FROM distritos WHERE idDist = $distrito_txt";
$nombre_distrito = obtener_linea( $query_nombre_distrito );
$nombre_dist = $nombre_distrito['distrito'];

$query_departamento = "select * from departamentos where id_departamentos = $departamento_txt";
$nombre_depa = obtener_linea( $query_departamento );
$nombredepa = $nombre_depa['nombre_departamento'];


$query_provincia = "select * from provincias where idProv = $provincia_txt";
$nombre_prov = obtener_linea( $query_provincia );
$nombreprov = $nombre_prov['provincia'];

	
$destinatario = "rbreva@gmail.com";
$asunto_comprador = "Gracias por tu compra en Singularity"; 
$asunto_vendedor = "Nuevo Pedido en Singularity"; 
$cuerpo = " 
<html>
<head>
	<title>Compras Singularity</title>
</head>
<body>
<br>
<table width='600' align='center' border='0' cellpadding='0' cellspacing='0'>
	<tbody>
		<tr>
			<td>
			<img alt='Singularity' src='https://www.limalocal.com/images/rep/logo_singularity_mail.jpg' style='display:block; border:0px;' />
			</td>
		</tr>
		<tr>
			<td>
			&nbsp;<br>
			</td>
		</tr>
		<tr>
			<td style='font-size:14px; color:#000; font-family:Arial, Helvetica, sans-serif; text-decoration:none; line-height:12px; -webkit-text-size-adjust:none' align='center'>
				<p><strong>Â¡Hola $nombre_compra Muchas gracias por tu compra!</strong></p><br>

				<table width='100%' border='1px' cellspacing='0'>
					<tr height='40px'>
						<td align='center' colspan='4'>
						<strong>Pedido: $codigo</strong>
						</td>
					</tr>
					
					<tr align='center'  height='16px'>
					<td> # </td>
					<td> Producto, Talla</td>
					<td> Cantidad </td>
					<td> Subtotal </td>
					</tr>
					
					";

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
				$cuerpo .=" 
				<tr align='center'>
					<td> $i </td>
					<td> $nombre_producto - $nombre_codigo - $nombre_color</td>
					<td> $row[cantidad] </td>
					<td> $simbolo $row[total] </td>
					</tr>";
				$i++;
			}

$cuerpo .="
<tr align='center'  height='16px'>
<td>&nbsp;</td>
<td>Costo de Envio</td>
<td></td>
<td>S/. $costo_envio</td>
</tr>
<tr>
<td align='center' colspan='4'>&nbsp;</td>
</tr>
<tr align='center'>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>Total: $simbolo $total_mail </td>
</tr>

<tr>
<td align='center' colspan='4'>&nbsp;</td>
</tr>

<tr  height='40px'>
<td align='center' colspan='4'><strong>Persona de Contacto</strong></td>
</tr>
<tr>
<td align='center' colspan='4'>
<br>
<p>Nombre: $nombre_compra </p>
<p>Email: $mail_compra </p>
<p>Celular: $celular_compra </p>
<p>Fijo: $fijo_compra </p>
<br>
</td>
</tr>
<tr  height='40px'>";
if ($envrec == "enviometod") {
	$cuerpo .="
<td align='center' colspan='4'><strong>Env&iacute;o: $zona_envio</strong></td>
</tr>
<tr>
<td align='center' colspan='4'>
<br>";
}elseif ($envrec == "recojometod") {
	$cuerpo .="
<td align='center' colspan='4'><strong>Metodo: Recojo en tienda</strong></td>
</tr>
<tr>
<td align='center' colspan='4'>
<br>";
}else{
	$cuerpo .="
<td align='center' colspan='4'><strong>Env&iacute;o: $zona_envio</strong></td>
</tr>
<tr>
<td align='center' colspan='4'>
<br>";
}
if ($envrec == "enviometod") {
	$cuerpo .="<p>Departamento: $departamento</p>
<p>Provincia: $provincia</p>
<p>Distrito: $nombre_dist</p>
<p>DNI: $dni</p>";

$cuerpo .="<p>Direcci&oacute;n: $direccion_envio </p>
<p>Referencia: $referencia_envio </p>
<br>
</td>
</tr>
</table>";
}elseif ($envrec == "recojometod") {
		$cuerpo .="
<p>DNI: $dni</p>";

$cuerpo .="
<br>
</td>
</tr>
</table>";
}else{
		$cuerpo .="<p>Departamento: $departamento</p>
<p>Provincia: $provincia</p>
<p>Distrito: $nombre_dist</p>
<p>DNI: $dni</p>";

$cuerpo .="<p>Direcci&oacute;n: $direccion_envio </p>
<p>Referencia: $referencia_envio </p>
<br>
</td>
</tr>
</table>";
}


$cuerpo .="
<br>
<br>
<br>
</td>
</tr>

<tr>
<td>&nbsp;<br></td>
</tr>

<tr bgcolor='#000000'>
<td style='font-size:14px; color:#FFF; font-family:Arial, Helvetica, sans-serif; text-decoration:none; line-height:12px; -webkit-text-size-adjust:none' align='center'>
<br>
<p>Mantente en contacto</p>
<br>
</td>
</tr>

</tbody>
</table>

<table align='center' border='0' cellpadding='0' cellspacing='0'>
    <tbody>
        <tr>
            <td style='font-size:10px; color:#000; font-family:Arial, Helvetica, sans-serif; text-decoration:none; line-height:12px; -webkit-text-size-adjust:none'>
            	<br>
                <p><font color='#333333'>Copyright &copy; 2021 Singularity, Todos los derechos reservados.</font></p>
                <br>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>";
// echo $cuerpo;
//para el envio en formato HTML 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

//direcciÃ³n del remitente 
$headers .= "From: Compras - limalocal.com <compras@limalocal.com>\r\n"; 

//direcciÃ³n de respuesta, si queremos que sea distinta que la del remitente 
$headers .= "Reply-To: compras@limalocal.com\r\n"; 

//ruta del mensaje desde origen a destino 
$headers .= "Return-path: compras@limalocal.com\r\n"; 

//direcciones que recibiÃ¡n copia 
//$headers .= "Cc: ".$mail_compra."\r\n"; 

//direcciones que recibirÃ¡n copia oculta 
$headers .= "Bcc: rbreva@gmail.com\r\n"; 


$descripcion = "";

/*----------------	
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
if ($envrec == "enviometod") {
	$tipo = "envio";
	//$query_agregar_pedido = "INSERT INTO pedidos (codigo_pedido, descripcion_pedido, moneda_pedido, total_pedido, fecha_pedido, nombre_pedido, email_pedido, celular_pedido,	fijo_pedido, envio_pedido, depa_pedido, prov_pedido, dist_pedido, dni_pedido, direccion_pedido, referencia_pedido, cupon_pedido, nota_pedido,metodo_pedido) VALUE ('$codigo', '$descripcion', '$simbolo', '$total_mail', '$hoy', '$nombre_compra', '$mail_compra', '$celular_compra', '$fijo_compra', '$zona_envio', '$departamento', '$provincia', '$nombre_dist', '$dni', '$direccion_envio', '$referencia_envio', '$cupon', '$nota','$tipo')";
//actualizar_registro($query_agregar_pedido);
}elseif ($envrec == "recojometod") {
	$tipo = "recojo";
	//$query_agregar_pedido = "INSERT INTO pedidos (codigo_pedido, descripcion_pedido, moneda_pedido, total_pedido, fecha_pedido, nombre_pedido, email_pedido,	celular_pedido,	fijo_pedido,dni_pedido, cupon_pedido, nota_pedido,id_tienda,metodo_pedido) VALUE ('$codigo', '$descripcion', '$simbolo', '$total_mail', '$hoy', '$nombre_compra', '$mail_compra', '$celular_compra', '$fijo_compra',  '$dni', '$cupon', '$nota','$tienda','$tipo')";
//actualizar_registro($query_agregar_pedido);
}


//desactivar nota de credito
if (isset($_SESSION["kby_nota_nombre"])){
 $query_anular_nota = "UPDATE notas_credito SET usado_nota = '1', fecha_uso_nota ='$hoy' WHERE codigo_nota = '$nota'";
 actualizar_registro($query_anular_nota);
}

//desactivar cupon cliente
if (isset($_SESSION["kby_coupon_cliente"])){
$id_cliente = $_SESSION["kby_coupon_cliente_id"];
 $query_anular_cupon_cliente = "UPDATE cupon_cliente SET usado_cupon_cliente = '1', fecha_uso_cupon_cliente ='$hoy' WHERE id_cliente = '$id_cliente'";
 actualizar_registro($query_anular_cupon_cliente);
}
---------------*/
//enviar mail
mail($mail_compra,$asunto_comprador,$cuerpo,$headers);
mail($destinatario,$asunto_vendedor,$cuerpo,$headers);

//eliminar stock
	/*----------------------
foreach ($productos as $row){
$id_producto = $row['id_producto'];
$id_codigo = $row['id_codigo'];
$cantidad = $row['cantidad'];
$query_actual_stock = "SELECT * FROM codigos WHERE id_codigo = '$id_codigo'";
$codigo_linea = obtener_linea($query_actual_stock);
$stock_actual = $codigo_linea['stock_codigo'];
$nuevo_stock = ($stock_actual-$cantidad);

$query_act_stock = "UPDATE codigos SET stock_codigo = '$nuevo_stock' WHERE id_codigo = '$id_codigo'";
actualizar_registro($query_act_stock);
}
-------------------*/
//eliminar sesiones
unset($_SESSION["sing_prod_x_comp"]);


$mensaje_exito = "<p>¡Listo! Te hemos enviado la constancia de tu compra al correo indicado.</p><p> ¡Gracias por tu compra!</p>";
?>
	
<div id="mensaje_de_izipay">
 
<!--<div id="salida"><?php //echo $mensaje_culqi."<br>"; ?></div>-->
	
<div id="salida"><?php echo $mensaje_exito."<br>"; ?></div>

	

<div id="regreso"><a href="https://limalocal.com/singularity/">SEGUIR COMPRANDO</a></div>

</div>
<?php
}
	
footer();
?>

