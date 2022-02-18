<?php
unset($_SESSION['sing_coupon']);
unset($_SESSION['sing_nota_credito']);
unset($_SESSION['sing_coupon_cliente']);
//unset($_SESSION['sing_prod_x_comp']);

$id_curso = "";

if (isset($_POST['id_curso'])){
$id_curso = $_POST['id_curso'];
$qty = "1";
	
$query_curso = "SELECT * FROM cursos WHERE id_curso = $id_curso";
$curso = obtener_linea($query_curso);
//print_r($curso);
$nombre_curso = $curso['nombre_curso'];
$subtitulo_curso = $curso['subtitulo_curso'];
$id_tipo_curso = $curso['id_tipo_curso'];
$codigo_curso = $curso['codigo_curso'];
$skills_curso = $curso['skills_curso'];
$imagen_curso = $curso['imagen_curso'];
$descripcion_curso = $curso['descripcion_curso'];
$dirigido_curso = $curso['dirigido_curso'];
$prerequisitos_curso = $curso['prerequisitos_curso'];
$incluye_curso = $curso['incluye_curso'];
$id_docente = $curso['id_docente'];
$inicio_curso = $curso['inicio_curso'];
$fin_curso = $curso['fin_curso'];
$ini_insc_curso = $curso['ini_insc_curso'];
$fin_insc_curso = $curso['fin_insc_curso'];
$modalidad_curso = $curso['modalidad_curso'];
$duracion_curso = $curso['duracion_curso'];
$horario_curso = $curso['horario_curso'];
$certificacion_curso = $curso['certificacion_curso'];
$precio_curso = $curso['precio_curso'];
$activo_curso = $curso['activo_curso'];
$oferta_activo_curso = $curso['oferta_activo_curso'];
$precio_oferta_curso = $curso['precio_oferta_curso'];
$oferta_detalle_curso = $curso['oferta_detalle_curso'];
$syllabus_curso = $curso['syllabus_curso'];

$query_fotos = "SELECT * FROM fotos WHERE id_curso = $id_curso AND activo_foto = 1";
$fotos = obtener_todo($query_fotos);

$query_syllabus = "SELECT * FROM syllabus_curso WHERE id_curso = $id_curso AND activo_syllabus_curso = 1";
$syllabus = obtener_todo($query_syllabus);

$query_modalidad = "SELECT * FROM syllabus_curso WHERE id_curso = $id_curso AND activo_syllabus_curso = 1";
$syllabus = obtener_todo($query_syllabus);

$query_banners = "SELECT * FROM banners_curso WHERE id_curso = '$id_curso' AND estado_banner_curso = 1";
$banners_curso = obtener_todo($query_banners);

if($oferta_activo_curso == 1){
	$precio_final_curso = $precio_oferta_curso;
}else{
	$precio_final_curso = $precio_curso;
}
}

$borrar = "";
if (isset($_POST['borrar'])) { 
  $borrar = $_POST['borrar']; 
} 
if ($borrar) {
	unset($_SESSION["sing_prod_x_comp"][$borrar]);
}

$agregar = "";
if (isset($_POST['agregar'])) { 
  $agregar = $_POST['agregar']; 
} 
if ($agregar) {
	$_SESSION['sing_prod_x_comp'][$agregar]['cantidad']++;
	$_SESSION['sing_prod_x_comp'][$agregar]['total'] = number_format((($_SESSION['sing_prod_x_comp'][$agregar]['precio_producto'])*($_SESSION['sing_prod_x_comp'][$agregar]['cantidad'])), 2, '.', '');
}

