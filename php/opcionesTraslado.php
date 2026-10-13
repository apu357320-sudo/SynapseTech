<?php
// Devuelve las opciones de los <select> del formulario de traslado:
// choferes, enfermeros y vehículos disponibles, leídos de la base de datos.
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion();

// Ejecuta una consulta que devuelve las columnas "valor" y "texto"
// y arma la lista que después recorre el JavaScript.
function listarOpciones($conn, $consulta)
{
    $opciones = [];
    $resultado = $conn->query($consulta);
    while ($opcion = $resultado->fetch_assoc()) {
        $opciones[] = $opcion;
    }
    return $opciones;
}

// El nombre del chofer y del enfermero está en la tabla usuario.
$choferes = listarOpciones($conn, "SELECT c.ci_chofer AS valor, CONCAT(u.nombre, ' ', u.apellido) AS texto FROM chofer c INNER JOIN usuario u ON u.id_usuario = c.id_usuario WHERE c.disponibilidad = 1 AND u.estado = 1 ORDER BY u.nombre");
$enfermeros = listarOpciones($conn, "SELECT e.ci_enfermero AS valor, CONCAT(u.nombre, ' ', u.apellido) AS texto FROM enfermero e INNER JOIN usuario u ON u.id_usuario = e.id_usuario WHERE e.disponibilidad = 1 AND u.estado = 1 ORDER BY u.nombre");
$vehiculos = listarOpciones($conn, "SELECT matricula AS valor, CONCAT(tipo_vehiculo, ' ', matricula) AS texto FROM vehiculo WHERE disponibilidad = 1 AND estado = 'Operativo' ORDER BY tipo_vehiculo, matricula");

responder(['exito' => true, 'choferes' => $choferes, 'enfermeros' => $enfermeros, 'vehiculos' => $vehiculos]);
