<?php
require_once __DIR__ . '/../php/sesion.php';
exigirSesionPagina(true); // solo administradores
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Panel administrador</title>
		<link rel="stylesheet" href="../estilo/style.css">
		<link rel="stylesheet" href="../estilo/administracion.css">
	</head>
	<body class="admin-page">
		<header>
			<a href="../index.html" class="enlace-logo" aria-label="Ir a la página inicial"><img src="../logo.svg" alt="Logo del Hospital de Clínicas" class="logo"></a>
			<div>
				<h1>Panel de administración</h1>
			<p>Gestionar usuarios y documentos</p>
			</div>
		</header>
		<main>
			<section class="panel panel-administracion">
				<div class="barra">
					<h2>Administración</h2>
					<nav class="menu-admin" aria-label="Secciones del sistema">
						<a class="boton-secundario" href="traslados.php">Traslados</a>
						<a class="boton-secundario" href="traslado_form.php">Nuevo traslado</a>
						<a class="boton-secundario" href="../usuario/cargar_documento.php">Cargar documento</a>
					<a class="boton-secundario" href="encuestas_panel.html">Encuestas</a>
						<a class="boton-secundario" href="../php/cerrarSesion.php">Cerrar la sesión</a>
					</nav>
				</div>
				<p id="mensajeAdmin" class="mensaje"></p>
			</section>
			<section class="panel panel-usuarios">
				<h2>Registrar un usuario</h2>
				<form id="formularioUsuario" class="formulario">
					<label>C.I.<input name="numeroId" inputmode="numeric" maxlength="8" placeholder="Sin puntos ni guion" required></label>
					<label>Contraseña<input name="password" type="password" required></label>
					<label>Nombre completo<input name="nombre_completo" required></label>
					<label>Email<input name="email" type="email" required></label>
					<label>Rol<select name="rol"><option>Administrador</option><option>Personal Administrativo</option><option>Chofer</option><option>Enfermero</option></select></label>
					  <button type="submit" class="boton-principal">Registrar el usuario</button>
				</form>
			</section>
			<section class="panel panel-usuarios-registrados">
				<h2>Usuarios registrados</h2>
				<div class="tabla-contenedor">
					<table class="tabla-admin"><thead><tr><th>C.I.</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Acciones</th></tr></thead><tbody id="tablaUsuarios"></tbody></table>
				</div>
			</section>
			<section class="panel panel-documentos">
				<h2>Documentos</h2>
				<label for="filtroCategoria">Filtrar por categoría</label>
				<select id="filtroCategoria" class="filtro-categoria">
					<option value="">Todas</option>
				</select>
				<div class="tabla-contenedor">
					<table class="tabla-admin"><thead><tr><th>Título</th><th>Categoría</th><th>Archivo</th><th>Fecha</th><th>Acciones</th></tr></thead><tbody id="tablaDocumentos"></tbody></table>
				</div>
				<!-- Acá aparece el QR del documento cuando se aprieta "Ver QR" -->
				<p id="textoQr" class="texto-qr"></p>
				<div id="qrDocumento" class="contenedor-qr"></div>
			</section>
		</main>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
		<script src="../js/qr.js"></script>
		<script src="../js/admin.js"></script>
	</body>
</html>
