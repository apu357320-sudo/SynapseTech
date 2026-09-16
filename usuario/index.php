<?php
require_once __DIR__ . '/../php/sesion.php';
if (empty($_SESSION['usuario'])) {
    header('Location: ../index.html');
    exit;
}
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
    <title>Panel de usuario | HR Clínica</title>
    <link rel="stylesheet" href="../estilo/style.css">
  </head>
  <body>
    <header>
      <a href="../index.html" class="enlace-logo" aria-label="Ir a la página inicial"><img src="../logo.svg" alt="Logo del Hospital de Clínicas" class="logo"></a>
      <h1>HR Clínica</h1>
      <p>Panel de usuario</p>
    </header>
    <main>
      <section class="panel">
        <div class="barra">
          <h2>Bienvenido/a, <?= htmlspecialchars($_SESSION['nombre_completo'] ?? $_SESSION['usuario'], ENT_QUOTES, 'UTF-8') ?></h2>
          <a class="boton boton-secundario" href="../php/cerrarSesion.php">Cerrar sesión</a>
        </div>
        <p>Seleccioná una opción para continuar.</p>
        <p><a class="boton" href="cargar_documento.php">Cargá un documento</a></p>
        <p><a class="boton boton-secundario" href="paciente.html">Accedé como paciente</a></p>
      </section>
    </main>
  </body>
</html>
