<?php
require_once __DIR__ . '/../php/sesion.php';
if (empty($_SESSION['usuario'])) {
    header('Location: ../index.html');
    exit;
}
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
      <a href="../index.html" class="enlace-logo" aria-label="Ir a la página inicial"><img src="../logo.svg" alt="Logo del Hospital de Clínicas" class="logo"></a>
      <h1>Cargar un documento</h1>
      <p>Documentación clínica y administrativa</p>
    </header>
    <main>
      <section class="panel">
        <form id="formularioCarga" class="formulario" enctype="multipart/form-data">
          <label for="titulo">Título</label>
          <input id="titulo" name="titulo" required>

          <label for="archivo">Archivo</label>
          <input id="archivo" name="archivo" type="file" accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx" required>

          <button type="submit">Cargar el documento</button>
          <p id="mensajeDocumentos" class="mensaje" role="alert"></p>
        </form>
      </section>

      <section class="panel">
        <h2>Documentos disponibles</h2>
        <select id="listaDocumentos" aria-label="Seleccioná un documento"></select>
        <iframe id="vistaDocumento" title="Vista previa del documento"></iframe>
        <img id="vistaImagen" alt="Vista previa del documento">
      </section>

      <a class="boton boton-secundario" href="index.php">Volver</a>
    </main>
    <script src="../js/documentos.js"></script>
  </body>
</html>
