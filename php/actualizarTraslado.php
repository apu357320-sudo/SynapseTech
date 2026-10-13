<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion();

$id = (int) ($_POST['id'] ?? 0);
$estado = limpiar($_POST['estado'] ?? '');
$estados = ['Solicitado', 'En camino', 'En destino', 'Retornando', 'Finalizado'];
if ($id < 1 || !in_array($estado, $estados, true)) {
    responder(['exito' => false, 'mensaje' => 'Estado de traslado inválido.'], 422);
}

if ($estado === 'Finalizado') {
    $stmt = $conn->prepare('UPDATE traslado SET estado = ?, hora_llegada_efectiva = NOW() WHERE id_traslado = ? AND estado NOT IN ("Finalizado", "Cancelado")');
    $stmt->bind_param('si', $estado, $id);
} else {
    $stmt = $conn->prepare('UPDATE traslado SET estado = ? WHERE id_traslado = ? AND estado NOT IN ("Finalizado", "Cancelado")');
    $stmt->bind_param('si', $estado, $id);
}
$stmt->execute();
// Si no cambió ninguna fila es porque el traslado no existe, ya estaba cerrado
// (finalizado o cancelado) o ya tenía ese mismo estado.
if ($stmt->affected_rows < 1) {
    responder(['exito' => false, 'mensaje' => 'No se pudo cambiar el estado: el traslado ya está cerrado o ya tenía ese estado.'], 422);
}
responder(['exito' => true, 'mensaje' => 'Estado actualizado correctamente.']);
