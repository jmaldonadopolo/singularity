<?php 
$pag = isset($_GET['pag']) ? $_GET['pag'] : '1' ;

$query_estaticas = "SELECT * FROM paginas_internas";
$paginas_internas = obtener_todo($query_estaticas);
?>

<div id="lateralizq">
    <nav>
	<ul>
		<?php
		foreach($paginas_internas as $row){

			?>
			<li class="item <?php if ($pag == $row['id_pagina_interna']){ echo 'seleccionado';} ?>"><a href="estaticas.php?pag=<?php echo $row['id_pagina_interna'] ?>"><?php echo $row['nombre_pagina_interna'] ?></a></li>
			<?php
			
		}
		?>
    </ul>
    </nav>
</div>
<div id="contenido">
	<div id="titulo">Páginas Estáticas</div>
	<div id="cuadro">
		<div id="lista_paginas">
			<?php pag_est($pag); ?>
		</div>
	</div>
</div>

<?php

function pag_est($pag){
$actualizar = isset($_POST['accion']) ? $_POST['accion'] : null ;
$query = "SELECT * FROM paginas_internas WHERE id_pagina_interna = '$pag'";
$pagina = obtener_linea($query);

$contenido = $pagina['contenido_pagina_interna'];
$contenido = htmlentities($contenido);


if ($actualizar == "actualizar"){

$titulo = isset($_POST['titulo_txt']) ? $_POST['titulo_txt'] : null ;
$contenido_new = isset($_POST['contenido_txt']) ? $_POST['contenido_txt'] : null ;
$query_pagint = "UPDATE paginas_internas SET nombre_pagina_interna = '$titulo', contenido_pagina_interna = '$contenido_new' WHERE id_pagina_interna = '$pag'";
	if(actualizar_registro($query_pagint)){
		$ruta = "estaticas.php?pag=".$pag;
		$msj = "Página Actualizada Correctamente";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}else{
		$ruta = "estaticas.php?pag=".$pag;
		$msj = "Ha habido un problema en el servidor por favor intentar nuevamente";
		$boton = "Regresar";
		mensaje_generico($msj, $ruta, $boton);
	}

}else{
?>
<div id="formulario">
	<form enctype="application/x-www-form-urlencoded" action="estaticas.php?pag=<?php echo $pag ?>" method="post">
		<div id="titulo_form"> Título: <input name="titulo_txt" type="text" value="<?php echo $pagina['nombre_pagina_interna'] ?>"></div><br />
		<div id="descripcion"> Contenido: <br />
		<textarea id="mytextarea" name="contenido_txt" rows="15" maxlength="1000"><?php echo $contenido ?></textarea>
		</div>
	<div>
	<input type="hidden" name="accion" value="actualizar"  />
	<input class="boton" type="submit" value="Actualizar" />
	</div>
	</form>
</div>
<script src="https://cdn.tiny.cloud/1/jrpahjfkukmd5c3xtic4mkbttepp9135rfkn4lzjwwlplc7y/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
	selector: '#mytextarea',
	language: 'es',
	content_style: 'body { font-size:14px }',
	menubar: true,
	plugins: [
		'autoresize',
		'advlist autolink lists link image charmap print preview anchor',
		'searchreplace visualblocks code fullscreen',
		'insertdatetime media table paste code help wordcount'
	],
	toolbar: 'undo redo | formatselect | ' +
	'bold italic backcolor | alignleft aligncenter ' +
	'alignright alignjustify | bullist numlist outdent indent | ' +
	'removeformat |',
});
</script>
<?php	
}

}

