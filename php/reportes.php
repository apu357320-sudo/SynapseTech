<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion(true);

function consultar($conn, $consulta)
{
    $filas = [];
    $resultado = $conn->query($consulta);
    while ($fila = $resultado->fetch_assoc()) {
        $filas[] = $fila;
    }
    return $filas;
}

$total = (int) consultar($conn, 'SELECT COUNT(*) AS total FROM respuesta_encuesta WHERE id_encuesta = 1')[0]['total'];

$resumen = consultar($conn, 'SELECT AVG(valor_respuesta) AS promedio, SUM(valor_respuesta >= 4) AS satisfechos FROM detalle_respuesta WHERE id_pregunta = 2')[0];
$claras = (int) consultar($conn, "SELECT COUNT(*) AS total FROM detalle_respuesta WHERE id_pregunta = 3 AND valor_respuesta = 'Sí'")[0]['total'];

$calificaciones = consultar($conn, 'SELECT valor_respuesta AS valor, COUNT(*) AS cantidad FROM detalle_respuesta WHERE id_pregunta = 2 GROUP BY valor_respuesta ORDER BY valor_respuesta DESC');
$servicios = consultar($conn, 'SELECT valor_respuesta AS valor, COUNT(*) AS cantidad FROM detalle_respuesta WHERE id_pregunta = 1 GROUP BY valor_respuesta ORDER BY cantidad DESC');

$comentarios = consultar($conn, 'SELECT d.valor_respuesta AS texto, r.fecha_respuesta AS fecha FROM detalle_respuesta d INNER JOIN respuesta_encuesta r ON r.id_respuesta = d.id_respuesta WHERE d.id_pregunta = 4 ORDER BY r.fecha_respuesta DESC LIMIT 10');

responder([
    'exito' => true,
    'total' => $total,
    'promedio' => $total > 0 ? round((float) $resumen['promedio'], 1) : 0,
    'porcentaje_satisfechos' => $total > 0 ? round($resumen['satisfechos'] * 100 / $total) : 0,
    'porcentaje_claro' => $total > 0 ? round($claras * 100 / $total) : 0,
    'calificaciones' => $calificaciones,
    'servicios' => $servicios,
    'comentarios' => $comentarios,
]);
