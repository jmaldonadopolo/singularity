<?php
date_default_timezone_set('America/Lima');
$hoy = date("Y-m-d");
$cursos = $_SESSION['sing_prod_x_comp'];	

$paso = "";
if (isset($_POST['paso'])){
$paso = $_POST['paso'];	
}

$nombre_txt = "";
if (isset($_POST['nombre_txt'])){
$nombre_txt = $_POST['nombre_txt'];	
}

$apellidos_txt = "";
if (isset($_POST['apellidos_txt'])){
$apellidos_txt = $_POST['apellidos_txt'];	
}

$dni_txt = "";
if (isset($_POST['dni_txt'])){
$dni_txt = $_POST['dni_txt'];	
}

$celular_nmb = "";
if (isset($_POST['celular_nmb'])){
$celular_nmb = $_POST['celular_nmb'];	
}

$email_ml = "";
if (isset($_POST['email_ml'])){
$email_ml = $_POST['email_ml'];	
}

$departamento_txt = "";
if (isset($_POST['departamento_txt'])){
$departamento_txt = $_POST['departamento_txt'];	
}

$provincia_txt = "";
if (isset($_POST['provincia_txt'])){
$provincia_txt = $_POST['provincia_txt'];	
}

$distrito_txt = "";
if (isset($_POST['distrito_txt'])){
$distrito_txt = $_POST['distrito_txt'];	
}

$direccion_txt = "";
if (isset($_POST['direccion_txt'])){
$direccion_txt = $_POST['direccion_txt'];	
}

$referencia_txt = "";
if (isset($_POST['referencia_txt'])){
$referencia_txt = $_POST['referencia_txt'];	
}

$empresa_txt = "";
if (isset($_POST['empresa_txt'])){
$empresa_txt = $_POST['empresa_txt'];	
}

/*-------ver datos-----------*/

//datos_envio($paso, $nombre_txt, $apellidos_txt, $dni_txt, $celular_nmb, $empresa_txt, $email_ml, $departamento_txt, $provincia_txt, $distrito_txt, $direccion_txt, $referencia_txt);



?>