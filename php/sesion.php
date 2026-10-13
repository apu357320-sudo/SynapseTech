<?php
require_once __DIR__ . '/config.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function exigirSesion($soloAdministrador = false)
{
    if (empty($_SESSION['usuario'])) {
        responder(['exito' => false, 'mensaje' => 'La sesión no está iniciada.'], 401);
    }

    if ($soloAdministrador && ($_SESSION['rol'] ?? '') !== 'Administrador') {
        responder(['exito' => false, 'mensaje' => 'No tienes permisos para esta acción.'], 403);
    }
}

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
