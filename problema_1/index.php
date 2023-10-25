<!DOCTYPE html>
<html>

<head>
     <title>Shopping List</title>
</head>

<body>
     <h1>Shopping List</h1>
     <form action="funciones.php" method="post">
          <button type="submit" name="action" value="show">Show List</button>
          <button type="submit" name="action" value="insert">Insert</button>
          <button type="submit" name="action" value="modify">Modify</button>
          <button type="submit" name="action" value="delete">Delete</button>

          <!-- Add input fields for inserting an item -->
          <label for="name">Name:</label>
          <input type="text" name="name" required>

          <label for="quantity">Quantity:</label>
          <input type="number" name="quantity" required>

          <label for="price">Price:</label>
          <input type="number" step="0.01" name="price" required>
     </form>

</body>

</html>