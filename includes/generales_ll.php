<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include_once ("ll-admin/funciones/lla_db_fns.php");
include_once ("ll-admin/funciones/admin_lla_fnc.php");
include_once ("includes/head_funciones.php");

function doctype($pagina){
$titulo_pagina = title($pagina);
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Singularity</title>
    <link rel="shortcut icon" href="images/singularity_icon.png" type="image/x-icon" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="js/menu.js"></script>
	 <link rel="stylesheet" href="css/floating-wpp.css">
<script type="text/javascript" src="js/floating-wpp.js"></script>	
<?php
if ($pagina == "Producto"){
headproducto();
}else{
//headgeneral();	
}
include_once("includes/slick.php");		
//include_once("includes/floating.php");		
include_once("includes/bootstrap.php");		
//include_once("includes/analytics.php");		
//include_once("includes/facebook_pixel.php");
//include_once("includes/marca_ssl.php"); 						  
?>
<link rel="stylesheet" href="css/styles_singularity.css" />	  
</head>
<body>
	<div class="wrapper">
<?php	
}

function title($pagina){
if($pagina == "Producto"){
$id_prod = $_GET['id'];
$query_prod = "SELECT * FROM productos WHERE id_producto = '$id_prod'";
$producto = obtener_linea($query_prod);
	$pagina = $producto['nombre_producto'];
}
return $pagina;
}

function cabecera_back(){
$redes = "";
$query_redes = "SELECT * FROM redes WHERE activo_red = 1";
$redes = obtener_todo($query_redes);	
$sesion_email = "";	
if (isset($_SESSION['singularity_email'])) {
$sesion_email = $_SESSION['singularity_email'];
}else{
$sesion_email = 'Registro / Login';	
}
?>
		
<header>
<div class="marquesina">Texto de referencia</div>
<div class="wrap_header">	
	<div class="redes">
		<?php
		if($redes){
		?>
		<ul>
			<?php
			foreach($redes as $row){
			$link = $row['ruta_red'];	
			$nombre = $row['nombre_red'];
			$imagen = $row['imagen_red'];
			?>
			<li>
				<a href="<?php echo $link ?>" target="_blank"><img src="images/svg/redes/<?php echo $imagen ?>" alt="<?php echo $nombre ?>" /></a>
			</li>
			<?php
			}
			?>
		</ul>
		<?php
		}else{
		?>
		<ul>
		</ul>
		<?php
		}
		?>
	  </div>
	  <div class="logo"><a href="index.php"><img src="images/svg/singularity_logo.svg" /></a></div>
	  <div class="personal">
		<div class="item"><div class="btn_head ses"><a href="sesion.php" ><?php echo $sesion_email ?><img src="images/svg/icons/sesion.svg" /></a></div></div>
		<div class="item"><div class="btn_head com"><a href="compra.php" ><img src="images/svg/icons/tienda.svg"/>Carro (<div id="cant_prod">0</div>)</a></div></div>
		<div class="item">
		  <form>
			<input name="buscar" type="text" placeholder="Buscar Curso" required />
			<button><img src="images/svg/icons/lupa.svg" /></button>
		  </form>
		</div>
	</div>
</div>
  
<?php
menu_singularity();
?>
</header>
<div class="contenedor">
<?php
}

function cabecera(){
$redes = "";
$query_redes = "SELECT * FROM redes WHERE activo_red = 1";
$redes = obtener_todo($query_redes);	

?>
<header>
	<div class="wrap_header">
		<div class="logo"><a href="index.php"><img src="images/svg/singularity_logo_new01.svg" /></a></div>
		<?php
		menu_singularity();
		?>
		
		<div class="buscar">
		  <form>
			<input name="buscar" type="text" placeholder="Buscar Curso" required />
			<button><img src="images/svg/icons/lupa.svg" /></button>
		  </form>
		</div>
		
		<div class="usuario">
			<div class="user_icon"><a href="sesion.php"><img src="images/svg/icons/usuario.svg"></a></div>
			<div class="user_carro"><a href="compra.php" ><img src="images/svg/icons/tienda.svg"/><div id="cant_prod">0</div></a></div>
		</div>
		<div class="redes">
		<?php
		if($redes){
		?>
		<ul>
			<?php
			foreach($redes as $row){
			$link = $row['ruta_red'];	
			$nombre = $row['nombre_red'];
			$imagen = $row['imagen_red_sec'];
			?>
			<li>
				<a href="<?php echo $link ?>" target="_blank"><img src="images/svg/redes/<?php echo $imagen ?>" alt="<?php echo $nombre ?>" /></a>
			</li>
			<?php
			}
			?>
		</ul>
		<?php
		}else{
		?>
		<ul>
		</ul>
		<?php
		}
		?>
	  </div>
	</div>
</header>
<div class="contenedor">
<?php
}

