<?php
include "checkAdmin.php";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edycja oferty klubowej</title>

    <link rel="stylesheet" href="https://unpkg.com/almond.css@latest/dist/almond.lite.min.css" />


</head>

<body>



</body>

</html>

<?php

include "config.php";

//Edycja aktualnej oferty klubu - zmiana nazwy, ilości dni karnetu, ceny
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $days = $_POST['days'];
    $price = $_POST['price'];

    $query = "UPDATE products SET name='$name', days='$days', price='$price' WHERE id='$product_id'";
    mysqli_query($mysqli, $query);
}

$query = "SELECT * FROM products";
$result = mysqli_query($mysqli, $query);

echo "<h1>Edycja oferty klubu</h1>";

while ($product = mysqli_fetch_assoc($result)) {
    echo "<h2>{$product['name']}</h2>";
    echo "<form method='post' action=''>";
    echo "<input type='hidden' name='product_id' value='{$product['id']}'>";
    echo "<label>Nazwa produktu:</label>";
    echo "<input type='text' name='name' value='{$product['name']}'>";
    echo "<br>";
    echo "<label>Dni:</label>";
    echo "<input type='number' name='days' value='{$product['days']}'>";
    echo "<br>";
    echo "<label>Cena:</label>";
    echo "<input type='number' name='price' value='{$product['price']}'>";
    echo "<br>";
    echo "<input type='submit' value='Zatwierdź'>";
    echo "</form>";
    echo "<br>";
}

?>