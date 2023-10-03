<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $cadena = "Esto es un string de varias palabras";
    //Número de caracteres que almacena la cadena
    echo "La cadena tiene " . strlen($cadena) . " caracteres";
    echo "<br>";
    //Devuelve la cadena escrita en mayúscula
    echo strtoupper($cadena);
    echo "<br>";
    //Número de palabras que almacena la cadena
    echo "La cadena tiene " . str_word_count($cadena) . " palabras";
    ?>
</body>

</html>