function cabecera_larga(){
$redes = "";
$query_redes = "SELECT * FROM redes WHERE activo_red = 1";
$redes = obtener_todo($query_redes);	

?>
<header>
	<div class="wrap_header">
		<div class="logo"><a href="index.php"><img src="images/svg/singularity_logo_new.svg" /></a></div>
		<?php
		menu_singularity();
		?>
		<div class="btn_head com"><a href="compra.php" ><img src="images/svg/icons/tienda_new.svg"/>Carro (<div id="cant_prod">0</div>)</a></div>
		<div class="redes">
		<?php
		if($redes){
		?>
		<ul>
			<?php
			foreach($redes as $row){
			$link = $row['ruta_red'];	
			$nombre = $row['nombre_red'];
			$imagen = $row['imagen_red'];
			?>
			<li>
				<a href="<?php echo $link ?>" target="_blank"><img src="images/svg/redes/<?php echo $imagen ?>" alt="<?php echo $nombre ?>" /></a>
			</li>
			<?php
			}
			?>
		</ul>
		<?php
		}else{
		?>
		<ul>
		</ul>
		<?php
		}
		?>
	  </div>
	</div>
</header>
<div class="contenedor">
<?php
}

function marquesina(){
$query_marquesina = "SELECT * FROM texto_oferta WHERE estado_textoferta = 1";
$marquesina = obtener_todo($query_marquesina);

?>
<div id="marquesina">
<?php
if($marquesina){
	foreach($marquesina as $row){
	?>
	<div class="marquesina"><?php echo $row['nombre_textoferta'] ?></div>
	<?php
	}
}
?>	
</div>	
<script type="text/javascript">
$(document).ready(function(){
	$('#marquesina').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 6000,
		dots: false,
		infinite: true,
		dots: false,
		arrows: false,
	});
});
</script>	
<?php

}

function menu_singularity(){
$query_cursos = "SELECT * FROM cursos WHERE activo_curso = 1 ORDER BY id_curso";
$cursos = obtener_todo($query_cursos);
$sesion_email = "";	
if (isset($_SESSION['singularity_email'])) {
$sesion_email = $_SESSION['singularity_email'];
}else{
$sesion_email = 'Registro / Login';	
}
?>
<nav>
  <ul>
    <li><a href="index.php">Inicio</a></li>
    <li class="submenu">
      <a href="#">Cursos</a>
      <ul class="children">
		<?php
		if($cursos){
			foreach($cursos as $men){
				$id = $men['id_curso'];
				$nombre = $men['nombre_curso'];	
		  	?>
			<li><a href="cursos.php?id=<?php echo $id ?>"><?php echo $nombre ?></a></li>
			<?php
			}
		}
		?>
      </ul>
    </li>
    <!--<li><a href="#">Consulting</a></li>
    <li><a href="#">Tienda</a></li>-->
    <li><a href="contacto.php">Contacto</a></li>
    <!--<li><a href="sesion.php"><?php // echo $sesion_email ?></a></li>-->
  </ul>
</nav>	
<?php
}

function footer(){
?>
</div>
<footer>
	<div class="tarjetas">
    	<ul>
    	<li><img src="images/svg/tarjetas/amex.svg" /></li>
		<li><img src="images/svg/tarjetas/diners.svg" /></li>
		<li><img src="images/svg/tarjetas/master.svg" /></li>
		<li><img src="images/svg/tarjetas/visa.svg" /></li>
		<li><img src="images/svg/tarjetas/yape.svg" /></li>
        </ul>
    </div>
    <div class="secciones">
		<div class="seccion">
			<div class="redes">
				<ul>
				<?php
				$redes = "";
				$query_redes = "SELECT * FROM redes WHERE activo_red = 1";
				$redes = obtener_todo($query_redes);	
				if($redes){
					foreach($redes as $row){
					$link = $row['ruta_red'];	
					$nombre = $row['nombre_red'];
					$imagen = $row['imagen_red_sec'];
					?>
					<li>
						<a href="<?php echo $link ?>" target="_blank"><img src="images/svg/redes/<?php echo $imagen ?>" alt="<?php echo $nombre ?>" /></a>
					</li>
					<?php
					}
				}
				?>
				</ul>	
			</div>
			<div class="logo_footer"><img src="images/svg/singularity_logo_footer.svg" alt="Singularity" /></div>
		</div>
		<div class="seccion">
            <div class="titulo">NOSOTROS</div>
			<ul>
				<?php
				$internas = "";
				$query_internas = "SELECT * FROM paginas_internas WHERE activo_pagina_interna = 1";
				$internas = obtener_todo($query_internas);	
				if($internas){
					foreach($internas as $row){
					$nombre_pagina_interna = $row['nombre_pagina_interna'];	
					$link_pagina_interna = $row['link_pagina_interna'];
					$id_pagina_interna = $row['id_pagina_interna'];
					?>
					<li>
						<a href="<?php echo $link_pagina_interna.'?id='.$id_pagina_interna ?>"><?php echo $nombre_pagina_interna ?></a>
					</li>
					<?php
					}
				}
				?>
			</ul>
		</div>
		<div class="seccion">
            <div class="titulo">LINKS</div>
			<ul>
				<li><a href="#">Contáctanos</a></li>
			</ul>
		</div>
		<div class="seccion">
            <div class="titulo">LIBRO DE RECLAMACIONES</div>
			<div class="logo_footer"><img src="images/svg/icons/libro.svg" alt="Singularity" /></div>
		</div>
    </div>
	<div class="copy">Singularity Perú 2021</div>
</footer>
<?php 
    $cursos = "";
    if(isset($_SESSION['sing_prod_x_comp'])){
        $cursos = $_SESSION['sing_prod_x_comp']; 
    }
     $cantidad = 0;
    foreach ($cursos as $row) {
        $cantidad = $cantidad + $row['cantidad'];
    }     
    ?>
<script>
    let cant_real = <?php echo $cantidad ?>;
    let cant_prod = document.getElementById('cant_prod');
    //console.log(cant_real);
    //console.log(cant_prod);
    document.getElementById("cant_prod").innerHTML = cant_real;
</script>     
<?php    
wa();
?>        
</div>	
</body>
<script>
</script>	
</html>
<?php
}

