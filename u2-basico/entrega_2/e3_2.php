<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sum of Prime Numbers</title>
</head>

<body>
    <?php
    /*
    3. Write a PHP program to compute the sum of the prime numbers less than 100.
    Note: There are 25 prime numbers are there in less than 100.
    2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47, 53, 59, 61, 67, 71, 73, 79, 83, 89, 97
    The sum of all these prime numbers is 1060.
    */
    $sumPrime = 0;

    for ($i = 2; $i < 100; $i++) {
        $isPrime = true; // Suponemos que $i es primo hasta que se demuestre lo contrario

        for ($j = 2; $j < $i; $j++) {
            if ($i % $j == 0) {
                // Si $i es divisible por algún número en este rango, no es primo
                $isPrime = false;
                break; // Salir del bucle interno
            }
        }

        if ($isPrime) {
            // Si $i es primo, añadirlo a la suma
            $sumPrime += $i;
            echo "$i ";
        }
    }

    echo "<h1>The sum of prime numbers less than 100 is: $sumPrime</h1>";
    ?>
</body>

</html>