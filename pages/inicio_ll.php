<?php
//banners();
video_banner();
proximos_inicios();
cursos_oferta();
docentes();

function video_banner(){
?>
<section class="banner_video">
	<video class="video_banner" loop autoplay muted>
		<source src="images/banners/03.mp4" type="video/mp4" >
	</video>
	<div class="over_vid">
		<div class="tit_over">Capacitación tecnológica disruptiva</div>
		<div class="parr_over">En todo lo que hacemos creemos en el cambio del “status quo”, creemos en un pensamiento diferente y la manera que desafiamos el “status quo” es brindando servicios de capacitación en tecnología disruptiva muy bien diseñados, innovadores y con docentes de experiencia, sencillamente cursos geniales. ¿Quieres unirte?</div>
		<div class="btn_over">PROGRAMAS Y CURSOS >></div>
	</div>
</section>	
<?php	
}

function banners(){
$query_banner ="SELECT * FROM banners WHERE estado_banner = 1";
$obt_banners = obtener_todo($query_banner);
if ( is_array($obt_banners)) {
$cantidad = count($obt_banners);
?>
<section class="banner">
<div id="carousel_banner" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner" role="listbox">
            <?php 
  			$i = 1;
  			foreach ($obt_banners as $itembanner) {
  				if ($i == 1){
					$active = "active";
					$i++;
				}else{
					$active = "";
				}	
				$verificarfile =  pathinfo($itembanner['imagen_banner'])['extension'];
if($verificarfile == "jpg" || $verificarfile == "jpeg" || $verificarfile == "JPG" || $verificarfile == "png"){
	?>
	<div class="item <?php echo $active; ?>">
	<a href="<?php echo $itembanner['link_banner'] ?>" target="_self">
	<img class="img-responsive center-block" src="images/banners/<?php echo $itembanner['imagen_banner'] ?>" alt="banner" >
	</a>
    </div>
	<?php
}else{
	?>
	<div class="item <?php echo $active; ?>">
		<a href="<?php echo $itembanner['link_banner'] ?>">
	<video class="img-responsive center-block" loop autoplay muted>
        <source src="images/banners/<?php echo $itembanner['imagen_banner'] ?>" type="video/mp4" >
    </video>
			</a>
    </div>
	<?php
}
			  ?>

	<?php } ?>
	<script>
	$(document).ready(function(){
		
	
$('#carousel_banner').on('slid.bs.carousel', function (e) {
   let elemento = $('#carousel_banner .item.active video').first();
   if (elemento.prop("tagName") == "VIDEO") {
     elemento.get(0).play();
   }
});

$('#carousel_banner').bind('slide.bs.carousel', function (e) {  
   let elemento = $('#carousel_banner .item.active video').first();
   if (elemento.prop("tagName") == "VIDEO") {
     elemento.get(0).pause();
   }
});
 $("#carousel_banner").on("touchstart", function(event){ var xClick = event.originalEvent.touches[0].pageX; $(this).one("touchmove", function(event){ var xMove = event.originalEvent.touches[0].pageX; if( Math.floor(xClick - xMove) > 5 ){ $(this).carousel('next'); } else if( Math.floor(xClick - xMove) < -5 ){ $(this).carousel('prev'); } }); $("#carousel_banner").on("touchend", function(){ $(this).off("touchmove"); }); }); 

})
</script>
  </div>
  <?php
	if ($cantidad > 1){
	?>	
  <!-- Controls -->
  <a class="left carousel-control desk" href="#carousel_banner" role="button" data-slide="prev">
	<span class="glyphicon glyphicon-chevron-left"></span>
	<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control desk" href="#carousel_banner" role="button" data-slide="next">
	<span class="glyphicon glyphicon-chevron-right"></span>
	<span class="sr-only">Next</span>
	</a>
  <?php
	}
	?>
</div>

<script>
$(document).ready(function(){
	$('#carousel_banner').carousel({
		interval: 7000
	})
})
</script>
</section>	
<?php
    }
}

function proximos_inicios_back(){
$query_cursos = "SELECT * FROM cursos WHERE activo_curso = 1 ORDER BY inicio_curso DESC LIMIT 4";
$cursos = obtener_todo($query_cursos);

if($cursos){
?>
<section class="proximos">
	<h2>Próximos inicios</h2>
	<div class="curso_inicio">
		<?php
		foreach ($cursos as $row){
			$id = $row['id_curso'];
			$imagen = $row['imagen_curso'];
			$nombre = $row['nombre_curso'];
			$codigo = $row['codigo_curso'];
			$descripcion = $row['skills_curso'];
			$syllabus = $row['syllabus_curso'];
		?>
		<article>
		<figure><img src="images/cursos/<?php echo $imagen ?>" alt="<?php echo $nombre ?>" /></figure>
		<h3><?php echo $nombre." (".$codigo.")" ?></h3>
		<div class="descripcion">
		<?php echo $descripcion ?>
		<form action="cursos.php" method="get" enctype="text/plain">
			<input type="hidden" name="id" value="<?php echo $id ?>">
		<button>Ver Curso</button>
		</form>	
		</div>	
		</article>
		<?php
		}
		?>
	</div>
</section>
<?php
}	
}

