<?php

function Calculate_Total_Price_Product($quantity, $price, &$total) {
    $total = $quantity * $price;
}

function Calculate_Total_Purchase_Price($items) {
    $totalPurchasePrice = 0;
    foreach ($items as $item) {
        $totalPurchasePrice += $item['total'];
    }
    return $totalPurchasePrice;
}

function Show_List($items) {
    // Display the shopping list in a table
    echo "<table border='1'>";
    echo "<tr><th>Name</th><th>Quantity</th><th>Price</th><th>Total</th></tr>";
    foreach ($items as $item) {
        echo "<tr><td>{$item['name']}</td><td>{$item['quantity']}</td><td>{$item['price']}</td><td>{$item['total']}</td></tr>";
    }
    echo "</table>";
    $totalPurchasePrice = Calculate_Total_Purchase_Price($items);
    echo "<p>Total Purchase Price: $totalPurchasePrice</p>";
}

function Insert_Item(&$items, $name, $quantity, $price) {
    if (!empty($name)) {
        $total = 0;
        Calculate_Total_Price_Product($quantity, $price, $total);
        $items[] = array('name' => $name, 'quantity' => $quantity, 'price' => $price, 'total' => $total);
        echo "Item added successfully!";
    } else {
        echo "Name is a mandatory field. Item not added.";
    }
}

function Modify_Item(&$items, $index, $name, $quantity, $price) {
    if (!empty($name)) {
        $total = 0;
        Calculate_Total_Price_Product($quantity, $price, $total);
        $items[$index] = array('name' => $name, 'quantity' => $quantity, 'price' => $price, 'total' => $total);
        echo "Item modified successfully!";
    } else {
        echo "Name is a mandatory field. Item not modified.";
    }
}

function Delete_Item(&$items, $index) {
    if (isset($items[$index])) {
        unset($items[$index]);
        echo "Item deleted successfully!";
    } else {
        echo "Item not found. Deletion failed.";
    }
}

$items = array();

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case 'show':
            Show_List($items);
            break;
        case 'insert':
            $name = $_POST['name'];
            $quantity = $_POST['quantity'];
            $price = $_POST['price'];
            Insert_Item($items, $name, $quantity, $price);
            break;
        case 'modify':
            $index = $_POST['index'];
            $name = $_POST['name'];
            $quantity = $_POST['quantity'];
            $price = $_POST['price'];
            Modify_Item($items, $index, $name, $quantity, $price);
            break;
        case 'delete':
            $index = $_POST['index'];
            Delete_Item($items, $index);
            break;
    }
}
?>
