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
    require("tables.php");
    $databaseConnection = connectToDatabase();
    ?>
    <form action="" method="post">
        <input type="submit" value="Consultar" name="consultar">
        <input type="submit" value="Insertar" name="insertar">
        <input type="submit" value="Modificar" name="modificar">
        <input type="submit" value="Eliminar" name="eliminar">
    </form>

    <?php
    if (isset($_POST["consultar"]) || isset($_POST['insertar']) || isset($_POST['modificar']) || isset($_POST['eliminar'])) {

        $tables = getAllTables($databaseConnection);

        echo '<form action="" method="post">';

        if (isset($_POST["consultar"])) {
            filterByVendor($databaseConnection);
        }

        if (isset($_POST['insertar'])) {
            postIns($databaseConnection, $tables);
        }

        if (isset($_POST['modificar'])) {
            postMod($databaseConnection, $tables);
        }

        if (isset($_POST["eliminar"])) {
            postShow($databaseConnection, $tables);
        }

        echo '</form>';
    }

    if (isset($_POST["table"])) {
        $comercialId = $_POST['comercial'];

        // Muestra los datos para el vendedor seleccionado

        // Mantén el valor seleccionado en el select
        echo '<form action="" method="post">';
        filterByVendor($databaseConnection);
        echo '</form>';
        showDataForComercial($comercialId, $databaseConnection);
    } elseif (isset($_POST["tableIns"])) {
        $selectedTable = $_POST["tableIns"];
        $fields = getTableStructure($selectedTable, $databaseConnection);

        if (!empty($fields)) {
            // Mostrar formulario de inserción con campos según la tabla seleccionada
            echo "<h2>Insertar Datos en la tabla '$selectedTable'</h2>";
            echo '<form action="" method="post">';
            echo "<input type='hidden' name='selected_table' value='$selectedTable'>";

            foreach ($fields as $field) {
                if ($field === "codComercial") {
                    echo "$field" . generateSelectOptions($field) . "<br>";
                } elseif ($field === "refProducto") {
                    echo "$field" . generateProductSelect($field) . "<br>";
                } else {
                    echo "<label for='$field'>$field:</label>";
                    echo "<input type='text' name='data[$field]'><br>";
                }
            }

            echo '<input type="submit" value="Insertar Datos">';
            echo '</form>';

            // Lógica de inserción de datos

        }
    } elseif (isset($_POST["tableMod"])) {
        $selectedModTable = $_POST["tableMod"];
        $_SESSION["selectedModTable"] = $selectedModTable; // Guardar en la sesión
        $tableData = showTableWithModifyButton($selectedModTable, $databaseConnection);
    } elseif (isset($_POST['tableDel'])) {
        $selectedDelTable = $_POST['tableDel'];
        $_SESSION["selectedDelTable"] = $selectedDelTable;
        showTableWithDeleteButton($selectedDelTable, $databaseConnection);
    }


    if (isset($_POST['update_data'])) {

        $selectedModTable = isset($_SESSION["selectedModTable"]) ? $_SESSION["selectedModTable"] : '';

        if (!empty($selectedModTable)) {
            $tables = getAllTables($databaseConnection);
            postMod($databaseConnection, $tables);
            if ($selectedModTable == "comerciales") {
                $formData = [
                    'codigo' => $_POST['codigo'],
                    'nombre' => $_POST['nombre'],
                    'salario' => $_POST['salario'],
                    'hijos' => $_POST['hijos'],
                    'fNacimiento' => $_POST['fNacimiento']
                ];

                // Ajusta según la estructura de la tabla 'comerciales'
                $primaryKey = "codigo";
                updateTableData($formData, $selectedModTable, $primaryKey, $databaseConnection);
            } elseif ($selectedModTable == "productos") {
                $formData = [
                    'referencia' => $_POST['referencia'],
                    'nombre' => $_POST['nombre'],
                    'descripcion' => $_POST['descripcion'],
                    'precio' => $_POST['precio'],
                    'descuento' => $_POST['descuento']
                ];
                // Ajusta según la estructura de la tabla 'productos'
                $primaryKey = "referencia";
                updateTableData($formData, $selectedModTable, $primaryKey, $databaseConnection);
            } elseif ($selectedModTable == "ventas") {
                $formData = [
                    'codComercial' => $_POST['codComercial'],
                    'refProducto' => $_POST['refProducto'],
                    'cantidad' => $_POST['cantidad'],
                    'fecha' => $_POST['fecha'],
                ];

                // Ajusta según la estructura de la tabla 'ventas'
                $primaryKey = "codComercial";
                updateTableData($formData, $selectedModTable, $primaryKey, $databaseConnection);
            } else {
                echo "Tabla no válida. "; // Mensaje de depuración
            }

            // Mostrar la tabla actualizada
            showTableWithModifyButton($selectedModTable, $databaseConnection);
        } else {
            echo "No se ha seleccionado ninguna tabla. "; // Mensaje de depuración
        }
    }

    if (isset($_POST["delete_row"])) {
        $selectedDelTable = isset($_SESSION["selectedDelTable"]) ? $_SESSION["selectedDelTable"] : '';

        if (!empty($selectedDelTable)) {
            $primaryKey = getPrimaryKey($selectedDelTable, $databaseConnection);

            if (!empty($primaryKey)) {
                $rowToDelete = [];

                // Construir el array de claves primarias
                foreach ($primaryKey as $column) {
                    $rowToDelete[] = $_POST["{$column}_delete"];
                }

                // Lógica de eliminación según la tabla seleccionada
                if ($selectedDelTable == "comerciales") {
                    deleteComercial($rowToDelete[0], $databaseConnection); // Modificar aquí
                } elseif ($selectedDelTable == "productos") {
                    deleteProducto($rowToDelete[0], $databaseConnection); // Modificar aquí
                } elseif ($selectedDelTable == "ventas") {
                    // Lógica para la tabla de ventas
                    deleteVenta($rowToDelete[0], $rowToDelete[1], $rowToDelete[2], $databaseConnection); // Modificar aquí
                } else {
                    echo "Error: Tabla no válida.";
                }

                echo "La fila ha sido eliminada exitosamente.";
                $selectedModTable = isset($_SESSION["selectedModTable"]) ? $_SESSION["selectedModTable"] : '';

                // Vuelve a mostrar la tabla después de la eliminación
                showTableWithModifyButton($selectedModTable, $databaseConnection);
            }
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["selected_table"]) && isset($_POST["data"])) {
        $selectedTable = $_POST["selected_table"];
        $data = $_POST["data"];

        // Realizar la inserción de datos
        $insertResult = insertData($selectedTable, $data, $databaseConnection);

        if ($insertResult) {
            echo "Los datos han sido insertados en la tabla '$selectedTable' exitosamente.";
        } else {
            echo "Error al insertar datos en la tabla '$selectedTable'.";
        }
    }
    $databaseConnection->close();
    ?>
</body>

</html>