<div class="sesion">
<div class="titulo_sesion">Proceso de compra</div>
<?php
falso_pedido();
if (isset($_SESSION['singularity_email'])) {
	checkout();
} else {
	alerta();
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
$paso = "";
if(isset($_POST['paso'])){	
$paso = $_POST['paso'];
}
?>
<div class="pedido">
<div class="proceso">	
<?php
$email = $_SESSION['singularity_email'];
$query_cliente = "SELECT * FROM clientes WHERE email_cliente = '$email'";
$cliente = obtener_linea($query_cliente);
if (!$paso) {
	pasouno();
	pasodos($cliente);
	pasotres();
	pasocuatro();
} elseif ( $paso == "dos" ) {
	pasouno();
	pasodosfll();
	pasotresemp();
	pasocuatro();
} elseif ( $paso == "tres" ) {
	pasouno();
	pasodosfll();
	pasotresfll();
	pasocuatroemp();
} elseif ( $paso == "cuatro" ) {
	//pasouno();
}elseif($paso == "pago"){
	//pago();
}
?>
</div>
<?php
orden();	
?>
</div>
<?php

}	
}

function pasouno() {
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

function pasodos($cliente) {
$nombres_cliente = $cliente['nombres_cliente'];
$apellidos_cliente = $cliente['apellidos_cliente'];
$dni_cliente = $cliente['dni_cliente'];
$celular_cliente = $cliente['celular_cliente'];
$email_cliente = $cliente['email_cliente'];	
	
$query_distritos = "SELECT * FROM distritos WHERE idProv = '127'";
$distritos = obtener_todo($query_distritos);
$envio = "";
?>
<div class="paso">
	<div class="barra_titulo">
		<div class="icono"><img src="images/svg/dos.svg"></div>
		<div class="texto">Información Adicional</div>
	</div>
	<div id="check">
	<form action="checkout.php" enctype="multipart/form-data" method="post">
		<div class="linea">
			<div class="datos">Seleccione Destino:</div>
			<div class="item">
				<select type="text" name="envio_txt" id="select1" required>
					<option value="lima" >Lima Metropolitana</option>
					<option value="restoperu">Resto del Perú</option>
				</select>
				
			</div>
			<div id="detalles_peru">
			<div class="datos">Envío:</div>
			<div class="item">
			<input type="radio" name="metodod" value="enviometod" checked>
			</div>
			<div class="datos">Recojo:</div>
			<div class="item">
			<input type="radio" name="metodod" value="recojometod">
			</div>
			<script>
				$("input[name=metodod]").on("change",function(){
					if($(this).val() == "enviometod"){
					   $(".escrec").show();
						$("select[name=distrito_txt]").prop("required",true);
						$("input[name=direccion_txt]").prop("required",true);
						$("textarea[name=referencia_txa]").prop("required",true);
						$(".tnd").hide();
						$("select[name=txt_tienda]").prop("required",false);
					   }else if($(this).val() == "recojometod"){
						   $(".escrec").hide();
						   $("select[name=distrito_txt]").prop("required",false);
						   $("input[name=direccion_txt]").prop("required",false);
						   $("textarea[name=referencia_txa]").prop("required",false);
						   $(".tnd").show();
						   $("select[name=txt_tienda]").prop("required",true);
					   }
				})
			</script>
			<div class="escrec">
			
				<div class="datos">Distrito:</div>
				<div class="item">
					<select type="text" name="distrito_txt" required>
					<?php
					foreach ($distritos as $dist){
					echo "<option value='".$dist['idDist']."' ";
					if ($dist['idDist'] == $envio){
					echo " selected ";
					}
					echo ">".$dist['distrito']."</option>";
					}
					?>
					</select>
				</div>
				
			
<script language="JavaScript" type="text/JavaScript">
    $(document).ready(function(){
        $("#select1").change(function(event){
            var id = $("#select1").find(':selected').val();
            $("#detalles_peru").load('genera-datos.php?id='+id);
        });
    });
</script>
		
							
		<div class="linea">
			<div class="datos">Dirección de Envío:</div>
			<div class="item">
				<input type="text" name="direccion_txt" value="" required>
			</div>
		</div>
							
		<div class="linea">
			<div class="datos">Referencia: </div>
			<div class="item">
				<textarea name="referencia_txa" required></textarea>
			</div>
		</div>
		</div>
				</div>
			<div class="tnd" style="display: none">
				<?php 
					$query_tienda = "SELECT * FROM tiendas where estado_tienda = 1";
					$tienda = obtener_todo($query_tienda);
				?>
			<div class="linea">
			<div class="datos">Tiendas: </div>
			<div class="item">
				<select name="txt_tienda" required>
					<?php 
						foreach($tienda as $itemtienda){
						
					?>
				<option value="<?php echo $itemtienda['id_tienda'] ?>"><?php echo $itemtienda['nombre_tienda'] ?></option>
					<?php 
	
						}
					?>
				</select>
			</div>
		</div>
			</div>
		<div class="linea">
			<div class="datos">Nombre: </div>
			<div class="item">
				<input type="text" name="nombre_txt" value="<?php echo $cliente['nombres_cliente'] ?>" required>
			</div>
		</div>
		<div class="linea">
			<div class="datos">Apellidos: </div>
			<div class="item">
				<input type="text" name="apellidos_txt" value="<?php echo $cliente['apellidos_cliente'] ?>" required>
			</div>
		</div>		
		<div class="linea">
			<div class="datos">DNI:</div>
				<div class="item"><input type="text" name="dni_txt" value="<?php echo $cliente['correo_promociones'] ?>" placeholder="DNI" required></div>	
		</div>						
		<div class="linea">
			<div class="datos">Email:</div>
			<div class="item">
				<?php
				$mail_cliente = $cliente['email_cliente'];
				if ( !$mail_cliente ) {
					$mail_cliente = substr( $_SESSION[ 'singularity_email' ], 9 );
				}
				echo $mail_cliente;
				?>
				<input type="hidden" name="mail_txt" value="<?php echo $mail_cliente  ?>">
			</div>
		</div>
							
		<div class="linea">
			<div class="datos">Celular:</div>
			<div class="item">
				<input type="text" name="celular_txt" value="<?php echo $cliente['celular_cliente'] ?>" required>
			</div>
		</div>	
							
		<div class="linea">
			<div class="datos">Fijo:</div>
			<div class="item">
				<input type="text" name="fijo_txt" value="<?php echo $cliente['fijo_cliente'] ?>">
			</div>
		</div>

		<div id="proceder">
			<input name="paso" value="dos" type="hidden">
			<input type="submit" value="Continuar" class="button">
		</div>

	</form>
	</div>
</div>
</div>	
<?php
}

function pasodosfll(){

include("includes/datos_envio.php");	

$act_cli = "UPDATE clientes SET nombres_cliente = '$nombre', apellidos_cliente = '$apellidos', celular_cliente = '$celular', fijo_cliente = '$fijo', correo_promociones = '$dni' WHERE email_cliente = '$mail'";
actualizar_registro($act_cli);
	
?>
<div id="dos">
	<div class="titulo"><img src="images/svg/check.svg" width="30px"> Envío</div>
	<div id="check">
					
		<div class="linea">
			<div class="datos">Zona:</div>
			<div class="item"><?php echo $nombre_zona ?></div>
		</div>
					
		<?php
		if ($zona == "lima"){
		$query_distrito = "SELECT * FROM distritos WHERE idDist = '$distrito'";
		$distrito = obtener_linea($query_distrito);
		$nombre_distrito = $distrito['distrito'];
			if($envrec == "enviometod"){
			
		?>
		<div class="linea">
			<div class="datos">Metodo:</div>
			<div class="item"> Envío</div>
		</div>
		<div class="linea">
			<div class="datos">Distrito:</div>
			<div class="item"><?php echo $nombre_distrito ?></div>
		</div>
					
		<div class="linea">
			<div class="datos">Dirección de Envío:</div>
			<div class="item"><?php echo $direccion ?></div>
		</div>
							
		<div class="linea">
			<div class="datos">Referencia: </div>
			<div class="item"><?php echo $referencia ?></div>
		</div>
					
		<?php
		}else{
				$tienda_s = "SELECT * FROM tiendas where id_tienda = '$tienda'";
				$obtnt = obtener_linea($tienda_s);
				?>
		<div class="linea">
			<div class="datos">Metodo:</div>
			<div class="item"> Recojo</div>
		</div>
		<div class="linea">
			<div class="datos">Tienda de Recojo:</div>
			<div class="item"> <?php echo $obtnt['nombre_tienda'] ?></div>
		</div>
		<?php
			}
		}
		
		if ($zona == "restoperu"){
			$query_dep ="select * from departamentos where id_departamentos = '$departamento'";
			$obtn_dep = obtener_linea($query_dep);
			$query_prov ="select * from provincias where idProv = '$provincia'";
			$obtn_prov = obtener_linea($query_prov);
			$query_dis ="select * from distritos where idDist = '$distrito'";
			$obtn_dis = obtener_linea($query_dis);
		?>
		<div class="linea">
			<div class="datos">Departamento:</div>
			<div class="item"><?php echo $obtn_dep['nombre_departamento'] ?></div>
		</div>
					
		<div class="linea">
			<div class="datos">Provincia:</div>
			<div class="item"><?php echo $obtn_prov['provincia'] ?></div>
		</div>
					
		<div class="linea">
			<div class="datos">Distrito:</div>
			<div class="item"><?php echo $obtn_dis['distrito'] ?></div>
		</div>

		<div class="linea">
			<div class="datos">Dirección de Envío:</div>
			<div class="item"><?php echo $direccion ?></div>
		</div>
							
		<div class="linea">
			<div class="datos">Referencia: </div>
			<div class="item"><?php echo $referencia ?></div>
		</div>
		
		<?php
		}
		
		?>
							
		<div class="linea">
			<div class="datos">Nombre:</div>
			<div class="item"><?php echo $nombre ?></div>
		</div>
		<div class="linea">
			<div class="datos">Apellidos:</div>
			<div class="item"><?php echo $apellidos ?></div>
		</div>
		<div class="linea">
			<div class="datos">DNI:</div>
			<div class="item"><?php echo $dni ?></div>
		</div>				
		<div class="linea">
			<div class="datos">Email:</div>
			<div class="item"><?php echo $mail ?></div>
		</div>
						
		<div class="linea">
			<div class="datos">Celular:</div>
			<div class="item"><?php echo $celular ?></div>
		</div>	
					
		<div class="linea">
			<div class="datos">Fijo:</div>
			<div class="item"><?php echo $fijo ?></div>
		</div>
						
		<div id="proceder_boton">
			</span><a href="checkout.php" id="button">Cambiar datos de envío</a>
		</div>

	</div>
</div>


<?php
}

function pasotres(){
?>
<div id="tres">
	<div class="titulo"><img src="images/svg/tres.svg" width="30px">Cupones o Notas de Crédito</div>
</div>
<?php
}

function pasotresemp(){
	
include("includes/datos_envio.php");
	
$cupon_nota = "";
if(isset($_POST['cupon_nota'])){	
$cupon_nota = $_POST['cupon_nota'];
}
$cupon_nota = strtoupper($cupon_nota);
?>
<div id="tres">
	<div class="titulo"><img src="images/svg/tres.svg" width="30px">Cupones o Notas de Crédito</div>
	<?php
	if($cupon_nota){
		cupon_nota();
	}else{
		cupon_ingresar();
	}
	
	?>
	<div id="cupon">
		<p></p>
		<form action="checkout.php" enctype="multipart/form-data" method="post">
		<div id="proceder">
			<input name="paso" value="tres" type="hidden">
			<input name="envio_txt" value="<?php echo $zona ?>" type="hidden">
			<input name="metodod" value="<?php echo $envrec ?>" type="hidden">
			<input name="txt_tienda" value="<?php echo $tienda ?>" type="hidden">
			<input name="departamento_txt" value="<?php echo $departamento ?>" type="hidden">
			<input name="provincia_txt" value="<?php echo $provincia ?>" type="hidden">
			<input name="distrito_txt" value="<?php echo $distrito ?>" type="hidden">
			<input name="dni_txt" value="<?php echo $dni ?>" type="hidden">
			<input name="direccion_txt" value="<?php echo $direccion ?>" type="hidden">
			<input name="referencia_txa" value="<?php echo $referencia ?>" type="hidden">
			<input name="nombre_txt" value="<?php echo $nombre ?>" type="hidden">
			<input name="apellidos_txt" value="<?php echo $apellidos ?>" type="hidden">
			<input name="mail_txt" value="<?php echo $mail ?>" type="hidden">
			<input name="celular_txt" value="<?php echo $celular ?>" type="hidden">
			<input name="fijo_txt" value="<?php echo $fijo ?>" type="hidden">
			<input type="submit" value="Continuar" class="button">
		</div>
		</form>
	</div>
</div>
<?php

}

function cupon_ingresar(){

if (isset($_SESSION['kby_coupon'])){
?>
<div id="cupon">
	<p>¡Tu cupón se encuentra validado!</p>
</div>
<?php
}elseif(isset($_SESSION['kby_nota_credito'])){
?>
<div id="cupon">
	<p>¡Tu Nota de crédito se encuentra Activa!</p>
</div>
<?php
}else{

include("includes/datos_envio.php");
	  
?>
<div id="cupon">
	<p>¡Si tienes un <span id="resalte">cupón</span> o una <span id="resalte">nota de crédito</span>, este es el momento!</p>
	<form action="checkout.php" enctype="multipart/form-data" method="post">
		<input class="cuponnota" name="cupon_nota" type="text" required>
	<div id="proceder">
		<input name="paso" value="dos" type="hidden">
			<input name="envio_txt" value="<?php echo $zona ?>" type="hidden">
			<input name="metodod" value="<?php echo $envrec ?>" type="hidden">
			<input name="txt_tienda" value="<?php echo $tienda ?>" type="hidden">
			<input name="departamento_txt" value="<?php echo $departamento ?>" type="hidden">
			<input name="provincia_txt" value="<?php echo $provincia ?>" type="hidden">
			<input name="distrito_txt" value="<?php echo $distrito ?>" type="hidden">
			<input name="dni_txt" value="<?php echo $dni ?>" type="hidden">
			<input name="direccion_txt" value="<?php echo $direccion ?>" type="hidden">
			<input name="referencia_txa" value="<?php echo $referencia ?>" type="hidden">
			<input name="nombre_txt" value="<?php echo $nombre ?>" type="hidden">
			<input name="apellidos_txt" value="<?php echo $apellidos ?>" type="hidden">
			<input name="mail_txt" value="<?php echo $mail ?>" type="hidden">
			<input name="celular_txt" value="<?php echo $celular ?>" type="hidden">
			<input name="fijo_txt" value="<?php echo $fijo ?>" type="hidden">
		<input type="submit" value="Validar" class="button">
	</div>
	</form>
</div>
<?php
}
}

function pasocuatro(){
?>
<div id="cuatro">
	<div class="titulo"><img src="images/svg/cuatro.svg" width="30px">Método de Pago</div>
</div>
<?php
}

function orden() {

// fin monto y envio
//print_r(productos);

include("includes/datos_envio.php");		

?>
<div id="orden">
	<div id="cuadro">
		<div id="prendas">
			<div id="cabecera_prendas">Sumario</div>
			<?php
$lista_array = array();
if (isset($_SESSION['kebeya_coupon'])){
$lista_array = explode(',',$_SESSION['kebeya_sec_coupon']);
}
	
foreach ($productos as $row){
	$id_prod = $row['id_producto'];
	$query_seccion = "SELECT * FROM productos WHERE id_producto = '$id_prod'";
	$src_seccion_producto = obtener_linea($query_seccion);
	$idproducto = $row['id_producto'];
	$query_producto = "SELECT * FROM productos WHERE id_producto = '$idproducto'";
	$producto = obtener_linea($query_producto);
	$idcodigo = $row['id_codigo'];
	$query_codigo = "SELECT * FROM codigos WHERE id_codigo = '$idcodigo'";
	$codigo = obtener_linea($query_codigo);
	$idcolor = $row['id_color'];
	$query_color = "SELECT * FROM colores WHERE id_color = '$idcolor'";
	$color = obtener_linea($query_color);
	$query_fotos = "SELECT * FROM fotos WHERE id_color = '$idcolor' AND activo_foto = '1' ORDER BY orden_foto";
	$fotos = obtener_todo($query_fotos);
	$foto = $fotos[0]['nombre_foto'];
	if(!$foto){
		$query_fotos_nohay = "SELECT * FROM fotos WHERE id_producto = '$idproducto' AND activo_foto = 1";
		$fotos_nohay = obtener_todo($query_fotos_nohay);
		$foto = $fotos_nohay[0]['nombre_foto'];
	}
	if (in_array($src_seccion_producto['id_seccion'], $lista_array)){
?>
<div class="prod_sel">
	<div class="imagen"><img src="images/productos/thumb/<?php echo $foto ?>"></div>
	<div class="datos">
	<?php
	//print_r($color);
	//echo $fotos[0]['nombre_foto']
	$porcentaje_desc = number_format(($codigo['precio_codigo']*$_SESSION['kebeya_coupon'])/100, 2, '.', '');
	$precio_final = number_format(($codigo['precio_codigo']-$porcentaje_desc), 2, '.', '');
	?>
	<div class="nombre"><?php echo $producto['nombre_producto'] ?></div>
	<div class="precio_uni tachado">Precio: S/. <?php echo $codigo['precio_codigo']; ?></div>
	<div class="precio_uni">Precio con cupón: S/. <?php echo $precio_final; ?></div>
	<div class="talla">Talla:
	<?php echo $codigo['talla_codigo'] ?>
	</div>
	<?php
	if ($color){
	?>
	<div class="talla">Color:
	<?php echo $color['nombre_color'] ?>
	</div>
	<?php
	}
	?>
	</div>
</div>
<?php
}else{
?>
<div class="prod_sel">
	<div class="imagen"><img src="images/productos/thumb/<?php echo $foto ?>"></div>
	<div class="datos">
	<?php
	//print_r($color);
	//echo $fotos[0]['nombre_foto']
	?>
	<div class="nombre"><?php echo $producto['nombre_producto'] ?></div>
	<?php
	if ($codigo['oferta_codigo'] == 0){
	$precio_final = number_format(($codigo['precio_codigo']), 2, '.', '');	
	?>
	<div class="precio_uni">Precio: S/. <?php echo $codigo['precio_codigo']; ?></div>	
	<?php
	}elseif($codigo['oferta_codigo'] == 1){
	$precio_final = number_format(($codigo['precio_oferta_codigo']), 2, '.', '');
	?>
	<div class="precio_uni tachado">Precio: S/. <?php echo $codigo['precio_codigo']; ?></div>
	<div class="precio_uni">Precio oferta: S/. <?php echo $codigo['precio_oferta_codigo']; ?></div>	
	<?php
	}		
	?>	
	
	<div class="talla">Talla:
	<?php echo $codigo['talla_codigo'] ?>
	</div>
	<?php
	if ($color){
	?>
	<div class="talla">Color:
	<?php echo $color['nombre_color'] ?>
	</div>
	<?php
	}
	?>
	</div>
</div>	
<?php
}
}
?>			
			
		</div>			
	</div>
	<div id="pedido">
	<?php
	if (isset($costo_envio)){
		if($costo_envio == "FALTA DEFINIR"){
		?>
		<div id="envio">Costo de envío: <?php echo $costo_envio ?></div>
		<?php	
		}else{	
		?>
		<div id="envio">Costo de envío: S/. <?php echo number_format($costo_envio, 2, '.', '') ?></div>
		<?php
		}
	}
		?>
		<div
			<?php
			if(isset($_SESSION['kby_coupon'])){
			echo "id='total_cupon'";
			}else{
				if(isset($_SESSION["kby_nota_credito"])){
				echo "id='total_cupon'";
				}else{
				echo "id='total'";
				}
			}
			if($costo_envio == "FALTA DEFINIR"){
				$costo_envio = 0;
			}
			?>
		>Total: S/. <?php echo number_format (($total), 2, '.', ''); ?>
		</div>
		<?php
		if(isset($_SESSION['kby_nota_credito'])){
			$total = $total+$costo_envio;
			$credito = $_SESSION['kby_nota_credito'];
			$total_nota = $total-$credito;
			if ($total_nota < 10){
				$total_nota = 10;
			}
			$credito_mod = "S/".$credito; 
		?>
		<div id="aclara">Nota de Crédito: <?php echo $credito_mod; ?></div>
		<div id="total">Total: S/. <?php echo number_format (($total_nota), 2, '.', '') ?>
		</div>
		<?php
		}
		?>			
		</div>
	
</div>



<?php
}

function pasotresfll(){
?>
<div id="tres">
	<div class="titulo"><img src="images/svg/check.svg" width="30x">Cupones o Notas de Crédito</div>
	<?php
	if (isset($_SESSION['kebeya_coupon'])){
	?>
	<div id="mensaje">Cupón Activo</div>
	<?php
	}elseif(isset($_SESSION['kby_nota_credito'])){
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
<?php
}

function datos_envio($envrec, $zona, $nombre_zona, $departamento, $provincia, $distrito, $direccion, $referencia, $nombre, $apellidos, $dni, $mail, $celular, $fijo, $tienda, $costo_envio, $paso){
echo "envrec: ".$envrec."<br>";	
echo "zona: ".$zona."<br>";		
echo "nombre_zona: ".$nombre_zona."<br>";		
echo "departamento: ".$departamento."<br>";		
echo "provincia: ".$provincia."<br>";		
echo "distrito: ".$distrito."<br>";		
echo "direccion: ".$direccion."<br>";		
echo "referencia: ".$referencia."<br>";		
echo "nombre: ".$nombre."<br>";		
echo "apellidos: ".$apellidos."<br>";		
echo "dni: ".$dni."<br>";		
echo "mail: ".$mail."<br>";		
echo "celular: ".$celular."<br>";		
echo "fijo: ".$fijo."<br>";		
echo "tienda: ".$tienda."<br>";		
echo "costo_envio: ".$costo_envio."<br>";	
echo "paso: ".$paso."<br>";	
}

function pasocuatroemp(){
	
include("includes/datos_envio.php");	
	
?>
<div id="cuatro">
	<div class="titulo"><img src="images/svg/cuatro.svg" width="30px">Medios de Pago:
	</div>
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
		?>
	<br>
	
	<div class="datos">
		<div id="frmmp">
			<?php
				formulario_izipay();
			?>
		</div>
		<div id="frmtr">
			<?php
				formulario_tranferencia();
			?>
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
			   }else if(des == "transferencia"){
				 $("#frmmp").hide();
				 $("#frmtr").show("slow"); 
			   }
		})
	})
	</script>


	
