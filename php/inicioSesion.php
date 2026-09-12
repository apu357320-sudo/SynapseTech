<?php
require_once __DIR__ . '/conexion.php';

$nombreUsuario = limpiar($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? '';

if ($nombreUsuario === '' || $password === '') {
    responder(['exito' => false, 'mensaje' => 'Completa el usuario y la contraseña.'], 422);
}

$stmt = $conn->prepare("SELECT u.id_usuario, u.ci AS nombre_usuario, u.contrasena AS password_hash, CONCAT(u.nombre, ' ', u.apellido) AS nombre_completo, u.email, r.nombre_rol AS rol, u.ci AS numeroId FROM usuario u INNER JOIN rol r ON r.id_rol = u.id_rol WHERE (u.ci = ? OR u.email = ?) AND u.estado = 1 LIMIT 1");
$stmt->bind_param('ss', $nombreUsuario, $nombreUsuario);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
    responder(['exito' => false, 'mensaje' => 'Usuario o contraseña incorrectos.'], 401);
}

session_start();
$_SESSION['usuario'] = $usuario['nombre_usuario'];
$_SESSION['id_usuario'] = (int) $usuario['id_usuario'];
$_SESSION['nombre_completo'] = $usuario['nombre_completo'];
$_SESSION['email'] = $usuario['email'];
$_SESSION['rol'] = $usuario['rol'];
$_SESSION['numeroId'] = $usuario['numeroId'];

responder([
    'exito' => true,
    'mensaje' => 'Inicio de sesión correcto.',
    'rol' => $usuario['rol'],
    'redireccion' => $usuario['rol'] === 'Administrador' ? 'admin/index.php' : 'usuario/index.php'
]);
