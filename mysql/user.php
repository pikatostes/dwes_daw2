<?php
include "tables.php";

function login($user, $password)
{
     // Establece la conexión a la base de datos
     $databaseConnection = connectToDatabase();

     // Verifica si el usuario y la contraseña son válidos
     $sql = "SELECT * FROM users WHERE user = ? AND password = ?";
     $stmt = $databaseConnection->prepare($sql);
     $stmt->bind_param("ss", $user, $password);
     $stmt->execute();
     $result = $stmt->get_result();

     if ($result->num_rows === 1) {
          // Inicio de sesión exitoso
          session_start();
          $_SESSION["userName"] = $user;

          // Agrega un mensaje de depuración
          echo "Inicio de sesión exitoso.";

          // Redirige al usuario a "register.php"
          header("Location: index.php");
          exit();
     } else {
          // Inicio de sesión fallido
          $stmt->close();
          $databaseConnection->close();

          // Agrega un mensaje de depuración
          echo "Inicio de sesión fallido.";
          return false;
     }
}

function registerUser($user, $password)
{
     // Conectar a la base de datos
     $databaseConnection = connectToDatabase();

     // Verificar si el nombre de usuario ya existe
     $checkUserSql = "SELECT * FROM users WHERE user = ?";
     $checkUserStmt = $databaseConnection->prepare($checkUserSql);
     $checkUserStmt->bind_param("s", $user);
     $checkUserStmt->execute();
     $checkUserResult = $checkUserStmt->get_result();

     if ($checkUserResult->num_rows > 0) {
          echo "El nombre de usuario ya está en uso. Por favor, elige otro.";
     } else {
          // Insertar el nuevo usuario si el nombre de usuario no existe
          $insertUserSql = "INSERT INTO users (user, password) VALUES (?, ?)";
          $insertUserStmt = $databaseConnection->prepare($insertUserSql);
          $insertUserStmt->bind_param("ss", $user, $password);

          if ($insertUserStmt->execute()) {
               echo "Usuario registrado con éxito.";
               header("Location: login.php");
          } else {
               echo "Error al registrar el usuario: " . $insertUserStmt->error;
          }

          $insertUserStmt->close();
     }

     $checkUserStmt->close();
     $databaseConnection->close();
}
