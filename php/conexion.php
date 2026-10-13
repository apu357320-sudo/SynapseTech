<?php
// Conexión a la base de datos. Los demás archivos PHP la incluyen y usan $conn.
require_once __DIR__ . '/config.php';

// Desde PHP 8.1 mysqli lanza una excepción cuando falla una consulta.
// Con esta línea vuelve a devolver false, que es lo que revisan los
// "if (!$stmt->execute())" de los otros archivos para mostrar su mensaje.
mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_errno) {
    responder(['exito' => false, 'mensaje' => 'No se pudo conectar con la base de datos.'], 500);
}

$conn->set_charset('utf8mb4');
