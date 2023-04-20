<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">

    <title>Cart</title>
    <style>
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1em;
        }
    </style>
</head>
<body>
    <!-- Title Banner -->
<div class="banner">
    <h1>Amazon</h1>
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
            if (isset($_POST['checkout'])) {
                $stmt = $pdo->prepare('SELECT * FROM carts WHERE user_id = ?');
                $stmt->execute([$user_id]);
                $cart = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($cart) {
                    $stmt = $pdo->prepare('DELETE FROM cart_items WHERE cart_id = ?');
                    $stmt->execute([$cart['cart_id']]);
                    echo "<p>Checkout complete! Your cart has been cleared.</p>";
                } else {
                    echo "<p>Your cart is empty.</p>";
                }
            }
        }

        $stmt = $pdo->prepare('SELECT * FROM carts WHERE user_id = ?');
        $stmt->execute([$user_id]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {
            $stmt = $pdo->prepare('SELECT ci.*, p.product_name, p.product_price FROM cart_items ci JOIN products p ON ci.product_id = p.product_id WHERE ci.cart_id = ?');
            $stmt->execute([$cart['cart_id']]);
            $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($cart_items) {
                foreach ($cart_items as $item) {
                    echo "<div class='cart-item'>\n";
                    echo "  <span>{$item['product_name']} - {$item['product_price']} (x{$item['quantity']})</span>\n";
                    echo "</div>\n";
                }

                echo "<form method='post' action=''>\n";
                echo "  <input type='hidden' name='checkout' value='1'>\n";
                echo "  <input type='submit' value='Checkout'>\n";
                echo "</form>\n";
            } else {
                echo "<p>Your cart is empty.</p>";
            }
        } else {
            echo "<p>Your cart is empty.</p>";
        }

    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    ?>

    <a href="index.php" class="cart-button">Back to Shop</a>
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
