<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion(true);

$id = (int) ($_POST['id'] ?? 0);
if ($id < 1) {
    responder(['exito' => false, 'mensaje' => 'Traslado inválido.'], 422);
}

$stmt = $conn->prepare("UPDATE traslado SET estado = 'Cancelado' WHERE id_traslado = ? AND estado NOT IN ('Finalizado', 'Cancelado')");
$stmt->bind_param('i', $id);
$stmt->execute();
if ($stmt->affected_rows < 1) {
    responder(['exito' => false, 'mensaje' => 'No se pudo cancelar: el traslado no existe o ya está cerrado.'], 422);
}
responder(['exito' => true, 'mensaje' => 'Traslado cancelado.']);
