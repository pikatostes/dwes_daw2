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
function getTableStructure($tableName, $databaseConnection)
{
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
function insertData($tableName, $data, $databaseConnection)
{
    // Verificar si la tabla existe antes de insertar datos
    if (!$databaseConnection || $databaseConnection->connect_error) {
        die("Error de conexión a la base de datos: " . $databaseConnection->connect_error);
    }

    $fields = getTableStructure($tableName, $databaseConnection);
    $fieldNames = implode(', ', $fields);
    $placeholders = rtrim(str_repeat('?, ', count($fields)), ', '); // Crear los marcadores de posición

    $sql = "INSERT INTO $tableName ($fieldNames) VALUES ($placeholders)";

    $stmt = $databaseConnection->prepare($sql);

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
function showTableWithModifyButton($table, $databaseConnection)
{
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

    // Devuelve el array con los datos de la tabla
    return $tableData;
}

// funciona
function updateTableData($data, $tableName, $primaryKey, $databaseConnection)
{
    // Verifica si la conexión a la base de datos fue exitosa
    if ($databaseConnection) {
        // Obtiene la estructura de la tabla
        $fields = getTableStructure($tableName, $databaseConnection);

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
            echo "<br>Datos actualizados correctamente.";
        } else {
            echo "Error al actualizar datos: " . $databaseConnection->error;
        }

        // Cierra la conexión a la base de datos
    }
}

// funciona
function filterByVendor($databaseConnection)
{
    echo '<label for="comercial">Selecciona un comercial:</label>';
    // Obtén los nombres de los comerciales de la tabla comerciales
    $query = "SELECT nombre FROM comerciales";
    $result = $databaseConnection->query($query);

    // Muestra el select con los nombres de los comerciales
    echo '<select name="comercial" id="comercial">';
    while ($row = $result->fetch_assoc()) {
        $selected = ($_POST["comercial"] == $row["nombre"]) ? 'selected' : '';
        echo '<option value="' . $row["nombre"] . '" ' . $selected . '>'
            . $row["nombre"] . '</option>';
    }
    echo '</select>';
    echo '<input type="submit" value="Mostrar" name="table">';  // Cambiado a "table"
}

// funciona
function postShow($databaseConnection, $tables)
{
    echo '<label for="tableDel">Selecciona una tabla:</label>
                    <select name="tableDel" id="tableDel">';
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

// funciona
function showDataForComercial($comercialId, $databaseConnection)
{
    // Obtén los datos de las tablas productos y ventas para el vendedor seleccionado
    $query = "SELECT p.referencia, p.nombre AS nombre_producto, p.descripcion, p.precio, p.descuento,
                     v.refProducto, v.cantidad, v.fecha
              FROM productos p
              JOIN ventas v ON p.referencia = v.refProducto
              WHERE v.codComercial = (SELECT codigo FROM comerciales WHERE nombre = '$comercialId')";

    $result = $databaseConnection->query($query);

    // Muestra los datos en una tabla
    echo '<table border="1">
            <tr>
                <th>Referencia Producto</th>
                <th>Nombre Producto</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Descuento</th>
                <th>Cantidad Vendida</th>
                <th>Fecha Venta</th>
            </tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . $row["referencia"] . '</td>
                <td>' . $row["nombre_producto"] . '</td>
                <td>' . $row["descripcion"] . '</td>
                <td>' . $row["precio"] . '</td>
                <td>' . $row["descuento"] . '</td>
                <td>' . $row["cantidad"] . '</td>
                <td>' . $row["fecha"] . '</td>
              </tr>';
    }

    echo '</table>';
}

// en pruebas
// Función para eliminar un comercial y sus ventas asociadas
function deleteComercial($codigo, $databaseConnection)
{
    // Eliminar ventas asociadas al comercial
    $sqlVentas = "DELETE FROM Ventas WHERE codComercial = ?";
    $stmtVentas = $databaseConnection->prepare($sqlVentas);
    $stmtVentas->bind_param("s", $codigo);
    $stmtVentas->execute();
    $stmtVentas->close();

    // Eliminar el comercial
    $sqlComercial = "DELETE FROM Comerciales WHERE codigo = ?";
    $stmtComercial = $databaseConnection->prepare($sqlComercial);
    $stmtComercial->bind_param("s", $codigo);
    $stmtComercial->execute();
    $stmtComercial->close();
}

// Función para eliminar un producto y sus ventas asociadas
function deleteProducto($referencia, $databaseConnection)
{
    // Eliminar ventas asociadas al producto
    $sqlVentas = "DELETE FROM Ventas WHERE refProducto = ?";
    $stmtVentas = $databaseConnection->prepare($sqlVentas);
    $stmtVentas->bind_param("s", $referencia);
    $stmtVentas->execute();
    $stmtVentas->close();

    // Eliminar el producto
    $sqlProducto = "DELETE FROM Productos WHERE referencia = ?";
    $stmtProducto = $databaseConnection->prepare($sqlProducto);
    $stmtProducto->bind_param("s", $referencia);
    $stmtProducto->execute();
    $stmtProducto->close();
}

// Función para eliminar una venta específica
function deleteVenta($codComercial, $refProducto, $fecha, $databaseConnection)
{
    // Eliminar la venta específica
    $sqlVenta = "DELETE FROM Ventas WHERE codComercial = ? AND refProducto = ? AND fecha = ?";
    $stmtVenta = $databaseConnection->prepare($sqlVenta);
    $stmtVenta->bind_param("sss", $codComercial, $refProducto, $fecha);
    $stmtVenta->execute();
    $stmtVenta->close();
}

// Función para obtener la clave primaria de una tabla
function getPrimaryKey($tableName, $databaseConnection)
{
    $fields = getTableStructure($tableName, $databaseConnection);
    $primaryKey = [];

    foreach ($fields as $field) {
        $sql = "SHOW COLUMNS FROM $tableName LIKE '$field'";
        $result = $databaseConnection->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['Key'] == 'PRI') {
                $primaryKey[] = $field;
            }
        }
    }

    return $primaryKey;
}

