<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion(true);

$id = (int) ($_POST['id'] ?? 0);
$titulo = limpiar($_POST['titulo'] ?? '');
if ($id < 1 || $titulo === '') {
    responder(['exito' => false, 'mensaje' => 'Indica un título válido.'], 422);
}

$stmt = $conn->prepare('UPDATE documento SET titulo = ? WHERE id_documento = ?');
$stmt->bind_param('si', $titulo, $id);
$stmt->execute();
responder(['exito' => $stmt->affected_rows >= 0, 'mensaje' => 'Documento actualizado correctamente.']);
