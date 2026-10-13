<?php
// Alta de un usuario desde el panel de administración.
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';
exigirSesion(true);

// 1) Recibir los datos (numeroId es la cédula: el usuario entra con ella)
$password = $_POST['password'] ?? '';
$nombreCompleto = limpiar($_POST['nombre_completo'] ?? '');
$email = limpiar($_POST['email'] ?? '');
$rol = limpiar($_POST['rol'] ?? '');
$numeroId = limpiar($_POST['numeroId'] ?? '');

// 2) Validar
if ($password === '' || $nombreCompleto === '' || $email === '' || $rol === '' || $numeroId === '') {
    responder(['exito' => false, 'mensaje' => 'Completa todos los campos.'], 422);
}

if (!preg_match('/^\d{7,8}$/', $numeroId)) {
    responder(['exito' => false, 'mensaje' => 'La cédula debe tener 7 u 8 dígitos, sin puntos ni guion.'], 422);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    responder(['exito' => false, 'mensaje' => 'El email no es válido.'], 422);
}

// La tabla guarda nombre y apellido separados: la última palabra es el apellido.
$partesNombre = preg_split('/\s+/', $nombreCompleto);
$apellido = array_pop($partesNombre);
$nombre = implode(' ', $partesNombre);
$hash = password_hash($password, PASSWORD_DEFAULT);

$consultaRol = $conn->prepare('SELECT id_rol FROM rol WHERE nombre_rol = ? LIMIT 1');
$consultaRol->bind_param('s', $rol);
$consultaRol->execute();
$rolEncontrado = $consultaRol->get_result()->fetch_assoc();
if (!$rolEncontrado) {
    responder(['exito' => false, 'mensaje' => 'El rol seleccionado no existe.'], 422);
}
$idRol = (int) $rolEncontrado['id_rol'];

// 3) Guardar. Un chofer o un enfermero necesita además su fila en la tabla
//    chofer o enfermero (si no, no aparece al crear un traslado). Son dos
//    INSERT que dependen uno del otro, por eso van en una transacción.
$conn->begin_transaction();

$stmt = $conn->prepare('INSERT INTO usuario (ci, nombre, apellido, email, contrasena, estado, id_rol) VALUES (?, ?, ?, ?, ?, 1, ?)');
$stmt->bind_param('sssssi', $numeroId, $nombre, $apellido, $email, $hash, $idRol);
if (!$stmt->execute()) {
    // 1062: se repite un valor que es UNIQUE (la cédula o el email).
    $mensaje = $stmt->errno === 1062 ? 'La cédula o el email ya existe.' : 'No se pudo registrar el usuario.';
    $conn->rollback();
    responder(['exito' => false, 'mensaje' => $mensaje], 422);
}
$idUsuario = $conn->insert_id;

if ($rol === 'Chofer' || $rol === 'Enfermero') {
    if ($rol === 'Chofer') {
        $stmt = $conn->prepare('INSERT INTO chofer (ci_chofer, id_usuario) VALUES (?, ?)');
    } else {
        $stmt = $conn->prepare('INSERT INTO enfermero (ci_enfermero, id_usuario) VALUES (?, ?)');
    }
    $stmt->bind_param('si', $numeroId, $idUsuario);
    if (!$stmt->execute()) {
        $conn->rollback();
        responder(['exito' => false, 'mensaje' => 'No se pudo registrar el usuario.'], 422);
    }
}

$conn->commit();
responder(['exito' => true, 'mensaje' => 'Usuario registrado correctamente.']);
