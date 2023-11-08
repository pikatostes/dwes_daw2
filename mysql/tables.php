<?php
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

        $stmt->close();
    }

    return false; // Error en la preparación de la consulta
}

function showEditForm($table, $rowId)
{
    $databaseConnection = connectToDatabase();

    // Obtén los nombres de las columnas de la tabla
    $columns = getTableStructure($table);

    // Obtén el nombre de la columna "id" que suele ser la primera
    $idColumnName = reset($columns);

    // Construye la consulta SQL para obtener la fila con el ID seleccionado
    $sql = "SELECT * FROM $table WHERE $idColumnName = ?";

    $stmt = $databaseConnection->prepare($sql);
    $stmt->bind_param("i", $rowId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        echo "<h2>Modificar Datos de la tabla '$table'</h2>";
        echo '<form action="" method="post">';
        echo "<input type='hidden' name='table' value='$table'>";
        echo "<input type='hidden' name='row_id' value='$rowId'>";

        foreach ($columns as $column) {
            $columnName = $column;
            $currentValue = $row[$columnName];
            echo "<label for='$columnName'>$columnName:</label>";
            echo "<input type='text' name='data[$columnName]' value='$currentValue'><br>";
        }

        echo '<input type="submit" value="Guardar Cambios" name="save_changes">';
        echo '</form>';
    } else {
        echo "No se encontraron resultados para la fila seleccionada.";
    }

    $stmt->close();
    $databaseConnection->close();
}

function updateData($table, $rowId, $data, $mysqli)
    {
        if (!empty($table) && !empty($rowId)) {
            $setClause = '';
            $params = array();

            foreach ($data as $field => $value) {
                $setClause .= "$field = ?, ";
                $params[] = &$value;
            }

            $setClause = rtrim($setClause, ', ');

            $sql = "UPDATE $table SET $setClause WHERE id = ?";
            $params[] = $rowId;

            $stmt = $mysqli->prepare($sql);

            if ($stmt) {
                $types = str_repeat('s', count($params) - 1) . 'i';
                array_unshift($params, $types);
                call_user_func_array(array($stmt, 'bind_param'), $params);

                if ($stmt->execute()) {
                    $stmt->close();
                    return true;
                } else {
                    $stmt->close();
                    return false;
                }
            }
        }

        return false;
    }

function showTableWithModifyButton($table)
{
    $databaseConnection = connectToDatabase();

    if (empty($table)) {
        echo "Debe proporcionar el nombre de una tabla.";
        return;
    }

    $sql = "SELECT * FROM $table";
    $result = $databaseConnection->query($sql);

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
    } else {
        echo "No se encontraron resultados.";
    }

    $databaseConnection->close();
}
