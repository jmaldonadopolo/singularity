<?


$id_temp = "";
$id_producto = "";

if (isset($_POST['id_producto'])){
$id_producto = $_POST['id_producto'];
$id_codigo = $_POST['txt_talla'];
$qty = $_POST['qty_num'];
$id_color = $_POST['txt_color'];	

$query_codigo = "SELECT * FROM codigos WHERE id_codigo = '$id_codigo'";
$codigo = obtener_linea($query_codigo);

$id_temp = $id_producto.$id_codigo.$id_color;

$estado_oferta = $codigo['oferta_codigo'];
	if ($estado_oferta == 0){
	   $precio_producto = $codigo['precio_codigo'];	
	}else{
	   $precio_producto = $codigo['precio_oferta_codigo'];	
	}
}

$agregar = "";
if (isset($_POST['agregar'])) { 
  $agregar = $_POST['agregar']; 
} 

$quitar = "";
if (isset($_POST['quitar'])) { 
  $quitar = $_POST['quitar']; 
} 


$borrar = "";
if (isset($_POST['borrar'])) { 
  $borrar = $_POST['borrar']; 
} 

if ($borrar) {
	unset($_SESSION["sing_prod_x_comp"][$borrar]);
}


if ($agregar) {
	$_SESSION['sing_prod_x_comp'][$agregar]['cantidad']++;
	$_SESSION['sing_prod_x_comp'][$agregar]['total'] = number_format((($_SESSION['sing_prod_x_comp'][$agregar]['precio_producto'])*($_SESSION['sing_prod_x_comp'][$agregar]['cantidad'])), 2, '.', '');
}

if ($quitar) {
	if (($_SESSION['sing_prod_x_comp'][$quitar]['cantidad']) == 1 ){
		unset($_SESSION["sing_prod_x_comp"][$quitar]);
	}else{
		$_SESSION['sing_prod_x_comp'][$quitar]['cantidad']--;
		$_SESSION['sing_prod_x_comp'][$quitar]['total'] = number_format((($_SESSION['sing_prod_x_comp'][$quitar]['precio_producto']) * ($_SESSION['sing_prod_x_comp'][$quitar]['cantidad'])), 2, '.', '');
	}
}

if(isset($_SESSION['sing_prod_x_comp'])){
	if (count($_SESSION['sing_prod_x_comp']) == 0 ) {
		unset($_SESSION['sing_prod_x_comp']);
	}
}

//print_r($_SESSION['sing_prod_x_comp']);
?>
<div id="sesion">
	<div id="titulo">Mis Compras</div>
	<?php
	if (!isset($_SESSION['sing_prod_x_comp'])){
        if (!$id_temp) {
            cesta_productos();
        }else{
            $_SESSION['sing_prod_x_comp'][$id_temp] = array(
                "producto" => $id_producto,
                "codigo" => $id_codigo,
				"precio_producto" => $precio_producto,
                "cantidad" => $qty,
				"color" => $id_color,
                "total" => number_format(($precio_producto*$qty), 2, '.', ''));
            cesta_productos();
        }
    }else{
        if (!$id_producto) {
            cesta_productos();
        }else{
            if ($_SESSION['sing_prod_x_comp'][$id_temp]) {
                $_SESSION['sing_prod_x_comp'][$id_temp]['cantidad']++;
                $_SESSION['sing_prod_x_comp'][$id_temp]['total'] = (($_SESSION['sing_prod_x_comp'][$id_temp]['precio_producto']) * ($_SESSION['sing_prod_x_comp'][$id_temp]['cantidad']));
                cesta_productos();
            }else{
                $_SESSION['sing_prod_x_comp'][$id_temp] = array(
					"producto" => $id_producto,
					"codigo" => $id_codigo,
					"precio_producto" => $precio_producto,
					"cantidad" => $qty,
					"color" => $id_color,
					"total" => number_format(($precio_producto*$qty), 2, '.', ''));
                cesta_productos();
            }
        }
    }
?>
</div>

<?php

