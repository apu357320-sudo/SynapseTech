<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion();

$titulo = limpiar($_POST['titulo'] ?? '');
$archivo = $_FILES['archivo'] ?? null;
$extensionesPermitidas = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'doc', 'docx'];

if ($titulo === '' || !$archivo || $archivo['error'] !== UPLOAD_ERR_OK) {
    responder(['exito' => false, 'mensaje' => 'Indicá un título y seleccioná un archivo.'], 422);
}

$extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
if (!in_array($extension, $extensionesPermitidas, true)) {
    responder(['exito' => false, 'mensaje' => 'Tipo de archivo no permitido.'], 422);
}

if (!is_dir(RUTA_DOCUMENTOS) && !mkdir(RUTA_DOCUMENTOS, 0755, true)) {
    responder(['exito' => false, 'mensaje' => 'No se pudo crear la carpeta de documentos.'], 500);
}

$nombreArchivo = uniqid('doc_', true) . '.' . $extension;
$rutaFisica = RUTA_DOCUMENTOS . $nombreArchivo;
$rutaBase = 'documentos/' . $nombreArchivo;

if (!move_uploaded_file($archivo['tmp_name'], $rutaFisica)) {
    responder(['exito' => false, 'mensaje' => 'No se pudo guardar el archivo.'], 500);
}

$idUsuario = (int) $_SESSION['id_usuario'];
$fechaSubida = date('Y-m-d');
$idCategoria = 1;
$stmt = $conn->prepare('INSERT INTO documento (titulo, descripcion, ruta_archivo, fecha_subida, id_categoria, id_usuario_admin) VALUES (?, ?, ?, ?, ?, ?)');
$stmt->bind_param('ssssii', $titulo, $archivo['name'], $rutaBase, $fechaSubida, $idCategoria, $idUsuario);

if (!$stmt->execute()) {
    unlink($rutaFisica);
    responder(['exito' => false, 'mensaje' => 'No se pudo guardar el documento en la base de datos.'], 500);
}

responder(['exito' => true, 'mensaje' => 'Documento cargado correctamente.']);
