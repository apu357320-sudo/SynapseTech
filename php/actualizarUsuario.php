<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion(true);

$id = (int) ($_POST['id_usuario'] ?? 0);
$nombreCompleto = limpiar($_POST['nombre_completo'] ?? '');
$email = limpiar($_POST['email'] ?? '');
$rol = limpiar($_POST['rol'] ?? '');
$password = $_POST['password'] ?? '';

if ($id < 1 || $nombreCompleto === '' || $email === '' || $rol === '') {
    responder(['exito' => false, 'mensaje' => 'Datos incompletos.'], 422);
}

$partesNombre = preg_split('/\s+/', $nombreCompleto);
$apellido = array_pop($partesNombre);
$nombre = implode(' ', $partesNombre);
$consultaRol = $conn->prepare('SELECT id_rol FROM rol WHERE nombre_rol = ? LIMIT 1');
$consultaRol->bind_param('s', $rol);
$consultaRol->execute();
$rolEncontrado = $consultaRol->get_result()->fetch_assoc();
if (!$rolEncontrado) {
    responder(['exito' => false, 'mensaje' => 'El rol seleccionado no existe.'], 422);
}
$idRol = (int) $rolEncontrado['id_rol'];

if ($password !== '') {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('UPDATE usuario SET nombre = ?, apellido = ?, email = ?, id_rol = ?, contrasena = ? WHERE id_usuario = ?');
    $stmt->bind_param('sssisi', $nombre, $apellido, $email, $idRol, $hash, $id);
} else {
    $stmt = $conn->prepare('UPDATE usuario SET nombre = ?, apellido = ?, email = ?, id_rol = ? WHERE id_usuario = ?');
    $stmt->bind_param('sssii', $nombre, $apellido, $email, $idRol, $id);
}

if (!$stmt->execute()) {
    responder(['exito' => false, 'mensaje' => 'No se pudo actualizar el usuario.'], 422);
}
responder(['exito' => true, 'mensaje' => 'Usuario actualizado correctamente.']);
