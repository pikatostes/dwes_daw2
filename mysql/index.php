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
    include("tables.php");

    // Verifica si la sesión está iniciada
    if (isset($_SESSION["userName"])) {
        // Si la sesión está iniciada, muestra un mensaje de bienvenida y un botón para cerrar la sesión
        echo "¡Bienvenido, " . $_SESSION["userName"] . "!";

        // Verifica si se hizo clic en el botón de cerrar sesión
        if (isset($_POST["logout"])) {
            // Destruye la sesión para cerrarla
            session_destroy();

            // Redirige al usuario a esta misma página
            header("Location: login.php");
            exit();
        }

    ?>
        <form action="" method="post">
            <input type="submit" value="Consultar" name="consultar">
            <input type="submit" value="Insertar" name="insertar">
            <input type="submit" value="Modificar">
            <input type="submit" value="Eliminar">
        </form>
    <?php
        if (isset($_POST["consultar"])) {
            echo '<form action="" method="post">
                    <input type="text" name="table" id="table">
                    <input type="submit" value="Mostrar">
                  </form>';
        }

        if (isset($_POST['insertar'])) {

            echo '<form action="" method="post">
                    <label for="tableIns">Nombre de la tabla:</label>
                    <input type="text" name="tableIns" id="tableIns">
                    <input type="submit" value="Mostrar">
                </form>';

           
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $tabla = isset($_POST["table"]) ? $_POST["table"] : null;

            if (!empty($tabla)) {
                showTable($tabla);
            } else {
                echo "Debe proporcionar un nombre de tabla válido.";
            }
        }

        if (isset($_POST["tableIns"])) {
            $selectedTable = $_POST["tableIns"];
            $fields = getTableStructure($selectedTable);

            if (!empty($fields)) {
                // Mostrar formulario de inserción si la tabla existe
                echo "<h2>Insertar Datos en la tabla '$selectedTable'</h2>";
                echo '<form action="" method="post">';
                echo "<input type='hidden' name='selected_table' value='$selectedTable'>";

                foreach ($fields as $field) {
                    echo "<label for='$field'>$field:</label>";
                    echo "<input type='text' name='data[$field]'><br>";
                }

                echo '<input type="submit" value="Insertar Datos">';
                echo '</form>';
            } else {
                echo "La tabla '$selectedTable' no existe.";
            }
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["selected_table"]) && isset($_POST["data"])) {
            $selectedTable = $_POST["selected_table"];
            $data = $_POST["data"];

            // Realiza la conexión a la base de datos con MySQLi
            $mysqli = new mysqli("localhost", "username", "password", "myDB");

            insertData($selectedTable, array_values($data), $mysqli);

            echo "Los datos han sido insertados en la tabla '$selectedTable' exitosamente.";

            $mysqli->close(); // Cierra la conexión a la base de datos
        }
    } else {
        header("Location: login.php");
    }
    ?>

</body>

</html>