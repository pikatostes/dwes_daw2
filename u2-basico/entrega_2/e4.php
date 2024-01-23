<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Duplicates</title>
</head>
<body>
    <?php
    $sortedList = array(1, 1, 2, 2, 3, 4, 5, 5);

    // Remove duplicates
    $uniqueList = array_unique($sortedList);

    // Convert the unique list back to indexed array
    $uniqueList = array_values($uniqueList);

    // Display the unique list
    echo "(" . implode(", ", $uniqueList) . ")";
    ?>
</body>
</html>
