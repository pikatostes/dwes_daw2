<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Tablas</title>
</head>

<body>
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
    ?>

</body>

</html>