<?php
require_once __DIR__ . '/../php/sesion.php';
if (empty($_SESSION['usuario'])) { header('Location: ../index.html'); exit; }
if ($_SESSION['rol'] !== 'Administrador') { header('Location: ../usuario/index.php'); exit; }
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Registrar usuario</title>
		<link rel="stylesheet" href="../estilo/style.css">
	</head>
	<body>
		<header><h1>Registrá un usuario</h1></header>
		<main>
			<section class="panel">
				<form id="formularioUsuario" class="formulario">
					<label>Usuario<input name="nombre_usuario" required></label>
					<label>Contraseña<input name="password" type="password" required></label>
					<label>Nombre completo<input name="nombre_completo" required></label>
					<label>Email<input name="email" type="email" required></label>
					<label>Rol<select name="rol"><option>Administrador</option><option>Personal Administrativo</option><option>Chofer</option><option>Enfermero</option></select></label>
					<label>Número de empleado<input name="numeroId" required></label>
					  <button type="submit">Registrá</button>
					<p id="mensajeRegistro" class="mensaje"></p>
				</form>
			</section>
			<a class="boton boton-secundario" href="index.php">Volvé</a>
		</main>
		<script src="../js/registro.js"></script>
	</body>
</html>
