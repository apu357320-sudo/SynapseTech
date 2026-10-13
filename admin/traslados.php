<?php
require_once __DIR__ . '/../php/sesion.php';
exigirSesionPagina(true);
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Seguimiento de traslados | Hospital de Clínicas</title>
		<link rel="stylesheet" href="../estilo/style.css">
		<link rel="stylesheet" href="../estilo/traslados.css">
	</head>
	<body class="seguimiento-traslados">
		<header>
			<a href="index.php" class="enlace-logo" aria-label="Ir al panel de administración"><img src="../logo.svg" alt="Logo del Hospital de Clínicas" class="logo"></a>
			<div>
				<h1>Seguimiento de traslados</h1>
				<p>Hospital de Clínicas</p>
			</div>
		</header>
		<main>
			<section class="panel">
				<label for="filtroTraslados">Filtrar por paciente o vehículo</label>
				<input type="search" id="filtroTraslados" class="filtro-traslados" placeholder="Nombre, cédula o matrícula">
				<p id="mensajeTraslado" class="mensaje"></p>
				<div class="tabla-contenedor">
					<table class="tabla-traslados">
						<thead>
							<tr><th>Paciente</th><th>Vehículo</th><th>Salida</th><th>Origen</th><th>Destino</th><th>Chofer</th><th>Enfermero</th><th>Estado</th><th>Acciones</th></tr>
						</thead>
						<tbody id="listaTraslados"></tbody>
					</table>
				</div>
			</section>
			<a class="boton-principal" href="traslado_form.php">Crear un traslado</a>
			<a class="boton-secundario" href="index.php">Volver</a>
		</main>
		<script src="../js/traslado.js"></script>
	</body>
</html>
