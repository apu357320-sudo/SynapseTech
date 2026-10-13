<?php
// Alta de un traslado. Recibe los datos del formulario (admin/traslado_form.php),
// los valida y guarda el paciente y el traslado en la base de datos.
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion();

// Rectángulo que encierra a Uruguay: los traslados son dentro del circuito nacional.
const LATITUD_MINIMA = -35.1;
const LATITUD_MAXIMA = -30.0;
const LONGITUD_MINIMA = -58.5;
const LONGITUD_MAXIMA = -53.0;

// Convierte la fecha del <input type="datetime-local"> (2026-10-12T14:30)
// al formato DATETIME de MySQL. Devuelve null si la fecha no es válida.
function convertirFecha($valor)
{
    $fecha = DateTime::createFromFormat('Y-m-d\TH:i', $valor);
    return $fecha ? $fecha->format('Y-m-d H:i:s') : null;
}

// Indica si un par de coordenadas son números y caen dentro de Uruguay.
function coordenadasValidas($latitud, $longitud)
{
    if (!is_numeric($latitud) || !is_numeric($longitud)) {
        return false;
    }
    return $latitud >= LATITUD_MINIMA && $latitud <= LATITUD_MAXIMA
        && $longitud >= LONGITUD_MINIMA && $longitud <= LONGITUD_MAXIMA;
}

// 1) Recibir los datos
$ciPaciente = limpiar($_POST['paciente_ci'] ?? '');
$nombrePaciente = limpiar($_POST['paciente_nombre'] ?? '');
$apellidoPaciente = limpiar($_POST['paciente_apellido'] ?? '');
$ciChofer = limpiar($_POST['ci_chofer'] ?? '');
$ciEnfermero = limpiar($_POST['ci_enfermero'] ?? '');
$matricula = limpiar($_POST['matricula'] ?? '');
$origen = limpiar($_POST['origen'] ?? '');
$destino = limpiar($_POST['destino'] ?? '');
$origenLat = limpiar($_POST['origen_lat'] ?? '');
$origenLng = limpiar($_POST['origen_lng'] ?? '');
$destinoLat = limpiar($_POST['destino_lat'] ?? '');
$destinoLng = limpiar($_POST['destino_lng'] ?? '');
$horaSalida = limpiar($_POST['hora_salida'] ?? '');
$horaEstimada = limpiar($_POST['hora_llegada_estimada'] ?? '');

// 2) Validar (el JavaScript ya valida, pero el servidor no puede confiar en eso)
if ($ciPaciente === '' || $nombrePaciente === '' || $apellidoPaciente === '' || $ciChofer === '' || $ciEnfermero === '' || $matricula === '' || $origen === '' || $destino === '' || $horaSalida === '') {
    responder(['exito' => false, 'mensaje' => 'Completa los datos obligatorios del traslado.'], 422);
}

if (!preg_match('/^\d{7,8}$/', $ciPaciente)) {
    responder(['exito' => false, 'mensaje' => 'La cédula debe tener 7 u 8 dígitos, sin puntos ni guion.'], 422);
}

if (!coordenadasValidas($origenLat, $origenLng) || !coordenadasValidas($destinoLat, $destinoLng)) {
    responder(['exito' => false, 'mensaje' => 'Marca el origen y el destino en el mapa, dentro de Uruguay.'], 422);
}

$horaSalida = convertirFecha($horaSalida);
if ($horaSalida === null) {
    responder(['exito' => false, 'mensaje' => 'La hora de salida no es válida.'], 422);
}

// La hora estimada es opcional, pero si viene tiene que ser posterior a la salida.
if ($horaEstimada !== '') {
    $horaEstimada = convertirFecha($horaEstimada);
    if ($horaEstimada === null || $horaEstimada <= $horaSalida) {
        responder(['exito' => false, 'mensaje' => 'La llegada estimada debe ser posterior a la salida.'], 422);
    }
} else {
    $horaEstimada = null;
}

$origenLat = (float) $origenLat;
$origenLng = (float) $origenLng;
$destinoLat = (float) $destinoLat;
$destinoLng = (float) $destinoLng;

// 3) Guardar. Son dos INSERT que dependen uno del otro, por eso van en una
//    transacción: si falla el segundo, se deshace el primero.
$conn->begin_transaction();

// El traslado referencia al paciente por su cédula (clave foránea), así que
// el paciente tiene que existir. Si ya estaba cargado, se actualiza el nombre.
$stmt = $conn->prepare('INSERT INTO paciente (ci_paciente, nombre, apellido) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE nombre = VALUES(nombre), apellido = VALUES(apellido)');
$stmt->bind_param('sss', $ciPaciente, $nombrePaciente, $apellidoPaciente);
if (!$stmt->execute()) {
    $conn->rollback();
    responder(['exito' => false, 'mensaje' => 'No se pudo guardar el paciente.'], 422);
}

$stmt = $conn->prepare("INSERT INTO traslado (origen, origen_lat, origen_lng, destino, destino_lat, destino_lng, hora_salida, hora_llegada_estimada, estado, matricula, ci_chofer, ci_enfermero, ci_paciente) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Solicitado', ?, ?, ?, ?)");
$stmt->bind_param('sddsddssssss', $origen, $origenLat, $origenLng, $destino, $destinoLat, $destinoLng, $horaSalida, $horaEstimada, $matricula, $ciChofer, $ciEnfermero, $ciPaciente);
if (!$stmt->execute()) {
    // 1452: falló una clave foránea (el chofer, el enfermero o el vehículo no existe).
    $mensaje = $stmt->errno === 1452 ? 'El chofer, el enfermero o el vehículo elegido no existe.' : 'No se pudo guardar el traslado.';
    $conn->rollback();
    responder(['exito' => false, 'mensaje' => $mensaje], 422);
}

$conn->commit();
responder(['exito' => true, 'mensaje' => 'Traslado solicitado correctamente.']);
