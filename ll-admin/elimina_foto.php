<?php 
include 'funciones/lla_db_fns.php';
include 'funciones/admin_lla_fnc.php';


    $foto = $_REQUEST['nomfoto'];

    $id = $_REQUEST['idfoto'];
    $eliminarfoto1 = "../images/productos/small/".$foto;
    
    $eliminarfoto3 = "../images/productos/".$foto;
    
    if($id){
        $query_id = "DELETE from fotos where id_foto = $id";
        $codigos = actualizar_registro($query_id);
        unlink($eliminarfoto1);

        unlink($eliminarfoto3);
        echo "bien";
    }else{
        echo "mal";
    }
 ?>