<?php
session_start();
require_once('funciones.php');

verificarSesion();

$usuario = $_SESSION['usuario'];
$hora_conexion = date('H:i:s', $_SESSION['hora_conexion']);

$chosenColor = isset($_COOKIE['chosenColor']) ? $_COOKIE['chosenColor'] : 'white';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información</title>
    <style>
        body {
            background-color: <?php echo $chosenColor; ?>;
        }
    </style>
</head>
<body>
    <h1>Información</h1>
    <p>Bienvenido, <?php echo $usuario; ?>. Conectado desde las <?php echo $hora_conexion; ?>.</p>
    <p>Esta es la página de información de la aplicación.</p>
    <p><a href="aplicacion.php">Volver a la página de inicio</a></p>
</body>
</html>
