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
            <input type="submit" value="Modificar" name="modificar">
            <input type="submit" value="Eliminar" name="eliminar">
        </form>

    <?php
        if (isset($_POST["consultar"]) || isset($_POST['insertar']) || isset($_POST['modificar'])) {
            $databaseConnection = connectToDatabase();
            $tables = getAllTables($databaseConnection);

            echo '<form action="" method="post">';

            if (isset($_POST["consultar"])) {
                echo '<label for="table">Selecciona una tabla:</label>
                    <select name="table" id="table">';
                foreach ($tables as $table) {
                    $selected = ($_POST["table"] == $table) ? 'selected' : '';
                    echo '<option value="' . $table . '" ' . $selected . '>' . $table . '</option>';
                }
                echo '<input type="submit" value="Mostrar">';
            }

            if (isset($_POST['insertar'])) {
                echo '<label for="tableIns">Nombre de la tabla:</label>
                    <select name="tableIns" id="tableIns">';
                foreach ($tables as $table) {
                    echo '<option value="' . $table . '">' . $table . '</option>';
                }
                echo '<input type="submit" value="Mostrar">';
            }

            if (isset($_POST['modificar'])) {
                echo '<label for="tableMod">Nombre de la tabla:</label>
                    <select name="tableMod" id="tableMod">';
                foreach ($tables as $table) {
                    echo '<option value="' . $table . '">' . $table . '</option>';
                }
                echo '<input type="submit" value="Mostrar">';
            }

            echo '</form>';
        }


        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (isset($_POST["table"])) {
                $selectedTable = $_POST["table"];

                if (isset($_POST["modificar"])) {
                } else {
                    showTable($selectedTable);
                }

                // Mantén el valor seleccionado en el select
                echo '<form action="" method="post">
                <label for="table">Selecciona una tabla:</label>
                <select name="table" id="table">';
                $databaseConnection = connectToDatabase();
                $tables = getAllTables($databaseConnection);

                foreach ($tables as $table) {
                    $selected = ($_POST["table"] == $table) ? 'selected' : '';
                    echo '<option value="' . $table . '" ' . $selected . '>' . $table . '</option>';
                }

                echo '</select>';

                if (isset($_POST['insertar']) || isset($_POST['modificar'])) {
                    echo '<input type="submit" value="Mostrar">';
                }

                echo '</form>';
            } elseif (isset($_POST["tableIns"])) {
                $selectedTable = $_POST["tableIns"];
                $fields = getTableStructure($selectedTable);

                if (!empty($fields)) {
                    // Mostrar formulario de inserción con campos según la tabla seleccionada
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
            } elseif (isset($_POST["tableMod"])) {
                $selectedTable = $_POST["tableMod"];
            
                // Verificar si se hizo clic en el botón "Modificar" de una fila específica
                if (isset($_POST['modify_row']) && isset($_POST['row_id']) && isset($_POST['tableMod'])) {
                    $selectedTable = $_POST['tableMod'];
                    $rowId = $_POST['row_id'];
            
                    // Llama a la función para mostrar el formulario de edición
                    showEditForm($selectedTable, $rowId);
            
                    // Verificar si se envió el formulario de edición y procesarlo
                    if (isset($_POST["save_changes"])) {
                        $table = $_POST["table"];
                        $rowId = $_POST["row_id"];
                        $data = $_POST["data"];
                    
                        echo "Se ha enviado el formulario de edición<br>";
                        echo "Tabla: $table<br>";
                        echo "Fila ID: $rowId<br>";
                        var_dump($data); // Puedes usar var_dump para ver el contenido del array de datos
                    
                        // Llama a la función para actualizar los datos
                        $updateResult = updateData($table, $rowId, $data, $databaseConnection);
                    
                        if ($updateResult) {
                            echo "Los datos se han actualizado exitosamente en la tabla '$table'.";
                        } else {
                            echo "Error al actualizar datos en la tabla '$table'.";
                        }
                    
                        $databaseConnection->close(); // Cierra la conexión a la base de datos
                    }                    
                } else {
                    // Mostrar la tabla con botones "Modificar" para cada fila
                    showTableWithModifyButton($selectedTable);
                }
            }            
            
        }
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["selected_table"]) && isset($_POST["data"])) {
            $selectedTable = $_POST["selected_table"];
            $data = $_POST["data"];

            $mysqli = connectToDatabase(); // Obtener la conexión a la base de datos

            // Realizar la inserción de datos
            $insertResult = insertData($selectedTable, $data, $mysqli);

            if ($insertResult) {
                echo "Los datos han sido insertados en la tabla '$selectedTable' exitosamente.";
            } else {
                echo "Error al insertar datos en la tabla '$selectedTable'.";
            }

            $mysqli->close(); // Cierra la conexión a la base de datos
        }
    } else {
        header("Location: login.php");
    }
    ?>
</body>

</html>