// Función para eliminar una fila de una tabla
function deleteRow($tableName, $primaryKey, $rowValue, $databaseConnection)
{
    $sql = "DELETE FROM $tableName WHERE $primaryKey = ?";
    $stmt = $databaseConnection->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $rowValue);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    return false;
}

function showTableWithDeleteButton($tableName, $databaseConnection)
{
    echo "<h2>Tabla: $tableName</h2>";

    // Obtener datos de la tabla
    $sql = "SELECT * FROM $tableName";
    $result = $databaseConnection->query($sql);

    if ($result && $result->num_rows > 0) {
        echo '<table border="1"><tr>';

        // Mostrar encabezados de columna
        while ($fieldInfo = $result->fetch_field()) {
            echo "<th>{$fieldInfo->name}</th>";
        }

        echo "<th>Acción</th></tr>";

        // Mostrar datos de la tabla con formulario y botón de eliminar para cada fila
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";

            foreach ($row as $value) {
                echo "<td>$value</td>";
            }

            echo "<td>";
            echo '<form action="" method="post">';
            echo "<input type='hidden' name='tableWithDelete' value='$tableName'>";

            // Agregar campos ocultos para la clave primaria
            foreach ($row as $column => $value) {
                echo "<input type='hidden' name='{$column}_delete' value='$value'>";
            }

            echo '<input type="submit" value="Eliminar Fila" name="delete_row">';
            echo '</form>';
            echo "</td>";

            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "La tabla está vacía.";
    }
}

function generateSelectOptions($field)
{
    // Conectarse a la base de datos
    $databaseConnection = connectToDatabase();

    // Obtener los códigos de la tabla "comerciales"
    $tableName = "comerciales";
    $fields = getTableStructure($tableName, $databaseConnection);

    // Verificar si la tabla contiene un campo llamado "codigo"
    if (in_array("codigo", $fields)) {
        // Consultar los códigos de la tabla
        $sql = "SELECT codigo FROM $tableName";
        $result = $databaseConnection->query($sql);

        // Verificar si se obtuvieron resultados
        if ($result) {
            // Crear el elemento <select>
            echo "<select name='data[$field]'>";

            // Iterar sobre los resultados y agregar opciones al select
            while ($row = $result->fetch_assoc()) {
                $codigo = $row["codigo"];
                echo "<option value=\"$codigo\" name='data[$field]'>$codigo</option>";
            }

            // Cerrar el elemento <select>
            echo '</select>';

            // Liberar los resultados
            $result->free();
        } else {
            echo "Error al ejecutar la consulta: " . $databaseConnection->error;
        }
    } else {
        echo "La tabla \"$tableName\" no contiene un campo llamado \"codigo\"";
    }

    // Cerrar la conexión a la base de datos
    $databaseConnection->close();
}

// Función para generar un select con las opciones de la tabla "productos"
function generateProductSelect($field)
{
    // Conectarse a la base de datos
    $databaseConnection = connectToDatabase();

    // Obtener las referencias de la tabla "productos"
    $tableName = "productos";
    $fields = getTableStructure($tableName, $databaseConnection);

    // Verificar si la tabla contiene un campo llamado "referencia"
    if (in_array("referencia", $fields)) {
        // Consultar las referencias de la tabla
        $sql = "SELECT referencia FROM $tableName";
        $result = $databaseConnection->query($sql);

        // Verificar si se obtuvieron resultados
        if ($result) {
            // Crear el elemento <select>
            echo "<select name='data[$field]'>";

            // Iterar sobre los resultados y agregar opciones al select
            while ($row = $result->fetch_assoc()) {
                $referencia = $row["referencia"];
                echo "<option value=\"$referencia\" name='data[$field]'>$referencia</option>";
            }

            // Cerrar el elemento <select>
            echo '</select>';

            // Liberar los resultados
            $result->free();
        } else {
            echo "Error al ejecutar la consulta: " . $databaseConnection->error;
        }
    } else {
        echo "La tabla \"$tableName\" no contiene un campo llamado \"referencia\"";
    }

    // Cerrar la conexión a la base de datos
    $databaseConnection->close();
}