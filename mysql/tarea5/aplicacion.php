<?php
session_start();
include("funciones.php");
include("Users.php");

verificarSesion();

// Incluir archivo con la conexión a la base de datos
$pdo = conectarBD();
$usuarios = new Users($pdo);

// Lógica para manejar la modificación de usuarios
if (isset($_POST["modificar"])) {
    $usuarioId = $_POST["usuario_id"];
    // Obtener los datos del usuario seleccionado
    $usuarioSeleccionado = $usuarios->obtenerUsuarioPorId($usuarioId);
}

// Lógica para procesar el formulario de modificación
if (isset($_POST["modificar_usuario"])) {
    $usuarioId = $_POST["usuario_id"];
    $nuevosDatos = array(
        "usuario" => $_POST["nuevo_usuario"],
        "pwd" => $_POST["nueva_contrasena"],
        "email" => $_POST["nuevo_email"]
    );

    // Actualizar los datos del usuario
    $usuarios->modificarUsuario($usuarioId, $nuevosDatos);
}

// Lógica para mostrar la tabla de usuarios
$listaUsuarios = $usuarios->obtenerTodosLosUsuarios();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplicación</title>
</head>

<body>

    <form action="" method="post">
        <input type="submit" value="Añadir usuario" name="add">
        <input type="submit" value="Modificar usuario" name="mod">
        <input type="submit" value="Eliminar usuario" name="del">
    </form>

    <?php
    if (isset($_POST["add"])) {
        echo '
            <h2>Alta de Usuario</h2>
            <form action="aplicacion.php" method="post">
                <label for="user">Usuario:</label>
                <input type="text" id="user" name="user" required>
                <br>
                <label for="pwd">Contraseña:</label>
                <input type="password" id="pwd" name="pwd" required>
                <br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <br>
                <button type="submit" name="alta">Dar de Alta</button>
            </form>
        ';
    } elseif (isset($_POST["mod"])) {
        // Mostrar la tabla de usuarios con botón de Modificar
        echo '
            <h2>Lista de Usuarios</h2>
            <table border="1">
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>';

        foreach ($listaUsuarios as $usuario) {
            echo "<tr>
                    <td>{$usuario['usuario']}</td>
                    <td>{$usuario['email']}</td>
                    <td>
                        <form action='' method='post'>
                            <input type='hidden' name='usuario_id' value='{$usuario['id']}'>
                            <button type='submit' name='modificar'>Modificar</button>
                        </form>
                    </td>
                </tr>";
        }

        echo '</table>';
    } elseif (isset($_POST["del"])) {
        # Código para la eliminación de usuario
    }

    if (isset($_POST['alta'])) {
        $user = $_POST["user"];
        $pwd = $_POST["pwd"];
        $email = $_POST["email"];

        $usuarios->altaUsuario($user, $pwd, $email);
    } elseif (isset($usuarioSeleccionado)) {
        // Formulario para modificar datos del usuario seleccionado
        echo '
            <h2>Modificar Usuario</h2>
            <form action="" method="post">
                <label for="nuevo_usuario">Nuevo Usuario:</label>
                <input type="text" id="nuevo_usuario" name="nuevo_usuario" value="' . $usuarioSeleccionado["usuario"] . '" required>
                <br>
                <label for="nueva_contrasena">Nueva Contraseña:</label>
                <input type="password" id="nueva_contrasena" name="nueva_contrasena" required>
                <br>
                <label for="nuevo_email">Nuevo Email:</label>
                <input type="email" id="nuevo_email" name="nuevo_email" value="' . $usuarioSeleccionado["email"] . '" required>
                <br>
                <input type="hidden" name="usuario_id" value="' . $usuarioSeleccionado["id"] . '">
                <button type="submit" name="modificar_usuario">Guardar Cambios</button>
            </form>
        ';
    }
    ?>

</body>

</html>