<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resolución de Ecuación Cuadrática</title>
</head>

<body>
    <form action="e12.php" method="post">
        <input type="number" name="a">x<sup>2</sup> +
        <input type="number" name="b">x +
        <input type="number" name="c">
        = 0
        <br>
        <input type="submit" value="Calcular">
    </form>

    <?php
    // Ejercicio 12
    /*
    12. Escribe un programa que resuelva ecuaciones de segundo grado (ax2 + bx + c = 0). Si la ecuación no tiene soluciones reales, hay que mostrar un mensaje de error. Usa funciones para ello. La función recibirá los coeficientes de la ecuación y devolverá un array con las soluciones reales. Si no hay soluciones devolverá false.
    */
    // Función para resolver una ecuación cuadrática
    function resolverEcuacionCuadratica($a, $b, $c)
    {
        $discriminante = ($b ** 2) - (4 * $a * $c);

        if ($discriminante > 0) {
            // Dos soluciones reales distintas
            $x1 = (-$b + sqrt($discriminante)) / (2 * $a);
            $x2 = (-$b - sqrt($discriminante)) / (2 * $a);
            return [$x1, $x2];
        } elseif ($discriminante == 0) {
            // Una solución real (raíz doble)
            $x = -$b / (2 * $a);
            return [$x];
        } else {
            // No hay soluciones reales
            return false;
        }
    }

    if (isset($_POST["a"]) && isset($_POST["b"]) && isset($_POST["c"])) {
        $a = $_POST["a"];
        $b = $_POST["b"];
        $c = $_POST["c"];

        // Llamar a la función para resolver la ecuación cuadrática
        $soluciones = resolverEcuacionCuadratica($a, $b, $c);

        // Mostrar las soluciones o un mensaje de error
        if ($soluciones !== false) {
            echo "<h2>Soluciones:</h2>";
            foreach ($soluciones as $i => $solucion) {
                echo "x$i = $solucion<br>";
            }
        } else {
            echo "<h2>No hay soluciones reales.</h2>";
        }
    }
    ?>
</body>

</html>