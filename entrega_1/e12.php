<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resolución de Ecuación Cuadrática</title>
</head>

<body>
    <form action="e12.php" method="get">
        <input type="number" name="a">x<sup>2</sup> +
        <input type="number" name="b">x +
        <input type="number" name="c">
        = 0
        <br>
        <input type="submit" value="Calcular">
    </form>

    <?php
    // Función para resolver una ecuación cuadrática
    function resolverEcuacionCuadratica($a, $b, $c) {
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

    if (isset($_GET["a"]) && isset($_GET["b"]) && isset($_GET["c"])) {
        $a = $_GET["a"];
        $b = $_GET["b"];
        $c = $_GET["c"];

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
