<?php
require_once __DIR__ . '/../php/sesion.php';
exigirSesionPagina(); // cualquier usuario con sesión iniciada
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cargar documento | Hospital de Clínicas</title>
    <link rel="stylesheet" href="../estilo/style.css">
    <link rel="stylesheet" href="../estilo/documentos.css">
  </head>
  <body class="documentos-page">
    <header>
      <a href="index.php" class="enlace-logo" aria-label="Ir al panel"><img src="../logo.svg" alt="Logo del Hospital de Clínicas" class="logo"></a>
      <div>
        <h1>Cargar un documento</h1>
        <p>Documentación clínica y administrativa</p>
      </div>
    </header>
    <main>
      <section class="panel">
        <form id="formularioCarga" class="formulario" enctype="multipart/form-data">
          <label for="titulo">Título</label>
          <input id="titulo" name="titulo" required>

          <!-- Las categorías las carga documentos.js desde la base de datos -->
          <label for="categoria">Categoría</label>
          <select id="categoria" name="id_categoria" required>
            <option value="">Seleccionar</option>
          </select>

          <label for="archivo">Archivo</label>
          <input id="archivo" name="archivo" type="file" accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx" required>

          <button type="submit" class="boton-principal">Cargar el documento</button>
          <p id="mensajeDocumentos" class="mensaje" role="alert"></p>
        </form>
      </section>

      <section class="panel">
        <h2>Documentos disponibles</h2>
        <label for="filtroCategoria">Filtrar por categoría</label>
        <select id="filtroCategoria">
          <option value="">Todas</option>
        </select>
        <label for="listaDocumentos">Documento</label>
        <select id="listaDocumentos"></select>
        <iframe id="vistaDocumento" title="Vista previa del documento"></iframe>
        <img id="vistaImagen" alt="Vista previa del documento">
      </section>

      <a class="boton-secundario" href="index.php">Volver</a>
    </main>
    <script src="../js/documentos.js"></script>
  </body>
</html>
