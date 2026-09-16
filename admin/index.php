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
		<title>Panel administrador</title>
		<link rel="stylesheet" href="../estilo/style.css">
	</head>
	<body>
		<header>
						<a href="../index.html" class="enlace-logo" aria-label="Ir a la página inicial"><img src="../logo.svg" alt="Logo del Hospital de Clínicas" class="logo"></a>
						<h1>Panel de administración</h1>
			<p>Gestionar usuarios y documentos</p>
		</header>
		<main>
			<section class="panel">
				<div class="barra">
					  <h2>Administración</h2>
					  <a class="boton boton-secundario" href="../php/cerrarSesion.php">Cerrar la sesión</a>
				</div>
				<p id="mensajeAdmin" class="mensaje"></p>
			</section>
			<section class="panel panel-usuarios">
				<h2>Registrar un usuario</h2>
				<form id="formularioUsuario" class="formulario">
					<label>Usuario<input name="nombre_usuario" required></label>
					<label>Contraseña<input name="password" type="password" required></label>
					<label>Nombre completo<input name="nombre_completo" required></label>
					<label>Email<input name="email" type="email" required></label>
					<label>Rol<select name="rol"><option>Administrador</option><option>Personal Administrativo</option><option>Chofer</option><option>Enfermero</option></select></label>
					<label>Número de empleado<input name="numeroId" required></label>
					  <button type="submit">Registrar el usuario</button>
				</form>
			</section>
			<section class="panel">
				<h2>Usuarios registrados</h2>
				<div style="overflow-x:auto">
					<table><thead><tr><th>Usuario</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Número</th><th>Acciones</th></tr></thead><tbody id="tablaUsuarios"></tbody></table>
				</div>
			</section>
			<section class="panel panel-documentos">
				<h2>Documentos</h2>
				<div style="overflow-x:auto">
					<table class="tabla-admin"><thead><tr><th>Título</th><th>Archivo</th><th>Fecha</th><th>Acciones</th></tr></thead><tbody id="tablaDocumentos"></tbody></table>
				</div>
			</section>
			<section class="panel panel-tabla-usuarios">
		</main>
		<script src="../js/admin.js"></script>
					<table class="tabla-admin tabla-usuarios"><thead><tr><th>Usuario</th><th>Nombre completo</th><th>Correo electrónico</th><th>Rol</th><th>Número</th><th>Acciones</th></tr></thead><tbody id="tablaUsuarios"></tbody></table>
</html>
