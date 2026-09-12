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
        responder(['exito' => false, 'mensaje' => 'No tenés permisos para esta acción.'], 403);
    }
}