function cesta_productos() {
$productos = "";
if (isset($_SESSION["sing_prod_x_comp"])){
    $productos = $_SESSION['sing_prod_x_comp'];
    //$sobrestock = "";
    foreach ($productos as $row){
        $idproducto = $row['producto'];
		$idcodigo = $row['codigo'];
		$precio_producto = $row['precio_producto'];
        $cantidad = $row['cantidad'];
		$id_color = $row['color'];
		$query_comp = "SELECT stock_codigo FROM codigos WHERE id_codigo = '$idcodigo'";
		$stock = obtener_linea($query_comp);
        if ($cantidad > $stock['stock_codigo']){
		$sobrestock[] = $idcodigo;	
		}	
	}
}

if (!isset($_SESSION['sing_prod_x_comp'])) {
?>
<div id="vacio">
    <p>No tiene productos seleccionados</p>
    <div class="btn"><a href="index.php">Continuar comprando</a></div>
</div>
<?php
}else{
$productos = $_SESSION['sing_prod_x_comp'];
?>
<div id="cuadro">
    <div id="prendas">
        <div id="cabecera_prendas">
            <div id="producto_detalle">Producto</div>
            <div id="cantidad">Cant.</div>
            <div id="subtotal">Subtotal</div>
        </div>
        <?php
        $i = 1;
        foreach ($productos as $row) {
        
		$query_producto = "SELECT * FROM productos WHERE id_producto = '$row[producto]'";
        $producto = obtener_linea($query_producto);
			
		$query_codigo = "SELECT * FROM codigos WHERE id_codigo = '$row[codigo]'";
        $codigo = obtener_linea($query_codigo);
			
        $query_fotos = "SELECT * FROM fotos WHERE id_producto = '$row[producto]' ORDER BY orden_foto";
        $fotos = obtener_todo($query_fotos);
		
		$query_color = "SELECT * FROM colores WHERE id_color = '$row[color]'";
        $color = obtener_linea($query_color);
		if($color){
			$nombre_color= $color['nombre_color'];
		}else{
			$nombre_color= "-";
		}	
			
       ?>
        <div class="detalle">
            <div class="descripcion">
                <div class="imagen"><img src="images/productos/thumb/<?php echo $fotos[0]['nombre_foto'] ?>" ></div>
                <div class="datos">
                    <div class="nombre"><?php echo $producto['nombre_producto'] ?></div>
                    <div class="precio_uni">
                        <?php
                        if ($codigo['oferta_codigo'] == 1){
							echo "S/. <span class='tachado'>". $codigo['precio_codigo']." </span> ". $codigo['precio_oferta_codigo'];
                        }else{
                            echo "S/. ". $codigo['precio_codigo'];
                        }	
						?>	
                    </div>
                    <div class="talla">Opción:
                        <?php echo $codigo['talla_codigo'] ?>
                    </div>
					<div class="talla">Color:
                        <?php echo $nombre_color ?>
                    </div>
                </div>
            </div>
            <div class="cantidad">
                <div class="sube_baja">
                    <form action="compra.php" method="post">
                        <input type="hidden" name="agregar" value="<?php echo $row['producto'].$row['codigo'].$row['color'] ?>">
                        <input type="submit" value="+">
                    </form>
                </div>
                <div class="cant">
                    <?php echo $row['cantidad'] ?>
                </div>
                <div class="sube_baja">
                    <form action="compra.php" method="post">
                        <input type="hidden" name="quitar" value="<?php echo $row['producto'].$row['codigo'].$row['color'] ?>">
                        <input type="submit" value="-">
                    </form>
                </div>
                <?php
				//print_r($sobrestock);
                if (isset($sobrestock)){
                    foreach ($sobrestock as $sobrerow){
                        if ($sobrerow == $row['codigo']){
                        ?>
                <div class="supera">
                    <p>Has superado nuestro Stock</p>
                    <p>Por Favor Reduce la Cantidad</p>
                </div>
                <?php
                        }
                    }
                }
                ?>
            </div>
            <div class="subtotal">
                <p>S/. <?php echo $row['total'] ?></p>
                <div class="eliminar">
                    <form action="compra.php" method="post">
                        <input type="hidden" name="borrar" value="<?php echo $row['producto'].$row['codigo'].$row['color'] ?>">
                        <input type="submit" value="">
                    </form>
                </div>
            </div>
        </div>
    <?php
    $i++;
    }
	$cantidad = '0';
	$total = '0';
    foreach ($productos as $row) {
        $cantidad = $cantidad + $row['cantidad'];
        $total = $total+$row['total'];
    }
    ?>
    </div>
    <div id="pedido">
        <div id="sumario">Sumario</div>
        <div id="total">Total:  S/. <?php echo number_format($total, 2, '.', '') ?></div>
        <div id="cantidad">Productos: <?php echo $cantidad ?></div>
        <?php
        if (isset($sobrestock)){
        ?>
        <div id="check">
            <div class="btn2">Proceder a la compra</div>
        </div>
        <?php	
        }else{
        ?>
        <div id="check">
            <div class="btn"><a href="checkout.php">Proceder a la compra</a></div>
        </div>
        <?php
        }
        ?>
    </div>
</div>
<?php
}

}

