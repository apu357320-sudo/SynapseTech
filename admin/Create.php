<?php
  require_once '../conexion.php';
  $nombre = $_POST['nombre'];
  $stmt= $conn->prepare("INSERT INTO usuarios (nombre) VALUES (?)");
  $stmt->bind_param("s", $nombre);

    if ($stmt->execute()) {
        echo "Usuario creado exitosamente.";
    } else {
        echo "Error al crear el usuario: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    ?>