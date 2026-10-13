<?php
// Lista de documentos y de categorías.
// No pide sesión: la usa también la vista de pacientes que se abre con el QR,
// y los documentos son información pública para ellos. Solo permite leer.
require_once __DIR__ . '/conexion.php';

$id = (int) ($_GET['id'] ?? 0);
$idCategoria = (int) ($_GET['categoria'] ?? 0);

$consulta = "SELECT d.id_documento AS id, d.titulo, COALESCE(d.descripcion, SUBSTRING_INDEX(d.ruta_archivo, '/', -1)) AS archivo_nombre, d.ruta_archivo AS archivo_ruta, d.fecha_subida, d.id_categoria, c.nombre_categoria AS categoria FROM documento d INNER JOIN categoria_documento c ON c.id_categoria = d.id_categoria";
$orden = ' ORDER BY d.fecha_subida DESC, d.id_documento DESC';

if ($id > 0) {
    // Un solo documento
    $stmt = $conn->prepare($consulta . ' WHERE d.id_documento = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
} elseif ($idCategoria > 0) {
    // Filtro por categoría
    $stmt = $conn->prepare($consulta . ' WHERE d.id_categoria = ?' . $orden);
    $stmt->bind_param('i', $idCategoria);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $resultado = $conn->query($consulta . $orden);
}

$documentos = [];
while ($documento = $resultado->fetch_assoc()) {
    $documentos[] = $documento;
}

// Las categorías van siempre, para llenar los select de los formularios y filtros.
$categorias = [];
$resultado = $conn->query('SELECT id_categoria AS id, nombre_categoria AS nombre FROM categoria_documento ORDER BY nombre_categoria');
while ($categoria = $resultado->fetch_assoc()) {
    $categorias[] = $categoria;
}

responder(['exito' => true, 'documentos' => $documentos, 'categorias' => $categorias]);
