<?php
require_once __DIR__ . '/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_errno) {
    responder(['exito' => false, 'mensaje' => 'No se pudo conectar con la base de datos.'], 500);
}

$conn->set_charset('utf8mb4');
