<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>Plantilla para Ejercicios Tema 3</title>
	<link href="dwes.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div id="encabezado">
		<h1>Ejercicio:</h1>
		<form id="form_seleccion" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"></form>
	</div>

	<div id="contenido">
		<?php
		include 'tables.php';

		$conn = connectToDatabase();

		// Realiza la consulta SELECT a la tabla "producto"
		$sql = "SELECT cod, nombre_corto FROM producto";
		$result = $conn->query($sql);
		?>
		<h2>Contenido</h2>
		<form action="" method="post">
			<label for="productos">Selecciona un producto:</label>
			<select name="productos" id="productos">
				<?php
				if ($result->num_rows > 0) {
					// Recorre las filas y crea opciones para el select
					while ($row = $result->fetch_assoc()) {
						echo '<option value="' . $row["cod"] . '">' . $row["nombre_corto"] . '</option>';
					}
				} else {
					echo '<option value="">No hay productos disponibles</option>';
				}
				?>
			</select>
			<input type="submit" value="Seleccionar">
		</form>

		<?php
		if (isset($_POST["productos"])) {
			$productCod = $_POST["productos"];
			$stock = getStockByProductCod($productCod);

			if (!empty($stock)) {
				echo "<h2>Modificar Stock del producto seleccionado:</h2>";
				echo "<form action='' method='post'>";
				echo "<input type='hidden' name='product_cod' value='$productCod'>";

				foreach ($stock as $entry) {
					$tienda = $entry["tienda"];
					$unidades = $entry["unidades"];
					echo "<label for='$tienda'>Tienda $tienda:</label>";
					echo "<input type='number' name='stock[$tienda]' value='$unidades'><br>";
				}

				echo "<input type='submit' value='Guardar Cambios'>";
				echo "</form>";
			} else {
				echo "<p>No se encontró stock para este producto.</p>";
			}
		}
		if (isset($_POST["product_cod"]) && isset($_POST["stock"])) {
			$productCod = $_POST["product_cod"];
			$stockData = $_POST["stock"];

			updateStock($productCod, $stockData);


			echo "Los cambios en el stock han sido guardados exitosamente.";
		}

		?>
	</div>

	<div id="pie">
	</div>
</body>

</html>