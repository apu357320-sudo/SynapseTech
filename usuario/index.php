<?php
require_once __DIR__ . '/../php/sesion.php';
exigirSesionPagina();
if (($_SESSION['rol'] ?? '') === 'Administrador') {
    header('Location: ../admin/index.php');
    exit;
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de usuario | Hospital de Clínicas</title>
    <link rel="stylesheet" href="../estilo/style.css">
  </head>
  <body>
    <header>
      <a href="../index.html" class="enlace-logo" aria-label="Ir a la página inicial"><img src="../logo.svg" alt="Logo del Hospital de Clínicas" class="logo"></a>
      <div>
        <h1>Hospital de Clínicas Dr. Manuel Quintela</h1>
        <p>Panel de usuario</p>
      </div>
    </header>
    <main>
      <section class="panel">
        <div class="barra">
          <h2>Bienvenido/a, <?= htmlspecialchars($_SESSION['nombre_completo'] ?? $_SESSION['usuario'], ENT_QUOTES, 'UTF-8') ?></h2>
          <a class="boton-secundario" href="../php/cerrarSesion.php">Cerrar sesión</a>
        </div>
        <p>Selecciona una opción para continuar.</p>
        <p><a class="boton-principal" href="cargar_documento.php">Cargar un documento</a></p>
        <p><a class="boton-secundario" href="paciente.html">Acceder como paciente</a></p>
      </section>
    </main>
  </body>
</html> 
