<?php
function connectToDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "dwes";

    $databaseConnection = new mysqli($servername, $username, $password, $database);

    if ($databaseConnection->connect_error) {
        die("Conexión fallida: " . $databaseConnection->connect_error);
    }

    return $databaseConnection;
}

function showTable($table) {
    $databaseConnection = connectToDatabase();

    if (empty($table)) {
        echo "Debe proporcionar el nombre de una tabla.";
        return;
    }

    $sql = "SHOW TABLES LIKE ?";
    $stmt = $databaseConnection->prepare($sql);
    $stmt->bind_param("s", $table);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "La tabla '" . $table . "' no existe en la base de datos.";
    } else {
        displayTable($table, $databaseConnection);
    }

    $stmt->close();
    $databaseConnection->close();
}

function displayTable($table, $databaseConnection) {
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

function getStockByProductCod($productCod) {
    $conn = connectToDatabase();
    $stock = array();

    $stockSql = "SELECT tienda, unidades FROM stock WHERE producto = ?";
    $stmt = $conn->prepare($stockSql);
    $stmt->bind_param("s", $productCod);
    $stmt->execute();
    $stmt->bind_result($tienda, $unidades);

    while ($stmt->fetch()) {
        $stock[] = array("tienda" => $tienda, "unidades" => $unidades);
    }

    $stmt->close();
    $conn->close();

    return $stock;
}

function updateStock($productCod, $stockData) {
    // Establece la conexión a la base de datos
    $conn = connectToDatabase();

    // Itera a través de los datos de stock para cada tienda
    foreach ($stockData as $tienda => $unidades) {
        // Crea la consulta SQL para actualizar el stock de un producto en una tienda
        $updateSql = "UPDATE stock SET unidades = ? WHERE producto = ? AND tienda = ?";
        
        // Prepara la consulta SQL para evitar inyecciones SQL y la vincula con los parámetros
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("isi", $unidades, $productCod, $tienda);
        
        // Ejecuta la consulta SQL preparada
        if (!$stmt->execute()) {
            // Si la consulta no se ejecuta correctamente, muestra un mensaje de error
            echo "Error al actualizar el stock en la tienda $tienda.";
        }

        // Cierra la consulta
        $stmt->close();
    }

    // Cierra la conexión a la base de datos
    $conn->close();
}
