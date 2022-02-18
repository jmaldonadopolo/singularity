<?php

$archivo = $_FILES['nuevofoto_modal']['name'];
$archivo_mobile = $_FILES['nuevofoto_modal_mobile']['name'];
//	print_r($_FILES['nuevofoto_modal']);
	if (isset($archivo) && $archivo != "") {
		$rand = rand( 0, 9 ) . rand( 100, 9999 ) . rand( 100, 9999 );
		$dir_destino = '../images/modal/';
		$imagen = trim( $_FILES[ 'nuevofoto_modal' ][ 'name' ] );
		$tipo = $_FILES['nuevofoto_modal']['type'];
		$tamano = $_FILES['nuevofoto_modal']['size'];
		$temp = $_FILES['nuevofoto_modal']['tmp_name'];
		$nombre_foto = $rand . '_' . $imagen;
	if (!((strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")))) {
			$msj_desk = '<div><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
        - Se permiten archivos .gif, .jpg, .png. y de 1.5 mb como máximo.</b></div>';
		
     }else{
        if(is_writable($dir_destino)){
        if($tamano < 1500000){
            if($tipo == 'image/jpeg' || $tipo == "image/png"|| $tipo == "video/mp4"){
                if(is_uploaded_file( $_FILES[ 'nuevofoto_modal' ][ 'tmp_name'])){
                    $subir_imagen = $dir_destino . $nombre_foto;
                    if(move_uploaded_file($temp, $subir_imagen)){
                        $query_agregar_banner = "UPDATE modal SET imagen_modal = '$nombre_foto' WHERE id_modal = 1";
                        if(actualizar_registro($query_agregar_banner)){
                            $msj_desk = "Modal Actualizado";
                        }else{
                            $msj_desk = "No se pudo 'agregar a db' conectar con el servidor, por favor intentar de nuevo";
                        }
                    }else{
                        $msj_desk = "No se pudo conectar con el servidor, por favor intentar de nuevo";
                    }
                }else{
                    $msj_desk = "No se pudo subir la imagen, por favor intentar de nuevo";
                }
            }else{
                $msj_desk = "La imagen debe de ser de formato .jpg o .png, No se permite otro formato";
            }
        }else{
            $msj_desk = "La imagen supera el tamaño permitido '1.5 Mb'";
        }
    }else{
        $msj_desk = "No se tiene permisos de escritura";
    }
      }
	}
	
	if (isset($archivo_mobile) && $archivo_mobile != "") {
		$rand_mobile = rand( 0, 9 ) . rand( 100, 9999 ) . rand( 100, 9999 );
		$dir_destino_mobile = '../images/modal/';
		$imagen_mobile = trim( $_FILES[ 'nuevofoto_modal_mobile' ][ 'name' ] );
		$tipo_mobile = $_FILES['nuevofoto_modal_mobile']['type'];
		$tamano_mobile = $_FILES['nuevofoto_modal_mobile']['size'];
		$temp_mobile = $_FILES['nuevofoto_modal_mobile']['tmp_name'];
		$nombre_foto_mobile = $rand . '_' . $imagen;
	if (!((strpos($tipo_mobile, "jpeg") || strpos($tipo_mobile, "jpg") || strpos($tipo_mobile, "png")))) {
			$msj_mobile = '<div><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
        - Se permiten archivos .gif, .jpg, .png. y de 1.5 mb como máximo.</b></div>';
		
     }else{
        if(is_writable($dir_destino_mobile)){
        if($tamano_mobile < 1500000){
            if($tipo_mobile == 'image/jpeg' || $tipo_mobile == "image/png"|| $tipo_mobile == "video/mp4"){
                if(is_uploaded_file( $_FILES[ 'nuevofoto_modal_mobile' ][ 'tmp_name'])){
                    $subir_imagen_mobile = $dir_destino_mobile . $nombre_foto_mobile;
                    if(move_uploaded_file($temp_mobile, $subir_imagen_mobile)){
                        $query_agregar_banner_mobile = "UPDATE modal SET imagen_modal_mobile = '$nombre_foto_mobile' WHERE id_modal = 1";
                        if(actualizar_registro($query_agregar_banner_mobile)){
                            $msj_mobile = "Modal Actualizado";
                        }else{
                            $msj_mobile = "No se pudo 'agregar a db' conectar con el servidor, por favor intentar de nuevo";
                        }
                    }else{
                        $msj_mobile = "No se pudo conectar con el servidor, por favor intentar de nuevo";
                    }
                }else{
                    $msj_mobile = "No se pudo subir la imagen, por favor intentar de nuevo";
                }
            }else{
                $msj_mobile = "La imagen debe de ser de formato .jpg o .png, No se permite otro formato";
            }
        }else{
            $msj_mobile = "La imagen supera el tamaño permitido '1.5 Mb'";
        }
    }else{
        $msj_mobile = "No se tiene permisos de escritura";
    }
      }
	}
	
	$msj = $msj_desk.'<br>'.$msj_mobile;
	
	mensaje_exito_error_modal($msj);





?>