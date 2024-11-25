<?php
session_start();
require dirname(__DIR__) . '/database/db_connection.php'; // 引入数据库连接

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取用户输入的数据
    $delivery_option = $_POST['delivery-option'] ?? '';
    $address = ($_POST['delivery-option'] === 'delivery') ? ($_POST['address'] ?? '') : null; // 如果不是配送则地址为空
    $payment_method = $_POST['payment-method'] ?? '';
    $total_price = 0;

    // 计算购物车总价
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }
    }

    // 将订单存储到数据库
    $query = "INSERT INTO orders (total_price, delivery_option, address, payment_method) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("dsss", $total_price, $delivery_option, $address, $payment_method);

    if ($stmt->execute()) {
        // 获取订单号（新插入记录的 ID）
        $order_id = $conn->insert_id;
    
        // 格式化订单号为 4 位数
        $formatted_order_id = str_pad($order_id, 4, "0", STR_PAD_LEFT);
    
        // 清空购物车
        $_SESSION['cart'] = [];
    
        // 将订单号和配送选项存入会话
        $_SESSION['order_number'] = $formatted_order_id;
        $_SESSION['delivery_option'] = $delivery_option;
        $_SESSION['address'] = $address; // 如果需要显示地址
        $_SESSION['phone'] = $phone;     // 如果需要显示手机号
    
        // 跳转到订单成功页面
        header("Location: order_success.php");
        exit;
    } else {
        $error = "Failed to place order. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/checkout.css"> <!-- 菜单页面特定样式 -->
    <title>Checkout</title>
</head>
<body>
    <header>
        <h1>Checkout</h1>
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
                                <td>\${$subtotal}</td>
                              </tr>";
                        }
                    }
                    ?>
                    <tr>
                        <td colspan="2"><strong>Total</strong></td>
                        <td><strong>$<?php echo $total; ?></strong></td>
                    </tr>
                </tbody>
            </table>

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
                <button type="submit">Place Order</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Michael Restaurant</p>
    </footer>
    <script src="../scripts/checkout.js"></script> <!-- 引入外部 JavaScript 文件 -->
</body>
</html>