function paginas_especiales($pag){
 if ($pag == 4){
$actualizar = $_POST[accion];

	 if ($actualizar){
	 $titulo = $_POST[titulo_txt];
	$contenido_new = $_POST[contenido_txt];
	$contenido2_new = $_POST[contenido2_txt];
	$rand = rand( 0, 9 ) . rand( 100, 9999 ) . rand( 100, 9999 );
	$dir_destino = '../images/';
	$imagen = trim( $_FILES['nueva_foto']['name'] );
	$imagen_size = $_FILES['nueva_foto']['size'];
	$imagen_type = $_FILES['nueva_foto']['type'];
	$tmp_name = $_FILES['nueva_foto']['tmp_name'];
	$nombre_foto = $rand . '_' . $imagen;
	$esppag2 = "6";
	
	if (!$imagen){
	
	//echo "sin imagen";
		if (actualizar1_guia($esppag, $titulo, $contenido_new)) {
			if (actualizar0_guia($esppag2, $contenido2_new)){
				$msj = "Guía de Tallas actualizada con Éxito";
				mensaje_exito_error_guia($msj);
			}else{
			$msj = "No se pudo 'agregar a db0', por favor intentar de nuevo";
			mensaje_exito_error_guia($msj);
			}
		} else {
		$msj = "No se pudo 'agregar a db', por favor intentar de nuevo";
		mensaje_exito_error_guia( $msj );
		}
	
	}else{
	if ( is_writable( $dir_destino ) ) {
		if ( $imagen_size < 1500000 ) {
			if ( $imagen_type == 'image/jpeg' || $imagen_type == "image/png" ) {
				if ( is_uploaded_file( $tmp_name ) ) {
					$subir_imagen = $dir_destino . $nombre_foto;
						if (move_uploaded_file($tmp_name, $subir_imagen)){
							if (actualizar1_guia($esppag, $titulo, $contenido_new)) {
								if (actualizar2_guia($esppag2, $contenido2_new, $nombre_foto)){
											$msj = "Guía de Tallas actualizada con Éxito";
											mensaje_exito_error_guia($msj);
										}else{
										$msj = "No se pudo 'agregar a db2', por favor intentar de nuevo";
										mensaje_exito_error_guia($msj);
										}
									} else {
										$msj = "No se pudo 'agregar a db', por favor intentar de nuevo";
										mensaje_exito_error_guia( $msj );
									}
								} else {
									$msj = "No se pudo conectar con el servidor, por favor intentar de nuevo";
									mensaje_exito_error_guia( $msj );
								}
							} else {
								$msj = "No se pudo subir la imagen, por favor intentar de nuevo";
								mensaje_exito_error_guia( $msj );
							}
						} else {
							$msj = "La imagen debe de ser de formato .jpg o .png, No se permite otro formato";
							mensaje_exito_error_guia( $msj );
						}
					} else {
						$msj = "La imagen supera el tamaño permitido '1.5 Mb'";
						mensaje_exito_error_guia( $msj );
					}
				} else {
					$msj = "No se tiene permisos de escritura";
					mensaje_exito_error_guia( $msj );
				}
	}
	
	 }else{

$query = "SELECT * FROM paginas_internas WHERE id_pagina_interna = '$pag'";
$contenido = obtener_linea( $query );
$query2 = "SELECT * FROM paginas_internas WHERE id_pagina_interna = '6'";
$contenido2 = obtener_linea( $query2 );

?>
<script src='js/tinymce/tinymce.min.js'></script>
	<script>
	tinymce.init({
	selector: '#mytextarea',
	language: "es",
	menubar: false,
	 toolbar: [
		'undo redo | bold italic | bullist numlist | removeformat',
	  ],
	  formats: {
    removeformat: [
      {
        selector: 'h1,h2,h3,h4,h5,h6',
        remove: 'all',
        split: false,
        expand: false,
        block_expand: true,
        deep: true
      },
      {
        selector: 'a,b,strong,em,i,font,u,strike,sub,sup,dfn,code,samp,kbd,var,cite,mark,q,del,ins',
        remove: 'all',
        split: true,
        expand: false,
        deep: true
      },
      { selector: 'span', attributes: ['style', 'class'], remove: 'empty', split: true, expand: false, deep: true },
      { selector: '*', attributes: ['style', 'class'], split: false, expand: false, deep: true }
    ]
  }
	});
	</script>
<div id="formulario">
	<form enctype="multipart/form-data" action="estaticas.php?esppag=<?php echo $esppag ?>" method="post">
		<div id="titulo"> Título: <input name="titulo_txt" type="text" value="<?php echo $contenido[nombre_pagina_interna] ?>"></div>
		<div id="contenido"> Contenido: <br />
			<textarea id="mytextarea" name="contenido_txt" rows="8"><?php echo $contenido[0][contenido_pagina_interna] ?></textarea>
		</div>
<br><br>
<div id="contenido"> Imagen actual: <br />
<div id="imagen"><img src="../images/<?php echo $contenido2[0][imagen_pagina_interna] ?>" width="300px"></div>
 Nueva Imagen:<input id="file_url" type="file" name="nueva_foto" >
</div>
<br><br>
<div id="contenido"> Descripcion de talla: <br />
<textarea id="mytextarea2" name="contenido2_txt" rows="10"><?php echo $contenido2[0][contenido_pagina_interna] ?></textarea>
</div>
<br>
<div>
<input type="hidden" name="accion" value="actualizar"  />
<input type="submit" value="Actualizar Guía de Tallas" />
</div>
</form>
</div>

<?php
}

}

}

function mensaje_exito_error_estatica($msj, $pag) {
?>
<div id="msj">
	<?php echo $msj; ?><br><br>
	<a href="estaticas.php?pag=<?php echo $pag; ?>" class="boton">Regresar</a>
	</div>
<?php
}

function mensaje_exito_error_guia($msj) {
?>
<div id="msj">
	<?php echo $msj; ?><br><br>
	<a href="estaticas.php?pag=4" class="boton">Regresar</a>
	</div>
<?php
}

function mensaje_exito_error_mapa($msj) {
?>
<div id="msj">
	<?php echo $msj; ?><br><br>
	<a href="estaticas.php?pag=6" class="boton">Regresar</a>
	</div>
<?php
}



?>


