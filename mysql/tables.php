<?php
// funciona
function connectToDatabase()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "ventas_comerciales";

    $databaseConnection = new mysqli($servername, $username, $password, $database);

    if ($databaseConnection->connect_error) {
        die("Conexión fallida: " . $databaseConnection->connect_error);
    }

    return $databaseConnection;
}

// funciona
function getAllTables($databaseConnection)
{
    $tables = array();

    // Consulta para obtener el nombre de todas las tablas en la base de datos
    $sql = "SHOW TABLES";
    $result = $databaseConnection->query($sql);

    if ($result) {
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
    }

    return $tables;
}

// funciona
function showTable($table)
{
    $databaseConnection = connectToDatabase();

    if (empty($table)) {
        echo "Debe proporcionar el nombre de una tabla.";
        return;
    }

    // Construye la consulta SQL directamente con el nombre de la tabla
    $sql = "SHOW TABLES LIKE '$table'";

    $result = $databaseConnection->query($sql);

    if ($result->num_rows == 0) {
        echo "La tabla '" . $table . "' no existe en la base de datos.";
    } else {
        displayTable($table, $databaseConnection);
    }

    $databaseConnection->close();
}

// funciona
function displayTable($table, $databaseConnection)
{
    $sql = "SELECT * FROM " . $table;
    $result = $databaseConnection->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'>";

        $firstRow = $result->fetch_assoc();
        echo "<tr>";
        foreach ($firstRow as $column => $value) {
            echo "<th>$column</th>";
        }
        echo "</tr>";

        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . $value . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron resultados.";
    }
}

// funciona
function getTableStructure($tableName)
{
    $databaseConnection = connectToDatabase();
    $fields = [];

    $sql = "DESCRIBE $tableName";
    $result = $databaseConnection->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $fields[] = $row["Field"];
        }
        $result->free();
    }

    return $fields;
}

// funciona
function insertData($tableName, $data, $mysqli)
{
    // Verificar si la tabla existe antes de insertar datos
    if (!$mysqli || $mysqli->connect_error) {
        die("Error de conexión a la base de datos: " . $mysqli->connect_error);
    }

    $fields = getTableStructure($tableName);
    $fieldNames = implode(', ', $fields);
    $placeholders = rtrim(str_repeat('?, ', count($fields)), ', '); // Crear los marcadores de posición

    $sql = "INSERT INTO $tableName ($fieldNames) VALUES ($placeholders)";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $types = ''; // Determine data types for binding parameters
        $bindValues = array();

        foreach ($fields as $field) {
            // Verificar si el campo existe en los datos
            if (isset($data[$field])) {
                $value = $data[$field];

                if (is_numeric($value)) {
                    if (strpos($value, '.') !== false) {
                        $types .= 'd'; // Double
                    } else {
                        $types .= 'i'; // Integer
                    }
                } elseif (strtotime($value) !== false) {
                    $types .= 's'; // Date (Assuming it's a valid date string)
                } else {
                    $types .= 's'; // String
                }

                $bindValues[] = $value; // Pasar una copia del valor por referencia
            } else {
                // Si falta un valor para un campo, puedes manejarlo según tus necesidades, por ejemplo, mostrar un error o proporcionar un valor predeterminado.
                // En este ejemplo, asumiremos que el valor es NULL.
                $types .= 's'; // Asignar un tipo predeterminado de cadena
                $bindValues[] = NULL;
            }
        }

        $bindReferences = array();
        foreach ($bindValues as $key => $value) {
            $bindReferences[$key] = &$bindValues[$key];
        }

        array_unshift($bindReferences, $types); // Agregar tipos al principio del array

        // Ligar los valores y tipos a los marcadores de posición
        call_user_func_array(array($stmt, 'bind_param'), $bindReferences);

        if ($stmt->execute()) {
            return true; // Inserción exitosa
        } else {
            return false; // Error en la inserción
        }
    }
    $stmt->close();
    return false; // Error en la preparación de la consulta
}

