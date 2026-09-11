<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion();

$resultado = $conn->query("SELECT t.*, c.nombre AS chofer, e.nombre AS enfermero FROM traslados t INNER JOIN choferes c ON c.id = t.id_chofer INNER JOIN enfermeros e ON e.id = t.id_enfermero ORDER BY t.fecha DESC, t.id DESC");
$traslados = [];
while ($traslado = $resultado->fetch_assoc()) {
    $traslados[] = $traslado;
}
responder(['exito' => true, 'traslados' => $traslados]);
