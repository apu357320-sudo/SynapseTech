<?php
// Busca los datos de un paciente por su cédula.
// Hoy consulta la tabla paciente del propio sistema (simula la API externa
// que pide la letra). Si el hospital da acceso a su API real, alcanza con
// cambiar la consulta de este archivo: el formulario no se entera.
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion();

$ci = limpiar($_GET['ci'] ?? '');
if (!preg_match('/^\d{7,8}$/', $ci)) {
    responder(['exito' => false, 'mensaje' => 'La cédula debe tener 7 u 8 dígitos, sin puntos ni guion.'], 422);
}

$stmt = $conn->prepare('SELECT ci_paciente AS ci, nombre, apellido, numero_telefono AS telefono FROM paciente WHERE ci_paciente = ?');
$stmt->bind_param('s', $ci);
$stmt->execute();
$paciente = $stmt->get_result()->fetch_assoc();

if (!$paciente) {
    responder(['exito' => false, 'mensaje' => 'No hay un paciente con esa cédula. Completa el nombre y el apellido a mano.'], 404);
}
responder(['exito' => true, 'paciente' => $paciente]);
