<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion(true);

$id = (int) ($_POST['id'] ?? 0);
if ($id < 1) {
    responder(['exito' => false, 'mensaje' => 'Documento inválido.'], 422);
}

$stmt = $conn->prepare('SELECT ruta_archivo AS archivo_ruta FROM documento WHERE id_documento = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$documento = $stmt->get_result()->fetch_assoc();
if (!$documento) {
    responder(['exito' => false, 'mensaje' => 'Documento no encontrado.'], 404);
}

$stmt = $conn->prepare('DELETE FROM documento WHERE id_documento = ?');
$stmt->bind_param('i', $id);
if (!$stmt->execute()) {
    responder(['exito' => false, 'mensaje' => 'No se pudo eliminar el documento.'], 500);
}

$nombreFisico = basename($documento['archivo_ruta']);
$rutaFisica = RUTA_DOCUMENTOS . $nombreFisico;
if (is_file($rutaFisica)) {
    unlink($rutaFisica);
}
responder(['exito' => true, 'mensaje' => 'Documento eliminado.']);
