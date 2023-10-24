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

    $sql = "SHOW TABLES LIKE '" . $table . "'";
    $result = $databaseConnection->query($sql);

    if ($result->num_rows == 0) {
        echo "La tabla '" . $table . "' no existe en la base de datos.";
        return;
    }

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

function registerUser($user, $password) {
    // Conectar a la base de datos
    $databaseConnection = connectToDatabase();

    // Puedes adaptar esta función para insertar un usuario en la tabla de usuarios
    $sql = "INSERT INTO users (user, password) VALUES (?, ?)";
    $stmt = $databaseConnection->prepare($sql);
    $stmt->bind_param("ss", $user, $password);

    if ($stmt->execute()) {
        echo "Usuario registrado con éxito.";
    } else {
        echo "Error al registrar el usuario: " . $stmt->error;
    }
    $stmt->close();
    $databaseConnection->close();
}
?>