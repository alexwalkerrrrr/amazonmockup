<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">

    <title>Simple Shopping Cart</title>
    <style>
        .product {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1em;
        }
    </style>
</head>
<body>
   
<div class="banner">
    <h1>Amazon Mockup</h1>
</div>
<!-- Cart button -->
<div style="text-align: right; margin-right: 1rem;">
    <?php
    $item_count = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $item_count += $quantity;
        }
    }
    ?>
    <a href="cart.php" class="cart-button">View Cart (<?= $item_count ?>)</a>
</div>

    <?php
    session_start();

    $host = 'amazonmockup2.cp5hdsjldtup.us-east-1.rds.amazonaws.com';
    $port = 3306;
    $dbname = 'amazonmock';
    $user = 'admin';
    $pass = 'Group5!?';

    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Replace with the actual user ID after implementing user authentication
        $user_id = 1;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['product_id'])) {
                $product_id = $_POST['product_id'];

                // Get or create the cart for the user
                $stmt = $pdo->prepare('SELECT * FROM carts WHERE user_id = ?');
                $stmt->execute([$user_id]);
                $cart = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$cart) {
                    $stmt = $pdo->prepare('INSERT INTO carts (user_id) VALUES (?)');
                    $stmt->execute([$user_id]);
                    $cart_id = $pdo->lastInsertId();
                } else {
                    $cart_id = $cart['cart_id'];
                }

                // Add product to cart_items table
                $stmt = $pdo->prepare('SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?');
                $stmt->execute([$cart_id, $product_id]);
                $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($cart_item) {
                    $stmt = $pdo->prepare('UPDATE cart_items SET quantity = quantity + 1 WHERE cart_item_id = ?');
                    $stmt->execute([$cart_item['cart_item_id']]);
                } else {
                    $stmt = $pdo->prepare('INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, 1)');
                    $stmt->execute([$cart_id, $product_id]);
                }
            }
        }

        $categories = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);
        $products = $pdo->query('SELECT * FROM products')->fetchAll(PDO::FETCH_ASSOC);

        echo "<h2>Products:</h2>\n";
        foreach ($products as $product) {
            echo "<div class='product'>\n";
            echo "  <span>{$product['product_name']} - {$product['product_price']}</span>\n";
            echo "  <form method='post' action=''>\n";
            echo "    <input type='hidden' name='product_id' value='{$product['product_id']}'>\n";
            echo "    <input type='submit' value='Add to Cart'>\n";
            echo "  </form>\n";
            echo "</div>\n";
        }

    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    ?>

</body>
<!-- Footer -->
<footer class="footer">
    <div class="container footer-content">
        <div class="footer-section">
            <h3>Contact Us</h3>
            <p>Email: support@example.com</p>
            <p>Phone: +1 (800) 123-4567</p>
        </div>
        <div class="footer-section">
            <h3>About Us</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque tincidunt justo nec nisl facilisis volutpat.</p>
        </div>
    </div>
</footer>

</html>
