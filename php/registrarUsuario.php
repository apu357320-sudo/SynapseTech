<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion(true);

$nombreUsuario = limpiar($_POST['nombre_usuario'] ?? '');
$password = $_POST['password'] ?? '';
$nombreCompleto = limpiar($_POST['nombre_completo'] ?? '');
$email = limpiar($_POST['email'] ?? '');
$rol = limpiar($_POST['rol'] ?? '');
$numeroId = limpiar($_POST['numeroId'] ?? '');

if ($nombreUsuario === '' || $password === '' || $nombreCompleto === '' || $email === '' || $rol === '' || $numeroId === '') {
    responder(['exito' => false, 'mensaje' => 'Completá todos los campos.'], 422);
}

$partesNombre = preg_split('/\s+/', $nombreCompleto);
$apellido = array_pop($partesNombre);
$nombre = implode(' ', $partesNombre);
$hash = password_hash($password, PASSWORD_DEFAULT);
$consultaRol = $conn->prepare('SELECT id_rol FROM rol WHERE nombre_rol = ? LIMIT 1');
$consultaRol->bind_param('s', $rol);
$consultaRol->execute();
$rolEncontrado = $consultaRol->get_result()->fetch_assoc();
if (!$rolEncontrado) {
    responder(['exito' => false, 'mensaje' => 'El rol seleccionado no existe.'], 422);
}

$idRol = (int) $rolEncontrado['id_rol'];
$stmt = $conn->prepare('INSERT INTO usuario (ci, nombre, apellido, email, contrasena, estado, id_rol) VALUES (?, ?, ?, ?, ?, 1, ?)');
$stmt->bind_param('sssssi', $numeroId, $nombre, $apellido, $email, $hash, $idRol);

if (!$stmt->execute()) {
    $mensaje = $stmt->errno === 1062 ? 'La cédula o el email ya existe.' : 'No se pudo registrar el usuario.';
    responder(['exito' => false, 'mensaje' => $mensaje], 422);
}

responder(['exito' => true, 'mensaje' => 'Usuario registrado correctamente.']);
