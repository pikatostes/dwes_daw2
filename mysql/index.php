<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Tablas</title>
</head>

<body>
    <?php
    session_start();

    // Verifica si la sesión está iniciada
    if (isset($_SESSION["userName"])) {
        // Si la sesión está iniciada, muestra un mensaje de bienvenida y un botón para cerrar la sesión
        echo "¡Bienvenido, " . $_SESSION["userName"] . "!";

        // Agrega un botón para cerrar la sesión
        echo '<form action="" method="post">
                <input type="submit" name="logout" value="Cerrar Sesión">
              </form>';

        // Verifica si se hizo clic en el botón de cerrar sesión
        if (isset($_POST["logout"])) {
            // Destruye la sesión para cerrarla
            session_destroy();

            // Redirige al usuario a esta misma página
            header("Location: login.php");
            exit();
        }
    } else {
    ?>
        <form action="" method="post">
            <input type="text" name="table" id="table">
            <input type="submit" value="Mostrar">
        </form>
    <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $tabla = isset($_POST["table"]) ? $_POST["table"] : null;

            if (!empty($tabla)) {
                include("tables.php");
                showTable($tabla);
            } else {
                echo "Debe proporcionar un nombre de tabla válido.";
            }
        }
    }
    ?>

</body>

</html>