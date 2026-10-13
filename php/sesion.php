<?php
// Manejo de la sesión: la inicia y ofrece dos controles de acceso,
// uno para los archivos que responden JSON y otro para las páginas.
require_once __DIR__ . '/config.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Para los archivos de php/ que responden JSON: si no hay sesión (o no es
// administrador cuando se exige) corta con un error 401 o 403.
function exigirSesion($soloAdministrador = false)
{
    if (empty($_SESSION['usuario'])) {
        responder(['exito' => false, 'mensaje' => 'La sesión no está iniciada.'], 401);
    }

    if ($soloAdministrador && ($_SESSION['rol'] ?? '') !== 'Administrador') {
        responder(['exito' => false, 'mensaje' => 'No tienes permisos para esta acción.'], 403);
    }
}

// Para las páginas de admin/ y usuario/ (rutas protegidas): si no hay sesión
// redirige al login, y si la página es solo para administradores y el
// usuario no lo es, lo manda a su panel.
function exigirSesionPagina($soloAdministrador = false)
{
    if (empty($_SESSION['usuario'])) {
        header('Location: ../index.html');
        exit;
    }

    if ($soloAdministrador && ($_SESSION['rol'] ?? '') !== 'Administrador') {
        header('Location: ../usuario/index.php');
        exit;
    }
}
