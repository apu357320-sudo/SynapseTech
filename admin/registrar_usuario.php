<?php
require_once __DIR__ . '/../php/sesion.php';
exigirSesionPagina(true); // solo administradores
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Registrar usuario | Hospital de Clínicas</title>
		<link rel="stylesheet" href="../estilo/style.css">
	</head>
	<body>
		<header>
			<a href="index.php" class="enlace-logo" aria-label="Ir al panel de administración"><img src="../logo.svg" alt="Logo del Hospital de Clínicas" class="logo"></a>
			<div>
				<h1>Registrar un usuario</h1>
				<p>Hospital de Clínicas</p>
			</div>
		</header>
		<main>
			<section class="panel">
				<form id="formularioUsuario" class="formulario">
					<label>C.I.<input name="numeroId" inputmode="numeric" maxlength="8" placeholder="Sin puntos ni guion" required></label>
					<label>Contraseña<input name="password" type="password" required></label>
					<label>Nombre completo<input name="nombre_completo" required></label>
					<label>Email<input name="email" type="email" required></label>
					<label>Rol<select name="rol"><option>Administrador</option><option>Personal Administrativo</option><option>Chofer</option><option>Enfermero</option></select></label>
					<button type="submit" class="boton-principal">Registrar</button>
					<p id="mensajeRegistro" class="mensaje"></p>
				</form>
			</section>
			<a class="boton-secundario" href="index.php">Volver</a>
		</main>
		<script src="../js/registro.js"></script>
	</body>
</html>
