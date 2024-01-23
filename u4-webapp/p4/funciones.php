<?php

function conectarBD() {
    $conexion = new mysqli("localhost", "root", "", "tarea4");

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    return $conexion;
}

function login($usuario, $password) {
    $conexion = conectarBD();
    $stmt = $conexion->prepare("SELECT pwd FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();
    $conexion->close();

    if (password_verify($password, $hashed_password)) {
        return true;
    } else {
        return false;
    }
}

function verificarSesion() {
    // Iniciar la sesión si aún no está iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['usuario'])) {
        // Si no ha iniciado sesión, redirigir a la página de inicio de sesión
        header('Location: index.php');
        exit;
    }
}
?>