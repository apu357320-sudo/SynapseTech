<?php
require_once __DIR__ . '/../php/sesion.php';
if (empty($_SESSION['usuario'])) { header('Location: ../index.html'); exit; }
if ($_SESSION['rol'] !== 'Administrador') { header('Location: ../usuario/index.php'); exit; }
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Seguimiento de traslados</title><link rel="stylesheet" href="../estilo/estilos.css"><link rel="stylesheet" href="../estilo/traslado.css"></head><body><header><h1>Seguimiento de traslados</h1></header><main><section class="panel"><p id="mensajeTraslado" class="mensaje"></p><div style="overflow-x:auto"><table><thead><tr><th>Paciente</th><th>Origen</th><th>Destino</th><th>Chofer</th><th>Enfermero</th><th>Estado</th><th>Acciones</th></tr></thead><tbody id="listaTraslados"></tbody></table></div></section><a class="boton boton-secundario" href="traslado_form.php">Nuevo traslado</a> <a class="boton boton-secundario" href="index.php">Volver</a></main><script src="../js/traslado.js"></script></body></html>
