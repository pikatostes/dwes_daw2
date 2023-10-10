<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sum of Digits</title>
</head>

<body>
    <form method="post">
        <label for="number">Enter a number:</label>
        <input type="number" name="number" id="number">
        <input type="submit" value="Calculate">
    </form>
    
    <?php
    if (isset($_POST["number"])) {
        $number = $_POST["number"];

        // Llamar a la función para sumar los dígitos
        $result = sumDigits($number);

        // Mostrar el resultado
        echo "<h2>Result:</h2> $result";
    }

    function sumDigits($number){
        // Convertir el número en una cadena para iterar a través de sus dígitos
        $numberStr = (string)$number;

        // Inicializar una variable para almacenar la suma de los dígitos
        $sum = 0;

        // Iterar a través de cada dígito y sumarlo
        for ($i = 0; $i < strlen($numberStr); $i++) {
            $digit = $numberStr[$i];
            $sum += (int)$digit;
        }

        // Devolver la suma de los dígitos
        return "The sum of digits of $number is: $sum";
    }
    ?>
</body>

</html>
