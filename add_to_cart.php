<?php
// add_to_cart.php

session_start();

$product_id = $_POST['product_id'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (array_key_exists($product_id, $_SESSION['cart'])) {
    $_SESSION['cart'][$product_id]++;
} else {
    $_SESSION['cart'][$product_id] = 1;
}

$item_count = 0;
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $item_count += $quantity;
}

echo $item_count;
