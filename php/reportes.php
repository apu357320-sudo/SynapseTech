<?php
// Resultados de la encuesta de satisfacción para el panel de administración
// (admin/encuestas_panel.html). Devuelve los indicadores ya calculados.
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion(true);

// Ejecuta una consulta y devuelve todas sus filas en un arreglo.
function consultar($conn, $consulta)
{
    $filas = [];
    $resultado = $conn->query($consulta);
    while ($fila = $resultado->fetch_assoc()) {
        $filas[] = $fila;
    }
    return $filas;
}

// Los números de id_pregunta son los de la tabla pregunta:
// 1 servicio, 2 calificación (1 a 5), 3 claridad (Sí/No), 4 comentario.

$total = (int) consultar($conn, 'SELECT COUNT(*) AS total FROM respuesta_encuesta WHERE id_encuesta = 1')[0]['total'];

// Promedio de la calificación y cuántas son buenas (4 o 5).
$resumen = consultar($conn, 'SELECT AVG(valor_respuesta) AS promedio, SUM(valor_respuesta >= 4) AS satisfechos FROM detalle_respuesta WHERE id_pregunta = 2')[0];
$claras = (int) consultar($conn, "SELECT COUNT(*) AS total FROM detalle_respuesta WHERE id_pregunta = 3 AND valor_respuesta = 'Sí'")[0]['total'];

// Cuántas respuestas hubo de cada calificación y de cada servicio.
$calificaciones = consultar($conn, 'SELECT valor_respuesta AS valor, COUNT(*) AS cantidad FROM detalle_respuesta WHERE id_pregunta = 2 GROUP BY valor_respuesta ORDER BY valor_respuesta DESC');
$servicios = consultar($conn, 'SELECT valor_respuesta AS valor, COUNT(*) AS cantidad FROM detalle_respuesta WHERE id_pregunta = 1 GROUP BY valor_respuesta ORDER BY cantidad DESC');

// Últimos comentarios, con la fecha de la respuesta.
$comentarios = consultar($conn, 'SELECT d.valor_respuesta AS texto, r.fecha_respuesta AS fecha FROM detalle_respuesta d INNER JOIN respuesta_encuesta r ON r.id_respuesta = d.id_respuesta WHERE d.id_pregunta = 4 ORDER BY r.fecha_respuesta DESC LIMIT 10');

responder([
    'exito' => true,
    'total' => $total,
    'promedio' => $total > 0 ? round((float) $resumen['promedio'], 1) : 0,
    // Indicador de satisfacción: porcentaje de respuestas con 4 o 5.
    'porcentaje_satisfechos' => $total > 0 ? round($resumen['satisfechos'] * 100 / $total) : 0,
    'porcentaje_claro' => $total > 0 ? round($claras * 100 / $total) : 0,
    'calificaciones' => $calificaciones,
    'servicios' => $servicios,
    'comentarios' => $comentarios,
]);