$quitar = "";
if (isset($_POST['quitar'])) { 
  $quitar = $_POST['quitar']; 
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
<div class="add_curso">
<div class="titulo">Mis Cursos Seleccionados</div>
<?php
	if (!isset($_SESSION['sing_prod_x_comp'])){
        if (!$id_curso) {
            cesta_productos();
        }else{
            $_SESSION['sing_prod_x_comp'][$id_curso] = array(
                "curso" => $id_curso,
				"precio_producto" => $precio_final_curso,
                "cantidad" => $qty,
                "total" => number_format(($precio_final_curso*$qty), 2, '.', ''));
            cesta_productos();
        }
    }else{
        if (!$id_curso) {
            cesta_productos();
        }else{
            if (isset($_SESSION['sing_prod_x_comp'][$id_curso])) {
                $_SESSION['sing_prod_x_comp'][$id_curso]['cantidad']++;
                $_SESSION['sing_prod_x_comp'][$id_curso]['total'] = (($_SESSION['sing_prod_x_comp'][$id_curso]['precio_producto']) * ($_SESSION['sing_prod_x_comp'][$id_curso]['cantidad']));
                cesta_productos();
            }else{
                $_SESSION['sing_prod_x_comp'][$id_curso] = array(
					"curso" => $id_curso,
					"precio_producto" => $precio_final_curso,
					"cantidad" => $qty,
					"total" => number_format(($precio_final_curso*$qty), 2, '.', ''));
				cesta_productos();
            }
        }
    }
?>	
</div>
<?php

function cesta_productos() {
$cursos = "";
if (isset($_SESSION["sing_prod_x_comp"])){
    $cursos = $_SESSION['sing_prod_x_comp'];
    //$sobrestock = "";
    foreach ($cursos as $row){
        $id_curso = $row['curso'];
		$precio_final_curso = $row['precio_producto'];
		$qty = $row['cantidad'];
        $cantidad = $row['cantidad'];
		$total = $row['total'];
		$query_comp = "SELECT vacantes_curso FROM cursos WHERE id_curso = '$id_curso'";
		$vacantes = obtener_linea($query_comp);
        if ($cantidad > $vacantes['vacantes_curso']){
		$sobrestock[] = $id_curso;	
		}	
	}
}

if (!isset($_SESSION['sing_prod_x_comp'])) {
?>
<div class="sub_titulo">No se encuentran cursos seleccionados</div>
<div class="btn_curso"><a href="index.php">Regresar</a></div>	

<?php
}else{
$cursos = $_SESSION['sing_prod_x_comp'];
?>
<div class="cuadro">
	<div class="cursos">
    	<div class="curso_com cabecera">
            <div class="item">Curso</div>
            <div class="item">Cant.</div>
            <div class="item">Subtotal</div>
        </div>
        <?php
        $i = 1;
        foreach ($cursos as $row) {
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
       	<div class="curso_com">
       		<div class="item datos">
            	<div class="imagen"><img src="images/cursos/<?php echo $imagen ?>" ></div>
                <div class="datos">
                    <div class="nombre"><?php echo $nombre_curso ?></div>
                    <div class="precio_uni">S/ <?php echo $precio_final_curso ?></div>
                </div>
            </div>
            <div class="item cantitem">
                <div class="sube_baja">
                    <form action="compra.php" method="post">
                        <input type="hidden" name="quitar" value="<?php echo $id_curso ?>">
                        <input type="submit" value="-">
                    </form>
                </div>
                <div class="cant">
                    <?php echo $row['cantidad'] ?>
                </div>
                <div class="sube_baja">
                    <form action="compra.php" method="post">
                        <input type="hidden" name="agregar" value="<?php echo $id_curso ?>">
                        <input type="submit" value="+">
                    </form>
                </div>
                <?php
				//print_r($sobrestock);
                if (isset($sobrestock)){
                    foreach ($sobrestock as $sobrerow){
                        if ($sobrerow == $row['curso']){
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
            <div class="item total">
                <div class="subtotal">S/. <?php echo $row['total'] ?></div>
                <div class="eliminar">
                    <form action="compra.php" method="post">
                        <input type="hidden" name="borrar" value="<?php echo $id_curso ?>">
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
    foreach ($cursos as $row) {
        $cantidad = $cantidad + $row['cantidad'];
        $total = $total+$row['total'];
    }
    ?>
    </div>
    <div class="pedido_sum">
        <div class="sumario">Resumen</div>
		<div class="dest">
        <div class="total">Total:  S/. <?php echo number_format($total, 2, '.', '') ?></div>
        <div class="cantidad">Cursos: <?php echo $cantidad ?></div>
        <?php
        if (isset($sobrestock)){
        ?>
        <div class="check">
            <div class="btn2">Proceder a la compra</div>
        </div>
        <?php	
        }else{
        ?>
        <div class="check">
            <div class="btn_com"><a href="checkout.php">Proceder a la compra</a></div>
        </div>
        <?php
        }
        ?>
		</div>	
    </div>
</div>
<?php
}

}


?>

