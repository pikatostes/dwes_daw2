<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Document</title>
</head>

<body>
     <form action="" method="post">
          <input type="text" name="user" id="user">
          <input type="password" name="password" id="password">
          <input type="submit" value="Log In">
     </form>
     <?php
     if (isset($_POST["user"]) && isset($_POST["password"])) {
          $user = $_POST["user"];
          $password = $_POST["password"];

          include "user.php";
          login($user, $password);
     }
     ?>
</body>

</html>