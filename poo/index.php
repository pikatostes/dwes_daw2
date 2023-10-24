<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Document</title>
</head>

<body>
     <?php
     class Fruit
     {
          public $name;
          public $color;

          function __construct($name, $color)
          {
               $this->name = $name;
               $this->color = $color;
          }

          function get_name()
          {
               return $this->name;
          }
          function get_color()
          {
               return $this->color;
          }
     }
     ?>

     <form action="" method="post">
          <input type="text" name="name" id="name">
          <input type="text" name="color" id="color">
          <input type="submit" value="enviar">
     </form>

     <?php
     if (isset($_POST["name"]) && isset($_POST["color"])) {
          $fruit1 = new Fruit($_POST["name"], $_POST["color"]);

          echo "Name: " . $fruit1->get_name() . "<br>";
          echo "Color: " . $fruit1->get_color() . "<br>";
     }
     ?>
</body>

</html>