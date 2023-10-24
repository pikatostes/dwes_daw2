<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>Plantilla para Ejercicios Tema 3</title>
	<link href="dwes.css" rel="stylesheet" type="text/css">
</head>

<body>


	<div id="encabezado">
		<h1>Ejercicio: </h1>
		<form id="form_seleccion" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		</form>
	</div>

	<div id="contenido">
		<h2>Contenido</h2>
		<?php
		// Configura la conexión a la base de datos
		$servername = "localhost";  // Dirección del servidor MySQL (generalmente es localhost)
		$username = "root";         // Nombre de usuario de MySQL (por defecto es root)
		$password = "";             // Contraseña de MySQL (por defecto, dejar en blanco)
		$database = "dwes";         // Nombre de la base de datos

		// Crea la conexión a la base de datos
		$conn = new mysqli($servername, $username, $password, $database);

		// Verifica la conexión
		if ($conn->connect_error) {
			die("Conexión fallida: " . $conn->connect_error);
		}

		// Realiza la consulta SELECT
		$sql = "SELECT * FROM producto";
		$result = $conn->query($sql);

		// Verifica si la consulta fue exitosa
		if ($result->num_rows > 0) {
			// Muestra los resultados
			while ($row = $result->fetch_assoc()) {
				echo "ID: " . $row["cod"] . " - Nombre: " . $row["nombre"] . " - Descripción: " . $row["nombre_corto"] . "<br>";
			}
		} else {
			echo "No se encontraron resultados.";
		}

		// Cierra la conexión a la base de datos
		$conn->close();
		?>
	</div>

	<div id="pie">
	</div>
</body>

</html>