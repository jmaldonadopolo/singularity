<?php
$pe = $_GET['id'];
$query = "SELECT * FROM paginas_internas WHERE id_pagina_interna = '$pe'";
$contenido = obtener_linea($query);
?>

<div class="estaticas">
	<div class="titulo"><?php echo $contenido['nombre_pagina_interna'] ?></div>
	<div class="main">
		<?php menu_paginas_estaticas($pe);	?>
		<div class="estatica">
			<div class="contenido">	
			<?php echo $contenido['contenido_pagina_interna'] ?>
			</div>
		</div>
	</div>	
</div>