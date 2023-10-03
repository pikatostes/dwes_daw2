<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Ejercicios Array Asociativo</h1>
    <?php
    // ejercicio 3
    echo "<h2>Ejercicio 3</h2>";
    $estaturas = [
        "Juan" => 186,
        "Alberto" => 172,
        "Marta" => 173,
    ];

    // ejercicio 4
    echo "<h2>Ejercicio 4</h2>";
    echo $estaturas["Alberto"];
    echo "<br>";

    // ejercicio 5
    echo "<h2>Ejercicio 5</h2>";
    foreach ($estaturas as $name => $height) {
        echo $name . " mide " . $height;
        echo "<br>";
    }

    // ejercicio 6
    echo "<br>";
    echo "<h2>Ejercicio 6</h2>";
    arsort($estaturas);
    foreach ($estaturas as $name => $height) {
        echo $name . " mide " . $height;
        echo "<br>";
    }

    // ejercicio 7
    krsort($estaturas);
    echo "<br>";
    echo "<h2>Ejercicio 7</h2>";
    foreach ($estaturas as $name => $height) {
        echo $name . " mide " . $height;
        echo "<br>";
    }
    ?>
</body>

</html>