// funciona
function showTableWithModifyButton($table)
{
    $databaseConnection = connectToDatabase();

    if (empty($table)) {
        echo "Debe proporcionar el nombre de una tabla.";
        return;
    }

    $sql = "SELECT * FROM $table";
    $result = $databaseConnection->query($sql);

    $tableData = []; // Array para almacenar los datos de la tabla

    if ($result->num_rows > 0) {
        echo "<h2>Datos de la tabla '$table'</h2>";
        echo "<table border='1'>";

        $firstRow = $result->fetch_assoc();
        echo "<tr>";
        foreach ($firstRow as $column => $value) {
            echo "<th>$column</th>";
        }
        echo "<th>Modificar</th>"; // Encabezado del botón "Modificar"
        echo "</tr>";

        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $column => $value) {
                echo "<td>" . $value . "</td>";
            }

            // Almacena los datos de la fila en el array
            $tableData[] = $row;

            $row_id = $row[array_keys($row)[0]]; // Obtener el ID de la primera columna

            echo '<td>
                <form action="" method="post">
                    <input type="hidden" name="row_id" value="' . $row_id . '">
                    <input type="hidden" name="tableMod" value="' . $table . '">
                    <input type="submit" name="modify_row" value="Modificar">
                </form>
            </td>'; // Botón "Modificar" dentro de un formulario para cada fila
            echo "</tr>";
        }
        echo "</table>";

        if (isset($_POST["modify_row"]) && isset($_POST["row_id"])) {
            // Encuentra la fila correspondiente en $tableData
            $selectedRow = null;
            foreach ($tableData as $row) {
                if ($row[array_keys($row)[0]] == $_POST["row_id"]) {
                    $selectedRow = $row;
                    break;
                }
            }

            // Si se encontró la fila, utiliza sus valores como inputs
            if ($selectedRow) {
                echo '<h2>Formulario de Modificación de ' . ucfirst($table) . '</h2>
                    <form action="" method="POST">';

                foreach ($selectedRow as $column => $value) {
                    echo '<label for="' . $column . '">' . ucfirst($column) . ':</label>
                        <input type="text" id="' . $column . '" name="' . $column . '" value="' . $value . '" required>';
                }

                echo '<input type="submit" value="Actualizar datos" name="update_data">
                    </form>';
            } else {
                echo "No se encontró la fila seleccionada.";
            }
        }
    } else {
        echo "No se encontraron resultados.";
    }

    $databaseConnection->close();

    // Devuelve el array con los datos de la tabla
    return $tableData;
}

// funciona
function updateTableData($data, $tableName, $primaryKey)
{
    $databaseConnection = connectToDatabase();

    // Verifica si la conexión a la base de datos fue exitosa
    if ($databaseConnection) {
        // Obtiene la estructura de la tabla
        $fields = getTableStructure($tableName);

        // Construye la parte SET de la consulta SQL
        $setClause = '';
        foreach ($data as $field => $value) {
            if (in_array($field, $fields)) {
                $setClause .= "$field = '$value', ";
            }
        }
        $setClause = rtrim($setClause, ', ');

        // Construye la consulta SQL de actualización
        $sql = "UPDATE $tableName SET $setClause WHERE $primaryKey = '" . $data[$primaryKey] . "'";

        // Ejecuta la consulta
        if ($databaseConnection->query($sql) === TRUE) {
            echo "Datos actualizados correctamente.";
        } else {
            echo "Error al actualizar datos: " . $databaseConnection->error;
        }

        // Cierra la conexión a la base de datos
        $databaseConnection->close();
    }
}

// funciona
function postShow($databaseConnection, $tables)
{
    echo '<label for="table">Selecciona una tabla:</label>
                    <select name="table" id="table">';
    foreach ($tables as $table) {
        $selected = ($_POST["table"] == $table) ? 'selected' : '';
        echo '<option value="' . $table . '" ' . $selected . '>' . $table . '</option>';
    }
    echo '<input type="submit" value="Mostrar">';
}

//funciona
function postIns($databaseConnection, $tables)
{
    echo '<label for="tableIns">Nombre de la tabla:</label>
                    <select name="tableIns" id="tableIns">';
    foreach ($tables as $table) {
        echo '<option value="' . $table . '">' . $table . '</option>';
    }
    echo '<input type="submit" value="Mostrar">';
}

// funciona
function postMod($databaseConnection, $tables)
{
    echo '<label for="tableMod">Nombre de la tabla:</label>
                    <select name="tableMod" id="tableMod">';
    foreach ($tables as $table) {
        echo '<option value="' . $table . '">' . $table . '</option>';
    }
    echo '<input type="submit" value="Mostrar">';
}
