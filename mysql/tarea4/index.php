<?php
session_start();
require_once('funciones.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    if (login($usuario, $password)) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['hora_conexion'] = time();
        header('Location: aplicacion.php');
        exit;
    } else {
        $mensaje_error = "Usuario o contraseña incorrectos.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
</head>
<body>
    <h1>Inicio de sesión</h1>
    <?php if (isset($mensaje_error)) : ?>
        <p><?php echo $mensaje_error; ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" value="Iniciar sesión">
        <br>
        <p><a href="informacion.php">Acceder como invitado</a></p>
    </form>
</body>
</html>
