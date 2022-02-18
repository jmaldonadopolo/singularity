<?php
$id_curso = $_GET['id'];
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

?>
<main>
<h1><?php echo $nombre_curso." (".$codigo_curso.")" ?></h1>
<?php
if($banners_curso){
?>
<section class="banner_curso">
	<figure>
	<?php
	foreach ($banners_curso as $itembannersec) {
	?>
	<img src="images/banner_curso/<?php echo $itembannersec['imagen_banner_curso'] ?>" alt="Banner" />
	<?php 
	}
	?>
	</figure>
	<div>
    <h2><?php echo $nombre_curso?></h2>
	<?php
	if($subtitulo_curso){
	?>
	<h3><?php echo $subtitulo_curso?></h3>
	<?php
	}
	?>
	</div>
    </section>
	<?php
	}
	?>	
      <div class="curso">
        <div class="informacion">
          <section class="descripcion">
            <h2>Descripción del curso</h2>
            <div class="contenido">
              <?php echo $descripcion_curso?>
            </div>
          </section>
          <div class="adicionales">
            <section>
              <h2>¿A quién esta dirigido?</h2>
              <div class="contenido">
               <?php echo $dirigido_curso?> 
              </div>
            </section>
            <section>
              <h2>Pre-Requisitos</h2>
              <div class="contenido">
                <?php echo $prerequisitos_curso?>
              </div>
            </section>
          </div>
		<?php
		if($syllabus){
			syllabus($syllabus);
		}
		?>
        </div>
        <aside>
          	<?php
			inscripcion_aside($inicio_curso, $modalidad_curso, $duracion_curso, $certificacion_curso, $horario_curso, $oferta_activo_curso, $precio_oferta_curso, $precio_curso, $oferta_detalle_curso, $id_curso);
			docente($id_docente);
			reserva_curso();
            //minicurso($imagen_curso, $nombre_curso, $skills_curso);
			?>
        </aside>
      </div>
    </main>

<?php

function banners_curso($id_curso){
$query_bannersec = "SELECT * FROM fotos WHERE id_curso = $id_curso AND activo_foto = 1";
$obtn_bannersec = obtener_todo($query_bannersec);
if($obtn_bannersec){
?>
<div id="banner_sec">
	<div id="secciones">
    <?php
        foreach ($obtn_bannersec as $itembannersec) {
    ?>
	<div><img src="images/cursos/<?php echo $itembannersec['nombre_foto'] ?>"></div>
    <?php 
        }
    ?>
	</div>	
<script type="text/javascript">
$(document).ready(function(){
	$('#secciones').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 4000,
		dots: false,
		infinite: true,
		dots: false,
		arrows: false,
		responsive: [
			{
				breakpoint: 800,
				settings: {
					slidesToShow: 2,
					arrows: false,
				}
			}
		]
	});
});
</script>
</div>	
<?php
}
}

function syllabus($syllabus){
?>
<section class="syllabus">
<div id="accordion">	
<?php
foreach($syllabus as $row){
$activo = $row['activo_syllabus_curso'];	
$titulo = $row['titulo_syllabus_curso'];	
$contenido = $row['contenido_syllabus_curso'];
if($activo == 1){
	?>
	<button class="accordion"><?php echo $titulo ?></button>
	<div class="panel"><?php echo $contenido ?></div>
	<?php
}	
}	
?>
</div>
</section>
<style>
.accordion {
background-color: #014da5;
    color: #fff;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
}

.active, .accordion:hover {
  background-color: #b98410; 
	box-shadow: none;
}

.panel {
  padding: 0 18px;
  background-color: white;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
}
	
.accordion:after {
  content: '\02795'; /* Unicode character for "plus" sign (+) */
  font-size: 13px;
  color: #fff;
  float: right;
  margin-left: 5px;
	border: none;
}

.active:after {
  content: "\2796"; /* Unicode character for "minus" sign (-) */
}
</style>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  });
}
</script>
<?php
}

