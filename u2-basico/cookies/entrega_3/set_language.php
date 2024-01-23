<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["language"])) {
    $selectedLanguage = $_POST["language"];
    
    // Set a cookie to store the user's language preference
    setcookie("language", $selectedLanguage, time() + (86400 * 30), "/");
    
    // Redirect to the home page (index.php or any other page)
    header("Location: index.php");
    exit();
}
?>
