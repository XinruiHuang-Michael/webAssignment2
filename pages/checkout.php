<?php
session_start();
require dirname(__DIR__) . '/database/db_connection.php'; // connect to database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get the user information
    $delivery_option = $_POST['delivery-option'] ?? '';
    $address = ($_POST['delivery-option'] === 'delivery') ? ($_POST['address'] ?? '') : null;
    $phone = ($_POST['delivery-option'] === 'delivery') ? ($_POST['phone'] ?? '') : null;
    $payment_method = $_POST['payment-method'] ?? '';
    $total_price = 0;
    $total = 0;
    $tax = 0;

    // calculate the total price of this order
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }
        $tax = $total_price * 0.13;
        $total = $total_price + $tax;   
    }


    // insert orders
    $query = "INSERT INTO orders (total_price, delivery_option, address, phone, payment_method) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("dssss", $total, $delivery_option, $address, $phone, $payment_method);

    if ($stmt->execute()) {
        // get the order number
        $order_id = $conn->insert_id;
        // set the order number to keep 4 number
        $formatted_order_id = str_pad($order_id, 4, "0", STR_PAD_LEFT);
    } else {
        $error = "Failed to place order. Please try again.";
    }

    // inser order items
    if (!empty($_SESSION['cart'])) {
        $all_items_inserted = true; // check if all the items has been inserted
    
        foreach ($_SESSION['cart'] as $item) {
            $id = $item['id'];
            $quantity = $item['quantity'];
            $sql_item = "INSERT INTO orders_items (order_id, item_id, quantity) VALUES ('$order_id', '$id', '$quantity')";
    
            if ($conn->query($sql_item) !== TRUE) {
                $all_items_inserted = false;
                $error = "Failed to place order item. Please try again.";
                break;   // if failed, stop insert data
            }
        }
    
        if ($all_items_inserted) {
            // clean cart session
            $_SESSION['cart'] = [];
    
            // save order session
            $_SESSION['order_number'] = $formatted_order_id;
            $_SESSION['delivery_option'] = $delivery_option;
            $_SESSION['address'] = $address;
            $_SESSION['phone'] = $phone;
    
            // redirectory to success page
            header("Location: order_success.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/checkout.css">
    <title>Checkout</title>
</head>

<body>
    <header>
        <h1>Checkout</h1>
        <nav>
            <!-- navigation -->
            <ul>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="cart.php">Shopping Cart</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="admin_login.php" class="admin-link">Admin</a></li>
            </ul>
        </nav>
    </header>
    <!-- checkout form -->
    <main>
        <section class="checkout-form">
            <h2>Order Summary</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    if (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $item) {
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                            echo "<tr>
                                <td>{$item['name']}</td>
                                <td>{$item['quantity']}</td>
                                <td>$" . number_format($item['price'], decimals: 2) . "</td>
                              </tr>";
                        }
                    }
                    $tax = $subtotal * 0.13;
                    $total += $tax;
                    ?>
                    <tr>
                        <td colspan="2"><strong>Tax</strong></td>
                        <td><strong>$<?php echo number_format($tax, decimals: 2); ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Total</strong></td>
                        <td><strong>$<?php echo number_format($total, decimals: 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
            <!-- delivery form -->
            <h2>Delivery Information</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST" action="checkout.php">
                <div>
                    <label for="delivery-option">Delivery Option:</label>
                    <select name="delivery-option" id="delivery-option" required>
                        <option value="delivery">Delivery</option>
                        <option value="pickup">Pickup</option>
                        <option value="dine-in">Dine-in</option>
                    </select>
                </div>
                <div id="address-field">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" placeholder="Enter delivery address">
                </div>
                <div id="phone-field">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" placeholder="Enter phone number">
                </div>
                <div>
                    <label for="payment-method">Payment Method:</label>
                    <select name="payment-method" id="payment-method" required>
                        <option value="credit-card">Credit Card</option>
                        <option value="wallet">E-Wallet</option>
                        <option value="cash">Cash</option>
                    </select>
                </div>
                <button type="submit" id="placeorder">Place Order</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Assignment2-Restaurant Order Machine Page</p>
    </footer>
    <!-- link javascript to set display of address and phone input box -->
    <script src="../scripts/checkout.js"></script>
</body>

</html>