function minicurso($imagen_curso, $nombre_curso, $skills_curso){
?>
<section>
<figure><img src="images/cursos/<?php echo $imagen_curso ?>" alt="<?php echo $nombre_curso ?>" /></figure>
<div class="descripcion">
<?php echo $skills_curso ?>
</div>
</section>	
<?php
}

function docente($id_docente){
$query_docente = "SELECT * FROM docentes WHERE id_docente = $id_docente";
$docente = obtener_linea($query_docente);
$foto_docente = $docente['foto_docente'];
$descripcion_docente = $docente['descripcion_docente'];
$nombre_docente = $docente['nombres_docente']." ".$docente['apellidos_docente'];	
?>
<section class="docente">
<h2>Coach del curso</h2>
<figure></figure><img src="images/docentes/<?php echo $foto_docente ?>" /></figure>
<h3><?php echo $nombre_docente ?></h3>
<div>
<p>
<?php echo $descripcion_docente ?>
</p>
</div>
<button>Ver Más</button>
</section>	
<?php	
}

function inscripcion_aside($inicio_curso, $modalidad_curso, $duracion_curso, $certificacion_curso, $horario_curso, $oferta_activo_curso, $precio_oferta_curso, $precio_curso, $oferta_detalle_curso, $id_curso){
?>
<section class="inscripcion">
	<?php
			if($oferta_activo_curso == 1){
				$precio_final_curso = $precio_oferta_curso;
			}else{
				$precio_final_curso = $precio_curso;
			}	  
			?>
            <div class="precio_final">S/ <?php echo $precio_final_curso ?></div>
            <div class="precio_detalle"><?php echo $oferta_detalle_curso ?></div>
			<form method="post" action="compra.php" enctype="multipart/form-data">
			<input type="hidden" name="id_curso" value="<?php echo $id_curso ?>">
            <button type="submit">Inscríbete ahora</button>
			</form>	
			<?php
			if($oferta_activo_curso == 1){
			?>	
            <div class="precio_real">S/ <span class="tachado"><?php echo $precio_curso ?></span>  Precio regular</div>
			<?php
			}
	?>
            <div class="linea">
              <div class="item">Inicio: </div>
              <div class="dato"><?php echo $inicio_curso ?></div>
            </div>
            <div class="linea">
              <div class="item">Modalidad: </div>
              <div class="dato">
			  <?php
				$query_modalidad = "SELECT * FROM modalidad_curso WHERE id_modalidad_curso = '$modalidad_curso'";
				$modalidad = obtener_linea($query_modalidad);
				$nombre_modalidad = $modalidad['nombre_modalidad_curso'];
				echo $nombre_modalidad;	 
				?>
			</div>
            </div>
            <div class="linea">
              <div class="item">Duración: </div>
              <div class="dato"><?php echo $duracion_curso ?></div>
            </div>
            <div class="linea">
              <div class="item">Certificado: </div>
              <div class="dato">
				<?php
				$query_certificacion = "SELECT * FROM certificacion_curso WHERE id_certificacion_curso = '$certificacion_curso'";
				$certificacion = obtener_linea($query_certificacion);
				$nombre_certificacion = $certificacion['nombre_certificacion_curso'];
				echo $nombre_certificacion;	 
				?>	
				</div>
            </div>
            <div class="linea">
              <div class="item">Horario: </div>
              <div class="dato"><?php echo $horario_curso ?></div>
            </div>
</section>
<?php
}

function reserva_curso(){
?>
<section class="reserva">
<div>
<p>Duis efficitur eros tellus, id tempus mauris interdum et. Aliquam congue nisi sem, cursus consectetur arcu lobortis at. In quis nisl ac arcu placerat sodales. Mauris finibus drerit lacus quis porta. Nullam justo augue.</p>
</div>
<form>
<input type="text" name="" placeholder="Nombre Y apellidos">
<input type="email" name="" placeholder="Correo Electrónico">
<input type="number" name="" placeholder="Télefono Fijo o Celular">
<button>Reserva tu cupo</button>
</form>
</section>
<?php
}

?>
