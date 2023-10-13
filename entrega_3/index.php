<?php
// Check if the "language" cookie exists
if (isset($_COOKIE["language"])) {
    $userLanguage = $_COOKIE["language"];
} else {
    // Default to Spanish if the cookie doesn't exist
    $userLanguage = "es";
}

// Define content based on language preference
if ($userLanguage === "en") {
    $greeting = "Welcome to our website!";
    $content = "This is the English version of the page.";
} elseif ($userLanguage === "es") {
    $greeting = "¡Bienvenido a nuestro sitio web!";
    $content = "Esta es la versión en español de la página.";
}
?>

<!DOCTYPE html>
<html lang="<?= $userLanguage ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website</title>
</head>

<body>
    <h1><?= $greeting ?></h1>
    <p><?= $content ?></p>
</body>

</html>