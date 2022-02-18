<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once ("funciones/lla_db_fns.php");
include_once ("funciones/admin_lla_fnc.php"); 

function doctype($page){
$estatico = get_estatico();
?>
<!doctype html>
<html><head>
<meta charset="utf-8">
<title><?php echo $page; ?> - <?php echo $estatico['nombre_local']; ?></title>
<link href="css/limalocal.css" rel="stylesheet" type="text/css">
<link href="css/limalocal_mob.css" rel="stylesheet" type="text/css">
<link href="css/limalocal_class.css" rel="stylesheet" type="text/css">
<link href="css/reset.css" rel="stylesheet" type="text/css">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<script src="js/jscolor.js"></script>
<link rel="icon" type="image/png" href="<?php echo $estatico['icon_local']; ?>" />
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
	<link rel="stylesheet" href="css/dragdrovecss/bootstrap.min.css">
	<link rel="stylesheet" href="css/dragdrovecss/style.css">
	<link rel="stylesheet" href="css/dragdrovecss/responsive.bootstrap.min.css">
	<link rel="stylesheet" href="css/dragdrovecss/jquery-ui.min.css">
<!--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>-->
	<script src="js/dragdrovejs/jquery-2.2.0.min.js"></script>
	<script src="js/dragdrovejs/bootstrap.min.js"></script>
	<script src="js/dragdrovejs/jquery-ui.min.js"></script>
<link href="css/limalocal_2021.css" rel="stylesheet" type="text/css">	
</head>
<?php	
}

function ll_header($page){
$estatico = get_estatico();
?>
<body>
<header>
<div id="barra">
	<div id="marquesina">Bienvenido <span id="nombre"><?php echo $_SESSION[$estatico['sesion_ll']]; ?></span></div>
</div>
<div id="ver">
	<div id="logoadmin"><a href="inicio.php"><img id="admin" src="svg/limalocal.svg"></a></div>
	<div id="menugen">
    <nav>
    <ul>
        <li><a href="inicio.php" <?php if ($page == "Inicio"){ echo 'class="select"'; } ?>><?php echo $estatico['nombre_local']; ?></a></li>
        <li><a href="cursos.php" <?php if ($page == "Cursos"){ echo "class='select'"; } ?>>Cursos</a></li>
		<li><a href="opciones.php" <?php if ($page == "Opciones"){ echo "class='select'"; } ?>>Opciones de Cursos</a></li>
        <!--<li><a href="secciones.php" <?php if ($page == "Submenus"){ echo "class='select'"; } ?>>Menú</a></li>-->
        <!--<li><a href="cuponesyofertas.php" <?php if ($page == "Cupones"){ echo "class='select'"; } ?>>Cupones y ofertas</a></li>-->
		<!--<li><a href="pedidos.php" <?php if ($page == "Pedidos"){ echo "class='select'"; } ?>>Pedidos</a></li>-->
		<!--<li><a href="clientes.php" <?php if ($page == "Clientes"){ echo "class='select'"; } ?>>Clientes</a></li>-->
        <li><a href="estaticas.php" <?php if ($page == "Estáticas"){ echo "class='select'"; } ?>>Páginas Estáticas</a></li>
        <!--<li><a href="secciones.php" <?php if ($page == "Secciones"){ echo "class='select'"; } ?>>Secciones</a></li>-->
		<!--<li><a href="colecciones.php" <?php if ($page == "Colecciones"){ echo "class='select'"; } ?>>Colecciones</a></li>-->
		<!--<li><a href="tarjetas.php" <?php if ($page == "Tarjetas"){ echo "class='select'"; } ?>>Medios de Pago</a></li>-->
		<!--<li><a href="tiendas.php" <?php if ($page == "Tiendas"){ echo "class='select'"; } ?>>Tiendas</a></li>-->
		<!--<li><a href="carrito_abandonado.php" <?php if ($page == "Abandonado"){ echo "class='select'"; } ?>>Carrito Abandonado</a></li>-->
    </ul>
    </nav>
	</div>
    <div id="c_ses">
    	<div id="boton"><a href="logout.php">Cerrar Sesión</a></div>
    </div>
</div>
</header>
<div id="limalocal">
<?php
}

function ll_footer($estatico){
?>
</div>
<footer>
<div id="texto"><?php echo $estatico['footer_ll'] ?></div>
</footer>
	<script src="js/dragdrovejs/gestorSlide.js"></script>
</body>
</html>
<?php
}

function mensaje_generico($msj, $ruta, $boton){
?>
<div id="cuadro">
	<div class="msj"><?php echo $msj; ?></div>
	<div class="centro"><a href="<?php echo $ruta; ?>" class="boton centro"><?php echo $boton; ?></a></div>
</div>
<?php
}

?>