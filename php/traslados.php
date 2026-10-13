<?php
// Listado de traslados para la pantalla de seguimiento (admin/traslados.php).
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion();

// El nombre del chofer y del enfermero está en usuario, por eso hay dos JOIN
// a esa tabla (uc = usuario del chofer, ue = usuario del enfermero).
// El paciente va con LEFT JOIN porque ci_paciente puede ser NULL.
$resultado = $conn->query("SELECT t.id_traslado AS id, t.origen, t.origen_lat, t.origen_lng, t.destino, t.destino_lat, t.destino_lng, t.hora_salida, t.hora_llegada_estimada, t.hora_llegada_efectiva, t.estado, t.matricula, t.ci_paciente AS paciente_ci, CONCAT(p.nombre, ' ', p.apellido) AS paciente_nombre, CONCAT(uc.nombre, ' ', uc.apellido) AS chofer, CONCAT(ue.nombre, ' ', ue.apellido) AS enfermero FROM traslado t INNER JOIN chofer c ON c.ci_chofer = t.ci_chofer INNER JOIN usuario uc ON uc.id_usuario = c.id_usuario INNER JOIN enfermero e ON e.ci_enfermero = t.ci_enfermero INNER JOIN usuario ue ON ue.id_usuario = e.id_usuario LEFT JOIN paciente p ON p.ci_paciente = t.ci_paciente ORDER BY t.hora_salida DESC, t.id_traslado DESC");
$traslados = [];
while ($traslado = $resultado->fetch_assoc()) {
    $traslados[] = $traslado;
}
responder(['exito' => true, 'traslados' => $traslados]);
