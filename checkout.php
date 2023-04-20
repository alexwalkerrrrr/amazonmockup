<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">

    <title>Checkout</title>
</head>
<body>
    <!-- Title Banner -->
<div class="banner">
    <h1>Amazon</h1>
</div>

    <h1>Checkout</h1>
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

        echo "<h2>Cart:</h2>\n";
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $stmt = $pdo->prepare('SELECT * FROM products WHERE product_id = ?');
                $stmt->execute([$product_id]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                echo "<div class='cart'>\n";
                echo "  <span>{$product['product_name']} - {$product['product_price']} (x{$quantity})</span>\n";
                echo "</div>\n";
            }
        } else {
            echo "<p>Your cart is empty.</p>\n";
        }
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    ?>

    <a href="index.php">Go back to shopping</a>
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
