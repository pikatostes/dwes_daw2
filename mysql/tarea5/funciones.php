<?php
function conectarBD() {
    $host = "localhost";
    $db = "tarea4";
    $user = "root";
    $pass = "";

    try {
        $conexion = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

function login($usuario, $password) {
    $conexion = conectarBD();
    $stmt = $conexion->prepare("SELECT pwd FROM usuarios WHERE usuario = :usuario");
    $stmt->bindParam(":usuario", $usuario);
    $stmt->execute();
    $hashed_password = $stmt->fetchColumn();
    $conexion = null;

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