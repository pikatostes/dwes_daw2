<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    echo "<h1>Ejercicios Array</h1>";

    // ejercicio 8
    /* 8. Ordena alfabéticamente el siguiente array:
    $colores = array("rojo", "verde", "azul", "amarillo"); */
    
    echo "<h2>Ejercicio 8</h2>";
    $colores = array("rojo", "verde", "azul", "amarillo");
    sort($colores);
    for ($i = 0; $i < count($colores); $i++) {
        echo $colores[$i] . " ";
    }
    echo "<br>";

    // ejercicio 9
    /* 9. Ordena alfabéticamente en orden inverso (de la Z a la A) el siguiente array:
    $colores = array("rojo", "verde", "azul", "amarillo"); */
    
    echo "<h2>Ejercicio 9</h2>";
    rsort($colores);
    for ($i = 0; $i < count($colores); $i++) {
        echo $colores[$i] . " ";
    }
    ?>
</body>

</html>