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
    $stmt = $conn->prepare('UPDATE traslados SET estado = ?, hora_llegada_real = NOW() WHERE id = ?');
    $stmt->bind_param('si', $estado, $id);
} else {
    $stmt = $conn->prepare('UPDATE traslados SET estado = ? WHERE id = ?');
    $stmt->bind_param('si', $estado, $id);
}
$stmt->execute();
responder(['exito' => $stmt->affected_rows >= 0, 'mensaje' => 'Estado actualizado correctamente.']);
