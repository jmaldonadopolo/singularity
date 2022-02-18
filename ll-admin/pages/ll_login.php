<?php $estatico = get_estatico(); ?>
<body>
<header>
<div id="barra">
	<div id="marquesina"><?php echo $estatico['nombre_local']; ?></div>
</div>

<div id="login">
    <div id="logo"><img id="logo_svg" src="<?php echo $estatico['logo_ll']; ?>" ></div>
    
</div>
</header>

<div id="contenido_login">

	<div id="login">
	<h1>Administrador</h1>
	<h2>Versión: <?php echo $estatico['version_ll']; ?></h2>
		<form action="index.php" method="post">
            <p>Usuario</p>
            <div id="campo"><input id="dato" type="text" name="txtusuario" required/></div>	
            <p>Contraseña</p>
            <div id="campo"><input id="dato" type="password" name="txtpass" required/></div>
			<div id="campo"><input id="boton" type="submit" value="Ingresar"></div>
		</form>
    <?php
	$error = "";
	if(isset($_GET["err"])){	
	$error = $_GET["err"];
	}
	if ($error == 1){
		?>
        <div id="error">
        Usuario o Contraseña incorrecta
        </div>
		<?php
	}elseif ($error == 2){
		?>
        <div id="error">
        Por favor Ingrese su contraseña
        </div>
		<?php
	}
	?>
    </div>
</div>

<footer>
<div id="texto"><?php echo $estatico['footer_ll']; ?></div>
</footer>
</body>
</html>