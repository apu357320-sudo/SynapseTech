<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion(true);

$resultado = $conn->query("SELECT u.id_usuario, u.ci AS nombre_usuario, CONCAT(u.nombre, ' ', u.apellido) AS nombre_completo, u.email, r.nombre_rol AS rol, u.ci AS numeroId, u.estado FROM usuario u INNER JOIN rol r ON r.id_rol = u.id_rol ORDER BY u.id_usuario DESC");
$usuarios = [];
while ($usuario = $resultado->fetch_assoc()) {
    $usuarios[] = $usuario;
}
responder(['exito' => true, 'usuarios' => $usuarios]);
