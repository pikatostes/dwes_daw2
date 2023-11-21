<?php
session_start();
require_once('funciones.php');

verificarSesion();

$usuario = $_SESSION['usuario'];
$hora_conexion = date('H:i:s', $_SESSION['hora_conexion']);

if (isset($_POST["cerrar_sesion"])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

$hora_conexion = date('H:i:s', $_SESSION['hora_conexion']);

// Obtener el color elegido
$chosenColor = isset($_COOKIE['chosenColor']) ? $_COOKIE['chosenColor'] : 'white';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplicación</title>
    <style>
        body {
            background-color: <?php echo $chosenColor; ?>;
        }
    </style>
</head>

<body>
    <h1>Aplicación</h1>
    <p>Bienvenido, <?php echo $usuario; ?>. Conectado desde las <?php echo $hora_conexion; ?>.</p>
    <ul>
        <li><a href="informacion.php">Ir a Información</a></li>
        <li><a href="preferencias.php">Ir a Preferencias</a></li>
        <li>
            <form method="post" action="">
                <input type="submit" value="Cerrar sesión" name="cerrar_sesion">
            </form>
        </li>
    </ul>
</body>

</html>