function wa(){
$query_wsp = "SELECT * FROM whatsapp WHERE id_whatsapp = 1";
$whatsapp = obtener_linea($query_wsp);
if($whatsapp['estado_wsp']==1){	
?>	
<div id="myButton"></div>
<script type="text/javascript">
    $(function () {
        $('#myButton').floatingWhatsApp({
            phone: '+51<?php echo $whatsapp['numero_wsp'] ?>',
            popupMessage: 'Hola, ¿En qué podemos ayudarte?',
            message: "<?php echo $whatsapp['url_wsp'] ?> \n\n <?php echo $whatsapp['descripcion_wsp'] ?>",
            showPopup: false,
            showOnIE: false,
            headerTitle: 'Hola, ¿En qué podemos ayudarte?',
            headerColor: 'white',
            backgroundColor: '#ffffff',
            buttonImage: '<img src="images/svg/whatsapp_ico.svg" />'
        });
    });
</script>
<?php
 }
}

function productos($query_productos, $titulo){
$productos = obtener_todo($query_productos);
if ($productos){	
?>
<div id="productos">
<div class="titulo_productos"><?php echo $titulo?></div>
<?php
foreach ($productos as $row) {
$id_producto = 	$row['id_producto'];
$query_fotos = "SELECT * FROM fotos WHERE id_producto = $id_producto AND activo_foto = 1 and id_inicio = 1 ORDER BY orden_foto";
$fotos = obtener_todo($query_fotos);
$query_codigos = "SELECT * FROM codigos WHERE id_producto = $id_producto";
$codigos = obtener_todo($query_codigos);
?>
<div class="producto">
<?php
if ($fotos){
?>
<div class="imagen"><a href="producto.php?id=<?php echo $row['id_producto']?>"><img src="images/productos/small/<?php echo $fotos[0]['nombre_foto']?>" alt="<?php echo $row['nombre_producto']?>"/></a></div>
<?php
}else{
	$query_fotos = "SELECT * FROM fotos WHERE id_producto = '$row[id_producto]' AND activo_foto = 1 ORDER BY orden_foto";
	$fotos = obtener_todo($query_fotos);
	?>
<div class="imagen"><a href="producto.php?id=<?php echo $row['id_producto']?>"><img src="images/productos/small/<?php echo $fotos[0]['nombre_foto']?>" alt="<?php echo $row['nombre_producto']?>"/></a></div>
<?php
}
?>
<div class="nombre"><a href="producto.php?id=<?php echo $row['id_producto']?>"><?php echo $row['nombre_producto']?></a></div>
<div class="precio">
<?php 
if ($codigos[0]['oferta_codigo'] == 1){
?>
S/. <span class='oferta'><?php echo $codigos[0]['precio_codigo'] ?></span> <?php echo $codigos[0]['precio_oferta_codigo'] ?>
<?php
}else{
?>
S/. <?php echo $codigos[0]['precio_codigo'] ?>
<?php
}	
?>
</div>

</div>
<?php 
}
}else{
?>
<div id="productos">
<div class="titulo_productos"><?php echo $titulo?></div>
	<div class="vacio">Sección sin productos</div>
</div>	
<?php	
}
	
}

function menu_paginas_estaticas($pe){
    $query_estaticas = "SELECT * FROM paginas_internas WHERE activo_pagina_interna = '1'";
    $internas = obtener_todo($query_estaticas);
    ?>
    <div class="lista">
        <ul>
        <?php
        foreach ($internas as $row){
        $nombre_pagina_interna = $row['nombre_pagina_interna'];	
		$link_pagina_interna = $row['link_pagina_interna'];
		$id_pagina_interna = $row['id_pagina_interna'];
        ?>
        <li <?php if ($pe == "$id_pagina_interna"){ echo "class='activo'"; }; ?>><a href="<?php echo $link_pagina_interna.'?id='.$id_pagina_interna ?>"><?php echo $nombre_pagina_interna ?></a></li>
        <?php
        }
        ?>
        </ul>
    </div>
    <?php
    }

function mensaje_generico($msj, $ruta, $boton){
?>
<div class="msj_gen">
	<div class="msj_retorno"><?php echo $msj; ?></div>
	<div class="msj_retorno"><a href="<?php echo $ruta; ?>"><?php echo $boton; ?></a></div>
</div>
<?php
}

?>