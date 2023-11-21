<?php
session_start();
include('funciones.php');

verificarSesion();

$usuario = $_SESSION['usuario'];
$hora_conexion = date('H:i:s', $_SESSION['hora_conexion']);

// Procesar la solicitud de cambio de color si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["color"])) {
    $color = $_POST["color"];
    
    // Establecer la cookie con el nuevo color
    setcookie('chosenColor', $color, time() + 3600, '/'); // Caduca en una hora
    
    // Actualizar la variable $chosenColor para reflejar el cambio inmediato
    $chosenColor = $color;
} else {
    // Obtener el color elegido
    $chosenColor = isset($_COOKIE['chosenColor']) ? $_COOKIE['chosenColor'] : 'white';
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preferencias</title>
    <style>
        body {
            background-color: <?php echo $chosenColor; ?>;
        }
    </style>
</head>

<body>
    <h1>Preferencias</h1>
    <p>Bienvenido, <?php echo $usuario; ?>. Conectado desde las <?php echo $hora_conexion; ?>.</p>
    <form method="post" action="preferencias.php">
        <label for="color">Selecciona tu color de fondo preferido:</label>
        <select name="color" id="color">
            <option value="white" <?php echo ($chosenColor === 'white') ? 'selected' : ''; ?>>Blanco</option>
            <option value="blue" <?php echo ($chosenColor === 'blue') ? 'selected' : ''; ?>>Azul</option>
            <option value="green" <?php echo ($chosenColor === 'green') ? 'selected' : ''; ?>>Verde</option>
            <option value="red" <?php echo ($chosenColor === 'red') ? 'selected' : ''; ?>>Rojo</option>
        </select>
        <input type="submit" value="Guardar preferencias">
    </form>
    <form method="post" action="">
        <input type="submit" name="restablecer" value="Restablecer preferencias">
    </form>
    <p><a href="aplicacion.php">Volver a la aplicación</a></p>
</body>

</html>
