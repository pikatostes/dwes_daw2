<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <div id="container">
    <h1>Shopping List</h1>

<div class="menu">
    <ul>
        <li><a href="index.php">Show List</a></li>
        <li><a href="index.php?option=insert">Insert</a></li>
        <li><a href="index.php?option=modify">Modify</a></li>
        <li><a href="index.php?option=delete">Delete</a></li>
    </ul>
</div>

<div class="content">
    <?php
    session_start();

    if (!isset($_SESSION['shoppingList'])) {
        $_SESSION['shoppingList'] = [];
    }
    
    $shoppingList = &$_SESSION['shoppingList'];
    $inserted = false;
    $modified = false;
    $deleted = false;
    
    include('funciones.php');
    
    // Handle different options
    $option = isset($_GET['option']) ? $_GET['option'] : '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($option === 'insert' || $option === 'modify') {
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
            $price = isset($_POST['price']) ? $_POST['price'] : '';

            if ($option === 'insert') {
                $inserted = insertItem($name, $quantity, $price);
            } elseif ($option === 'modify') {
                $index = isset($_POST['index']) ? $_POST['index'] : -1;
                $modified = modifyItem($index, $name, $quantity, $price);
            }
        } elseif ($option === 'delete') {
            $index = isset($_POST['index']) ? $_POST['index'] : -1;
            $deleted = deleteItem($index);
        }
    }

    // Display the shopping list
    echo "<h2>Shopping List</h2>";
    echo "<table>";
    echo "<tr><th>Name</th><th>Quantity</th><th>Price</th><th>Total</th></tr>";

    foreach ($shoppingList as $index => $item) {
        echo "<tr>";
        echo "<td>{$item['name']}</td>";
        echo "<td>{$item['quantity']}</td>";
        echo "<td>\${$item['price']}</td>";
        $total = calculateTotalPriceProduct($item['quantity'], $item['price']);
        echo "<td>\${$total}</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<p>Total Purchase Price: \$" . calculateTotalPurchasePrice($shoppingList) . "</p>";

    if (isset($_POST['option'])) {
        if ($option === 'insert') {
            if ($inserted) {
                echo "<p>Item added successfully.</p>";
            } else {
                echo "<p>Item insertion failed. Name is required.</p>";
            }
        } elseif ($option === 'modify') {
            if ($modified) {
                echo "<p>Item modified successfully.</p>";
            } else {
                echo "<p>Item modification failed. Name is required.</p>";
            }
        } elseif ($option === 'delete') {
            if ($deleted) {
                echo "<p>Item deleted successfully.</p>";
            }
        }
    }
    ?>

    <?php if ($option === 'insert' || $option === 'modify') : ?>
        <h2><?php echo $option === 'insert' ? 'Insert Item' : 'Modify Item'; ?></h2>
        <form action="index.php?option=<?php echo $option; ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" required><br>

            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" required><br>

            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" required><br>

            <input type="hidden" name="index" value="<?php echo $option === 'modify' ? $index : -1; ?>">
            <input type="submit" value="<?php echo $option === 'insert' ? 'Add to List' : 'Modify Item'; ?>">
        </form>
    <?php endif; ?>

    <?php if ($option === 'delete') : ?>
        <h2>Delete Item</h2>
        <form action="index.php?option=delete" method="post">
            <select name="index">
                <?php foreach ($shoppingList as $index => $item) : ?>
                    <option value="<?php echo $index; ?>"><?php echo $item['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Delete Item">
        </form>
    <?php endif; ?>
</div>
    </div>
</body>

</html>