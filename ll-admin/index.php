<?php
session_start();
include_once ("includes/lla_generales.php");
$estatico = get_estatico();
$username = $_POST['txtusuario'];
$passwd = $_POST['txtpass'];

if ($username){
	if ($passwd){
		$check = get_pass_admin($username);
		if (tep_validate_password($passwd, $check)){
			$_SESSION[$estatico['sesion_ll']] = $username;
			header("Location: inicio.php");
		}else{
		header("Location: login.php?err=1");	
		//Regresa al login error 1
		}	
	}else{
	header("Location: login.php?err=2");
	//Regresa al login error 2
	}
}else{
	if(!isset($_SESSION[$estatico['sesion_ll']])){
	header("Location: login.php");
	}else{

header("Location: inicio.php");

	}
}

?>
