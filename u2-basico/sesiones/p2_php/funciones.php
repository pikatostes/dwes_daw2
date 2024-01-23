<?php
function calculateTotalPriceProduct($quantity, $price) {
    return $quantity * $price;
}

function calculateTotalPurchasePrice($shoppingList) {
    $total = 0;
    foreach ($shoppingList as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return $total;
}

function insertItem($name, $quantity, $price) {
    global $shoppingList;

    if (!empty($name)) {
        $item = [
            'name' => $name,
            'quantity' => $quantity,
            'price' => $price,
        ];

        array_push($shoppingList, $item);
        return true; // Insertion successful
    } else {
        return false; // Insertion failed
    }
}

function modifyItem($index, $name, $quantity, $price) {
    global $shoppingList;

    if (!empty($name) && isset($shoppingList[$index])) {
        $shoppingList[$index]['name'] = $name;
        $shoppingList[$index]['quantity'] = $quantity;
        $shoppingList[$index]['price'] = $price;
        return true; // Modification successful
    } else {
        return false; // Modification failed
    }
}

function deleteItem($index) {
    global $shoppingList;

    if (isset($shoppingList[$index])) {
        array_splice($shoppingList, $index, 1);
        return true; // Deletion successful
    } else {
        return false; // Deletion failed
    }
}
?>
