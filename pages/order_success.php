<?php
// start session and set default values
session_start();
$order_number = $_SESSION['order_number'] ?? 'Unknown';
$delivery_option = $_SESSION['delivery_option'] ?? 'Unknown';
$address = $_SESSION['address'] ?? 'N/A';
$phone = $_SESSION['phone'] ?? 'N/A';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css"> 
    <link rel="stylesheet" href="../styles/order_success.css">
    <title>Order Successful</title>
</head>
<body>
    <header>
        <h1>Order Successful</h1>
        <nav>
            <ul>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="cart.php">Shopping Cart</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="admin_login.php" class="admin-link">Admin</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <!-- show order number -->
        <section class="success-message">
            <h2>Thank you for your order!</h2>
            <p>Your order number is <strong><?php echo $order_number; ?></strong>.</p>
            <p>Delivery Option: <strong><?php echo ucfirst($delivery_option); ?></strong></p>
            <!-- if choose delivery, let user input the address and phone -->
            <?php if ($delivery_option === 'delivery'): ?>
                <p>Delivery Address: <strong><?php echo $address; ?></strong></p>
                <p>Phone Number: <strong><?php echo $phone; ?></strong></p>
            <?php endif; ?>
            <p>Please keep this number for reference. Your food will be served shortly.</p>
            <a href="menu.php" class="button">Continue Shopping</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Assignment2-Restaurant Order Machine Page</p>
    </footer>
</body>
</html>



