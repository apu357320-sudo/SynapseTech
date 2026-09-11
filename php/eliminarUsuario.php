<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion(true);

$id = (int) ($_POST['id_usuario'] ?? 0);
if ($id < 1 || $id === (int) ($_SESSION['id_usuario'] ?? 0)) {
    responder(['exito' => false, 'mensaje' => 'No puedes eliminar ese usuario.'], 422);
}

$documentos = $conn->prepare('SELECT COUNT(*) AS total FROM documento WHERE id_usuario_admin = ?');
$documentos->bind_param('i', $id);
$documentos->execute();
if ((int) $documentos->get_result()->fetch_assoc()['total'] > 0) {
    responder(['exito' => false, 'mensaje' => 'No puedes eliminar un usuario que tiene documentos asociados.'], 422);
}

$stmt = $conn->prepare('DELETE FROM usuario WHERE id_usuario = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
responder(['exito' => $stmt->affected_rows > 0, 'mensaje' => $stmt->affected_rows > 0 ? 'Usuario eliminado.' : 'Usuario no encontrado.']);
