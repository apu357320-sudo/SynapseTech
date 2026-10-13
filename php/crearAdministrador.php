<?php
require_once __DIR__ . '/conexion.php';

$conteo = $conn->query('SELECT COUNT(*) AS total FROM usuario WHERE id_rol = 1')->fetch_assoc();
if ((int) $conteo['total'] > 0) {
    responder(['exito' => false, 'mensaje' => 'La configuración inicial ya fue realizada.'], 403);
}

$password = $_POST['password'] ?? '';
$nombreCompleto = limpiar($_POST['nombre_completo'] ?? '');
$email = limpiar($_POST['email'] ?? '');
$numeroId = limpiar($_POST['numeroId'] ?? '');

if ($password === '' || $nombreCompleto === '' || $email === '' || $numeroId === '') {
    responder(['exito' => false, 'mensaje' => 'Completa todos los campos.'], 422);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$partesNombre = preg_split('/\s+/', $nombreCompleto);
$apellido = array_pop($partesNombre);
$nombre = implode(' ', $partesNombre);
$idRol = 1;
$stmt = $conn->prepare('INSERT INTO usuario (ci, nombre, apellido, email, contrasena, estado, id_rol) VALUES (?, ?, ?, ?, ?, 1, ?)');
$stmt->bind_param('sssssi', $numeroId, $nombre, $apellido, $email, $hash, $idRol);
if (!$stmt->execute()) {
    responder(['exito' => false, 'mensaje' => 'No se pudo crear el administrador.'], 422);
}
responder(['exito' => true, 'mensaje' => 'Administrador creado. Ya puedes iniciar sesión.']);