</div>
<?php
}

function formulario_tranferencia(){
$query_tarjeta = "SELECT * FROM detalle_tarjeta where estado_tarjeta = 1";
$tarjeta = obtener_todo($query_tarjeta);
	
include("includes/datos_envio.php");
$codigo = "KBFT".date("ymdHis");	
	
$total_cq = $total*100;
	
?>
<div id="check">
<form action="checkout.php" method="post" id="frmenviod">
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
		<?php
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
	<input type="hidden" value="<?php echo $apellidos ?>" name="apellidos">
	<input type="hidden" value="<?php echo $mail ?>" name="email">
	<input type="hidden" value="<?php echo $celular ?>" name="celular">
	<input type="hidden" value="<?php echo $tienda ?>" name="tienda">
	<input type="hidden" value="<?php echo $fijo ?>" name="fijo">
	<input type="hidden" value="<?php echo $codigo ?>" name="codigo">
	<input type="hidden" value="<?php echo $total_cq ?>" name="total">
	<input type="hidden" value="<?php echo $costo_envio ?>" name="costo_envio">
	<input type="hidden" value="<?php echo $estado_pedido ?>" name="estado_envio">
	<input type="hidden" value="<?php echo "PEN" ?>" name="moneda">
	<input type="hidden" value="<?php echo $envrec ?>" name="metodo">


	<input class="btn_transferencia" type="submit" value="Confirmar Pedido" id="pagar2"/>
</form>
	<label><input type="checkbox" id="checkbtn2"> Acepto los <a href="cambios.php" target="_blank">Términos y Condiciones</a> y la <a href="cambios.php" target="_blank">Política de Protección de CAmbios y Devoluciones</a>. </label><br>
	<label class="rspterminos2" style="display: none">*Tienes que aceptar los Términos y Condiciones y la Política de Protección de Datos Personales para finalizar tu compra*</label>
</div>
<script>
	
$(document).ready(function(){
	$('#pagar2').removeAttr("type");
		$('#pagar2').attr("type","button");
		$("#checkbtn2").on("change",function(){
			if ($(this).is(':checked')) {
				$('#pagar2').attr("type", "submit");
				$(".rspterminos2").hide();
			} else {
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
					"tienda": "<?php echo $tienda ?>",
					"metodo": "<?php echo $envrec ?>",
					"departamento": "<?php echo $departamento ?>",
					"provincia": "<?php echo $provincia ?>",
					"distrito": "<?php echo $distrito ?>",
					"dni": "<?php echo $dni ?>",
					"direccion": "<?php echo $direccion ?>",
					"referencia": "<?php echo $referencia ?>",
					"nombre": "<?php echo $nombre ?>",
					"apellidos": "<?php echo $apellidos ?>",
					"email": "<?php echo $mail ?>",
					"celular": "<?php echo $celular ?>",
					"fijo": "<?php echo $fijo ?>",
					"codigo": "<?php echo $codigo ?>",
					"total": "<?php echo $total_cq ?>",
					"moneda": "PEN",
					"costo_envio" : "<?php echo $costo_envio ?>",
				};
		  //console.log(parametros);
		$("#frmenviod").on("submit",function(e){
			e.preventDefault();
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
			
		
          <img src="images/rep/logo_kebeya_mail.jpg" class="img-rounded" width="100%" height="auto" >
      
          <p class="rpstventa"></p>
        </div>

      </div>
    </div>
  </div>
<!---->
<?php
}

function formulario_izipay(){
include("includes/datos_envio.php");
$codigo = "KBFizi".date("ymdHis");	
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
  "email" => $mail
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

?>
<!-- Javascript library. Should be loaded in head section -->
  <script 
   src="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
   kr-public-key="<?php echo $client->getPublicKey();?>"
   kr-post-url-success="paginaexito_izi.php?zona=<?php echo $zona ?>&tienda=<?php echo $tienda ?>&metodo=<?php echo $envrec ?>&departamento=<?php echo $departamento ?>&provincia=<?php echo $provincia ?>&distrito=<?php echo $distrito ?>&dni=<?php echo $dni ?>&direccion=<?php echo $direccion ?>&referencia=<?php echo $referencia ?>&nombre=<?php echo $nombre ?>&email=<?php echo $mail ?>&celular=<?php echo $celular ?>&fijo=<?php echo $fijo ?>&codigo=<?php echo $codigo ?>&total=<?php echo $total_cq ?>&moneda=PEN&costo_envio=<?php echo $costo_envio ?>">
  </script>

  <!-- theme and plugins. should be loaded after the javascript library -->
  <!-- not mandatory but helps to have a nice payment form out of the box -->
  <link rel="stylesheet" 
   href="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/ext/classic-reset.css">
  <script 
   src="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/ext/classic.js">
  </script>

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
		
<!--		<div class="pagculqi"><button  type="button" class="buyButton">Pagar Ahora</button></div>-->
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

function formulario_culqi(){
include("includes/datos_envio.php");
$codigo = "KBF".date("ymdHis");	
$total_cq = $total*100; 	
?>
<div class="linea">
			<div class="todas">Aceptamos las siguientes tarjetas:</div>
			<div class="tarjetas">
				<ul>
					<li><button  type="button" class="buyButton"><img src="images/tarjetas/visa.png" alt="Culqi visa"></button></li>
					<li><button  type="button" class="buyButton"><img src="images/tarjetas/mc.png" alt="Culqi Master Card"></button></li>
					<li><button  type="button" class="buyButton"><img src="images/tarjetas/diners.png" alt="Culqi Diners Club"></button></li>
					<li><button  type="button" class="buyButton"><img src="images/tarjetas/amex.png" alt="Culqi American Express"></button></li>
				</ul>
			</div>
</div>
		
<div class="pagculqi"><button  type="button" class="buyButton">Pagar Ahora</button></div>

<script>
$('.buyButton').on('click', function(e) {
    // Abre el formulario con la configuración en Culqi.settings
    Culqi.open();
    e.preventDefault();
});
</script>
<script>
Culqi.publicKey = 'pk_live_tLodkIJ0LxIMnpVF';
// Culqi.publicKey = 'pk_test_T4IjnXkydaLt9uoD';
Culqi.settings({
title: 'Kebeya Fashion',
currency: 'PEN',
description: 'Pedido N: <?php echo $codigo ?>',
amount: '<?php echo $total_cq ?>',
email: '<?php echo $mail ?>'
});
Culqi.options({
    modal: true,
	installments: true,		
    style: {
      logo: 'https://www.kebeyafashion.com/images/rep/logo_kebeya_culqi.jpg',
      maincolor: '#ca5037',
      buttontext: '#ffffff',
      maintext: '#4A4A4A',
      desctext: '#4A4A4A'
    }
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
		var cuotas = Culqi.token.metadata.installments;
		
		var codigo = "<?php echo $codigo ?>";
		var total = "<?php echo $total_cq ?>";
		var moneda = "PEN";
		
		var data = {codigo:codigo,total:total,token:token,email:email,moneda:moneda,cuotas:cuotas};
		var url = "proceso.php";
		$.post(url,data,function(res){
		
		//if (res){
			//alert(res);
			
			var contiene = res.indexOf("charge_id");
			//console.log();
			if (contiene >= 0){ // Es un error
				//console.log("asdasd");
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
					"tienda": "<?php echo $tienda ?>",
					"metodo": "<?php echo $envrec ?>",
					"departamento": "<?php echo $departamento ?>",
					"provincia": "<?php echo $provincia ?>",
					"distrito": "<?php echo $distrito ?>",
					"dni": "<?php echo $dni ?>",
					"direccion": "<?php echo $direccion ?>",
					"referencia": "<?php echo $referencia ?>",
					"nombre": "<?php echo $nombre ?>",
					"email": "<?php echo $mail ?>",
					"celular": "<?php echo $celular ?>",
					"fijo": "<?php echo $fijo ?>",
					"codigo": "<?php echo $codigo ?>",
					"total": "<?php echo $total_cq ?>",
					"moneda": "PEN",
					"costo_envio" : "<?php echo $costo_envio ?>",
					"mensaje_culqi": datos["outcome"]["user_message"],
				};
				
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

<?php
}

function cupon_nota(){
$cupon_nota = $_POST['cupon_nota'];
$query_cupon = "SELECT * FROM cupones_descuento WHERE codigo_cupon = '$cupon_nota'";
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

function cupon_verificado($cupon_existe){
date_default_timezone_set('America/Lima');
if($cupon_existe['activo_cupon'] == 1){
	$ahora = new DateTime();
	$datetime1 = new DateTime($cupon_existe['fecha_inicio_cupon'].$cupon_existe['hora_inicio_cupon']); 
	$datetime2   = new DateTime($cupon_existe['fecha_cierre_cupon'].$cupon_existe['hora_cierre_cupon']); 
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
			$lista_array = explode(',',$cupon_existe['secciones_cupon']);
			
			//arreglo
			
			$_SESSION['kebeya_coupon'] = $cupon_existe['porcentaje_cupon'];
			$_SESSION['kebeya_sec_coupon'] = $cupon_existe['secciones_cupon'];
			$_SESSION['kebeya_coupon_nombre'] = $cupon_existe['id_cupon'];
			
			?>
			<div id="mensaje">
				<div class="lineauno">El cupón ingresado es válido y se encuentra vigente.</div>
				<div class="lineados">Aplica a las siguientes prendas seleccionadas:</div>
			</div>
			<?php
			$i = 0;
			foreach ($productos as $row){
				$id_prod = $row['id_producto'];
				$query_seccion = "SELECT * FROM productos WHERE id_producto = '$id_prod'";
				$src_seccion_producto = obtener_linea($query_seccion);
				if (in_array($src_seccion_producto['id_seccion'], $lista_array)){
				$idproducto = $row['id_producto'];
					$query_producto = "SELECT * FROM productos WHERE id_producto = '$idproducto'";
					$producto = obtener_linea($query_producto);
				$idcodigo = $row['id_codigo'];
					$query_codigo = "SELECT * FROM codigos WHERE id_codigo = '$idcodigo'";
					$codigo = obtener_linea($query_codigo);
				$idcolor = $row['id_color'];
					$query_color = "SELECT * FROM colores WHERE id_color = '$idcolor'";
					$color = obtener_linea($query_color);
					$query_fotos = "SELECT * FROM fotos WHERE id_color = '$idcolor' AND activo_foto = '1' ORDER BY orden_foto";
					$fotos = obtener_todo($query_fotos);
				$foto = $fotos[0]['nombre_foto'];
				if(!$foto){
					$query_fotos_nohay = "SELECT * FROM fotos WHERE id_producto = '$idproducto' AND activo_foto = 1";
					$fotos_nohay = obtener_todo($query_fotos_nohay);
					$foto = $fotos_nohay[0]['nombre_foto'];
				}
				$_SESSION['kebeya_coupon_lista'][$i] = array('idcodigo' => $idcodigo);
				
				$_SESSION["timeout"] = time();	
					?>
					<div class="prod_sel">
						<div class="imagen"><img src="images/productos/thumb/<?php echo $foto ?>"></div>
						<div class="datos">
						<?php
							//print_r($color);
							//echo $fotos[0]['nombre_foto']
							$porcentaje_desc = number_format(($codigo['precio_codigo']*$_SESSION['kebeya_coupon'])/100, 2, '.', '');
							$precio_final = number_format(($codigo['precio_codigo']-$porcentaje_desc), 2, '.', '');
						?>
						<div class="nombre"><?php echo $producto['nombre_producto'] ?></div>
							<div class="precio_uni tachado">Precio: S/. <?php echo $codigo['precio_codigo']; ?></div>
							<div class="precio_uni">Precio con cupón: S/. <?php echo $precio_final; ?></div>
							<div class="talla">Talla:
								<?php echo $codigo['talla_codigo'] ?>
							</div>
							<?php
							if ($color){
							?>
							<div class="talla">Color:
							<?php echo $color['nombre_color'] ?>
							</div>
							<?php
							}
							?>
						</div>
					</div>
					<?php
					$i++;
				}
			}
			if($i == 0){
				?>
				<div id="mensaje">No se encuentran prendas selecciondas que apliquen al descuento</div>
				<?php
			}else{
				?>
				<div id="mensaje">Productos que aplican al descuento: <?php echo $i ?></div>
				<?php
			}
			//finarreglo
			
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

function nota_verificada($nota_existe){
$_SESSION['kby_nota_credito'] = $nota_existe['monto_soles'];
$_SESSION['kby_nota_nombre'] = $nota_existe['codigo_nota'];
$signo = "S/.";
$nota = $nota_existe['monto_soles'];
?>
<div id="mensaje">Felicidades su nota de Crédito por <?php echo $signo." ".$nota; ?> se ha activado</div>
<?php
}

function roundToTheNearestAnything($value, $roundTo){
    $mod = $value%$roundTo;
    return $value+($mod<($roundTo/2)?-$mod:$roundTo-$mod);
}

?>
