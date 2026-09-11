<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion();

$id = (int) ($_GET['id'] ?? 0);
$consulta = "SELECT d.id_documento AS id, d.titulo, COALESCE(d.descripcion, SUBSTRING_INDEX(d.ruta_archivo, '/', -1)) AS archivo_nombre, d.ruta_archivo AS archivo_ruta, d.fecha_subida FROM documento d";
if ($id > 0) {
    $stmt = $conn->prepare($consulta . ' WHERE d.id_documento = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $resultado = $conn->query($consulta . ' ORDER BY d.fecha_subida DESC, d.id_documento DESC');
}
$documentos = [];
while ($documento = $resultado->fetch_assoc()) {
    $documentos[] = $documento;
}
responder(['exito' => true, 'documentos' => $documentos]);
