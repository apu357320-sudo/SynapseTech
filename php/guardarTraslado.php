<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion();

$idChofer = (int) ($_POST['id_chofer'] ?? 0);
$idEnfermero = (int) ($_POST['id_enfermero'] ?? 0);
$pacienteCi = limpiar($_POST['paciente_ci'] ?? '');
$pacienteNombre = limpiar($_POST['paciente_nombre'] ?? '');
$tipoElemento = limpiar($_POST['tipo_elemento'] ?? 'Paciente');
$vehiculo = limpiar($_POST['vehiculo'] ?? '');
$origen = limpiar($_POST['origen'] ?? '');
$destino = limpiar($_POST['destino'] ?? '');
$horaSalida = limpiar($_POST['hora_salida'] ?? '');
$horaEstimada = limpiar($_POST['hora_llegada_estimada'] ?? '');
$observaciones = limpiar($_POST['observaciones'] ?? '');

if ($idChofer < 1 || $idEnfermero < 1 || $pacienteCi === '' || $pacienteNombre === '' || $vehiculo === '' || $origen === '' || $destino === '' || $horaSalida === '') {
    responder(['exito' => false, 'mensaje' => 'Completa los datos obligatorios del traslado.'], 422);
}

$horaSalida = str_replace('T', ' ', $horaSalida);
$horaEstimada = $horaEstimada === '' ? null : str_replace('T', ' ', $horaEstimada);
$stmt = $conn->prepare('INSERT INTO traslados (id_chofer, id_enfermero, paciente_ci, paciente_nombre, tipo_elemento, vehiculo, origen, destino, hora_salida, hora_llegada_estimada, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param('iisssssssss', $idChofer, $idEnfermero, $pacienteCi, $pacienteNombre, $tipoElemento, $vehiculo, $origen, $destino, $horaSalida, $horaEstimada, $observaciones);

if (!$stmt->execute()) {
    responder(['exito' => false, 'mensaje' => 'No se pudo guardar el traslado.'], 422);
}
responder(['exito' => true, 'mensaje' => 'Traslado solicitado correctamente.']);
