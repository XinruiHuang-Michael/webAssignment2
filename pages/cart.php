<?php
session_start(); // 启用会话
// 初始化购物车
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 处理添加到购物车
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_name']) && isset($_POST['item_price'])) {
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $found = false;

    // 检查购物车中是否已有该商品
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $item_name) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }

    // 如果购物车中没有该商品，则添加新商品
    if (!$found) {
        $_SESSION['cart'][] = [
            'name' => $item_name,
            'price' => $item_price,
            'quantity' => 1,
        ];
    }

    // 重定向回购物车页面
    header("Location: cart.php");
    exit;
}

// 处理更新购物车项的数量
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $index = $_POST['index'] ?? -1;
    $quantity = $_POST['quantity'] ?? 1;

    if ($index !== -1 && isset($_SESSION['cart'][$index])) {
        // 更新数量，确保数量不小于1
        $_SESSION['cart'][$index]['quantity'] = max(1, intval($quantity));
    }

    // 刷新页面
    header("Location: cart.php");
    exit;
}

// 处理删除购物车项
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $index = $_POST['index'] ?? -1;

    if ($index !== -1 && isset($_SESSION['cart'][$index])) {
        // 从购物车中移除指定项
        array_splice($_SESSION['cart'], $index, 1);
    }

    // 刷新页面
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/cart.css"> <!-- 菜单页面特定样式 -->
    <title>Shopping Cart</title>
</head>
<body>
    <header>
        <h1>Your Cart</h1>
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
        <section class="cart-container">
            <h2>Your Shopping Cart</h2>
            <?php
            if (!empty($_SESSION['cart'])) {
                echo '<table class="cart-table">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Item</th>';
                echo '<th>Quantity</th>';
                echo '<th>Price</th>';
                echo '<th>Total</th>';
                echo '<th>Actions</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                
                $total = 0;
                foreach ($_SESSION['cart'] as $index => $item) {
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                    echo '<tr>';
                    echo '<td>' . $item['name'] . '</td>';
                    echo '<td>' . $item['quantity'] . '</td>';
                    echo '<td>$' . number_format($item['price'], 2) . '</td>';
                    echo '<td>$' . number_format($subtotal, 2) . '</td>';
                    echo '<td>';
                    echo '<form method="POST" action="cart.php">';
                    echo '<input type="hidden" name="action" value="delete">';
                    echo '<input type="hidden" name="index" value="' . $index . '">';
                    echo '<button type="submit">Remove</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
                echo '<div class="cart-total">Total: $' . number_format($total, 2) . '</div>';
            } else {
                echo '<p class="empty-cart-message">Your cart is empty.</p>';
            }
            ?>
            <a href="checkout.php" class="cart-button">Proceed to Checkout</a>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Michael Restaurant</p>
    </footer>
</body>
</html>



