<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion(true);

$id = (int) ($_POST['id'] ?? 0);
if ($id < 1) {
    responder(['exito' => false, 'mensaje' => 'Traslado inválido.'], 422);
}

$stmt = $conn->prepare("UPDATE traslados SET estado = 'Cancelado' WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
responder(['exito' => true, 'mensaje' => 'Traslado cancelado.']);
