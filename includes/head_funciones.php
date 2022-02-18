<?php

function headproducto(){
$id_prod = $_GET['id'];
$query_prod = "SELECT * FROM productos WHERE id_producto = '$id_prod'";
$producto = obtener_linea($query_prod);
$query_fotos = "SELECT * FROM fotos WHERE id_producto = '$id_prod' AND activo_foto = '1'";
$fotos = obtener_todo($query_fotos);
$foto = $fotos[0]['nombre_foto'];
	
$query_codigos = "SELECT * FROM codigos WHERE id_producto = '$id_prod'";
$codigos = obtener_todo($query_codigos);	

if ($codigos[0]['oferta_codigo'] == 1){
	$precio = $codigos[0]['precio_oferta_codigo'];
}else{
	$precio = $codigos[0]['precio_codigo'];
}	
$nombre_codigo = $codigos[0]['nombre_codigo'];
	
$stock = $codigos[0]['stock_codigo'];
if($stock > 0){
	$stc = "in stock";
}else{
	$stc = "out of stock";	
}	
	
?>
<link href="css/style.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/libs_producto.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<link href="css/carousel.css" media="all" type="text/css" rel="stylesheet">

<meta property="og:type" content="product">
<meta property="og:title" content="<?php echo strtolower($producto['nombre_producto']) ?>">
<meta property="og:description" content="<?php echo strip_tags($producto['descripcion_producto']) ?>">
<meta property="og:url" content="https://www.bembellaperu.com/producto.php?id=<?php echo $producto['id_producto'] ?>" />
<meta property="og:image" content="https://www.bembellaperu.com/images/productos/<?php echo $foto ?>">
<meta property="product:brand" content="bembellaperu">
<meta property="product:availability" content="<?php echo $stc ?>">
<meta property="product:condition" content="new">
<meta property="product:price:amount" content="<?php echo $precio ?>">
<meta property="product:price:currency" content="PEN">
<meta property="product:retailer_item_id" content="<?php echo $id_prod.$nombre_codigo ?>">
<meta property="product:item_group_id" content="<?php echo $id_prod ?>">

<?php
}

function headgeneral(){
?>
<link rel="canonical" href="https://www.bembellaperu.com/">
<link rel="shortcut icon" href="images/bembella_icon.png" type="image/png">

<meta name="description" content="Es una mujer que con su belleza y fuerza logra vencer cualquier obstáculo y salir adelante para cumplir sus sueños.">

<meta property="og:site_name" content="Bembella Perú">
<meta property="og:url" content="https://www.bembellaperu.com/">
<meta property="og:title" content="Bembella Perú">
<meta property="og:type" content="website">
<meta property="og:description" content="Es una mujer que con su belleza y fuerza logra vencer cualquier obstáculo y salir adelante para cumplir sus sueños.">

<meta property="og:image" content="https://bembellaperu.com/images/rep/bembella.jpg">
<meta property="og:image:secure_url" content="https://bembellaperu.com/images/rep/bembella.jpg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="628">
<?php
}

?>