function proximos_inicios(){
$query_cursos = "SELECT * FROM cursos WHERE activo_curso = 1 ORDER BY inicio_curso DESC LIMIT 4";
$cursos = obtener_todo($query_cursos);

if($cursos){
?>
<section class="proximos">
	<h2>Próximos inicios</h2>
	<div class="curso_inicio">
		<?php
		foreach ($cursos as $row){
			$id = $row['id_curso'];
			$imagen = $row['imagen_curso'];
			$nombre = $row['nombre_curso'];
			$codigo = $row['codigo_curso'];
			$descripcion = $row['skills_curso'];
			$imagen_fondo = $row['syllabus_curso'];
			$inicio = $row['inicio_curso'];
			$duracion = $row['duracion_curso'];
			$detalle = $row['oferta_detalle_curso'];
		?>
		<article>
		<figure><img src="images/curso_back/<?php echo $imagen_fondo ?>" alt="<?php echo $nombre ?>" /></figure>
		<div class="descripcion">
			<div class="inicio">Inicio: <?php echo $inicio ?></div>
			<div class="reclame">Duración: <?php echo $duracion ?></div>
			<div class="oferta"><?php echo $detalle ?></div>
		<form action="cursos.php" method="get" enctype="text/plain">
			<input type="hidden" name="id" value="<?php echo $id ?>">
			
			<button>Ver Syllabus</button>
		</form>	
		</div>	
		</article>
		<?php
		}
		?>
	</div>
</section>
<?php
}	
}

function cursos_oferta(){
$query_cursos = "SELECT * FROM cursos WHERE activo_curso = 1 AND oferta_activo_curso = '1' ORDER BY inicio_curso DESC LIMIT 4";
$cursos = obtener_todo($query_cursos);

if($cursos){
?>
<section class="curso_oferta">
	<h2>Ofertas</h2>
	<div class="oferta">
		<?php
		foreach ($cursos as $row){
			$id = $row['id_curso'];
			$imagen = $row['imagen_curso'];
			$nombre = $row['nombre_curso'];
			$codigo = $row['codigo_curso'];
			$descripcion = $row['skills_curso'];
			$syllabus = $row['syllabus_curso'];
			$fecha_inicio = $row['inicio_curso'];
			$precio_curso = $row['precio_curso'];
			$activo_curso = $row['activo_curso'];
			$oferta_activo_curso = $row['oferta_activo_curso'];
			$precio_oferta_curso = $row['precio_oferta_curso'];
			$oferta_detalle_curso = $row['oferta_detalle_curso'];
		?>
		<article>
			<div class="oferta_curso">
			 	<figure><img src="images/cursos/<?php echo $imagen ?>" alt="<?php echo $nombre ?>" /></figure>
				<div class="precio_oferta">
					<div class="precio_final">S/ <?php echo $precio_oferta_curso ?></div>
					<div class="precio_real">S/ <span class="tachado"><?php echo $precio_curso ?></span>  Precio regular</div>
				</div>
			</div>
		<h3><?php echo $nombre." (".$codigo.")" ?></h3>
		<div class="descripcion">
			<div class="inicio">Inicio: <?php echo $fecha_inicio ?></div>
		<form action="cursos.php" method="get" enctype="text/plain">
			<input type="hidden" name="id" value="<?php echo $id ?>">
		<button>Ver Curso</button>
		</form>	
		</div>	
		</article>
		<?php
		}
		?>
	</div>
</section>
<?php
}	
}

function docentes(){
$query_docentes = "SELECT * FROM docentes WHERE activo_docente = 1 ORDER BY id_docente";
$docentes = obtener_todo($query_docentes);
if($docentes){	
?>
<section class="docentes">
		<?php
		foreach($docentes as $row){
			$nombres = $row['nombres_docente'];
			$apellidos = $row['apellidos_docente'];
			$foto = $row['foto_docente'];
			$lkd = $row['linkedin_docente'];
			$puesto = $row['puesto_docente'];
		?>
		<article>
            <figure><img src="images/docentes/<?php echo $foto ?>" /></figure>
			<div>
              <a href="<?php echo $lkd ?>"><img src="images/svg/icons/btn_lkd.svg" /></a>
            </div>
            <h2><?php echo $nombres." ".$apellidos ?></h2>
            <div class="puesto"><?php echo $puesto ?></div>
          </article>
		<?php
		}
		
		?>
</section>
<script type="text/javascript">
$(document).ready(function(){
	$('#cont_docentes').slick({
		slidesToShow: 4,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 4000,
		dots: true,
		infinite: true,
		dots: false,
		responsive: [
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 1,
					arrows: false,
				}
			}
		]
	});
});
</script>
<?php
}
}

?>
