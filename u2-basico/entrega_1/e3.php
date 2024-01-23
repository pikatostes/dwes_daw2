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
    // 3. Crea un array asociativo llamado $estaturas que contenga la estatura de las siguientes personas:
    echo "<h2>Ejercicio 3</h2>";
    $estaturas = [
        "Juan" => 186,
        "Alberto" => 172,
        "Marta" => 173,
    ];

    // ejercicio 4
    // 4. Escribe el código necesario para mostrar la estatura de Alberto.
    echo "<h2>Ejercicio 4</h2>";
    echo $estaturas["Alberto"];
    echo "<br>";

    // ejercicio 5
    // 5. Recorre el array asociativo estaturas y muestra los pares clave/valor.
    echo "<h2>Ejercicio 5</h2>";
    foreach ($estaturas as $name => $height) {
        echo $name . " mide " . $height;
        echo "<br>";
    }

    // ejercicio 6
    // 6. Ordena el array asociativo $estaturas en orden descendente de acuerdo al valor.
    echo "<br>";
    echo "<h2>Ejercicio 6</h2>";
    arsort($estaturas);
    foreach ($estaturas as $name => $height) {
        echo $name . " mide " . $height;
        echo "<br>";
    }

    // ejercicio 7
    // 7. Ordena el array asociativo $estaturas en orden descendente de acuerdo a la clave.
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