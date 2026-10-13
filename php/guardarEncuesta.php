<?php
require_once __DIR__ . '/conexion.php';

const ID_ENCUESTA = 1;
const PREGUNTA_SERVICIO = 1;
const PREGUNTA_CALIFICACION = 2;
const PREGUNTA_CLARIDAD = 3;
const PREGUNTA_COMENTARIO = 4;

$servicio = limpiar($_POST['servicio'] ?? '');
$calificacion = (int) ($_POST['calificacion'] ?? 0);
$claridad = limpiar($_POST['claridad'] ?? '');
$comentario = limpiar($_POST['comentario'] ?? '');

$servicios = ['Nefrología', 'Imagenología', 'Cardiología', 'Urología', 'Ginecología', 'Otro'];
if (!in_array($servicio, $servicios, true)) {
    responder(['exito' => false, 'mensaje' => 'Selecciona el servicio que quieres evaluar.'], 422);
}
if ($calificacion < 1 || $calificacion > 5) {
    responder(['exito' => false, 'mensaje' => 'Elige una calificación del 1 al 5.'], 422);
}
if ($claridad !== 'Sí' && $claridad !== 'No') {
    responder(['exito' => false, 'mensaje' => 'Indica si la información fue clara.'], 422);
}
if (mb_strlen($comentario) > 500) {
    responder(['exito' => false, 'mensaje' => 'El comentario puede tener hasta 500 caracteres.'], 422);
}

$stmt = $conn->prepare("SELECT id_encuesta FROM encuesta WHERE id_encuesta = ? AND estado = 'Activa'");
$idEncuesta = ID_ENCUESTA;
$stmt->bind_param('i', $idEncuesta);
$stmt->execute();
if (!$stmt->get_result()->fetch_assoc()) {
    responder(['exito' => false, 'mensaje' => 'La encuesta no está disponible.'], 404);
}

$respuestas = [
    PREGUNTA_SERVICIO => $servicio,
    PREGUNTA_CALIFICACION => (string) $calificacion,
    PREGUNTA_CLARIDAD => $claridad,
];
if ($comentario !== '') {
    $respuestas[PREGUNTA_COMENTARIO] = $comentario;
}

$conn->begin_transaction();

$stmt = $conn->prepare('INSERT INTO respuesta_encuesta (id_encuesta) VALUES (?)');
$stmt->bind_param('i', $idEncuesta);
if (!$stmt->execute()) {
    $conn->rollback();
    responder(['exito' => false, 'mensaje' => 'No se pudo guardar la encuesta.'], 500);
}
$idRespuesta = $conn->insert_id;

$stmt = $conn->prepare('INSERT INTO detalle_respuesta (id_respuesta, id_pregunta, valor_respuesta) VALUES (?, ?, ?)');
foreach ($respuestas as $idPregunta => $valor) {
    $stmt->bind_param('iis', $idRespuesta, $idPregunta, $valor);
    if (!$stmt->execute()) {
        $conn->rollback();
        responder(['exito' => false, 'mensaje' => 'No se pudo guardar la encuesta.'], 500);
    }
}

$conn->commit();
responder(['exito' => true, 'mensaje' => '¡Gracias! Tu respuesta fue registrada.']);
